# Deployment Guide for ZRZ POS System

This guide will help you deploy your Laravel POS application to a production server.

## Server Requirements

- **PHP**: 8.2 or higher
- **Extensions Required**:
  - OpenSSL PHP Extension
  - PDO PHP Extension
  - Mbstring PHP Extension
  - Tokenizer PHP Extension
  - XML PHP Extension
  - Ctype PHP Extension
  - JSON PHP Extension
  - BCMath PHP Extension
  - Fileinfo PHP Extension
  - GD or Imagick PHP Extension (for image processing)
  - Zip PHP Extension
- **Database**: MySQL 5.7+ / MariaDB 10.3+ / PostgreSQL 10+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Composer**: Latest version
- **Node.js & NPM**: (if you need to compile assets)

## Pre-Deployment Checklist

1. ✅ Ensure all code is committed to version control
2. ✅ Test the application locally
3. ✅ Backup your database
4. ✅ Prepare your `.env` file with production settings
5. ✅ Ensure server meets all requirements

## Deployment Methods

### Method 1: Using FTP/SFTP (Traditional)

#### Step 1: Prepare Files for Upload

**Files/Folders to EXCLUDE** (don't upload):
- `.env` (create new on server)
- `vendor/` (install via Composer on server)
- `node_modules/` (if exists)
- `.git/` (optional, but usually excluded)
- `storage/logs/*` (keep folder, exclude log files)
- `.phpunit.result.cache`
- `Homestead.json`, `Homestead.yaml`
- Any local development files

**Files/Folders to INCLUDE**:
- All files in `app/`
- All files in `config/`
- All files in `database/`
- All files in `public/`
- All files in `resources/`
- All files in `routes/`
- All files in `Modules/`
- `artisan`
- `composer.json`
- `composer.lock`
- `package.json` (if exists)
- `.gitignore`
- `server.php`
- `bootstrap/` (except cache files)

#### Step 2: Upload Files to Server

1. Connect to your server via FTP/SFTP (FileZilla, WinSCP, etc.)
2. Upload all files to your web root directory (usually `public_html/` or `www/`)
3. Ensure file permissions are correct (see Step 4)

#### Step 3: Set Up Environment File

1. On the server, create a `.env` file in the root directory
2. Copy the structure from `.env.example` (if exists) or create manually:

```env
APP_NAME="ZRZ POS"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

3. Generate application key:
   ```bash
   php artisan key:generate
   ```

#### Step 4: Set File Permissions

**On Linux/Unix servers:**
```bash
# Set ownership (replace 'www-data' with your web server user)
sudo chown -R www-data:www-data /path/to/your/project

# Set directory permissions
find /path/to/your/project -type d -exec chmod 755 {} \;

# Set file permissions
find /path/to/your/project -type f -exec chmod 644 {} \;

# Special permissions for storage and bootstrap/cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

**On Windows/IIS servers:**
- Right-click folders → Properties → Security
- Give `IIS_IUSRS` full control to:
  - `storage/`
  - `bootstrap/cache/`

#### Step 5: Install Dependencies

**Via SSH/Terminal:**
```bash
# Navigate to project directory
cd /path/to/your/project

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# If you have frontend assets to compile
npm install
npm run build
```

**If you don't have SSH access:**
- Install Composer locally
- Run `composer install --optimize-autoloader --no-dev` locally
- Upload the `vendor/` folder to the server

#### Step 6: Configure Web Server

**For Apache (.htaccess should be in public/):**

Ensure `public/.htaccess` exists and contains:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

**Apache Virtual Host Configuration:**
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /path/to/your/project/public

    <Directory /path/to/your/project/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

**For Nginx:**

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/your/project/public;

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

**For IIS (Windows Server):**
- The `web.config` file in `public/` should handle URL rewriting
- Ensure URL Rewrite module is installed in IIS
- Point the website root to the `public/` folder

#### Step 7: Run Database Migrations

```bash
php artisan migrate --force
```

**If you have seeders:**
```bash
php artisan db:seed --force
```

#### Step 8: Optimize Application

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

#### Step 9: Create Storage Link

```bash
php artisan storage:link
```

### Method 2: Using Git (Recommended)

#### Step 1: Push Code to Repository

```bash
git add .
git commit -m "Ready for production"
git push origin main
```

#### Step 2: On Server - Clone Repository

```bash
cd /path/to/web/root
git clone https://your-repository-url.git your-project-name
cd your-project-name
```

#### Step 3: Follow Steps 3-9 from Method 1

### Method 3: Using Deployment Scripts (Advanced)

Create a `deploy.sh` script:

```bash
#!/bin/bash

# Pull latest code
git pull origin main

# Install dependencies
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize
composer dump-autoload --optimize

echo "Deployment completed!"
```

## Post-Deployment Tasks

1. **Test the Application**
   - Visit your domain
   - Test login functionality
   - Test key features

2. **Set Up Cron Jobs**
   
   Add to crontab (`crontab -e`):
   ```bash
   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
   ```

3. **Set Up Queue Worker** (if using queues)
   ```bash
   php artisan queue:work --daemon
   ```
   Or use Supervisor to manage the queue worker.

4. **Enable HTTPS/SSL**
   - Install SSL certificate (Let's Encrypt, etc.)
   - Update `APP_URL` in `.env` to use `https://`
   - Force HTTPS in your web server configuration

5. **Monitor Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

6. **Set Up Backups**
   - Database backups (daily)
   - File backups (weekly)
   - Store backups off-server

## Troubleshooting

### Common Issues:

1. **500 Internal Server Error**
   - Check file permissions
   - Check `.env` file exists and is configured
   - Check `storage/` and `bootstrap/cache/` are writable
   - Check error logs: `storage/logs/laravel.log`

2. **Permission Denied**
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

3. **Composer Memory Limit**
   ```bash
   php -d memory_limit=-1 /usr/bin/composer install
   ```

4. **Module Not Found Errors**
   - Ensure modules are properly installed
   - Run: `php artisan module:migrate`
   - Check `modules_statuses.json` exists

5. **Database Connection Error**
   - Verify database credentials in `.env`
   - Ensure database exists
   - Check database user has proper permissions

## Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] Strong `APP_KEY` generated
- [ ] Database credentials are secure
- [ ] File permissions are correct
- [ ] `.env` file is not publicly accessible
- [ ] HTTPS/SSL is enabled
- [ ] Regular security updates applied
- [ ] Firewall configured
- [ ] Backup strategy in place

## Additional Notes

- This application uses **Laravel Modules** - ensure modules are properly migrated
- The application has **Ecommerce** and **Manufacturing** modules
- Check `modules_statuses.json` for module status
- Some features may require additional server configuration (SMS, Payment gateways, etc.)

## Support

For issues specific to this application, check:
- Application logs: `storage/logs/laravel.log`
- Server error logs
- Laravel documentation: https://laravel.com/docs

---

**Last Updated**: 2024

