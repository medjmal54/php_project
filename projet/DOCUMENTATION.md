# Hiking Management System - Complete Documentation

**Version:** 1.0  
**Language:** PHP (Pure) with MVC Architecture  
**Database:** MySQL  
**Last Updated:** June 2026

---

## Table of Contents

1. [Project Overview](#project-overview)
2. [Features](#features)
3. [System Requirements](#system-requirements)
4. [Installation Guide](#installation-guide)
5. [Configuration](#configuration)
6. [Project Structure](#project-structure)
7. [Database Schema](#database-schema)
8. [API Documentation](#api-documentation)
9. [User Roles & Permissions](#user-roles--permissions)
10. [Architecture Overview](#architecture-overview)
11. [Troubleshooting](#troubleshooting)
12. [License & Support](#license--support)

---

## Project Overview

### What is this system?

This is a **complete hiking/hiking trip management system** designed for organizing and managing hiking expeditions. It provides a platform where administrators can create, manage, and organize hiking trips, while users can browse available trips, request participation, and manage their hiking interests.

### Key Use Case

Perfect for:
- Adventure travel companies
- Tourist organizations
- Outdoor activity clubs
- Regional tourism boards
- NGOs organizing eco-tourism

### Technology Stack

| Component | Technology |
|-----------|-----------|
| Backend | PHP 7.0+ (Pure, No Framework) |
| Database | MySQL 5.7+ / MariaDB 10.3+ |
| Frontend | HTML5, CSS3, Vanilla JavaScript |
| Server | Apache 2.4+ with mod_rewrite |
| Database Driver | PDO with prepared statements |

---

## Features

### For End Users (Randonneurs)

- ✅ **User Account Management**
  - Secure registration with email validation
  - Password hashing (bcrypt)
  - Profile management with avatar upload
  - Account settings and preferences

- ✅ **Trip Discovery**
  - Browse all available hiking trips
  - Advanced filtering (difficulty, region, date, price)
  - Search functionality
  - View detailed trip information with photos
  - See real-time availability

- ✅ **Trip Management**
  - Request participation in hiking trips
  - Automatic conflict detection (date overlaps)
  - Capacity checking before participation
  - Track participation status (Pending/Accepted/Rejected)
  - Withdraw from trips

- ✅ **Dashboard**
  - View all participation requests
  - Track accepted/pending/rejected trips
  - Personal favorites management
  - Notification system for request status changes

- ✅ **Favorites**
  - Save favorite trips for later
  - Quick access to saved trips
  - Remove from favorites

### For Administrators

- ✅ **Admin Dashboard**
  - Real-time statistics and analytics
  - Total trips, users, participations, revenue overview
  - Recent activity monitoring

- ✅ **Trip Management (CRUD)**
  - Create new hiking trips with detailed information
  - Edit trip details (dates, capacity, price, description)
  - Assign buses to trips
  - Change trip status (Planned/In Progress/Completed/Cancelled)
  - Upload trip images

- ✅ **Bus Fleet Management**
  - Add/Edit/Delete buses
  - Track bus capacity and driver information
  - Assign buses to trips

- ✅ **Participation Management**
  - View all participation requests
  - Accept or reject requests
  - Check user details
  - Track acceptance rates

- ✅ **User Management**
  - View all registered users
  - Monitor user activity
  - Role assignment

---

## System Requirements

### Server Requirements

- **PHP Version:** 7.2 or higher
- **MySQL Version:** 5.7 or higher (or MariaDB 10.3+)
- **Apache:** 2.4+ with `mod_rewrite` enabled
- **PHP Extensions Required:**
  - PDO (PDO MySQL)
  - Session
  - GD (for image processing)
  - File Upload Support

### Browser Requirements

- Modern browsers (Chrome 60+, Firefox 55+, Safari 11+, Edge 79+)
- JavaScript enabled
- Cookies enabled for sessions

### Disk Space

- Minimum: 50 MB for application files
- Recommended: 200 MB+ for uploads (varies with trip image volume)

---

## Installation Guide

### Step 1: Download & Extract

1. Download the project files
2. Extract to your web server root:
   - **XAMPP:** `C:\xampp\htdocs\projet`
   - **WAMP:** `C:\wamp64\www\projet`
   - **Linux:** `/var/www/html/projet`

### Step 2: Database Setup

#### Using phpMyAdmin (Recommended)

1. Open `http://localhost/phpmyadmin`
2. Click "New" to create a new database
3. Enter database name: `randonnee_tunisie`
4. Set charset to `utf8mb4` and collation to `utf8mb4_unicode_ci`
5. Click "Create"
6. Select the new database
7. Click "Import" tab
8. Choose `database.sql` from the project root
9. Click "Go" to execute

#### Using MySQL Command Line

```bash
mysql -u root -p
CREATE DATABASE randonnee_tunisie CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE randonnee_tunisie;
SOURCE /path/to/database.sql;
```

### Step 3: Configure Database Connection

1. Open `config/db.php`
2. Update credentials if needed:

```php
$host = 'localhost';      // MySQL host
$dbname = 'randonnee_tunisie';  // Database name
$user = 'root';            // MySQL username
$pass = '';                // MySQL password (empty for XAMPP)
```

### Step 4: Set Upload Permissions

Ensure these directories are writable:

```bash
# Linux/macOS
chmod 755 public/uploads/
chmod 755 public/uploads/profiles/
chmod 755 public/uploads/randonnees/

# Windows: Right-click folder → Properties → Security → Edit → Grant Full Control
```

### Step 5: Access the Application

Open your browser and navigate to:
```
http://localhost/projet
```

---

## Configuration

### Database Configuration

Edit `config/db.php`:

```php
<?php
$host = 'localhost';          // Database host
$dbname = 'randonnee_tunisie'; // Database name
$user = 'root';               // MySQL user
$pass = '';                   // Password (empty for local dev)

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
```

### Default Test Credentials

**Administrator Account:**
- Email: `admin@randonnee.tn`
- Password: `password`

### Security Notes

- ⚠️ **Change admin password immediately** after first login
- Use strong, unique passwords
- Store database credentials securely
- Enable HTTPS in production
- Regularly backup your database

---

## Project Structure

```
projet/
├── config/
│   └── db.php                 # Database connection configuration
├── controllers/               # Business logic layer
│   ├── AdminController.php    # Admin panel operations
│   ├── AuthController.php     # Login/Register/Logout
│   ├── HomeController.php     # Public pages
│   ├── RandonneeController.php # Trip management
│   └── UserController.php     # User dashboard & profile
├── models/                    # Data access layer (PDO)
│   ├── UserModel.php          # User database operations
│   ├── RandonneeModel.php     # Trip database operations
│   ├── ParticipationModel.php # Participation requests
│   ├── BusModel.php           # Bus fleet management
│   └── FavoriteModel.php      # Favorites/Wishlist
├── views/                     # Presentation layer (Templates)
│   ├── home.php               # Landing page
│   ├── admin/                 # Admin panel templates
│   │   ├── dashboard.php
│   │   ├── manage_buses.php
│   │   ├── manage_participations.php
│   │   ├── manage_randonnees.php
│   │   └── settings.php
│   ├── auth/                  # Authentication pages
│   │   ├── login.php
│   │   └── register.php
│   ├── layout/                # Reusable layout components
│   │   ├── header.php
│   │   ├── footer.php
│   │   ├── admin_sidebar.php
│   │   └── user_sidebar.php
│   ├── randonnee/             # Trip browsing
│   │   ├── index.php          # List all trips
│   │   └── details.php        # Trip detail page
│   └── user/                  # User pages
│       ├── dashboard.php      # User dashboard
│       ├── favorites.php      # Saved trips
│       └── settings.php       # Profile settings
├── public/                    # Static assets
│   └── assets/
│       └── css/               # Stylesheets
│           ├── global.css
│           ├── admin.css
│           ├── auth.css
│           ├── home.css
│           ├── randonnee-list.css
│           ├── randonnee-details.css
│           ├── user.css
│           └── style.css
│   └── uploads/               # User uploads
│       ├── profiles/          # Profile pictures
│       └── randonnees/        # Trip images
├── index.php                  # Main entry point (Front Controller)
├── database.sql               # Database schema & sample data
├── README.md                  # Quick start guide
└── DOCUMENTATION.md           # This file
```

---

## Database Schema

### Users Table

```sql
CREATE TABLE user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    telephone VARCHAR(20),
    image_profile VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Fields:**
- `id_user`: Unique user identifier
- `nom`: Full name
- `email`: Email address (unique, used for login)
- `password`: Hashed password (bcrypt)
- `role`: User type (admin/user)
- `telephone`: Contact phone number
- `image_profile`: Profile picture path

---

### Buses Table

```sql
CREATE TABLE bus (
    id_bus INT AUTO_INCREMENT PRIMARY KEY,
    immat_bus VARCHAR(50) NOT NULL,
    nb_places INT NOT NULL,
    chauffeur VARCHAR(100) NOT NULL,
    tel_chauff VARCHAR(20) NOT NULL
);
```

**Fields:**
- `id_bus`: Bus identifier
- `immat_bus`: License plate number
- `nb_places`: Passenger capacity
- `chauffeur`: Driver name
- `tel_chauff`: Driver phone number

---

### Randonnee Table

```sql
CREATE TABLE randonnee (
    id_randonnee INT AUTO_INCREMENT PRIMARY KEY,
    categorie VARCHAR(50) NOT NULL,
    titre VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME NOT NULL,
    region VARCHAR(100) NOT NULL,
    distance FLOAT NOT NULL,
    difficulte ENUM('Facile', 'Moyen', 'Difficile') NOT NULL,
    capacite INT NOT NULL,
    image VARCHAR(255),
    id_bus INT,
    statut ENUM('Prévue', 'En cours', 'Terminée', 'Annulée') DEFAULT 'Prévue',
    prix DECIMAL(10, 2) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_bus) REFERENCES bus(id_bus) ON DELETE SET NULL
);
```

**Fields:**
- `id_randonnee`: Trip identifier
- `categorie`: Type of trip (Mountain, Desert, etc.)
- `titre`: Trip title
- `description`: Detailed description
- `date_debut`: Start date and time
- `date_fin`: End date and time
- `region`: Geographic region
- `distance`: Distance in kilometers
- `difficulte`: Difficulty level
- `capacite`: Maximum participants allowed
- `image`: Trip cover image path
- `id_bus`: Assigned bus ID (nullable)
- `statut`: Trip status
- `prix`: Price per participant

---

### Participation Table

```sql
CREATE TABLE participation (
    id_participation INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_randonnee INT NOT NULL,
    statut ENUM('en attente', 'acceptée', 'refusée') DEFAULT 'en attente',
    is_seen_by_user BOOLEAN DEFAULT FALSE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_randonnee) REFERENCES randonnee(id_randonnee) ON DELETE CASCADE,
    UNIQUE (id_user, id_randonnee)
);
```

**Fields:**
- `id_participation`: Request identifier
- `id_user`: Requesting user
- `id_randonnee`: Requested trip
- `statut`: Request status (pending/accepted/rejected)
- `is_seen_by_user`: Whether user has viewed the response

---

### Favorites Table

```sql
CREATE TABLE favoris (
    id_user INT NOT NULL,
    id_randonnee INT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_user, id_randonnee),
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_randonnee) REFERENCES randonnee(id_randonnee) ON DELETE CASCADE
);
```

---

## API Documentation

### URL Structure

The application uses a simple URL-based routing system:

```
index.php?page=PAGE_NAME&action=ACTION_NAME
```

### Authentication Routes

#### Login
- **URL:** `index.php?page=auth&action=login`
- **Method:** GET/POST
- **Access:** Public
- **Parameters (POST):**
  - `email`: User email
  - `password`: User password

#### Register
- **URL:** `index.php?page=auth&action=register`
- **Method:** GET/POST
- **Access:** Public
- **Parameters (POST):**
  - `nom`: Full name
  - `email`: Email address
  - `password`: Password
  - `confirm_password`: Password confirmation
  - `telephone`: Phone number (optional)

#### Logout
- **URL:** `index.php?page=auth&action=logout`
- **Method:** GET
- **Access:** Authenticated users

---

### Home/Public Routes

#### Home Page
- **URL:** `index.php?page=home&action=index`
- **Method:** GET
- **Access:** Public
- **Description:** Displays landing page with featured trips

#### List All Trips
- **URL:** `index.php?page=randonnee&action=index`
- **Method:** GET
- **Access:** Public
- **Query Parameters:**
  - `search`: Search keyword
  - `difficulte`: Filter by difficulty
  - `region`: Filter by region
  - `sort`: Sort order (price, date, difficulty)

#### Trip Details
- **URL:** `index.php?page=randonnee&action=details&id=ID`
- **Method:** GET
- **Access:** Public
- **Parameters:**
  - `id`: Trip ID

---

### User Routes

#### User Dashboard
- **URL:** `index.php?page=user&action=dashboard`
- **Method:** GET
- **Access:** Authenticated users only
- **Description:** View participation requests and activity

#### Request Participation
- **URL:** `index.php?page=randonnee&action=participate`
- **Method:** POST
- **Access:** Authenticated users only
- **Parameters:**
  - `id_randonnee`: Trip ID
- **Validations:**
  - No date conflicts with existing participations
  - Trip capacity not exceeded
  - User hasn't already requested this trip

#### View Favorites
- **URL:** `index.php?page=user&action=favorites`
- **Method:** GET
- **Access:** Authenticated users only

#### Add to Favorites
- **URL:** `index.php?page=randonnee&action=addFavorite`
- **Method:** POST
- **Access:** Authenticated users only
- **Parameters:**
  - `id_randonnee`: Trip ID

#### User Settings
- **URL:** `index.php?page=user&action=settings`
- **Method:** GET/POST
- **Access:** Authenticated users only
- **POST Parameters:**
  - `nom`: Full name
  - `telephone`: Phone number
  - `image_profile`: Profile picture (file upload)

---

### Admin Routes

#### Admin Dashboard
- **URL:** `index.php?page=admin&action=dashboard`
- **Method:** GET
- **Access:** Admin only
- **Description:** Overview and statistics

#### Manage Trips (CRUD)
- **List:** `index.php?page=admin&action=randonnees`
- **Create:** `index.php?page=admin&action=createRandonnee` (GET/POST)
- **Edit:** `index.php?page=admin&action=editRandonnee&id=ID` (GET/POST)
- **Delete:** `index.php?page=admin&action=deleteRandonnee&id=ID`
- **Access:** Admin only

**Create/Edit Parameters:**
- `titre`: Trip title
- `categorie`: Category
- `description`: Description
- `date_debut`: Start date (YYYY-MM-DD HH:MM:SS)
- `date_fin`: End date
- `region`: Region
- `distance`: Distance in km
- `difficulte`: Difficulty level
- `capacite`: Max participants
- `prix`: Price
- `id_bus`: Bus assignment (optional)
- `image`: Image file (optional)

#### Manage Buses (CRUD)
- **List:** `index.php?page=admin&action=buses`
- **Create:** `index.php?page=admin&action=createBus` (GET/POST)
- **Edit:** `index.php?page=admin&action=editBus&id=ID` (GET/POST)
- **Delete:** `index.php?page=admin&action=deleteBus&id=ID`
- **Access:** Admin only

**Parameters:**
- `immat_bus`: License plate
- `nb_places`: Capacity
- `chauffeur`: Driver name
- `tel_chauff`: Driver phone

#### Manage Participations
- **List:** `index.php?page=admin&action=participations`
- **Accept:** `index.php?page=admin&action=acceptParticipation&id=ID`
- **Reject:** `index.php?page=admin&action=rejectParticipation&id=ID`
- **Access:** Admin only

---

## User Roles & Permissions

### Admin Role

**Permissions:**
- ✅ Access admin dashboard
- ✅ Create, read, update, delete trips
- ✅ Create, read, update, delete buses
- ✅ View and manage all participations
- ✅ Manage user accounts
- ✅ View statistics and reports

**Access Control:**
```php
verifierAcces('admin');  // In controllers
```

### User Role

**Permissions:**
- ✅ Browse and search trips
- ✅ Request participation
- ✅ Manage participation requests
- ✅ Create and manage favorites
- ✅ Edit personal profile
- ✅ View own dashboard

**Restrictions:**
- ❌ Cannot access admin panel
- ❌ Cannot create/edit/delete trips
- ❌ Cannot manage other users' data

**Access Control:**
```php
verifierAcces();  // Require authentication
```

---

## Architecture Overview

### MVC Pattern Implementation

```
REQUEST
   ↓
ROUTER (index.php)
   ↓
CONTROLLER (Handle Logic)
   ├→ MODEL (Database Operations)
   └→ VIEW (Render Template)
   ↓
RESPONSE (HTML)
```

### Data Flow

1. **Request** → User submits form or clicks link
2. **Routing** → `index.php` parses `?page=` and `?action=`
3. **Controller** → Processes request, validates input
4. **Model** → Retrieves/modifies data from database
5. **View** → Renders HTML template
6. **Response** → Sends HTML to browser

### Security Features

- **SQL Injection Prevention:** Prepared statements with PDO
- **Password Security:** Bcrypt hashing
- **Session Management:** PHP sessions with ID validation
- **Input Validation:** Server-side validation
- **CSRF Protection:** Can be added via token validation

---

## Troubleshooting

### Common Issues

#### 1. "Error connecting to database"

**Problem:** PDOException on startup

**Solutions:**
- Verify `config/db.php` has correct credentials
- Ensure MySQL service is running
- Check if database `randonnee_tunisie` exists
- Verify MySQL user has proper permissions

```bash
# Check MySQL service (Windows)
Get-Service MySQL*

# Check MySQL service (Linux)
sudo systemctl status mysql
```

#### 2. "403 Forbidden" or "404 Not Found"

**Problem:** Page not accessible

**Solutions:**
- Ensure `.htaccess` or Apache rewrite is configured
- Check folder permissions (755 for directories, 644 for files)
- Verify the URL path matches your installation location

#### 3. Upload directory issues

**Problem:** "Unable to save file" or image uploads fail

**Solutions:**
```bash
# Fix permissions (Linux/Mac)
chmod 755 public/uploads/
chmod 755 public/uploads/profiles/
chmod 755 public/uploads/randonnees/
```

#### 4. Session not persisting

**Problem:** User gets logged out frequently

**Solutions:**
- Check `session.save_path` in `php.ini`
- Ensure temporary directory is writable
- Verify `session_start()` is called in index.php

#### 5. Database import fails

**Problem:** Error when importing `database.sql`

**Solutions:**
- Check file encoding is UTF-8
- Verify MySQL charset is utf8mb4
- Try importing line-by-line if batch import fails
- Check for special characters in data

---

## License & Support

### License Information

This product is provided for commercial use. Please refer to the included LICENSE file for terms and conditions.

### Support

For technical support or customization inquiries:
- Include detailed error messages
- Provide screenshots when possible
- Describe steps to reproduce issues
- Specify your server environment (OS, PHP version, MySQL version)

### Recommended Hosting

- PHP 7.2+ support
- MySQL 5.7+ / MariaDB 10.3+
- Apache with mod_rewrite enabled
- Minimum 50MB disk space
- HTTPS support

---

## Future Enhancement Possibilities

### Phase 2 Features
- Email notifications for participation updates
- Payment gateway integration
- Advanced analytics and reporting
- Mobile app (native iOS/Android)
- API for third-party integrations
- Multi-language support
- Review and rating system
- Chat/messaging between users and admins

### Technical Improvements
- Migration to modern PHP framework (Laravel/Symfony)
- API (REST/GraphQL)
- Unit testing suite
- Automated backup system
- CDN integration for uploads
- Search engine optimization (SEO)

---

**Thank you for choosing our Hiking Management System!**

For more information or to report issues, please contact the development team.

---

*Document Version: 1.0 | Last Updated: June 2026*
