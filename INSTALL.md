# Installation Guide

This guide will walk you through setting up the PHP Task Manager application on your server.

## Prerequisites

Before you begin, ensure you have the following installed:

- **PHP** 7.4 or higher
- **MySQL** 5.7 or higher (or MariaDB 10.2+)
- **Apache** or **Nginx** web server
- **mod_rewrite** enabled (for Apache)

## Step-by-Step Installation

### 1. Download the Application

Clone the repository or download the source code:

```bash
git clone https://github.com/Dilip-Khattri/php-task-manager.git
cd php-task-manager
```

Or download and extract the ZIP file to your web server's document root.

### 2. Create the Database

#### Using MySQL Command Line:

```bash
mysql -u root -p
```

Then run:

```sql
CREATE DATABASE task_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'taskmanager'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON task_manager.* TO 'taskmanager'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Using phpMyAdmin:

1. Open phpMyAdmin in your browser
2. Click "New" to create a database
3. Enter `task_manager` as the database name
4. Select `utf8mb4_unicode_ci` as collation
5. Click "Create"

### 3. Import the Database Schema

#### Using MySQL Command Line:

```bash
mysql -u taskmanager -p task_manager < database/task_manager.sql
```

#### Using phpMyAdmin:

1. Select the `task_manager` database
2. Click on the "Import" tab
3. Click "Choose File" and select `database/task_manager.sql`
4. Click "Go" to import

### 4. Configure Database Connection

Copy the example configuration file:

```bash
cp config/database.example.php config/database.php
```

Edit `config/database.php` with your database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'task_manager');
define('DB_USER', 'taskmanager');
define('DB_PASS', 'your_secure_password');
```

### 5. Set File Permissions

Ensure the uploads directory is writable by the web server:

```bash
chmod 755 uploads/
chown www-data:www-data uploads/  # For Ubuntu/Debian
# OR
chown apache:apache uploads/      # For CentOS/RHEL
```

### 6. Configure Web Server

#### Apache (.htaccess)

Create an `.htaccess` file in the root directory if needed:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Prevent access to config files
    <Files "database.php">
        Require all denied
    </Files>
</IfModule>

# Disable directory listing
Options -Indexes

# Set default charset
AddDefaultCharset UTF-8
```

#### Nginx

Add this to your Nginx configuration:

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/php-task-manager;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /config/database\.php$ {
        deny all;
    }

    location ~ /\.git {
        deny all;
    }
}
```

### 7. Update Application Configuration

Edit `config/config.php` to set your application URL:

```php
define('APP_URL', 'http://yourdomain.com');
```

For production, set the environment:

```php
define('ENVIRONMENT', 'production');
```

### 8. Access the Application

Open your web browser and navigate to:

```
http://yourdomain.com
```

Or for local development:

```
http://localhost/php-task-manager
```

### 9. Login with Demo Account

Use these credentials to login:

- **Email**: demo@taskmanager.com
- **Password**: demo123

## Post-Installation Steps

### Security Recommendations

1. **Change Demo Password**: Login and change the demo account password immediately
2. **Remove Demo Data**: Delete sample tasks if deploying to production
3. **Enable HTTPS**: Set up SSL/TLS certificate for secure connections
4. **Secure Database**: Use strong passwords and restrict database user permissions
5. **File Permissions**: Ensure config files are not publicly accessible
6. **Backup**: Set up regular database backups

### Optional Enhancements

1. **Email Configuration**: Set up email for notifications (requires additional configuration)
2. **File Uploads**: Configure max upload size in `php.ini`:
   ```ini
   upload_max_filesize = 5M
   post_max_size = 5M
   ```
3. **Session Security**: Configure secure session settings in `php.ini`:
   ```ini
   session.cookie_httponly = 1
   session.cookie_secure = 1  # For HTTPS only
   session.use_strict_mode = 1
   ```

## Troubleshooting

### Database Connection Failed

**Problem**: "Database connection failed" error

**Solution**:
- Verify database credentials in `config/database.php`
- Ensure MySQL service is running: `sudo systemctl status mysql`
- Check database user permissions
- Verify database exists: `SHOW DATABASES;`

### Blank Page / White Screen

**Problem**: Application shows blank page

**Solution**:
- Check PHP error log: `tail -f /var/log/apache2/error.log` (or nginx error log)
- Verify PHP version: `php -v` (must be 7.4 or higher)
- Check file permissions
- Enable error display temporarily in `config/config.php`

### Tasks Not Loading

**Problem**: Dashboard loads but tasks don't appear

**Solution**:
- Open browser console (F12) and check for JavaScript errors
- Verify AJAX endpoints are accessible
- Check database connection
- Clear browser cache

### File Upload Errors

**Problem**: Cannot upload profile pictures

**Solution**:
- Check `uploads/` directory exists and is writable
- Verify `upload_max_filesize` in `php.ini`
- Check available disk space
- Verify file type is allowed (JPG, PNG, GIF)

### Permission Denied Errors

**Problem**: Various permission denied errors

**Solution**:
```bash
# Set correct ownership
sudo chown -R www-data:www-data /var/www/php-task-manager

# Set correct permissions
find /var/www/php-task-manager -type d -exec chmod 755 {} \;
find /var/www/php-task-manager -type f -exec chmod 644 {} \;
chmod 755 uploads/
```

## Updating the Application

To update to a newer version:

1. **Backup Database**:
   ```bash
   mysqldump -u taskmanager -p task_manager > backup.sql
   ```

2. **Backup Files**:
   ```bash
   cp -r uploads/ uploads_backup/
   cp config/database.php database.php.backup
   ```

3. **Pull Updates**:
   ```bash
   git pull origin main
   ```

4. **Run Migrations** (if any):
   Check `database/migrations/` for update scripts

5. **Clear Cache**:
   Clear browser cache and restart web server

## Getting Help

If you encounter issues not covered in this guide:

1. Check the [README.md](README.md) for general information
2. Review the [CHANGELOG.md](CHANGELOG.md) for recent changes
3. Open an issue on [GitHub](https://github.com/Dilip-Khattri/php-task-manager/issues)

## Production Deployment Checklist

Before deploying to production:

- [ ] Changed demo account password
- [ ] Removed or secured sample data
- [ ] Configured HTTPS/SSL
- [ ] Set `ENVIRONMENT` to 'production'
- [ ] Disabled error display
- [ ] Set strong database password
- [ ] Configured file upload limits
- [ ] Set up regular backups
- [ ] Configured session security
- [ ] Tested all functionality
- [ ] Set up monitoring/logging
- [ ] Documented custom configurations

---

For more information, see the [README.md](README.md) file.
