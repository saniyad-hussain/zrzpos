# How to Get Latest Git Updates on Server

This guide explains how to pull the latest code changes from your Git repository to your production server.

## Method 1: Using Git Pull (Recommended)

### Step 1: Connect to Your Server

Connect to your server via SSH:
```bash
ssh username@your-server-ip
# or
ssh username@yourdomain.com
```

### Step 2: Navigate to Your Project Directory

```bash
cd /path/to/your/project
# Example: cd /var/www/html/zrzpos
# or: cd /home/username/public_html/zrzpos
```

### Step 3: Pull Latest Changes

**Basic pull:**
```bash
git pull origin main
```

**If you want to see what will change first:**
```bash
# Check current status
git status

# Fetch changes without merging
git fetch origin

# See what will change
git log HEAD..origin/main

# Then pull
git pull origin main
```

### Step 4: Update Dependencies (if needed)

If `composer.json` or `package.json` changed:
```bash
# Update PHP dependencies
composer install --optimize-autoloader --no-dev

# If you have frontend assets
npm install
npm run build
```

### Step 5: Run Database Migrations (if needed)

If there are new migrations:
```bash
php artisan migrate --force
```

### Step 6: Clear and Rebuild Caches

```bash
# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 7: Update Storage Link (if needed)

```bash
php artisan storage:link
```

## Method 2: Using Deployment Script

Create a `deploy.sh` script on your server:

```bash
#!/bin/bash

# Navigate to project directory
cd /path/to/your/project

# Pull latest changes
echo "Pulling latest changes..."
git pull origin main

# Install/update dependencies
echo "Updating dependencies..."
composer install --optimize-autoloader --no-dev

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Clear caches
echo "Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Rebuild caches
echo "Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize
composer dump-autoload --optimize

echo "Deployment completed successfully!"
```

Make it executable:
```bash
chmod +x deploy.sh
```

Run it:
```bash
./deploy.sh
```

## Method 3: One-Line Update Command

For quick updates, you can combine everything:

```bash
cd /path/to/your/project && git pull origin main && composer install --optimize-autoloader --no-dev && php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## Important Notes

### Before Pulling:

1. **Backup your database** (especially if there are migrations):
   ```bash
   mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql
   ```

2. **Check for uncommitted changes** on server:
   ```bash
   git status
   ```
   If there are local changes, you may need to stash them:
   ```bash
   git stash
   git pull origin main
   git stash pop
   ```

3. **Check what will change**:
   ```bash
   git fetch origin
   git log HEAD..origin/main --oneline
   ```

### After Pulling:

1. **Check for errors** in logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Test the application** to ensure everything works

3. **Restart queue workers** (if using queues):
   ```bash
   php artisan queue:restart
   ```

## Troubleshooting

### Issue: "Your local changes would be overwritten"

**Solution:**
```bash
# Stash local changes
git stash

# Pull updates
git pull origin main

# Apply stashed changes (if needed)
git stash pop
```

### Issue: "Permission denied"

**Solution:**
```bash
# Check file ownership
ls -la

# Fix ownership if needed
sudo chown -R www-data:www-data /path/to/your/project
```

### Issue: "Merge conflicts"

**Solution:**
```bash
# See conflicts
git status

# Resolve conflicts manually, then:
git add .
git commit -m "Resolved merge conflicts"
```

### Issue: "Composer memory limit"

**Solution:**
```bash
php -d memory_limit=-1 /usr/bin/composer install --optimize-autoloader --no-dev
```

## Automated Updates (Advanced)

### Using GitHub Webhooks

You can set up automatic deployments using GitHub webhooks:

1. Create a webhook endpoint on your server
2. Configure GitHub to send webhook on push
3. Server automatically pulls and deploys

### Using Cron Job

Set up a cron job to check for updates periodically:

```bash
# Edit crontab
crontab -e

# Add this line (checks every hour)
0 * * * * cd /path/to/your/project && git fetch origin && [ $(git rev-list HEAD...origin/main --count) != 0 ] && git pull origin main && php artisan migrate --force && php artisan config:cache
```

## Quick Reference Commands

```bash
# Navigate to project
cd /path/to/your/project

# Pull updates
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan config:clear && php artisan config:cache
php artisan route:clear && php artisan route:cache
php artisan view:clear && php artisan view:cache

# Check status
git status
php artisan --version
```

---

**Remember:** Always backup your database before running migrations in production!

