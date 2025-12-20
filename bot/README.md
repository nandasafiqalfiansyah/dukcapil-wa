# WhatsApp Bot Server

Node.js server untuk menjalankan WhatsApp bot menggunakan whatsapp-web.js.

## Installation

```bash
npm install
```

## Configuration

Copy `.env.example` to `.env` and configure:

```env
BOT_PORT=3000
BOT_API_TOKEN=your-secure-api-token-here
APP_URL=http://localhost:8000
```

**Important:** `BOT_API_TOKEN` harus sama dengan yang ada di Laravel `.env`

## Running

### Development
```bash
npm start
```

### Production with PM2
```bash
pm2 start server.js --name "dukcapil-whatsapp-bot"
pm2 save
```

## Features

- Multiple bot instances support
- QR code generation for WhatsApp Web
- Auto-reconnection on disconnect
- Session persistence (no need to scan QR every restart)
- Message forwarding to Laravel backend
- Event notifications (connect, disconnect, etc)

## API Endpoints

All endpoints require Bearer token authentication.

### Initialize Bot
```
POST /bot/initialize
{
  "bot_id": "bot-1",
  "bot_name": "My Bot"
}
```

### Get Bot Status
```
GET /bot/:botId/status
```

### Send Message
```
POST /bot/:botId/send
{
  "to": "628123456789",
  "message": "Hello!"
}
```

### Disconnect Bot
```
POST /bot/:botId/disconnect
```

### Logout Bot
```
POST /bot/:botId/logout
```

## Directory Structure

```
.wwebjs_auth/       # WhatsApp session data (auto-created)
.wwebjs_cache/      # WhatsApp cache (auto-created)
node_modules/       # Node dependencies
server.js           # Main server file
package.json        # NPM dependencies
.env               # Configuration (create from .env.example)
```

## Troubleshooting

### Puppeteer/Chrome errors

Install Chrome dependencies:
```bash
# Ubuntu/Debian
sudo apt-get update
sudo apt-get install -y chromium-browser

# Or install full Chrome
wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
sudo dpkg -i google-chrome-stable_current_amd64.deb
sudo apt-get install -f
```

### Port already in use
Change `BOT_PORT` in `.env` to a different port.

### Bot disconnects frequently
This is normal for WhatsApp Web. The bot will auto-reconnect. If it persists, logout and rescan QR code.

## Logs

View logs with PM2:
```bash
pm2 logs dukcapil-whatsapp-bot
```

## Security

- Keep `BOT_API_TOKEN` secret
- Don't commit `.env` file
- Backup `.wwebjs_auth/` directory regularly
- Use HTTPS in production

## Support

See main documentation: [BOT_SETUP_GUIDE.md](../BOT_SETUP_GUIDE.md)
