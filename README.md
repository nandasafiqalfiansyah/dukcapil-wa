# DUKCAPIL WhatsApp Bot

> Modern WhatsApp chatbot system for DUKCAPIL Ponorogo with full-stack Laravel architecture using Fonnte WhatsApp API

## 🎨 Features

- **WhatsApp-Style UI** - Green and white theme matching WhatsApp's design
- **Fonnte WhatsApp API** - Easy integration with QR code scanning (no Facebook Business account needed)
- **Single Terminal Operation** - Full-stack Laravel application, no separate bot server required
- **Multiple Device Support** - Manage multiple WhatsApp Business accounts
- **Real-time Messaging** - Send and receive messages via Fonnte API
- **Service Request Management** - Handle citizen service requests (KTP, KK, Birth Certificates)
- **Auto-Reply System** - Automated responses for common queries
- **User Management** - Role-based access control
- **Conversation Logging** - Complete message history
- **Webhook Integration** - Receive real-time message updates from Fonnte

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+ (for asset building only)
- Fonnte account and API token (get it from [fonnte.com](https://fonnte.com))

### Installation

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# 3. Configure Fonnte WhatsApp API
# Add these to your .env file:
# FONNTE_API_URL=https://md.fonnte.com
# FONNTE_TOKEN=your_fonnte_token_here

# 4. Build assets
npm run build

# 5. Run (single terminal)
composer run dev
```

### Default Login
- Email: `admin@dukcapil.ponorogo.go.id`
- Password: `password`

## 📖 Documentation

- **[Fonnte Visual Guide](FONNTE_VISUAL_GUIDE.md)** - Visual flowcharts and diagrams (Indonesian) 📊
- **[Fonnte Quick Start](FONNTE_QUICK_START.md)** - 5-minute setup guide (English) 🚀
- **[Fonnte Setup Guide](FONNTE_SETUP_GUIDE.md)** - Complete guide for connecting WhatsApp via Fonnte (Indonesian) 📱
- **[Complete Setup Guide](SETUP_GUIDE.md)** - Detailed installation and configuration
- **[WhatsApp Business API Guide](WHATSAPP_BUSINESS_API_GUIDE.md)** - Legacy Meta API guide (deprecated)
- **[DUKCAPIL Features](DUKCAPIL_README.md)** - Feature documentation

## 🎨 UI Preview

The application features a modern WhatsApp-inspired design with:
- Green gradient navigation (`#25D366` WhatsApp green)
- Card-based device management interface
- Real-time message handling
- Responsive design for all screen sizes

## 🏗️ Architecture

```
Laravel Full-Stack Application
    ↓
Admin Dashboard
    ↓
Fonnte WhatsApp API Integration
    ↓
API Routes (Webhook)
    ↓
Database
```

## 📱 Setting Up WhatsApp Connection

1. Login to admin dashboard
2. Navigate to "Bots" → "Add New Device"
3. Enter bot name and ID
4. Enter your Fonnte API token (or configure in .env)
5. Bot is ready to send and receive messages!

**Get Fonnte Token:**
- Visit [fonnte.com](https://fonnte.com)
- Register and verify your account
- Scan QR code to connect WhatsApp
- Copy your API token from dashboard
- See [FONNTE_SETUP_GUIDE.md](FONNTE_SETUP_GUIDE.md) for detailed steps

## 🛡️ Security

**Important:** Change default passwords and secure your API credentials:

```env
FONNTE_TOKEN=your-fonnte-api-token
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

### WhatsApp Connection
Get credentials from Fonnte:
1. Register at [fonnte.com](https://fonnte.com)
2. Scan QR code to connect WhatsApp
3. Copy API token from dashboard
4. Configure in .env: `FONNTE_TOKEN=your_token`

Or enter token directly when creating a new bot in admin dashboard.

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Messages not received | Check webhook configuration in Fonnte dashboard |
| Cannot send messages | Verify FONNTE_TOKEN is valid and WhatsApp is connected |
| Build errors | Run `npm run build && php artisan config:clear` |

## 📚 Tech Stack

- **Backend**: Laravel 12, PHP 8.2
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **WhatsApp**: Fonnte WhatsApp API
- **Database**: SQLite/MySQL/PostgreSQL
- **Queue**: Laravel Queue
- **Testing**: Pest

## 🆕 What's New

- ✅ Integrated with Fonnte API for easy WhatsApp connection
- ✅ No Facebook Business account required
- ✅ Easy setup with QR code scanning
- ✅ Single terminal operation - no separate Node.js bot server needed
- ✅ Per-bot token support for multi-device management
- ✅ Comprehensive Indonesian setup guide

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
