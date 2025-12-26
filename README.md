# DUKCAPIL WhatsApp Bot

> Modern WhatsApp chatbot system for DUKCAPIL Ponorogo with full-stack Laravel architecture using WhatsApp Business API

## 🎨 Features

- **WhatsApp-Style UI** - Green and white theme matching WhatsApp's design
- **WhatsApp Business API** - Official Meta API integration (no QR code scanning needed)
- **Single Terminal Operation** - Full-stack Laravel application, no separate bot server required
- **Multiple Device Support** - Manage multiple WhatsApp Business accounts
- **Real-time Messaging** - Send and receive messages via official API
- **Service Request Management** - Handle citizen service requests (KTP, KK, Birth Certificates)
- **Auto-Reply System** - Automated responses for common queries
- **User Management** - Role-based access control
- **Conversation Logging** - Complete message history
- **Webhook Integration** - Receive real-time message updates from Meta

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+ (for asset building only)
- WhatsApp Business API credentials from Meta

### Installation

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# 3. Configure WhatsApp Business API
# Add these to your .env file:
# WHATSAPP_API_URL=https://graph.facebook.com/v18.0
# WHATSAPP_ACCESS_TOKEN=your_access_token
# WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id
# WHATSAPP_VERIFY_TOKEN=your_verify_token

# 4. Build assets
npm run build

# 5. Run (single terminal)
composer run dev
```

### Default Login
- Email: `admin@dukcapil.ponorogo.go.id`
- Password: `password`

## 📖 Documentation

- **[Complete Setup Guide](SETUP_GUIDE.md)** - Detailed installation and configuration
- **[WhatsApp Business API Guide](WHATSAPP_BUSINESS_API_GUIDE.md)** - How to get API credentials
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
WhatsApp Business API Integration
    ↓
API Routes (Webhook)
    ↓
Database
```

## 📱 Setting Up WhatsApp Business API

1. Login to admin dashboard
2. Navigate to "Bots" → "Add New Device"
3. Enter bot name and ID
4. Configure WhatsApp Business API credentials in .env
5. Set up webhook in Meta for Developers dashboard
6. Bot is ready to send and receive messages!

## 🛡️ Security

**Important:** Change default passwords and secure your API credentials:

```env
WHATSAPP_ACCESS_TOKEN=your-permanent-access-token
WHATSAPP_PHONE_NUMBER_ID=your-phone-number-id
WHATSAPP_VERIFY_TOKEN=your-secure-verify-token
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

### WhatsApp Business API
Get credentials from Meta for Developers:
1. Create a Meta App
2. Add WhatsApp product
3. Get access token and phone number ID
4. Configure webhook URL: `https://yourdomain.com/api/webhook/whatsapp`

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Messages not received | Check webhook configuration in Meta dashboard |
| Cannot send messages | Verify WHATSAPP_ACCESS_TOKEN and WHATSAPP_PHONE_NUMBER_ID |
| Build errors | Run `npm run build && php artisan config:clear` |

## 📚 Tech Stack

- **Backend**: Laravel 12, PHP 8.2
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **WhatsApp**: WhatsApp Business API (Meta)
- **Database**: SQLite/MySQL/PostgreSQL
- **Queue**: Laravel Queue
- **Testing**: Pest

## 🆕 What's New

- ✅ Converted from WhatsApp Web.js (QR code based) to WhatsApp Business API
- ✅ Single terminal operation - no separate Node.js bot server needed
- ✅ Official Meta API integration for better reliability
- ✅ No more QR code scanning required
- ✅ Production-ready webhook integration

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
