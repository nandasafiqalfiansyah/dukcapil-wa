# WhatsApp Business API Setup Guide

## Overview

This application now uses WhatsApp Business API (Meta/Facebook's official API) instead of WhatsApp Web.js. This provides:
- ✅ More reliable messaging
- ✅ No QR code scanning required
- ✅ Better for production use
- ✅ Official support from Meta
- ✅ Single terminal operation

## Prerequisites

1. **Facebook Business Account** - You need a Facebook Business account
2. **Meta App** - Create an app in Meta for Developers
3. **WhatsApp Business Phone Number** - A phone number dedicated to WhatsApp Business

## Step-by-Step Setup

### 1. Create Meta App

1. Go to [Meta for Developers](https://developers.facebook.com/)
2. Click "My Apps" → "Create App"
3. Select "Business" as app type
4. Fill in the app details:
   - App Name: "DUKCAPIL WhatsApp Bot"
   - Contact Email: Your email
   - Business Account: Select your business

### 2. Add WhatsApp Product

1. In your app dashboard, find "WhatsApp" in the products list
2. Click "Set up" on WhatsApp
3. Follow the setup wizard to:
   - Add a phone number or use test number
   - Verify your business

### 3. Get API Credentials

#### Access Token
1. Go to WhatsApp → API Setup
2. Copy the "Temporary access token" (for testing)
3. For production, generate a "Permanent token":
   - Go to Settings → Business Settings → System Users
   - Create a system user
   - Generate token with `whatsapp_business_messaging` permission

#### Phone Number ID
1. In WhatsApp → API Setup
2. Find "Phone Number ID" under "From"
3. Copy this ID

#### Verify Token
1. Create a random secure string for verification
2. Example: Use `php -r "echo bin2hex(random_bytes(32));"`

### 4. Configure Application

Add credentials to your `.env` file:

```env
WHATSAPP_API_URL=https://graph.facebook.com/v18.0
WHATSAPP_ACCESS_TOKEN=your_permanent_access_token_here
WHATSAPP_PHONE_NUMBER_ID=123456789012345
WHATSAPP_VERIFY_TOKEN=your_random_secure_string
```

### 5. Setup Webhook

#### Configure Webhook in Meta Dashboard

1. Go to WhatsApp → Configuration → Webhook
2. Click "Edit"
3. Set these values:
   - Callback URL: `https://yourdomain.com/api/webhook/whatsapp`
   - Verify Token: Same as `WHATSAPP_VERIFY_TOKEN` in .env
4. Subscribe to webhook fields:
   - `messages` (required)
   - `message_status` (optional)

#### Important Notes:
- Your webhook URL must be HTTPS (SSL required)
- Use ngrok for local testing: `ngrok http 8000`
- Meta will send a GET request to verify the webhook

### 6. Test the Integration

1. Create a bot in the admin dashboard:
   ```
   Login → Bots → Add New Device
   Bot Name: Test Bot
   Bot ID: test-bot-1
   ```

2. Send a test message from Meta dashboard:
   - Go to WhatsApp → API Setup
   - Use "Send a test message" feature
   - Send to a phone number (format: country code + number, e.g., 6281234567890)

3. Check if message appears in admin dashboard

## Pricing & Limits

### Free Tier
- **1,000 conversations/month** free
- Each conversation = 24-hour messaging window
- Additional conversations: ~$0.05 per conversation (varies by country)

### Rate Limits
- **80 messages/second** per phone number
- **Quality rating** affects limits (maintain high quality)

### Best Practices
1. Use templates for business-initiated messages
2. Respond to user messages within 24 hours
3. Don't spam users
4. Follow WhatsApp Business Policy

## Message Templates

For business-initiated conversations, you need approved templates:

### Creating Templates

1. Go to WhatsApp → Message Templates
2. Click "Create Template"
3. Fill in:
   - Template name (lowercase, underscores allowed)
   - Category (e.g., UTILITY, MARKETING)
   - Language
   - Content with placeholders

### Example Template
```
Name: greeting_message
Category: UTILITY
Content: Halo {{1}}, selamat datang di DUKCAPIL Ponorogo. Ada yang bisa kami bantu?
```

### Using Templates in Code
```php
$this->whatsappService->sendTemplateMessage(
    '6281234567890',
    'greeting_message',
    [
        [
            'type' => 'body',
            'parameters' => [
                ['type' => 'text', 'text' => 'John Doe']
            ]
        ]
    ]
);
```

## Webhook Events

Your application receives these webhook events:

### Message Event
```json
{
  "entry": [{
    "changes": [{
      "value": {
        "messages": [{
          "from": "6281234567890",
          "id": "wamid.XXX",
          "timestamp": "1234567890",
          "type": "text",
          "text": {
            "body": "Hello"
          }
        }]
      }
    }]
  }]
}
```

### Status Update
```json
{
  "entry": [{
    "changes": [{
      "value": {
        "statuses": [{
          "id": "wamid.XXX",
          "status": "delivered",
          "timestamp": "1234567890"
        }]
      }
    }]
  }]
}
```

## Troubleshooting

### Messages Not Received
1. Check webhook configuration in Meta dashboard
2. Verify webhook URL is accessible (HTTPS)
3. Check `storage/logs/laravel.log` for errors
4. Ensure `WHATSAPP_VERIFY_TOKEN` matches in .env and Meta

### Cannot Send Messages
1. Verify `WHATSAPP_ACCESS_TOKEN` is valid
2. Check `WHATSAPP_PHONE_NUMBER_ID` is correct
3. Ensure phone number format is correct (no +, spaces, or hyphens)
4. Check if you're within rate limits

### Webhook Verification Failed
1. Ensure `WHATSAPP_VERIFY_TOKEN` matches exactly
2. Check if your server is reachable from Meta's servers
3. Look for errors in `storage/logs/laravel.log`

### 403 Forbidden Error
- Access token expired or invalid
- Generate a new permanent access token

### 400 Bad Request Error
- Phone number format incorrect (use: 6281234567890, not +62-812-3456-7890)
- Template not approved or doesn't exist
- Invalid parameters in request

## Local Development

### Using ngrok

1. Install ngrok: https://ngrok.com/download
2. Run your Laravel app: `php artisan serve`
3. Expose it: `ngrok http 8000`
4. Use the ngrok URL in Meta webhook configuration:
   ```
   https://abc123.ngrok.io/api/webhook/whatsapp
   ```
5. Update .env with ngrok URL:
   ```env
   APP_URL=https://abc123.ngrok.io
   ```

## Production Deployment

### SSL Certificate
- Required for webhook URL
- Use Let's Encrypt (free): `certbot --nginx -d yourdomain.com`

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
WHATSAPP_ACCESS_TOKEN=permanent_token_here
WHATSAPP_PHONE_NUMBER_ID=production_phone_id
WHATSAPP_VERIFY_TOKEN=secure_random_string
```

### Queue Worker
Run queue worker to process messages:
```bash
php artisan queue:work --tries=3
```

Use Supervisor to keep queue worker running:
```ini
[program:dukcapil-queue]
command=php /path/to/artisan queue:work --tries=3
directory=/path/to/dukcapil-wa
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/dukcapil-wa/storage/logs/queue.log
```

## Migration from WhatsApp Web.js

If you're migrating from the old QR-code based system:

1. **No session migration needed** - Business API uses different authentication
2. **Update bot instances** - Remove old bot processes
3. **Configure API credentials** - Follow steps above
4. **Test thoroughly** - Ensure messages flow correctly
5. **Update documentation** - Inform users about new setup

## Resources

- [WhatsApp Business Platform Documentation](https://developers.facebook.com/docs/whatsapp)
- [WhatsApp Business API Reference](https://developers.facebook.com/docs/whatsapp/api/messages)
- [Message Templates Guide](https://developers.facebook.com/docs/whatsapp/api/messages/message-templates)
- [Webhook Reference](https://developers.facebook.com/docs/whatsapp/webhooks)

## Support

For issues or questions:
1. Check [Meta Developer Community](https://developers.facebook.com/community/)
2. Review [WhatsApp Business Policy](https://www.whatsapp.com/legal/business-policy)
3. Open an issue in this repository

---

**Happy Messaging! 📱💬**
