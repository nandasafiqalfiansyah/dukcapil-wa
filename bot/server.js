import { Client, LocalAuth } from 'whatsapp-web.js';
import QRCode from 'qrcode';
import express from 'express';
import bodyParser from 'body-parser';
import cors from 'cors';
import dotenv from 'dotenv';
import axios from 'axios';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

dotenv.config({ path: path.join(__dirname, '..', '.env') });

const app = express();
const PORT = process.env.BOT_PORT || 3000;
const LARAVEL_API_URL = process.env.APP_URL || 'http://localhost:8000';
const BOT_API_TOKEN = process.env.BOT_API_TOKEN || 'default-token';
const LARAVEL_TIMEOUT = parseInt(process.env.LARAVEL_TIMEOUT || '5000', 10);

app.use(cors());
app.use(bodyParser.json());

// Store bot instances
const botInstances = new Map();
const qrCodes = new Map();
const connectionStatus = new Map();

// Create sessions directory if it doesn't exist
const sessionsDir = path.join(__dirname, '.wwebjs_auth');
if (!fs.existsSync(sessionsDir)) {
    fs.mkdirSync(sessionsDir, { recursive: true });
}

// Middleware for API token authentication
const authenticateToken = (req, res, next) => {
    const token = req.headers['authorization']?.replace('Bearer ', '');
    if (token === BOT_API_TOKEN) {
        next();
    } else {
        res.status(401).json({ error: 'Unauthorized' });
    }
};

// Initialize a bot instance
async function initializeBot(botId, botName = 'Default Bot') {
    if (botInstances.has(botId)) {
        console.log(`Bot ${botId} already initialized`);
        return { success: true, message: 'Bot already initialized' };
    }

    try {
        const client = new Client({
            authStrategy: new LocalAuth({
                clientId: botId,
                dataPath: sessionsDir
            }),
            puppeteer: {
                headless: true,
                args: [
                    '--no-sandbox',
                    '--disable-setuid-sandbox',
                    '--disable-dev-shm-usage',
                    '--disable-accelerated-2d-canvas',
                    '--no-first-run',
                    '--no-zygote',
                    '--disable-gpu'
                ]
            }
        });

        // QR code event
        client.on('qr', async (qr) => {
            console.log(`QR Code generated for bot ${botId}`);
            try {
                const qrDataUrl = await QRCode.toDataURL(qr);
                qrCodes.set(botId, qrDataUrl);
                connectionStatus.set(botId, 'qr_generated');
                
                // Notify Laravel backend
                await notifyLaravel('qr_generated', {
                    bot_id: botId,
                    bot_name: botName,
                    qr_code: qrDataUrl
                });
            } catch (err) {
                console.error('Error generating QR code:', err);
            }
        });

        // Ready event
        client.on('ready', async () => {
            console.log(`Bot ${botId} is ready!`);
            qrCodes.delete(botId);
            connectionStatus.set(botId, 'connected');
            
            const info = client.info;
            await notifyLaravel('connected', {
                bot_id: botId,
                bot_name: botName,
                phone_number: info.wid.user,
                platform: info.platform
            });
        });

        // Authentication events
        client.on('authenticated', () => {
            console.log(`Bot ${botId} authenticated`);
            connectionStatus.set(botId, 'authenticated');
        });

        client.on('auth_failure', async (msg) => {
            console.error(`Bot ${botId} authentication failure:`, msg);
            connectionStatus.set(botId, 'auth_failed');
            await notifyLaravel('auth_failed', {
                bot_id: botId,
                error: msg
            });
        });

        // Disconnection event
        client.on('disconnected', async (reason) => {
            console.log(`Bot ${botId} disconnected:`, reason);
            connectionStatus.set(botId, 'disconnected');
            botInstances.delete(botId);
            qrCodes.delete(botId);
            
            await notifyLaravel('disconnected', {
                bot_id: botId,
                reason: reason
            });
        });

        // Message event
        client.on('message', async (message) => {
            console.log(`Message received on bot ${botId}:`, message.from);
            
            try {
                // Forward message to Laravel
                await axios.post(`${LARAVEL_API_URL}/api/bot/webhook`, {
                    bot_id: botId,
                    message: {
                        id: message.id._serialized,
                        from: message.from,
                        to: message.to,
                        body: message.body,
                        type: message.type,
                        timestamp: message.timestamp,
                        hasMedia: message.hasMedia,
                        isForwarded: message.isForwarded,
                        isStatus: message.isStatus,
                        isStarred: message.isStarred,
                        broadcast: message.broadcast,
                        fromMe: message.fromMe,
                        hasQuotedMsg: message.hasQuotedMsg,
                        author: message.author,
                        mentionedIds: message.mentionedIds
                    }
                }, {
                    headers: {
                        'Authorization': `Bearer ${BOT_API_TOKEN}`,
                        'Content-Type': 'application/json'
                    }
                });
            } catch (error) {
                console.error('Error forwarding message to Laravel:', error.message);
            }
        });

        // Initialize the client
        await client.initialize();
        botInstances.set(botId, { client, botName });
        connectionStatus.set(botId, 'initializing');

        console.log(`Bot ${botId} initialization started`);
        return { success: true, message: 'Bot initialization started' };
    } catch (error) {
        console.error(`Error initializing bot ${botId}:`, error);
        return { success: false, error: error.message };
    }
}

// Notify Laravel backend
async function notifyLaravel(event, data) {
    try {
        await axios.post(`${LARAVEL_API_URL}/api/bot/event`, {
            event,
            data
        }, {
            headers: {
                'Authorization': `Bearer ${BOT_API_TOKEN}`,
                'Content-Type': 'application/json'
            },
            timeout: LARAVEL_TIMEOUT
        });
    } catch (error) {
        console.error('Error notifying Laravel:', error.message);
    }
}

// API Routes

// Health check
app.get('/health', (req, res) => {
    res.json({ status: 'ok', timestamp: new Date().toISOString() });
});

// Initialize bot
app.post('/bot/initialize', authenticateToken, async (req, res) => {
    const { bot_id, bot_name } = req.body;
    
    if (!bot_id) {
        return res.status(400).json({ error: 'bot_id is required' });
    }

    const result = await initializeBot(bot_id, bot_name || `Bot ${bot_id}`);
    res.json(result);
});

// Get bot status
app.get('/bot/:botId/status', authenticateToken, (req, res) => {
    const { botId } = req.params;
    const status = connectionStatus.get(botId) || 'not_initialized';
    const qrCode = qrCodes.get(botId) || null;
    const hasInstance = botInstances.has(botId);

    res.json({
        bot_id: botId,
        status,
        qr_code: qrCode,
        connected: status === 'connected',
        has_instance: hasInstance
    });
});

// Get all bots status
app.get('/bots/status', authenticateToken, (req, res) => {
    const bots = [];
    
    for (const [botId, instance] of botInstances.entries()) {
        bots.push({
            bot_id: botId,
            bot_name: instance.botName,
            status: connectionStatus.get(botId) || 'unknown',
            qr_code: qrCodes.get(botId) || null,
            connected: connectionStatus.get(botId) === 'connected'
        });
    }

    res.json({ bots, total: bots.length });
});

// Disconnect bot
app.post('/bot/:botId/disconnect', authenticateToken, async (req, res) => {
    const { botId } = req.params;
    const instance = botInstances.get(botId);

    if (!instance) {
        return res.status(404).json({ error: 'Bot not found' });
    }

    try {
        await instance.client.destroy();
        botInstances.delete(botId);
        qrCodes.delete(botId);
        connectionStatus.set(botId, 'disconnected');
        res.json({ success: true, message: 'Bot disconnected' });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

// Send message
app.post('/bot/:botId/send', authenticateToken, async (req, res) => {
    const { botId } = req.params;
    const { to, message, type = 'text' } = req.body;

    const instance = botInstances.get(botId);
    if (!instance) {
        return res.status(404).json({ error: 'Bot not found or not connected' });
    }

    if (!to || !message) {
        return res.status(400).json({ error: 'to and message are required' });
    }

    try {
        // Ensure phone number format
        // Individual chats: 628123456789@c.us
        // Group chats: 123456789@g.us (must include @g.us)
        // Note: For group chats, the full ID with @g.us must be provided
        let chatId = to;
        if (!to.includes('@')) {
            // Default to individual chat format
            chatId = `${to}@c.us`;
        }

        const result = await instance.client.sendMessage(chatId, message);
        res.json({
            success: true,
            message_id: result.id._serialized,
            timestamp: result.timestamp
        });
    } catch (error) {
        console.error('Error sending message:', error);
        res.status(500).json({ error: error.message });
    }
});

// Get bot info
app.get('/bot/:botId/info', authenticateToken, async (req, res) => {
    const { botId } = req.params;
    const instance = botInstances.get(botId);

    if (!instance) {
        return res.status(404).json({ error: 'Bot not found or not connected' });
    }

    try {
        const state = await instance.client.getState();
        const info = instance.client.info;
        
        res.json({
            bot_id: botId,
            bot_name: instance.botName,
            state,
            phone_number: info?.wid?.user,
            platform: info?.platform,
            pushname: info?.pushname
        });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

// Logout bot
app.post('/bot/:botId/logout', authenticateToken, async (req, res) => {
    const { botId } = req.params;
    const instance = botInstances.get(botId);

    if (!instance) {
        return res.status(404).json({ error: 'Bot not found' });
    }

    try {
        await instance.client.logout();
        botInstances.delete(botId);
        qrCodes.delete(botId);
        connectionStatus.delete(botId);
        
        res.json({ success: true, message: 'Bot logged out successfully' });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

// Start server
app.listen(PORT, () => {
    console.log(`WhatsApp Bot Server running on port ${PORT}`);
    console.log(`Laravel API URL: ${LARAVEL_API_URL}`);
    console.log(`Sessions directory: ${sessionsDir}`);
});

// Graceful shutdown
process.on('SIGINT', async () => {
    console.log('Shutting down gracefully...');
    
    for (const [botId, instance] of botInstances.entries()) {
        try {
            await instance.client.destroy();
            console.log(`Bot ${botId} destroyed`);
        } catch (error) {
            console.error(`Error destroying bot ${botId}:`, error);
        }
    }
    
    process.exit(0);
});
