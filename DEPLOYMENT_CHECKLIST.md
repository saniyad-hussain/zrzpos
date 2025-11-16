# Quick Deployment Checklist

Use this checklist for a quick deployment reference.

## Pre-Upload
- [ ] Code tested locally
- [ ] Database backed up
- [ ] `.env` file prepared with production values
- [ ] All sensitive data removed from code

## Upload Files
- [ ] Upload all project files (excluding vendor, node_modules, .env)
- [ ] OR clone from Git repository

## Server Setup
- [ ] Create `.env` file on server
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure database credentials
- [ ] Set `APP_URL` to your domain

## Install Dependencies
- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `npm install && npm run build` (if needed)

## Permissions
- [ ] Set `storage/` to 775
- [ ] Set `bootstrap/cache/` to 775
- [ ] Set ownership to web server user

## Application Setup
- [ ] Run `php artisan key:generate`
- [ ] Run `php artisan migrate --force`
- [ ] Run `php artisan storage:link`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`

## Web Server
- [ ] Point document root to `public/` folder
- [ ] Configure URL rewriting (mod_rewrite for Apache)
- [ ] Test website loads

## Post-Deployment
- [ ] Test login functionality
- [ ] Test key features
- [ ] Set up cron job for scheduler
- [ ] Set up queue worker (if needed)
- [ ] Enable HTTPS/SSL
- [ ] Set up backups

## Security
- [ ] Verify `APP_DEBUG=false`
- [ ] Verify `.env` is not publicly accessible
- [ ] Check file permissions
- [ ] Enable firewall rules

---

**Quick Commands Reference:**

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Cache everything
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions (Linux)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

