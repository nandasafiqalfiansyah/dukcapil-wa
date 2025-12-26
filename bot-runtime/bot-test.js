const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode');
const axios = require('axios');
const fs = require('fs');

const BOT_ID = 'test';
const BOT_NAME = 'nanda';
const SESSIONS_PATH = 'C:\Users\USER\Documents\Magang hub\dukcapil-wa\storage\app/whatsapp-sessions';
const APP_URL = 'http://localhost';

let currentQR = null;
let isReady = false;

const client = new Client({
    authStrategy: new LocalAuth({
        clientId: BOT_ID,
        dataPath: SESSIONS_PATH
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

// QR Code event
client.on('qr', async (qr) => {
    console.log('QR Code generated for bot', BOT_ID);
    try {
        const qrDataUrl = await qrcode.toDataURL(qr);
        currentQR = qrDataUrl;
        
        // Save QR to file
        const qrPath = SESSIONS_PATH + '/qr-' + BOT_ID + '.txt';
        fs.writeFileSync(qrPath, qrDataUrl);
        
        // Notify Laravel
        await axios.post(APP_URL + '/api/bot/event', {
            event: 'qr_generated',
            data: {
                bot_id: BOT_ID,
                bot_name: BOT_NAME,
                qr_code: qrDataUrl
            }
        }).catch(err => console.error('Error notifying Laravel:', err.message));
    } catch (err) {
        console.error('Error generating QR code:', err);
    }
});

// Ready event
client.on('ready', async () => {
    console.log('Bot', BOT_ID, 'is ready!');
    isReady = true;
    currentQR = null;
    
    const info = client.info;
    await axios.post(APP_URL + '/api/bot/event', {
        event: 'connected',
        data: {
            bot_id: BOT_ID,
            bot_name: BOT_NAME,
            phone_number: info.wid.user,
            platform: info.platform
        }
    }).catch(err => console.error('Error notifying Laravel:', err.message));
});

// Authentication events
client.on('authenticated', () => {
    console.log('Bot', BOT_ID, 'authenticated');
});

client.on('auth_failure', async (msg) => {
    console.error('Bot', BOT_ID, 'authentication failure:', msg);
    await axios.post(APP_URL + '/api/bot/event', {
        event: 'auth_failed',
        data: {
            bot_id: BOT_ID,
            error: msg
        }
    }).catch(err => console.error('Error notifying Laravel:', err.message));
});

// Disconnection event
client.on('disconnected', async (reason) => {
    console.log('Bot', BOT_ID, 'disconnected:', reason);
    await axios.post(APP_URL + '/api/bot/event', {
        event: 'disconnected',
        data: {
            bot_id: BOT_ID,
            reason: reason
        }
    }).catch(err => console.error('Error notifying Laravel:', err.message));
    
    process.exit(0);
});

// Message event
client.on('message', async (message) => {
    console.log('Message received on bot', BOT_ID, 'from:', message.from);
    
    try {
        await axios.post(APP_URL + '/api/bot/webhook', {
            bot_id: BOT_ID,
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
        }).catch(err => console.error('Error forwarding message:', err.message));
    } catch (error) {
        console.error('Error processing message:', error.message);
    }
});

// Initialize
console.log('Initializing bot', BOT_ID);
client.initialize();

// Keep alive and status monitoring
setInterval(() => {
    const statusPath = SESSIONS_PATH + '/status-' + BOT_ID + '.json';
    fs.writeFileSync(statusPath, JSON.stringify({
        bot_id: BOT_ID,
        status: isReady ? 'connected' : (currentQR ? 'qr_generated' : 'initializing'),
        qr_code: currentQR,
        timestamp: new Date().toISOString()
    }));
}, 5000);

// Handle process termination
process.on('SIGINT', async () => {
    console.log('Shutting down bot', BOT_ID);
    await client.destroy();
    process.exit(0);
});

process.on('SIGTERM', async () => {
    console.log('Terminating bot', BOT_ID);
    await client.destroy();
    process.exit(0);
});