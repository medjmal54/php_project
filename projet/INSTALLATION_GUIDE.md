# Installation Guide - Hiking Management System

Complete step-by-step installation instructions for setting up the Hiking Management System.

## Quick Start (5 minutes)

### Prerequisites
- Apache web server with mod_rewrite
- PHP 7.2+
- MySQL 5.7+ or MariaDB 10.3+

### Installation Steps

1. **Download & Extract**
   ```bash
   # Extract to web root
   cd /var/www/html
   # or C:\xampp\htdocs on Windows
   ```

2. **Create Database**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Create new database: `randonnee_tunisie`
   - Import `database.sql` file

3. **Configure Database**
   - Edit `config/db.php`
   - Set credentials: `$user = 'root'`, `$pass = ''`

4. **Access Application**
   - Go to: `http://localhost/projet`
   - Login with: `admin@randonnee.tn` / `password`

---

## Detailed Installation

### Windows (XAMPP)

#### Step 1: Install XAMPP

1. Download XAMPP from https://www.apachefriends.org
2. Run installer, select Apache and MySQL
3. Start Apache and MySQL from XAMPP Control Panel

#### Step 2: Extract Project

1. Copy project folder to `C:\xampp\htdocs\projet`
2. Verify folder structure exists

#### Step 3: Create Database

1. Open `http://localhost/phpmyadmin` in browser
2. Click "New" button
3. Enter database name: `randonnee_tunisie`
4. Set Collation: `utf8mb4_unicode_ci`
5. Click "Create"
6. Select new database
7. Click "Import" tab
8. Browse to `database.sql` in project folder
9. Click "Go"

#### Step 4: Configure Connection

1. Open `config/db.php` in text editor
2. Verify settings:
   ```php
   $host = 'localhost';
   $dbname = 'randonnee_tunisie';
   $user = 'root';
   $pass = '';  // Empty for XAMPP default
   ```
3. Save file

#### Step 5: Set Permissions

1. Right-click `public/uploads` folder
2. Properties → Security → Edit
3. Select your user → Grant "Full Control"
4. Apply to subfolders

#### Step 6: Test Installation

1. Open browser
2. Navigate to: `http://localhost/projet`
3. You should see the home page

---

### Linux (Ubuntu/Debian)

#### Step 1: Install Dependencies

```bash
# Update package manager
sudo apt update

# Install Apache
sudo apt install apache2
sudo a2enmod rewrite
sudo systemctl restart apache2

# Install PHP and extensions
sudo apt install php php-mysql php-gd php-cli php-common

# Install MySQL
sudo apt install mysql-server

# Start services
sudo systemctl start apache2 mysql
sudo systemctl enable apache2 mysql
```

#### Step 2: Extract Project

```bash
# Copy project to web root
sudo cp -r projet /var/www/html/

# Change ownership
sudo chown -R www-data:www-data /var/www/html/projet

# Set permissions
sudo chmod -R 755 /var/www/html/projet
sudo chmod -R 777 /var/www/html/projet/public/uploads
```

#### Step 3: Create Database

```bash
# Connect to MySQL
mysql -u root -p

# Create database
CREATE DATABASE randonnee_tunisie CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE randonnee_tunisie;
SOURCE /var/www/html/projet/database.sql;
EXIT;
```

#### Step 4: Configure Apache

```bash
# Create virtual host
sudo nano /etc/apache2/sites-available/projet.conf
```

Add:
```apache
<VirtualHost *:80>
    ServerName projet.local
    ServerAlias www.projet.local
    DocumentRoot /var/www/html/projet

    <Directory /var/www/html/projet>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/projet_error.log
    CustomLog ${APACHE_LOG_DIR}/projet_access.log combined
</VirtualHost>
```

Enable and test:
```bash
sudo a2ensite projet.conf
sudo apache2ctl configtest
sudo systemctl restart apache2
```

#### Step 5: Update Hosts File

Add to `/etc/hosts`:
```
127.0.0.1   projet.local
```

#### Step 6: Access Application

Open: `http://projet.local` or `http://localhost/projet`

---

### macOS

#### Step 1: Install via Homebrew

```bash
# Install Homebrew if not present
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install services
brew install apache2
brew install php
brew install mysql@5.7
```

#### Step 2: Start Services

```bash
# Start Apache
sudo apachectl start

# Start MySQL
mysql.server start

# Enable auto-start
brew services start httpd
brew services start mysql@5.7
```

#### Step 3: Extract Project

```bash
# Copy to Apache root
sudo cp -r projet /Library/WebServer/Documents/

# Fix ownership
sudo chown -R _www:_www /Library/WebServer/Documents/projet
```

#### Step 4: Create Database

```bash
# Connect to MySQL
mysql -u root

# Create database
CREATE DATABASE randonnee_tunisie CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE randonnee_tunisie;
SOURCE /Library/WebServer/Documents/projet/database.sql;
```

#### Step 5: Access Application

Visit: `http://localhost/projet`

---

## Cloud Server Installation (Linux)

### DigitalOcean / Linode / AWS Ubuntu

```bash
#!/bin/bash
# Complete installation script

# Update system
sudo apt update && sudo apt upgrade -y

# Install stack
sudo apt install -y apache2 mysql-server php php-mysql php-gd php-cli

# Enable Apache modules
sudo a2enmod rewrite
sudo systemctl restart apache2

# Secure MySQL
sudo mysql_secure_installation

# Create database
mysql -u root -p << EOF
CREATE DATABASE randonnee_tunisie CHARACTER SET utf8mb4;
USE randonnee_tunisie;
SOURCE /var/www/html/projet/database.sql;
EOF

# Set permissions
sudo chown -R www-data:www-data /var/www/html/projet
sudo chmod -R 755 /var/www/html/projet
sudo chmod 777 /var/www/html/projet/public/uploads/*

# Configure firewall
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable

echo "Installation complete!"
```

---

## Post-Installation Configuration

### 1. Change Admin Password

1. Login to admin account
2. Navigate to Settings
3. Change password from default `password`
4. Save changes

### 2. Configure Email (Optional)

Add to `config/db.php`:
```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-password');
define('SITE_EMAIL', 'noreply@hiking.com');
```

### 3. Enable HTTPS

For production, use Let's Encrypt:

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Get certificate
sudo certbot --apache -d yourdomain.com

# Auto-renew
sudo systemctl enable certbot.timer
```

### 4. Configure PHP

Edit `php.ini`:

```ini
; Increase upload size
upload_max_filesize = 50M
post_max_size = 50M

; Set timezone
date.timezone = "Africa/Tunis"

; Session settings
session.cookie_httponly = 1
session.cookie_secure = 1
```

Restart Apache:
```bash
sudo systemctl restart apache2
```

### 5. Backup Strategy

Create backup script `backup.sh`:

```bash
#!/bin/bash
BACKUP_DIR="/backups"
DATE=$(date +%Y%m%d_%H%M%S)

# Backup database
mysqldump -u root -p randonnee_tunisie > "$BACKUP_DIR/db_$DATE.sql"

# Backup files
tar -czf "$BACKUP_DIR/files_$DATE.tar.gz" /var/www/html/projet

# Keep only last 7 days
find "$BACKUP_DIR" -type f -mtime +7 -delete

echo "Backup completed: $DATE"
```

Schedule with cron:
```bash
0 2 * * * /home/user/backup.sh
```

---

## Verification Checklist

After installation, verify:

- [ ] Apache is running and serving pages
- [ ] MySQL database created and tables exist
- [ ] `config/db.php` has correct credentials
- [ ] `public/uploads` directories are writable
- [ ] Can login with `admin@randonnee.tn`
- [ ] Can access home page without errors
- [ ] Can create new admin user
- [ ] Can upload trip images
- [ ] Session persistence works (stay logged in)
- [ ] Database backups are working

---

## Troubleshooting

### Connection Refused

```bash
# Check if MySQL is running
sudo systemctl status mysql

# Restart MySQL
sudo systemctl restart mysql

# Check MySQL is listening
sudo netstat -tulpn | grep mysql
```

### Permission Denied on Uploads

```bash
# Fix ownership
sudo chown -R www-data:www-data /var/www/html/projet/public/uploads

# Fix permissions
sudo chmod 755 /var/www/html/projet/public/uploads
sudo chmod 755 /var/www/html/projet/public/uploads/*
```

### PHP Errors Not Showing

Add to `index.php` (temporarily):
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Rewrite Module Not Working

```bash
# Enable rewrite module
sudo a2enmod rewrite

# Check .htaccess
<Directory /var/www/html/projet>
    AllowOverride All
</Directory>

sudo systemctl restart apache2
```

### Database Import Fails

1. Check file encoding is UTF-8
2. Increase MySQL max_allowed_packet:

```bash
mysql -u root -p -e "SET GLOBAL max_allowed_packet=16777216;"
```

3. Try importing from command line:
```bash
mysql -u root -p randonnee_tunisie < database.sql
```

---

## Getting Help

If you encounter issues:

1. Check error logs:
   - Apache: `/var/log/apache2/error.log`
   - MySQL: `/var/log/mysql/error.log`
   - PHP: Check with `php -i | grep error_log`

2. Verify PHP extensions:
   ```bash
   php -m | grep -E 'pdo|mysql|gd'
   ```

3. Test database connection:
   ```bash
   php -r "new PDO('mysql:host=localhost;dbname=randonnee_tunisie', 'root', '');"
   ```

---

**Installation complete! Start managing hiking trips now.**
