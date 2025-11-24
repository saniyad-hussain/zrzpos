# Shared Hosting Deployment Guide (Hostinger)

This guide will help you deploy your Laravel POS application to Hostinger shared hosting using the File Manager.

## Prerequisites

- Hostinger hosting account with cPanel access
- Database created in Hostinger (MySQL)
- FTP/File Manager access
- PHP 8.2 or higher enabled
- Composer installed locally (on your computer)

## Step 1: Prepare Files for Upload

### Files/Folders to EXCLUDE (DO NOT upload):
- `.env` (we'll create this on the server)
- `.git/` folder
- `node_modules/` (if exists)
- `storage/logs/*` (keep the folder, but delete log files)
- `bootstrap/cache/*.php` (keep the folder, but delete cache files)
- `.phpunit.result.cache`
- `Homestead.json`, `Homestead.yaml`
- `vendor/` (we'll install this separately)

### Files/Folders to INCLUDE (upload these):
- `app/`
- `bootstrap/` (empty the cache folder first)
- `config/`
- `database/`
- `Modules/`
- `public/`
- `resources/`
- `routes/`
- `storage/` (empty the logs folder first)
- `artisan`
- `composer.json`
- `composer.lock`
- `package.json` (if exists)
- `server.php`
- `.gitignore`

## Step 2: Install Dependencies Locally

Before uploading, install dependencies on your local machine:

```bash
# Navigate to your project directory
cd C:\laragon\www\zrzpos

# Install dependencies (production mode)
composer install --optimize-autoloader --no-dev

# This will create/update the vendor folder
```

**Important:** After running this command, you'll need to upload the `vendor/` folder to your server.

## Step 3: Upload Files to Hostinger

### Option A: Using File Manager (Recommended for beginners)

1. **Log in to Hostinger hPanel**
   - Go to https://hpanel.hostinger.com
   - Log in with your credentials

2. **Open File Manager**
   - Navigate to **Files** → **File Manager**
   - Go to `public_html` folder (this is your web root)

3. **Upload Files**
   - Click **Upload** button
   - Select all files and folders (except those in the exclude list)
   - Upload them to `public_html`
   - **Important:** Upload the `vendor/` folder that was created in Step 2

4. **Upload Structure**
   ```
   public_html/
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── database/
   ├── Modules/
   ├── public/          ← All files from your local public/ folder
   ├── resources/
   ├── routes/
   ├── storage/
   ├── vendor/          ← Upload this folder
   ├── artisan
   ├── composer.json
   ├── composer.lock
   └── server.php
   ```

### Option B: Using FTP Client (Faster for large files)

1. **Get FTP Credentials**
   - In hPanel, go to **Files** → **FTP Accounts**
   - Note your FTP host, username, and password

2. **Connect with FTP Client**
   - Use FileZilla, WinSCP, or any FTP client
   - Connect to your server
   - Navigate to `public_html` folder
   - Upload all files and folders

## Step 4: Move Public Folder Contents

**CRITICAL STEP:** Laravel's `public/` folder should be your document root.

### Method 1: Move public folder contents (Recommended)

1. In File Manager, go to `public_html/public/`
2. Select ALL files and folders inside `public/`
3. Move them to `public_html/` (one level up)
4. Delete the now-empty `public/` folder

**Result:** Your `public_html/` should now contain:
- `index.php` (from public folder)
- `css/`, `js/`, `images/`, etc. (from public folder)
- Plus all your Laravel folders (app, config, etc.)

### Method 2: Update index.php paths (Alternative)

If you can't move files, you need to update `public_html/index.php`:

1. Open `public_html/index.php` in File Manager editor
2. Change these lines:
   ```php
   // Change from:
   require __DIR__.'/../vendor/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';
   
   // To:
   require __DIR__.'/vendor/autoload.php';
   $app = require_once __DIR__.'/bootstrap/app.php';
   ```

## Step 5: Create .env File

1. **In File Manager**, go to `public_html/`
2. **Create new file** named `.env`
3. **Add this content** (adjust values for your server):

```env
APP_NAME="ZRZ POS"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
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
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=your_email@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

**Important:**
- Replace `yourdomain.com` with your actual domain
- Replace database credentials with your Hostinger database details
- Replace email settings with your Hostinger email settings

## Step 6: Set File Permissions

1. **In File Manager**, right-click on `storage/` folder
2. Select **Change Permissions** (or **File Permissions**)
3. Set to **755** (or **775** if available)
4. Check **Recurse into subdirectories**
5. Click **Change Permissions**

6. **Repeat for:**
   - `bootstrap/cache/` folder → **755** or **775**
   - `storage/` folder → **755** or **775**

## Step 7: Generate Application Key

### Option A: Using SSH (if available)

1. **Open SSH Terminal** in hPanel
2. Navigate to your project:
   ```bash
   cd public_html
   ```
3. Generate key:
   ```bash
   php artisan key:generate
   ```

### Option B: Using PHP Script (if no SSH)

1. **Create a file** `generate_key.php` in `public_html/`
2. **Add this code:**
   ```php
   <?php
   require __DIR__.'/vendor/autoload.php';
   $app = require_once __DIR__.'/bootstrap/app.php';
   $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
   $kernel->bootstrap();
   Artisan::call('key:generate');
   echo "Application key generated!";
   ```
3. **Visit** `https://yourdomain.com/generate_key.php` in browser
4. **Delete** `generate_key.php` after use

## Step 8: Run Database Migrations

### Option A: Using SSH

```bash
cd public_html
php artisan migrate --force
```

### Option B: Using PHP Script

1. **Create** `run_migrations.php` in `public_html/`:
   ```php
   <?php
   require __DIR__.'/vendor/autoload.php';
   $app = require_once __DIR__.'/bootstrap/app.php';
   $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
   $kernel->bootstrap();
   Artisan::call('migrate', ['--force' => true]);
   echo "Migrations completed!";
   ```
2. **Visit** `https://yourdomain.com/run_migrations.php`
3. **Delete** the file after use

## Step 9: Create Storage Link

### Option A: Using SSH

```bash
php artisan storage:link
```

### Option B: Using PHP Script

1. **Create** `create_storage_link.php`:
   ```php
   <?php
   require __DIR__.'/vendor/autoload.php';
   $app = require_once __DIR__.'/bootstrap/app.php';
   $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
   $kernel->bootstrap();
   Artisan::call('storage:link');
   echo "Storage link created!";
   ```
2. **Visit** the file in browser
3. **Delete** after use

## Step 10: Optimize Application

### Option A: Using SSH

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Option B: Using PHP Script

1. **Create** `optimize.php`:
   ```php
   <?php
   require __DIR__.'/vendor/autoload.php';
   $app = require_once __DIR__.'/bootstrap/app.php';
   $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
   $kernel->bootstrap();
   
   Artisan::call('config:cache');
   Artisan::call('route:cache');
   Artisan::call('view:cache');
   
   echo "Application optimized!";
   ```
2. **Visit** the file
3. **Delete** after use

## Step 11: Configure Domain (if needed)

1. **In hPanel**, go to **Domains** → **Manage**
2. **Point your domain** to `public_html` folder
3. **Ensure** `.htaccess` file exists in `public_html/`

## Step 12: Test Your Application

1. **Visit** your domain: `https://yourdomain.com`
2. **Test login** functionality
3. **Check** if pages load correctly
4. **Verify** file uploads work (check storage permissions)

## Troubleshooting

### Issue: 500 Internal Server Error

**Solutions:**
1. Check `.env` file exists and has correct values
2. Check file permissions (storage and bootstrap/cache should be 755/775)
3. Check error logs: `storage/logs/laravel.log`
4. Verify `APP_KEY` is generated
5. Check database connection in `.env`

### Issue: Permission Denied

**Solution:**
- Set `storage/` and `bootstrap/cache/` to 755 or 775
- Ensure folders are writable

### Issue: Database Connection Error

**Solutions:**
1. Verify database credentials in `.env`
2. Check database exists in Hostinger
3. Verify database user has proper permissions
4. Try `localhost` instead of `127.0.0.1` for `DB_HOST`

### Issue: Vendor Folder Missing

**Solution:**
- Upload the `vendor/` folder from your local installation
- Or install via SSH: `composer install --optimize-autoloader --no-dev`

### Issue: CSS/JS Not Loading

**Solutions:**
1. Verify `public/` folder contents are in `public_html/`
2. Check `.htaccess` file exists
3. Clear browser cache
4. Check file permissions

### Issue: Module Errors

**Solutions:**
1. Verify `Modules/` folder is uploaded
2. Check `modules_statuses.json` exists
3. Run migrations: `php artisan migrate --force`

## Security Checklist

- [ ] `APP_DEBUG=false` in `.env`
- [ ] `APP_ENV=production` in `.env`
- [ ] Strong `APP_KEY` generated
- [ ] Database credentials are secure
- [ ] File permissions are correct (755 for folders, 644 for files)
- [ ] `.env` file is not publicly accessible
- [ ] Delete any temporary PHP scripts (generate_key.php, etc.)
- [ ] Enable HTTPS/SSL in Hostinger

## Post-Deployment Tasks

1. **Set Up Cron Job** (for scheduled tasks)
   - In hPanel, go to **Advanced** → **Cron Jobs**
   - Add: `* * * * * cd /home/username/public_html && php artisan schedule:run >> /dev/null 2>&1`
   - Replace `username` with your Hostinger username

2. **Enable SSL Certificate**
   - In hPanel, go to **SSL**
   - Install free SSL certificate (Let's Encrypt)

3. **Set Up Backups**
   - Configure automatic backups in Hostinger
   - Or set up manual database backups

4. **Monitor Logs**
   - Check `storage/logs/laravel.log` regularly
   - Monitor server error logs in hPanel

## Quick Reference Commands (if SSH available)

```bash
# Navigate to project
cd public_html

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear cache (if needed)
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Important Notes

- **Always backup** your database before running migrations
- **Test locally** before deploying to production
- **Keep** your `.env` file secure and never commit it to version control
- **Regularly update** your application and dependencies
- **Monitor** your application logs for errors

---

**Need Help?**
- Check Hostinger documentation: https://support.hostinger.com
- Check Laravel documentation: https://laravel.com/docs
- Review application logs: `storage/logs/laravel.log`

**Last Updated:** 2024

