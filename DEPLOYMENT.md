# Foundation Management System - Deployment Guide

## Production Deployment

### Server Requirements

- Ubuntu 22.04 LTS or similar
- Nginx or Apache
- PHP 8.3+ with extensions: php8.3-fpm, php8.3-mysql, php8.3-xml, php8.3-mbstring, php8.3-zip, php8.3-gd, php8.3-curl
- MySQL 8.0+
- Composer 2.x
- Node.js 18+
- SSL certificate (Let's Encrypt recommended)

### Step 1: Server Setup

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx php8.3-fpm php8.3-mysql php8.3-xml php8.3-mbstring php8.3-zip php8.3-gd php8.3-curl php8.3-intl php8.3-bcmath php8.3-soap

# Install MySQL
sudo apt install -y mysql-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

### Step 2: Database Setup

```bash
sudo mysql_secure_installation

# Create database and user
sudo mysql
```

```sql
CREATE DATABASE foundation_management_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'fms_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON foundation_management_system.* TO 'fms_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 3: Application Setup

```bash
# Clone repository
cd /var/www
sudo git clone <repository-url> foundation-ms
cd foundation-ms

# Set permissions
sudo chown -R www-data:www-data /var/www/foundation-ms
sudo chmod -R 775 /var/www/foundation-ms/storage /var/www/foundation-ms/bootstrap/cache

# Install dependencies
sudo -u www-data composer install --no-dev --optimize-autoloader

# Environment configuration
sudo -u www-data cp .env.example .env
sudo -u www-data php artisan key:generate
```

### Step 4: Configure Environment

```bash
sudo nano /var/www/foundation-ms/.env
```

Update with production values:

```env
APP_NAME="Foundation Management System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=foundation_management_system
DB_USERNAME=fms_user
DB_PASSWORD=strong_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@foundation.org
MAIL_FROM_NAME="${APP_NAME}"

STRIPE_KEY=pk_live_xxxxx
STRIPE_SECRET=sk_live_xxxxx
```

### Step 5: Run Migrations

```bash
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan db:seed --force
sudo -u www-data php artisan storage:link
```

### Step 6: Build Assets

```bash
sudo -u www-data npm install
sudo -u www-data npm run build
```

### Step 7: Nginx Configuration

```bash
sudo nano /etc/nginx/sites-available/foundation-ms
```

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com www.your-domain.com;

    root /var/www/foundation-ms/public;
    index index.php;

    ssl_certificate /etc/letsencrypt/live/your-domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-domain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;

    charset utf-8;

    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~ /\.(jpg|jpeg|gif|png|webp|svg|ico|css|js)$ {
        expires 1y;
        access_log off;
    }

    location ~ /\.(sql|log)$ {
        deny all;
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/foundation-ms /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Step 8: SSL Certificate

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

### Step 9: Queue Worker Setup

```bash
# Create systemd service
sudo nano /etc/systemd/system/fms-worker.service
```

```ini
[Unit]
Description=FMS Queue Worker
After=network.target

[Service]
Type=oneshot
User=www-data
WorkingDirectory=/var/www/foundation-ms
ExecStart=/usr/bin/php /var/www/foundation-ms/artisan queue:work --tries=3 --max-time=3600

[Install]
WantedBy=multi-user.target
```

```bash
# Or use supervisor for continuous worker
sudo apt install -y supervisor
sudo nano /etc/supervisor/conf.d/fms-worker.conf
```

```ini
[program:fms-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/foundation-ms/artisan queue:work --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/foundation-ms/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start fms-worker:*
```

### Step 10: Scheduler Setup

```bash
# Add to crontab
sudo crontab -e
```

```bash
* * * * * cd /var/www/foundation-ms && php artisan schedule:run >> /dev/null 2>&1
```

### Step 11: Security Hardening

```bash
# Firewall
sudo ufw allow 'Nginx Full'
sudo ufw delete allow 'Nginx HTTP'
sudo ufw enable

# File permissions
sudo find /var/www/foundation-ms -type f -exec chmod 644 {} \;
sudo find /var/www/foundation-ms -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/foundation-ms/storage /var/www/foundation-ms/bootstrap/cache
```

### Step 12: Monitoring

```bash
# Create health check endpoint
sudo -u www-data php artisan route:list --path=health
```

Add to your monitoring system:
- Health check: `https://your-domain.com/health`
- Queue worker status via supervisor
- Error logs: `/var/www/foundation-ms/storage/logs/laravel.log`

## Docker Deployment (Alternative)

```bash
# Build and run with Docker
docker-compose up -d

# With custom environment
cp .env.docker .env
docker-compose up -d --build
```

## Update Procedure

```bash
cd /var/www/foundation-ms
sudo -u www-data git pull origin main
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data npm run build
sudo systemctl restart php8.3-fpm
sudo supervisorctl restart fms-worker:*
```

## Backup Procedure

```bash
# Database backup
mysqldump -u fms_user -p foundation_management_system > backup_$(date +%Y%m%d).sql

# Files backup
tar -czf files_backup_$(date +%Y%m%d).tar.gz /var/www/foundation-ms/storage/app

# Automated backup (add to crontab)
0 2 * * * /path/to/backup-script.sh
```

## Troubleshooting

### High CPU Usage
```bash
# Check queue workers
sudo supervisorctl status

# Restart workers
sudo supervisorctl restart fms-worker:*
```

### Memory Issues
```bash
# Increase PHP memory
sudo nano /etc/php/8.3/fpm/php.ini
# memory_limit = 256M
sudo systemctl reload php8.3-fpm
```

### Database Connection Issues
```bash
# Test MySQL connection
mysql -u fms_user -p -h 127.0.0.1 foundation_management_system
```

## Support

For production support, contact the development team.
