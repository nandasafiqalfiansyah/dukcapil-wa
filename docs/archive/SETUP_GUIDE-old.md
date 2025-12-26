# DUKCAPIL WhatsApp Bot - Complete Setup Guide

## 📱 Overview

This is a full-stack WhatsApp chatbot system for DUKCAPIL Ponorogo built with:
- **Laravel 12** - Backend API and Admin Dashboard
- **Node.js Bot Server** - WhatsApp Web integration (runs alongside Laravel)
- **Tailwind CSS** - Modern WhatsApp-style UI (green theme)

## 🎨 Features

### WhatsApp-Like Interface
- Modern green and white design matching WhatsApp's aesthetic
- QR code scanning interface for device connection
- Real-time device status monitoring
- Multiple device management

### Core Features
- **Device Management**: Connect multiple WhatsApp accounts via QR code
- **Conversation Logging**: Track all incoming and outgoing messages
- **Service Requests**: Manage citizen service requests (KTP, KK, Birth Certificates)
- **User Management**: Role-based access control (Admin, Officer, Viewer)
- **Auto-Reply System**: Automated responses for common queries
- **Document Validation**: Review and validate uploaded documents

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- NPM
- Chrome/Chromium (for Puppeteer - WhatsApp Web automation)

### Step 1: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies (frontend)
npm install

# Install Node.js dependencies (bot server)
cd bot
npm install
cd ..
```

### Step 2: Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database (default: SQLite)
# Edit .env if needed

# Run migrations and seeders
php artisan migrate --seed
```

### Step 3: Build Frontend Assets

```bash
npm run build
```

### Step 4: Run the Application

**Development Mode:**
```bash
npm run start
```

Or manually with **2 terminals**:

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - Frontend Dev Server:**
```bash
npm run dev
```

**Terminal 2 - Frontend Dev Server:**
```bash
npm run dev
```

The application will be available at: **http://localhost:8000**

## 📱 Connecting Your WhatsApp Device

### Via Web Interface

1. **Login to Admin Dashboard**
   - Visit: http://localhost:8000/login
   - Email: `admin@dukcapil.ponorogo.go.id`
   - Password: `password`

2. **Add New Device**
   - Go to "Bots" menu
   - Click "Add New Device"
   - Enter Device Name and unique Device ID
   - Click "Create Device"

3. **Scan QR Code**
   - QR code will appear automatically
   - Open WhatsApp on your phone
   - Tap Menu ⋮ → Settings → Linked Devices
   - Tap "Link a Device"
   - Scan the QR code displayed on screen

4. **Start Using!**
   - Once connected, the bot will automatically receive and respond to messages
   - All conversations are logged in the admin dashboard

### Via CLI

```bash
# List all bots
php artisan whatsapp:bot list

# Start a specific bot
php artisan whatsapp:bot start --bot-id=bot-1

# Check bot status
php artisan whatsapp:bot status --bot-id=bot-1

# Stop a bot
php artisan whatsapp:bot stop --bot-id=bot-1
```

## 🏗️ Project Structure

```
dukcapil-wa/
├── app/                    # Laravel application code
│   ├── Console/Commands/  # CLI commands
│   │   └── WhatsAppBotCommand.php
│   ├── Http/Controllers/  # Controllers
│   ├── Models/            # Database models
│   └── Services/          # Business logic
│       ├── WhatsAppBotManager.php  # Bot lifecycle manager
│       └── WhatsAppService.php     # WhatsApp operations
├── bot-runtime/           # Auto-generated bot scripts
│   └── bot-{id}.js        # Individual bot instance script
├── storage/
│   ├── app/
│   │   └── whatsapp-sessions/  # WhatsApp sessions per bot
│   └── logs/
│       └── bot-{id}.log        # Bot logs
├── resources/
│   ├── views/             # Blade templates
│   │   └── admin/
│   │       ├── bots/      # Device management UI
│   │       └── dashboard.blade.php
│   ├── css/               # Tailwind styles
│   └── js/                # Frontend JavaScript
├── routes/
│   ├── web.php            # Web routes
│   └── api.php            # API routes
├── database/
│   └── migrations/        # Database migrations
└── .env                   # Laravel configuration
```

## 🎨 UI Theme

The application uses a WhatsApp-inspired green and white color scheme:

### Color Palette
- **Primary Green**: `#25D366` (WhatsApp brand green)
- **Dark Green**: `#128C7E` 
- **Darker Green**: `#075E54`
- **Light Green**: Various shades for backgrounds and accents

### Key UI Components
- **Navigation**: Green header with WhatsApp logo
- **Cards**: Rounded corners with shadow and hover effects
- **Status Badges**: Color-coded with borders (connected, pending, failed, etc.)
- **QR Code Display**: Center-focused with step-by-step instructions
- **Device Cards**: Grid layout with status indicators

## 🔧 Configuration

### Database
Default: SQLite (no additional setup needed)

To use MySQL/PostgreSQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dukcapil_wa
DB_USERNAME=root
DB_PASSWORD=
```

### Bot Server
Adjust timeout and port as needed:
```env
BOT_PORT=3000              # Bot server port
LARAVEL_TIMEOUT=5000       # API call timeout (ms)
```

## 🛡️ Security

**Important Security Steps:**

1. **Change Default Passwords**
   ```bash
   # After first login, change the default admin password
   ```

2. **Use Strong API Token**
   ```bash
   # Generate a 64-character random token
   php -r "echo bin2hex(random_bytes(32));"
   ```

3. **Enable HTTPS in Production**
   - Use SSL certificate
   - Update APP_URL to https://

4. **Restrict Access**
   - Configure firewall rules
   - Use IP whitelisting for admin panel

## 📊 Default Login Credentials

**Admin Account:**
- Email: `admin@dukcapil.ponorogo.go.id`
- Password: `password`

**Officer Account:**
- Email: `officer@dukcapil.ponorogo.go.id`
- Password: `password`

⚠️ **Change these passwords immediately after first login!**

## 🐛 Troubleshooting

### QR Code Not Showing
```bash
# Check bot server is running
cd bot && npm start

# Check logs
pm2 logs dukcapil-whatsapp-bot  # if using PM2
```

### Bot Disconnects Frequently
- WhatsApp sessions expire after 15-20 days
- Rescan QR code when this happens
- Session data is stored in `storage/app/whatsapp-sessions/`

### Build Errors
```bash
# Clear cache and rebuild
npm run build
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [WhatsApp Web.js Documentation](https://wwebjs.dev/)
- [Tailwind CSS Documentation](https://tailwindcss.com/)

## 🔄 Development

### Running in Development Mode
```bash
# Automatic (recommended)
npm run start
```

### Manual Development
```bash
# Terminal 1 - Laravel
php artisan serve

# Terminal 2 - Frontend
npm run dev
```

## 📝 License

MIT License

---

**Developed with ❤️ for DUKCAPIL Ponorogo**
