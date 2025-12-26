# DUKCAPIL WhatsApp Bot

> Modern WhatsApp chatbot system for DUKCAPIL Ponorogo with full-stack Laravel + Node.js architecture

## 🎨 Features

- **WhatsApp-Style UI** - Green and white theme matching WhatsApp's design
- **QR Code Device Connection** - Easy setup via WhatsApp Web scanning
- **Multiple Device Support** - Manage multiple WhatsApp accounts
- **Real-time Monitoring** - Track device status and conversations
- **Service Request Management** - Handle citizen service requests (KTP, KK, Birth Certificates)
- **Auto-Reply System** - Automated responses for common queries
- **User Management** - Role-based access control
- **Conversation Logging** - Complete message history

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- Chrome/Chromium

### Installation

```bash
# 1. Install dependencies
composer install
npm install
cd bot && npm install && cd ..

# 2. Setup environment
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# 3. Build assets
npm run build

# 4. Run
npm run start
```

### Default Login
- Email: `admin@dukcapil.ponorogo.go.id`
- Password: `password`

## 📖 Documentation

- **[Complete Setup Guide](SETUP_GUIDE.md)** - Detailed installation and configuration
- **[Bot Setup Guide](BOT_SETUP_GUIDE.md)** - WhatsApp device connection
- **[DUKCAPIL Features](DUKCAPIL_README.md)** - Feature documentation

## 🎨 UI Preview

The application features a modern WhatsApp-inspired design with:
- Green gradient navigation (`#25D366` WhatsApp green)
- Card-based device management interface
- Real-time QR code scanning display
- Responsive design for all screen sizes

## 🏗️ Architecture

```
Laravel App (Port 8000)     Node.js Bot Server (Port 3000)
    ↓                              ↓
Admin Dashboard             WhatsApp Web.js
    ↓                              ↓
API Routes        ←→        Express API
    ↓                              ↓
Database                    WhatsApp Sessions
```

## 📱 Connecting WhatsApp

1. Login to admin dashboard
2. Navigate to "Bots" → "Add New Device"
3. Scan QR code with WhatsApp on your phone
4. Bot is now connected and ready!

## 🛡️ Security

**Important:** Change default passwords and generate a secure API token:

```bash
php -r "echo bin2hex(random_bytes(32));"
```

Add to `.env` and `bot/.env`:
```env
BOT_API_TOKEN=your-generated-token
```

## 🔧 Configuration

### Database
Default: SQLite (no setup required)

For MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=dukcapil_wa
DB_USERNAME=root
DB_PASSWORD=
```

### Bot Server
```env
BOT_PORT=3000
BOT_API_TOKEN=match-laravel-token
APP_URL=http://localhost:8000
```

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| QR code not showing | Check bot log: `tail -f storage/logs/bot-*.log` |
| Bot disconnects | Sessions expire after 15-20 days, rescan QR code |
| Build errors | Run `npm run build && php artisan config:clear` |

## 📚 Tech Stack

- **Backend**: Laravel 12, PHP 8.2
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Bot**: WhatsApp Web.js (integrated in Laravel)
- **Database**: SQLite/MySQL/PostgreSQL
- **Queue**: Laravel Queue
- **Testing**: Pest

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

MIT License - see LICENSE file for details

---

**Built with ❤️ for DUKCAPIL Ponorogo**
