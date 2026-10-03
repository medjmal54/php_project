# Security & Best Practices Guide

Critical security information for protecting your Hiking Management System.

## Table of Contents

1. [Security Overview](#security-overview)
2. [Initial Security Setup](#initial-security-setup)
3. [Database Security](#database-security)
4. [File & Directory Security](#file--directory-security)
5. [PHP Configuration](#php-configuration)
6. [Authentication & Access Control](#authentication--access-control)
7. [Data Protection](#data-protection)
8. [Monitoring & Maintenance](#monitoring--maintenance)
9. [Incident Response](#incident-response)
10. [Compliance](#compliance)

---

## Security Overview

### Built-in Security Features

✅ **Already Implemented:**
- PDO prepared statements (SQL injection prevention)
- Password hashing with bcrypt
- Session-based authentication
- Role-based access control (RBAC)
- Server-side input validation
- CSRF token support (can be added)
- File upload validation
- Error logging (non-display)

⚠️ **Requires Manual Setup:**
- HTTPS/SSL certificate
- Strong database passwords
- File permissions
- Database backups
- Access logging
- Monitoring

❌ **Not Included (Add as Needed):**
- Two-factor authentication
- API rate limiting
- Advanced threat detection
- Intrusion prevention
- Compliance certifications

---

## Initial Security Setup

### 1. Change Default Credentials

**Admin Account:**
```
Current: admin@randonnee.tn / password
Action: CHANGE IMMEDIATELY after first login
```

**Steps:**
1. Login with default credentials
2. Click "Settings"
3. Go to "Account Security"
4. Change password to strong unique password
5. Save

**Strong Password Requirements:**
- Minimum 12 characters
- Mix of uppercase, lowercase, numbers, special characters
- Not based on dictionary words
- Not related to username or personal info
- Unique (not used elsewhere)

### 2. Create New Admin Account

For team use, create dedicated admin accounts:

1. Go to User Management
2. Create new user
3. Set Role: "Admin"
4. Send credentials via secure channel
5. Require password change on first login

### 3. Set Up SSH Keys (If Using Linux)

```bash
# Generate key pair
ssh-keygen -t rsa -b 4096

# Add public key to server
cat ~/.ssh/id_rsa.pub >> ~/.ssh/authorized_keys

# Disable password authentication
sudo nano /etc/ssh/sshd_config
# Set: PasswordAuthentication no
sudo systemctl restart sshd
```

---

## Database Security

### MySQL User Permissions

**Create Minimal Privilege User:**

```sql
-- Create user for application
CREATE USER 'randonnee_app'@'localhost' IDENTIFIED BY 'strong_password_here';

-- Grant only needed permissions
GRANT SELECT, INSERT, UPDATE, DELETE ON randonnee_tunisie.* TO 'randonnee_app'@'localhost';

-- Don't grant administrative privileges
-- No GRANT, ALTER, DROP, etc.

-- Apply changes
FLUSH PRIVILEGES;
```

**Remove Default Accounts:**

```sql
-- Remove anonymous users
DROP USER ''@'localhost';
DROP USER ''@'%';

-- Remove remote root access
DROP USER 'root'@'%';

-- Change root password
ALTER USER 'root'@'localhost' IDENTIFIED BY 'strong_root_password';
```

### Backup Strategy

**Automated Daily Backups:**

```bash
#!/bin/bash
# File: /usr/local/bin/backup-db.sh

BACKUP_DIR="/backups/mysql"
DB_NAME="randonnee_tunisie"
DB_USER="randonnee_app"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p "$BACKUP_DIR"

# Backup with compression
mysqldump -u "$DB_USER" -p --single-transaction \
    --quick --lock-tables=false "$DB_NAME" | \
    gzip > "$BACKUP_DIR/db_$DATE.sql.gz"

# Keep only last 30 days
find "$BACKUP_DIR" -type f -mtime +30 -delete

echo "Backup completed: $DATE"
```

**Schedule with cron:**
```bash
# Edit crontab
sudo crontab -e

# Add: Run backup daily at 2 AM
0 2 * * * /usr/local/bin/backup-db.sh >> /var/log/backup.log 2>&1
```

### Database Hardening

**Disable External Access:**

```bash
# MySQL should only listen on localhost
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
# Set: bind-address = 127.0.0.1
sudo systemctl restart mysql
```

**Enable Query Logging (for auditing):**

```sql
-- Enable binary logging (before enabling full logging)
SET GLOBAL log_bin = 'ON';

-- Enable general query log (warning: impacts performance)
SET GLOBAL general_log = 'ON';
SET GLOBAL log_output = 'TABLE';

-- View logs
SELECT * FROM mysql.general_log ORDER BY event_time DESC LIMIT 10;
```

---

## File & Directory Security

### Proper File Permissions

**Directory Permissions (755):**

```bash
# Application directory
chmod 755 /var/www/html/projet

# Public asset directories (readable)
chmod 755 /var/www/html/projet/public
chmod 755 /var/www/html/projet/public/assets
```

**Restricted Write Access (755):**

```bash
# Only specific upload directories
chmod 755 /var/www/html/projet/public/uploads
chmod 755 /var/www/html/projet/public/uploads/profiles
chmod 755 /var/www/html/projet/public/uploads/randonnees

# Make web server owner
sudo chown -R www-data:www-data /var/www/html/projet/public/uploads
```

**Configuration File Security (644/640):**

```bash
# Configuration should be readable by web server only
chmod 640 /var/www/html/projet/config/db.php

# Owner should be root or web server
sudo chown root:www-data /var/www/html/projet/config/db.php
```

**Deny Direct Access:**

Create `.htaccess` in config directory:

```apache
# config/.htaccess
<FilesMatch "\.(php|phtml|php3|php4|php5|phtml|shtml|pht|phar)$">
    Deny from all
</FilesMatch>
```

### File Upload Security

**Validate Upload Type:**

```php
<?php
// In upload handler
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$file_info = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['image']['tmp_name']);

if (!in_array($file_info, $allowed_types)) {
    die('Invalid file type');
}
?>
```

**Verify File Size:**

```php
<?php
$max_size = 5 * 1024 * 1024; // 5MB
if ($_FILES['image']['size'] > $max_size) {
    die('File too large');
}
?>
```

**Store Outside Web Root (Recommended):**

```php
<?php
// Store uploads outside public folder
$upload_dir = '/var/www/uploads/'; // Not in web root
$file_path = $upload_dir . uniqid() . '.jpg';

// Serve via PHP script
// /download.php?file=name.jpg
// Validate file ownership before serving
?>
```

---

## PHP Configuration

### Production php.ini Settings

**Error Handling:**

```ini
; DO NOT display errors to users
display_errors = Off
display_startup_errors = Off

; DO log errors
log_errors = On
error_log = /var/log/php/error.log

; Set appropriate error reporting
error_reporting = E_ALL
```

**Session Security:**

```ini
; Secure session cookies
session.cookie_httponly = 1
session.cookie_secure = 1        ; HTTPS only
session.use_only_cookies = 1
session.cookie_samesite = Strict

; Shorter session timeout
session.gc_maxlifetime = 3600    ; 1 hour
```

**File Upload Security:**

```ini
; Restrict uploads
file_uploads = On
upload_max_filesize = 50M
post_max_size = 50M
upload_tmp_dir = /tmp

; Disable execution in upload directory (via .htaccess)
```

**Disable Dangerous Functions:**

```ini
; Disable risky functions
disable_functions = exec,system,shell_exec,passthru,proc_open,proc_nice,curl_exec,curl_multi_exec,parse_ini_file,show_source

; OR limit for specific functions
suhosin.executor.func.blacklist = eval,assert,system,exec,passthru,shell_exec,proc_open
```

**Other Security Settings:**

```ini
; General security
expose_php = Off
default_charset = "UTF-8"
magic_quotes_gpc = Off

; Prevent race conditions
default_socket_timeout = 60
```

---

## Authentication & Access Control

### Session Security

**Regenerate Session ID on Login:**

```php
<?php
// In AuthController.php login method
session_regenerate_id(true);  // Delete old session

$_SESSION['user_id'] = $user->getId();
$_SESSION['user_role'] = $user->getRole();
?>
```

**Validate Session:**

```php
<?php
// In index.php
session_start();

// Check if session expired
if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 3600) {
    session_destroy();
    header('Location: index.php?page=auth&action=login&error=session_expired');
    exit();
}

$_SESSION['last_activity'] = time();
?>
```

### Access Control Implementation

**Verify Role Before Admin Action:**

```php
<?php
function verifyAdminAccess() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=auth&action=login');
        exit();
    }
    
    if ($_SESSION['user_role'] !== 'admin') {
        header('Location: index.php?page=home&error=access_denied');
        exit();
    }
}

// Use in admin controllers
class AdminController {
    public function dashboard() {
        verifyAdminAccess();
        // Safe to proceed
    }
}
?>
```

### Password Security

**Hash Passwords on Storage:**

```php
<?php
// On registration/password change
$password_hash = password_hash($input_password, PASSWORD_BCRYPT, ['cost' => 12]);

$stmt = $db->prepare("INSERT INTO user (email, password) VALUES (?, ?)");
$stmt->execute([$email, $password_hash]);
?>
```

**Verify on Login:**

```php
<?php
$user = UserModel::findByEmail($email);

if ($user && password_verify($input_password, $user->getPassword())) {
    // Password matches
    $_SESSION['user_id'] = $user->getId();
} else {
    // Invalid credentials
    $error = "Invalid email or password";
}
?>
```

---

## Data Protection

### Input Validation

**Server-Side Only (Never Trust Client):**

```php
<?php
// ✅ Good - Server-side validation
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email";
}

if (strlen($_POST['password']) < 8) {
    $error = "Password too short";
}

// ❌ Bad - Client-side only
// JavaScript validation can be bypassed
?>
```

**Sanitize Output:**

```php
<?php
// ✅ Good - Prevent XSS
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');

// For URLs
echo urlencode($user_input);

// For JSON
echo json_encode($data);

// ❌ Bad - Direct output
// echo $user_input;  // Vulnerable to XSS
?>
```

### Data Encryption (Optional)

**For Sensitive Data:**

```php
<?php
// Consider encrypting phone numbers, addresses, etc.
// Using OpenSSL:

$encryption_key = 'your-secret-key-here';
$data_to_encrypt = $_POST['phone'];

$encrypted = openssl_encrypt($data_to_encrypt, 'AES-256-CBC', $encryption_key);
$decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $encryption_key);
?>
```

---

## Monitoring & Maintenance

### Enable Logging

**Apache Error Log:**

```bash
# Check logs
tail -f /var/log/apache2/error.log

# Common locations
/var/log/apache2/error.log
/var/log/apache2/access.log
/home/user/public_html/logs/error.log
```

**PHP Error Log:**

```bash
# Find PHP error log location
php -i | grep error_log

# Check logs
tail -f /var/log/php/error.log
```

### Monitor for Suspicious Activity

**Check for Unauthorized Files:**

```bash
# Find recently modified files
find /var/www/html/projet -type f -mtime -1

# Look for .php files outside of application
find /var/www/html/projet -name "*.php" -type f

# Check for suspicious permissions
find /var/www/html/projet -perm 777
```

**Review Access Logs:**

```bash
# See who accessed what
tail -100 /var/log/apache2/access.log

# Check for unusual patterns
grep -i "admin" /var/log/apache2/access.log
grep -i "\.sql\|union\|select" /var/log/apache2/access.log
```

### Regular Security Updates

**Update PHP & MySQL:**

```bash
# Check current versions
php -v
mysql --version

# Update (varies by system)
sudo apt update && sudo apt upgrade  # Debian/Ubuntu
sudo yum update                      # RedHat/CentOS

# Restart services
sudo systemctl restart apache2
sudo systemctl restart mysql
```

---

## Incident Response

### If Compromised

**Immediate Actions:**

1. **Take site offline** if suspected breach
   ```bash
   sudo systemctl stop apache2
   ```

2. **Preserve evidence**
   ```bash
   # Copy logs and suspicious files
   cp -r /var/www/html/projet /forensics/backup
   cp /var/log/apache2/*.log /forensics/
   ```

3. **Check recent changes**
   ```bash
   # Files modified in last 24 hours
   find /var/www/html -mtime -1 -type f
   ```

4. **Reset all credentials**
   - Change database password
   - Change admin password
   - Regenerate session IDs

5. **Scan for malware**
   ```bash
   # Use clamscan or other antivirus
   sudo clamscan -r /var/www/html/projet
   ```

6. **Restore from clean backup**
   ```bash
   # Restore from known-good backup
   mysql randonnee_tunisie < backup_clean.sql
   ```

---

## Compliance

### Data Protection

**GDPR Compliance (If serving EU users):**
- User consent for data collection
- Right to access personal data
- Right to deletion
- Data breach notification (72 hours)
- Data protection documentation

**CCPA Compliance (If serving California users):**
- Privacy policy required
- User rights to data
- Opt-out mechanism
- Third-party data sharing disclosure

### Privacy Policy

**Minimum Requirements:**
- What data you collect
- How you use it
- How long you keep it
- Who has access
- User rights
- Contact information

### Regular Audits

**Monthly Security Checklist:**
- [ ] Review access logs for anomalies
- [ ] Check file permissions are correct
- [ ] Verify backups are working
- [ ] Test password reset process
- [ ] Review user accounts (remove inactive)
- [ ] Check for outdated files/code
- [ ] Review database logs
- [ ] Test disaster recovery plan

---

## Security Quick Reference

### DO:
✅ Use HTTPS in production
✅ Keep software updated
✅ Use strong passwords
✅ Backup regularly
✅ Monitor logs
✅ Validate all inputs
✅ Use prepared statements
✅ Hash passwords
✅ Limit file permissions
✅ Log security events

### DON'T:
❌ Display PHP errors to users
❌ Store passwords in plaintext
❌ Trust client-side validation only
❌ Use hardcoded credentials
❌ Allow direct execution in upload directories
❌ Expose system information
❌ Use deprecated functions
❌ Ignore security updates
❌ Skip backups
❌ Use default credentials in production

---

## Additional Resources

- OWASP Top 10: https://owasp.org/www-project-top-ten/
- PHP Security Guide: https://www.php.net/manual/en/security.php
- MySQL Security: https://dev.mysql.com/doc/refman/en/security.html
- Let's Encrypt HTTPS: https://letsencrypt.org/

---

**Security is ongoing. Review regularly and stay updated!** 🔐

Last Updated: June 2026
