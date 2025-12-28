# Quick Start Guide - Fonnte WhatsApp API Integration

This guide helps you quickly set up WhatsApp messaging using Fonnte API.

## 🚀 Quick Setup (5 Minutes)

### Step 1: Get Fonnte Token

1. Visit [fonnte.com](https://fonnte.com)
2. Sign up for a free account
3. Login to dashboard
4. Scan QR code with WhatsApp to connect your number
5. Copy your API token from Settings > API

### Step 2: Create a Bot

**Method 1: Using .env (Global Configuration)**

```bash
# Edit .env file
FONNTE_TOKEN=your_token_here
```

**Method 2: Per-Bot Token (Recommended for multiple devices)**

1. Login to admin dashboard
2. Navigate to **Bots** → **Add New Device**
3. Fill in:
   - **Bot Name**: My WhatsApp Bot
   - **Bot ID**: bot-1
   - **Fonnte Token**: [paste your token]
4. Click **Create Bot**
5. Done! Your bot is connected ✅

### Step 3: Test Sending Messages

The bot is now ready to send and receive WhatsApp messages!

## 📋 Requirements

- PHP 8.2+
- Laravel 12
- Active Fonnte account
- WhatsApp number (personal or business)

## 🔗 Webhook Setup (Optional)

To receive incoming messages:

1. Go to Fonnte dashboard
2. Navigate to **Webhook** settings
3. Enter your webhook URL:
   ```
   https://yourdomain.com/api/webhook/whatsapp
   ```
4. Select events: **Message received**
5. Save

**For Local Development:**
```bash
# Use ngrok to expose local server
ngrok http 8000

# Use the https URL in Fonnte webhook
https://abc123.ngrok.io/api/webhook/whatsapp
```

## 💡 Key Features

✅ **Easy Setup** - No Facebook Business account required  
✅ **QR Code Based** - Quick connection via WhatsApp  
✅ **Multi-Device** - Manage multiple WhatsApp numbers  
✅ **Webhook Support** - Real-time message receiving  
✅ **Media Support** - Send images, documents, and more  
✅ **Auto-Reply** - Configure automated responses  

## 🔧 API Examples

### Send Text Message
```php
$whatsappService->sendMessage(
    '6281234567890',
    'Hello from DUKCAPIL Bot!'
);
```

### Send Image
```php
$whatsappService->sendMessage(
    '6281234567890',
    'Check this image!',
    null,
    [
        'url' => 'https://example.com/image.jpg',
        'filename' => 'image.jpg'
    ]
);
```

### Send Document
```php
$whatsappService->sendMessage(
    '6281234567890',
    'Your document is ready',
    null,
    [
        'url' => 'https://example.com/doc.pdf',
        'filename' => 'document.pdf'
    ]
);
```

## 🐛 Troubleshooting

### Token Invalid
- Generate new token in Fonnte dashboard
- Update token in .env or bot settings
- Restart application

### Messages Not Sending
- Check WhatsApp connection in Fonnte dashboard
- Verify phone number format: `6281234567890` (no + or spaces)
- Check Fonnte message quota

### Webhook Not Working
- Ensure URL uses HTTPS
- Verify webhook URL in Fonnte dashboard
- Check Laravel logs: `tail -f storage/logs/laravel.log`

## 📱 Phone Number Format

```php
// Correct formats:
6281234567890      // ✅ With country code
+6281234567890     // ✅ With + (auto-cleaned)

// Wrong formats:
081234567890       // ❌ Without country code
62-812-345-6789    // ❌ With hyphens
```

## 💰 Pricing

- **Free Trial**: 100 messages
- **Regular**: Starting from ~$5/month
- **Business**: Custom pricing

Visit [fonnte.com/pricing](https://fonnte.com/pricing)

## 📚 Documentation

- [Complete Guide (Indonesian)](FONNTE_SETUP_GUIDE.md)
- [Fonnte API Docs](https://fonnte.com/api)
- [Laravel Documentation](https://laravel.com/docs)

## 🆘 Support

- **Fonnte Support**: support@fonnte.com
- **GitHub Issues**: [Create an issue](https://github.com/nandasafiqalfiansyah/dukcapil-wa/issues)
- **Community**: [Fonnte Forum](https://forum.fonnte.com)

## ✨ Benefits vs WhatsApp Business API (Meta)

| Feature | Fonnte | Meta API |
|---------|--------|----------|
| Setup Complexity | Easy | Complex |
| Business Account | Not Required | Required |
| Approval Time | Instant | 1-3 days |
| Cost | ~$5/month | ~$0.05/msg |
| Best For | Small-Medium Business | Enterprise |

---

**Ready to start? Create your first bot now! 🚀**
