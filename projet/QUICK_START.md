# Quick Start Guide - For Marketplace Buyers

Get your Hiking Management System up and running in under 10 minutes!

## ⚡ 5-Minute Setup

### Prerequisites
- Apache web server
- PHP 7.2+
- MySQL 5.7+
- FTP/SFTP access (or direct file access)

### Step 1: Extract Files (1 min)
Extract the project zip to your web hosting root:
- **Shared Hosting:** Public_html or www folder
- **Own Server:** /var/www/html or equivalent

### Step 2: Import Database (2 min)

**Using cPanel:**
1. Go to phpMyAdmin
2. Create new database: `randonnee_tunisie`
3. Import `database.sql` file
4. Done! ✅

**Using Direct Access:**
```bash
mysql -u username -p
CREATE DATABASE randonnee_tunisie CHARACTER SET utf8mb4;
USE randonnee_tunisie;
SOURCE /path/to/database.sql;
```

### Step 3: Configure Database (1 min)

Edit `config/db.php`:
```php
$host = 'localhost';
$dbname = 'randonnee_tunisie';
$user = 'your_mysql_user';
$pass = 'your_mysql_password';
```

### Step 4: Set Permissions (1 min)

```bash
chmod 755 public/uploads/
chmod 755 public/uploads/profiles/
chmod 755 public/uploads/randonnees/
```

### Step 5: Access Your System

Open: `http://yourdomain.com/projet`

**Test Login:**
- Email: `admin@randonnee.tn`
- Password: `password`

✅ **You're live!** Start creating trips and managing users.

---

## 🔐 Important First Steps

### 1. Change Admin Password ⚠️
**DO THIS FIRST:**
1. Login with admin account
2. Go to Settings
3. Change from default password

### 2. Create Your First Trip
1. Click "Manage Trips"
2. Click "Add Trip"
3. Fill in details and save
4. Trip will appear on public page

### 3. Add a Bus
1. Click "Manage Buses"
2. Add your first bus
3. Assign to trips

### 4. Create Admin Account
1. Go to User Management
2. Create new user
3. Set role to "Admin"
4. Share credentials with team

---

## 📁 What's Included?

✅ **Full application code** (PHP MVC)
✅ **Complete database schema** (pre-configured)
✅ **User authentication system**
✅ **Admin panel** with dashboards
✅ **Trip management** (CRUD operations)
✅ **Participation request system**
✅ **Favorites/Wishlist feature**
✅ **Responsive design** (mobile-friendly)
✅ **Professional UI** (CSS included)
✅ **Ready to sell** (no coding needed)

---

## 🎯 Main Features at a Glance

### For Your Customers
- 🔍 Browse and search trips
- ❤️ Save favorite trips
- 📝 Request to join trips
- 📊 Track participation status
- 👤 Manage profile

### For You (Admin)
- ➕ Create/edit trips
- 🚌 Manage buses
- ✅ Approve requests
- 📈 View statistics
- 👥 Manage users

---

## 🚀 Common Tasks

### Add a New Trip
```
Admin Dashboard → Manage Trips → Add Trip
Fill: Title, Description, Date, Price, Capacity
Set: Difficulty, Region, Distance
Click: Save
```

### Approve User Request
```
Admin Dashboard → Manage Participations
Review: User name, Trip name
Click: Accept or Reject
User gets: Email notification
```

### Create User Account
```
Admin Dashboard → Manage Users → Add User
Fill: Name, Email, Password, Role
Click: Create
Send: Credentials to user
```

### Manage Fleet
```
Admin Dashboard → Manage Buses
Add: License plate, Capacity, Driver info
Assign: To specific trips
Track: Bus availability
```

---

## 💡 Tips for Success

### Pricing Strategy
- Set competitive prices
- Consider difficulty level
- Factor in bus/transportation costs
- Offer seasonal discounts

### Content Quality
- Use high-quality trip images
- Write detailed descriptions
- Include difficulty ratings
- Specify what's included

### User Experience
- Keep navigation simple
- Write clear trip details
- Respond quickly to requests
- Provide good customer service

### Marketing
- Feature popular trips
- Promote new trips
- Build email list
- Gather user reviews

---

## ❓ FAQ - Quick Answers

**Q: How many users can register?**
A: Unlimited! System scales with your database.

**Q: Can I customize colors/logo?**
A: Yes! Edit CSS files in `public/assets/css/`

**Q: How do users pay?**
A: Payment integration ready (add Stripe/PayPal)

**Q: Is HTTPS supported?**
A: Yes! Configure on your hosting control panel.

**Q: Can I add custom fields?**
A: Yes! Guide in Developer documentation.

**Q: What if I need support?**
A: See DOCUMENTATION.md for all guides.

---

## 🆘 Quick Troubleshooting

**"Database connection error"**
→ Check credentials in `config/db.php`

**"Can't upload images"**
→ Set `public/uploads` permissions to 755

**"Login not working"**
→ Verify database imported correctly

**"Page shows blank"**
→ Enable PHP errors (see Developer Guide)

**"Slow performance"**
→ Clear cache, optimize database

---

## 📚 Documentation Files

| Document | Purpose |
|----------|---------|
| **DOCUMENTATION.md** | Complete system guide |
| **INSTALLATION_GUIDE.md** | Detailed setup instructions |
| **USER_GUIDE.md** | For your customers |
| **ADMIN_GUIDE.md** | For administrators |
| **DEVELOPER_GUIDE.md** | For developers/customization |

---

## ✨ Next Steps

1. ✅ Complete setup (you're here!)
2. Add your first trip
3. Test with user registration
4. Test participation flow
5. Customize branding
6. Add payment integration
7. Launch publicly
8. Market to users

---

## 💬 Need Help?

**Check these first:**
1. DOCUMENTATION.md → Full guide
2. TROUBLESHOOTING section → Common issues
3. ADMIN_GUIDE.md → Feature tutorials

**Still stuck?**
- Check error logs
- Review database structure
- Test with sample data
- Review PHP configuration

---

## 🎉 You're Ready!

Your hiking management system is ready to:
- ✅ Accept users
- ✅ Manage trips
- ✅ Process requests
- ✅ Manage buses
- ✅ Generate revenue

**Start creating trips now and begin accepting customers!**

---

**For detailed information, see the complete DOCUMENTATION.md file.**

Last Updated: June 2026
