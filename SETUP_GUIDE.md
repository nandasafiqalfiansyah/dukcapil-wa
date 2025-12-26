# DUKCAPIL WhatsApp Bot - Complete Setup Guide

## Table of Contents
1. [System Requirements](#system-requirements)
2. [Installation](#installation)
3. [WhatsApp Business API Configuration](#whatsapp-business-api-configuration)
4. [Database Configuration](#database-configuration)
5. [Running the Application](#running-the-application)
6. [Production Deployment](#production-deployment)
7. [Troubleshooting](#troubleshooting)

## System Requirements

### Minimum Requirements
- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 18.x or higher
- **NPM**: 9.x or higher
- **Database**: SQLite (included) or MySQL 8.0+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **SSL Certificate**: Required for production (webhook)

### Recommended Server Specs
- **CPU**: 2 cores
- **RAM**: 2GB
- **Storage**: 10GB SSD
- **OS**: Ubuntu 20.04 LTS or newer

## Installation

### 1. Clone Repository

```bash
git clone https://github.com/yourusername/dukcapil-wa.git
cd dukcapil-wa
```

### 2. Install PHP Dependencies

```bash
composer install --optimize-autoloader --no-dev
```

For development:
```bash
composer install
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

### 4. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Database Setup

#### Using SQLite (Default)
```bash
touch database/database.sqlite
php artisan migrate --seed
```

#### Using MySQL
1. Create database:
```sql
CREATE DATABASE dukcapil_wa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dukcapil_wa
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

3. Run migrations:
```bash
php artisan migrate --seed
```

### 6. Build Frontend Assets

For production:
```bash
npm run build
```

For development:
```bash
npm run dev
```

## WhatsApp Business API Configuration

### Step 1: Create Meta App

1. Visit [Meta for Developers](https://developers.facebook.com/)
2. Click "My Apps" → "Create App"
3. Select "Business" type
4. Fill in app details
5. Add WhatsApp product to your app

### Step 2: Get API Credentials

#### Access Token
1. Go to WhatsApp → Getting Started
2. Copy temporary access token (for testing)
3. For production, create permanent token:
   - Business Settings → System Users
   - Create system user
   - Generate token with `whatsapp_business_messaging` permission
   - **Important**: Save this token securely!

#### Phone Number ID
1. In WhatsApp → Getting Started
2. Find "Phone Number ID" section
3. Copy the ID (numeric value)

#### Verify Token
Generate a secure random string:
```bash
php -r "echo bin2hex(random_bytes(32));"
```

### Step 3: Configure Environment

Update `.env` file:

```env
# Application
APP_NAME="DUKCAPIL WhatsApp Bot"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# WhatsApp Business API
WHATSAPP_API_URL=https://graph.facebook.com/v18.0
WHATSAPP_ACCESS_TOKEN=your_permanent_access_token
WHATSAPP_PHONE_NUMBER_ID=123456789012345
WHATSAPP_VERIFY_TOKEN=your_secure_random_string
```

### Step 4: Setup Webhook

1. In Meta Dashboard, go to WhatsApp → Configuration
2. Click "Edit" in Webhook section
3. Configure:
   - **Callback URL**: `https://yourdomain.com/api/webhook/whatsapp`
   - **Verify Token**: Same as `WHATSAPP_VERIFY_TOKEN` in .env
4. Subscribe to fields:
   - `messages` ✓
   - `message_status` ✓ (optional)
5. Click "Verify and Save"

**Important**: Your domain must have valid SSL certificate!

## Database Configuration

### SQLite (Default - Recommended for Small Deployments)

Advantages:
- No separate database server
- Zero configuration
- Perfect for testing and small deployments

Limitations:
- Not suitable for high-traffic sites
- Limited concurrent write operations

Configuration:
```env
DB_CONNECTION=sqlite
```

### MySQL (Recommended for Production)

Advantages:
- Better performance under load
- Concurrent operations
- Advanced features

Configuration:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dukcapil_wa
DB_USERNAME=dbuser
DB_PASSWORD=secure_password
```

### PostgreSQL (Alternative)

Configuration:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=dukcapil_wa
DB_USERNAME=dbuser
DB_PASSWORD=secure_password
```

## Running the Application

### Development Mode (Single Terminal)

```bash
composer run dev
```

This starts:
- Laravel development server (port 8000)
- Queue worker
- Vite development server

### Production Mode

#### 1. Optimize Application

```bash
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 2. Setup Web Server

##### Nginx Configuration

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com;
    root /var/www/dukcapil-wa/public;

    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

##### Apache Configuration

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    Redirect permanent / https://yourdomain.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName yourdomain.com
    DocumentRoot /var/www/dukcapil-wa/public

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/yourdomain.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/yourdomain.com/privkey.pem

    <Directory /var/www/dukcapil-wa/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/dukcapil_error.log
    CustomLog ${APACHE_LOG_DIR}/dukcapil_access.log combined
</VirtualHost>
```

#### 3. Setup Queue Worker

Create supervisor configuration: `/etc/supervisor/conf.d/dukcapil-queue.conf`

```ini
[program:dukcapil-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/dukcapil-wa/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/dukcapil-wa/storage/logs/queue.log
stopwaitsecs=3600
```

Start supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start dukcapil-queue:*
```

#### 4. Setup Cron Jobs

Add to crontab:
```bash
sudo crontab -e -u www-data
```

Add line:
```
* * * * * cd /var/www/dukcapil-wa && php artisan schedule:run >> /dev/null 2>&1
```

## Production Deployment

### SSL Certificate (Required)

Using Let's Encrypt:
```bash
sudo apt update
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

### File Permissions

```bash
sudo chown -R www-data:www-data /var/www/dukcapil-wa
sudo chmod -R 755 /var/www/dukcapil-wa
sudo chmod -R 775 /var/www/dukcapil-wa/storage
sudo chmod -R 775 /var/www/dukcapil-wa/bootstrap/cache
```

### Security Checklist

- [ ] Change default admin password
- [ ] Set `APP_DEBUG=false` in production
- [ ] Use strong `APP_KEY`
- [ ] Secure database credentials
- [ ] Keep `WHATSAPP_ACCESS_TOKEN` secret
- [ ] Enable HTTPS only
- [ ] Configure firewall (UFW/iptables)
- [ ] Regular backups
- [ ] Keep dependencies updated

### Backup Strategy

#### Database Backup

For SQLite:
```bash
cp database/database.sqlite database/backup-$(date +%Y%m%d).sqlite
```

For MySQL:
```bash
mysqldump -u username -p dukcapil_wa > backup-$(date +%Y%m%d).sql
```

#### Full Application Backup

```bash
tar -czf dukcapil-backup-$(date +%Y%m%d).tar.gz \
    /var/www/dukcapil-wa \
    --exclude=node_modules \
    --exclude=vendor \
    --exclude=storage/logs
```

## Troubleshooting

### Common Issues

#### 1. Webhook Verification Failed

**Symptoms**: Meta can't verify webhook
**Solutions**:
- Ensure SSL certificate is valid
- Check `WHATSAPP_VERIFY_TOKEN` matches in .env and Meta
- Verify URL is accessible from internet
- Check firewall settings

#### 2. Messages Not Received

**Symptoms**: Incoming messages don't appear
**Solutions**:
- Check webhook subscription in Meta dashboard
- Review `storage/logs/laravel.log`
- Ensure queue worker is running
- Test webhook manually

#### 3. Cannot Send Messages

**Symptoms**: 403 or 401 errors when sending
**Solutions**:
- Verify `WHATSAPP_ACCESS_TOKEN` is valid and not expired
- Check `WHATSAPP_PHONE_NUMBER_ID` is correct
- Ensure phone number format is correct (no spaces or special chars)
- Verify Meta app permissions

#### 4. Queue Worker Not Processing

**Symptoms**: Jobs stuck in database
**Solutions**:
- Check supervisor status: `sudo supervisorctl status`
- Restart queue worker: `sudo supervisorctl restart dukcapil-queue:*`
- Check logs: `tail -f storage/logs/queue.log`
- Verify database connection

#### 5. Application Errors

**Check logs**:
```bash
tail -f storage/logs/laravel.log
```

**Clear cache**:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Performance Optimization

#### Enable OPcache

Edit `/etc/php/8.2/fpm/php.ini`:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

#### Enable Redis Cache (Optional)

Install Redis:
```bash
sudo apt install redis-server php-redis
```

Update `.env`:
```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Monitoring

#### Check Application Health

```bash
php artisan health:check
```

#### Monitor Queue

```bash
php artisan queue:monitor
```

#### Check Logs

```bash
tail -f storage/logs/laravel.log
```

## Getting Help

### Documentation
- [Laravel Documentation](https://laravel.com/docs)
- [WhatsApp Business API Docs](https://developers.facebook.com/docs/whatsapp)
- [Repository Issues](https://github.com/yourusername/dukcapil-wa/issues)

### Support Channels
1. Check existing documentation
2. Review GitHub issues
3. Create new issue with:
   - Detailed description
   - Steps to reproduce
   - Error logs
   - Environment details

---

## Next Steps

After setup:
1. Login to admin dashboard
2. Create your first bot device
3. Configure auto-reply rules
4. Test message sending/receiving
5. Monitor system performance

**Need help?** Check [WHATSAPP_BUSINESS_API_GUIDE.md](WHATSAPP_BUSINESS_API_GUIDE.md) for detailed API setup.

---

**Built with ❤️ for DUKCAPIL Ponorogo**
