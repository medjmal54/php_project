# Developer & Architecture Guide

Technical documentation for developers working with the Hiking Management System.

## Table of Contents

1. [System Architecture](#system-architecture)
2. [Technology Stack](#technology-stack)
3. [Project Structure](#project-structure)
4. [MVC Implementation](#mvc-implementation)
5. [Database Layer](#database-layer)
6. [Controller Development](#controller-development)
7. [Model Development](#model-development)
8. [View/Template System](#viewtemplate-system)
9. [Session & Authentication](#session--authentication)
10. [Code Standards](#code-standards)
11. [API Endpoints](#api-endpoints)
12. [Adding New Features](#adding-new-features)
13. [Testing](#testing)
14. [Performance Optimization](#performance-optimization)
15. [Deployment](#deployment)

---

## System Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────┐
│         Web Browser / Client            │
└──────────────────┬──────────────────────┘
                   │ HTTP Request
┌──────────────────▼──────────────────────┐
│    Apache Web Server (mod_rewrite)      │
└──────────────────┬──────────────────────┘
                   │
┌──────────────────▼──────────────────────┐
│    index.php (Front Controller)         │
│    - Session initialization             │
│    - Include dependencies               │
│    - Simple routing                     │
└──────────────────┬──────────────────────┘
                   │
┌──────────────────▼──────────────────────┐
│  Controller Layer (Business Logic)      │
│  - HomeController                       │
│  - AuthController                       │
│  - RandonneeController                  │
│  - UserController                       │
│  - AdminController                      │
└──────────────────┬──────────────────────┘
                   │
     ┌─────────────┼─────────────┐
     │             │             │
┌────▼──┐  ┌─────▼──────┐  ┌──▼────────┐
│ Models│  │  Database  │  │  Views    │
│       │  │   Layer    │  │ Templates │
└───────┘  └────────────┘  └───────────┘
```

### Request Flow Diagram

```
1. User Request
   ↓
2. URL Parsed: ?page=X&action=Y
   ↓
3. Route to Controller
   ↓
4. Controller Method Called
   ↓
5. Model Data Retrieved/Updated
   ↓
6. View Template Rendered
   ↓
7. HTML Response Sent
```

---

## Technology Stack

### Backend
- **Language:** PHP 7.2+
- **Pattern:** Model-View-Controller (MVC)
- **Database:** MySQL 5.7+ / MariaDB 10.3+
- **Database Access:** PDO (PHP Data Objects)
- **Session Management:** Native PHP Sessions

### Frontend
- **HTML:** HTML5
- **CSS:** CSS3 (Vanilla, no framework)
- **JavaScript:** Vanilla JavaScript (no jQuery or frameworks)
- **Responsive Design:** Mobile-first approach

### Server
- **Web Server:** Apache 2.4+
- **Required Modules:** mod_rewrite for URL rewriting
- **PHP Extensions:** PDO, PDO MySQL, GD Library

### Version Control & Deployment
- **Version Control:** Git (optional)
- **Deployment:** FTP/SFTP or direct file upload

---

## Project Structure

### Complete Directory Tree

```
projet/
├── config/
│   └── db.php                      # Database connection configuration
│       └── Contains: PDO connection setup, error handling
│
├── controllers/                    # Business logic layer
│   ├── AdminController.php         # Admin panel operations
│   │   ├── dashboard()            # Show admin dashboard
│   │   ├── randonnees()          # List trips
│   │   ├── createRandonnee()     # Create new trip
│   │   ├── editRandonnee()       # Edit existing trip
│   │   ├── deleteRandonnee()     # Delete trip
│   │   ├── buses()               # Manage buses
│   │   ├── participations()      # Manage requests
│   │   └── acceptParticipation() # Approve request
│   │
│   ├── AuthController.php          # Authentication
│   │   ├── login()               # Handle login
│   │   ├── register()            # Handle registration
│   │   └── logout()              # Handle logout
│   │
│   ├── HomeController.php          # Public pages
│   │   └── index()               # Home page
│   │
│   ├── RandonneeController.php     # Trip management
│   │   ├── index()               # List all trips
│   │   ├── details()             # Show trip details
│   │   ├── participate()         # Request participation
│   │   └── addFavorite()         # Add to favorites
│   │
│   └── UserController.php          # User operations
│       ├── dashboard()           # User dashboard
│       ├── favorites()           # List favorites
│       └── settings()            # User profile settings
│
├── models/                         # Data access layer
│   ├── UserModel.php              # User database operations
│   │   ├── findById()            # Get user by ID
│   │   ├── findByEmail()         # Get user by email
│   │   ├── create()              # Create new user
│   │   ├── update()              # Update user info
│   │   └── getAll()              # Get all users
│   │
│   ├── RandonneeModel.php         # Trip database operations
│   │   ├── findById()            # Get trip by ID
│   │   ├── getAll()              # Get all trips
│   │   ├── create()              # Create new trip
│   │   ├── update()              # Update trip
│   │   ├── delete()              # Delete trip
│   │   └── findByRegion()        # Filter by region
│   │
│   ├── ParticipationModel.php     # Participation requests
│   │   ├── create()              # Submit request
│   │   ├── getByUser()           # Get user's requests
│   │   ├── getByTrip()           # Get trip's requests
│   │   ├── accept()              # Approve request
│   │   └── reject()              # Reject request
│   │
│   ├── BusModel.php              # Bus fleet management
│   │   ├── getAll()              # Get all buses
│   │   ├── findById()            # Get specific bus
│   │   ├── create()              # Add new bus
│   │   ├── update()              # Update bus info
│   │   └── delete()              # Delete bus
│   │
│   └── FavoriteModel.php          # Favorites/Wishlist
│       ├── add()                 # Add to favorites
│       ├── remove()              # Remove from favorites
│       └── getByUser()           # Get user's favorites
│
├── views/                          # Presentation layer
│   ├── home.php                   # Landing page
│   │
│   ├── admin/                     # Admin panel templates
│   │   ├── dashboard.php         # Admin overview
│   │   ├── manage_randonnees.php # Trip management UI
│   │   ├── manage_buses.php      # Bus management UI
│   │   ├── manage_participations.php
│   │   └── settings.php          # Admin settings
│   │
│   ├── auth/                      # Authentication pages
│   │   ├── login.php             # Login form
│   │   └── register.php          # Registration form
│   │
│   ├── layout/                    # Reusable components
│   │   ├── header.php            # Common header
│   │   ├── footer.php            # Common footer
│   │   ├── admin_header_actions.php
│   │   ├── admin_sidebar.php     # Admin navigation
│   │   ├── user_header_actions.php
│   │   └── user_sidebar.php      # User navigation
│   │
│   ├── randonnee/                 # Trip browsing
│   │   ├── index.php             # Trip list page
│   │   └── details.php           # Trip detail page
│   │
│   └── user/                      # User pages
│       ├── dashboard.php         # User dashboard
│       ├── favorites.php         # Favorites page
│       └── settings.php          # Profile settings
│
├── public/                         # Static assets
│   ├── assets/
│   │   └── css/
│   │       ├── global.css        # Global styles
│   │       ├── admin.css         # Admin panel styles
│   │       ├── auth.css          # Auth page styles
│   │       ├── home.css          # Home page styles
│   │       ├── randonnee-list.css
│   │       ├── randonnee-details.css
│   │       ├── user.css          # User page styles
│   │       └── style.css         # Additional styles
│   │
│   └── uploads/                  # User uploads
│       ├── profiles/             # Profile pictures
│       └── randonnees/           # Trip images
│
├── index.php                       # Main entry point
│   ├── Session start
│   ├── Include dependencies
│   ├── URL routing logic
│   └── Error handling
│
├── database.sql                    # Database schema
│
└── DOCUMENTATION files/
    ├── DOCUMENTATION.md           # Main documentation
    ├── INSTALLATION_GUIDE.md      # Setup instructions
    ├── USER_GUIDE.md              # End user guide
    ├── ADMIN_GUIDE.md             # Admin guide
    ├── DEVELOPER_GUIDE.md         # This file
    └── README.md                  # Quick start
```

---

## MVC Implementation

### Model-View-Controller Pattern

This project follows a simplified MVC pattern without heavy frameworks:

### Model Layer

**Purpose:** Data persistence and retrieval

**Characteristics:**
- Each model represents a database entity
- Handles all database operations (CRUD)
- Uses prepared statements for security
- Returns data objects or arrays

**Example Model Structure:**

```php
<?php
class RandonneeModel {
    // Properties
    private $id_randonnee;
    private $titre;
    private $description;
    // ... other properties

    // Constructor
    public function __construct($row) {
        // Initialize from database row
    }

    // Getters
    public function getId() { return $this->id_randonnee; }
    public function getTitre() { return $this->titre; }

    // Static finder methods (database queries)
    public static function findById($id) { }
    public static function getAll() { }
    public static function create($data) { }
    public static function update($id, $data) { }
    public static function delete($id) { }
}
?>
```

### Controller Layer

**Purpose:** Request handling and business logic

**Characteristics:**
- Routes requests to appropriate models
- Processes user input
- Validates data
- Prepares data for views
- Handles redirects and errors

**Example Controller Structure:**

```php
<?php
class RandonneeController {
    // Display trip list
    public function index() {
        // Get filters from URL
        $search = $_GET['search'] ?? '';
        $difficulty = $_GET['difficulty'] ?? null;
        
        // Query database
        $trips = RandonneeModel::search($search, $difficulty);
        
        // Load view
        require_once 'views/randonnee/index.php';
    }

    // Display trip details
    public function details() {
        $id = $_GET['id'] ?? null;
        
        // Validate input
        if (!$id || !is_numeric($id)) {
            header('Location: index.php?page=randonnee&error=invalid');
            exit();
        }
        
        // Get trip data
        $trip = RandonneeModel::findById($id);
        
        // Check if found
        if (!$trip) {
            header('Location: index.php?page=randonnee&error=not_found');
            exit();
        }
        
        // Load view
        require_once 'views/randonnee/details.php';
    }

    // Handle participation request
    public function participate() {
        // Check authentication
        verifierAcces();
        
        // Handle POST only
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit('Invalid request');
        }
        
        // Get and validate input
        $id_randonnee = $_POST['id_randonnee'] ?? null;
        
        // Create participation
        $result = ParticipationModel::create([
            'id_user' => $_SESSION['user_id'],
            'id_randonnee' => $id_randonnee
        ]);
        
        // Handle result
        if ($result) {
            header('Location: index.php?page=user&action=dashboard&success=request_sent');
        } else {
            header('Location: index.php?page=randonnee&action=details&id='.$id_randonnee.'&error=request_failed');
        }
    }
}
?>
```

### View Layer

**Purpose:** Present data to users

**Characteristics:**
- Contains HTML and minimal PHP
- Uses variables set by controller
- Displays data from models
- Handles form submissions
- Responsive and user-friendly

**Example View Structure:**

```php
<!-- views/randonnee/index.php -->
<?php require_once 'views/layout/header.php'; ?>

<div class="container">
    <h1>Hiking Trips</h1>
    
    <!-- Filter form -->
    <form method="get" class="filters">
        <input type="hidden" name="page" value="randonnee">
        <input type="text" name="search" placeholder="Search trips...">
        <select name="difficulty">
            <option value="">All Difficulties</option>
            <option value="Facile">Easy</option>
            <option value="Moyen">Medium</option>
            <option value="Difficile">Hard</option>
        </select>
        <button type="submit">Filter</button>
    </form>
    
    <!-- Trip listing -->
    <div class="trips-grid">
        <?php foreach ($trips as $trip): ?>
            <div class="trip-card">
                <img src="<?php echo htmlspecialchars($trip->getImage()); ?>" alt="Trip image">
                <h3><?php echo htmlspecialchars($trip->getTitre()); ?></h3>
                <p class="difficulty">Difficulty: <?php echo htmlspecialchars($trip->getDifficulte()); ?></p>
                <p class="price">Price: $<?php echo number_format($trip->getPrix(), 2); ?></p>
                <a href="index.php?page=randonnee&action=details&id=<?php echo $trip->getId(); ?>">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
```

---

## Database Layer

### PDO Connection

Located in `config/db.php`:

```php
<?php
$host = 'localhost';
$dbname = 'randonnee_tunisie';
$user = 'root';
$pass = '';

try {
    $db = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass
    );
    // Set error mode
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Error: " . $e->getMessage());
}
?>
```

### Using PDO in Models

**Prepared Statement Example:**

```php
<?php
public static function findByEmail($email) {
    include 'config/db.php';
    
    // Prepare statement (prevents SQL injection)
    $stmt = $db->prepare("SELECT * FROM user WHERE email = ?");
    
    // Execute with parameter
    $stmt->execute([$email]);
    
    // Fetch result
    $row = $stmt->fetch();
    
    // Return object or null
    return $row ? new UserModel($row) : null;
}
?>
```

**Insert/Update Example:**

```php
<?php
public static function create($data) {
    include 'config/db.php';
    
    $stmt = $db->prepare("
        INSERT INTO randonnee 
        (titre, description, date_debut, date_fin, difficulte, prix)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $data['titre'],
        $data['description'],
        $data['date_debut'],
        $data['date_fin'],
        $data['difficulte'],
        $data['prix']
    ]);
    
    return $db->lastInsertId();
}
?>
```

### Database Transactions

For multiple related operations:

```php
<?php
try {
    $db->beginTransaction();
    
    // Multiple operations
    $stmt1 = $db->prepare("INSERT INTO participation ...");
    $stmt1->execute($data1);
    
    $stmt2 = $db->prepare("UPDATE randonnee ...");
    $stmt2->execute($data2);
    
    // Commit if all successful
    $db->commit();
} catch (Exception $e) {
    // Rollback on any error
    $db->rollBack();
    throw $e;
}
?>
```

---

## Controller Development

### Creating a New Controller

1. Create file: `controllers/MyController.php`
2. Define class extending base functionality (optional)
3. Add action methods

**Template:**

```php
<?php
/*
 * MyController - Description of what it manages
 */
class MyController {

    public function index() {
        // Default action logic
        require_once 'views/myview/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form submission
            $data = [
                'field1' => $_POST['field1'] ?? null,
                'field2' => $_POST['field2'] ?? null
            ];
            
            // Validate
            if (!$this->validate($data)) {
                $error = "Validation failed";
                require_once 'views/myview/create.php';
                return;
            }
            
            // Save
            $result = MyModel::create($data);
            
            if ($result) {
                header('Location: index.php?page=mypage&action=index&success=created');
                exit();
            } else {
                $error = "Failed to create";
                require_once 'views/myview/create.php';
            }
        } else {
            // Display form
            require_once 'views/myview/create.php';
        }
    }

    private function validate($data) {
        // Validation logic
        return true;
    }
}
?>
```

### Common Controller Patterns

**Authentication Check:**
```php
verifierAcces();  // Require login
verifierAcces('admin');  // Require admin
```

**Input Sanitization:**
```php
$input = htmlspecialchars($_POST['field']);
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
```

**Error Handling:**
```php
try {
    // Database operation
} catch (Exception $e) {
    $error = "An error occurred: " . $e->getMessage();
    require_once 'views/error.php';
}
```

---

## Model Development

### Creating a New Model

```php
<?php
/*
 * MyModel - Represents database 'my_table'
 */
class MyModel {
    private $id;
    private $name;
    private $description;

    public function __construct($row) {
        $this->id = $row['id'] ?? null;
        $this->name = $row['name'] ?? null;
        $this->description = $row['description'] ?? null;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getDescription() { return $this->description; }

    // Setters
    public function setName($name) { $this->name = $name; }
    public function setDescription($desc) { $this->description = $desc; }

    // Static finder methods
    public static function findById($id) {
        include 'config/db.php';
        $stmt = $db->prepare("SELECT * FROM my_table WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? new MyModel($row) : null;
    }

    public static function getAll() {
        include 'config/db.php';
        $stmt = $db->prepare("SELECT * FROM my_table ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    }

    public static function create($data) {
        include 'config/db.php';
        $stmt = $db->prepare("
            INSERT INTO my_table (name, description)
            VALUES (?, ?)
        ");
        return $stmt->execute([
            $data['name'],
            $data['description']
        ]) ? $db->lastInsertId() : false;
    }

    public static function update($id, $data) {
        include 'config/db.php';
        $stmt = $db->prepare("
            UPDATE my_table 
            SET name = ?, description = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['name'],
            $data['description'],
            $id
        ]);
    }

    public static function delete($id) {
        include 'config/db.php';
        $stmt = $db->prepare("DELETE FROM my_table WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
```

---

## View/Template System

### Template Includes

**Header:**
```php
<?php require_once 'views/layout/header.php'; ?>
```

**Footer:**
```php
<?php require_once 'views/layout/footer.php'; ?>
```

**Conditional Includes:**
```php
<?php 
if ($_SESSION['role'] === 'admin') {
    require_once 'views/layout/admin_sidebar.php';
} else {
    require_once 'views/layout/user_sidebar.php';
}
?>
```

### Passing Data to Views

Controller prepares variables:
```php
$trip = RandonneeModel::findById($id);
$participations = ParticipationModel::getByTrip($id);
require_once 'views/trip_details.php';
```

View uses variables:
```php
<h1><?php echo htmlspecialchars($trip->getTitre()); ?></h1>
<p><?php echo htmlspecialchars($trip->getDescription()); ?></p>
```

### Security in Views

**Always escape output:**
```php
// ✅ Good - prevents XSS
<?php echo htmlspecialchars($user_input); ?>

// ❌ Bad - vulnerable to XSS
<?php echo $user_input; ?>
```

**Escape for different contexts:**
```php
// HTML context
htmlspecialchars($text)

// URL context
urlencode($text)

// JavaScript context
json_encode($data)

// SQL context (use PDO prepared statements)
```

---

## Session & Authentication

### Session Management

**Start session (in index.php):**
```php
<?php
session_start();
```

**Store user info on login:**
```php
<?php
$_SESSION['user_id'] = $user->getId();
$_SESSION['user_name'] = $user->getNom();
$_SESSION['email'] = $user->getEmail();
$_SESSION['role'] = $user->getRole();
```

**Check if logged in:**
```php
<?php
if (isset($_SESSION['user_id'])) {
    // User is logged in
}
```

**Logout:**
```php
<?php
session_destroy();
header('Location: index.php');
```

### Access Control

**Function in index.php:**
```php
<?php
function verifierAcces($roleAttendu = null) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=auth&action=login');
        exit();
    }
    if ($roleAttendu && $_SESSION['role'] !== $roleAttendu) {
        header('Location: index.php?page=home&error=acces_refuse');
        exit();
    }
}
?>
```

**Using in controllers:**
```php
<?php
class AdminController {
    public function dashboard() {
        verifierAcces('admin');  // Require admin role
        // ...
    }
}
?>
```

---

## Code Standards

### Naming Conventions

**Files and Classes:**
- Class names: PascalCase, match filename
- Filename: `MyClass.php`
- Class: `class MyClass { }`

**Variables and Functions:**
- Variables: $camelCase or $snake_case
- Methods: camelCase
- Constants: UPPER_SNAKE_CASE

**Database:**
- Tables: lowercase, singular (user, randonnee)
- Columns: lowercase, snake_case (id_user, date_debut)
- Foreign keys: `id_<table_name>`

### Code Formatting

**Indentation:**
```php
<?php
if ($condition) {
    // 4 spaces indent
    $variable = "value";
    
    if ($nested) {
        // 8 spaces indent
    }
}
?>
```

**Comments:**
```php
<?php
// Single line comment

/*
 * Multi-line comment
 * explaining complex logic
 */

// TODO: Description of work needed
// FIXME: Description of issue to fix
?>
```

### Security Practices

**SQL Injection Prevention:**
```php
// ✅ Good - prepared statements
$stmt = $db->prepare("SELECT * FROM user WHERE id = ?");
$stmt->execute([$user_id]);

// ❌ Bad - string concatenation
$query = "SELECT * FROM user WHERE id = $user_id";
```

**XSS Prevention:**
```php
// ✅ Good - escape output
echo htmlspecialchars($user_input);

// ❌ Bad - direct output
echo $user_input;
```

**Password Security:**
```php
// ✅ Good - bcrypt hashing
$password_hash = password_hash($password, PASSWORD_BCRYPT);
$verified = password_verify($input_password, $password_hash);

// ❌ Bad - no hashing
$query = "INSERT INTO user ... VALUES ('$password')";
```

---

## API Endpoints

### Request Format

All requests use URL-based routing:
```
index.php?page=PAGE_NAME&action=ACTION_NAME[&param=value]
```

### Core Endpoints

**Authentication:**
- `?page=auth&action=login` - User login
- `?page=auth&action=register` - User registration
- `?page=auth&action=logout` - User logout

**Trips:**
- `?page=randonnee&action=index` - List trips
- `?page=randonnee&action=details&id=1` - Trip details
- `?page=randonnee&action=participate` - Submit participation (POST)
- `?page=randonnee&action=addFavorite` - Add to favorites (POST)

**User:**
- `?page=user&action=dashboard` - User dashboard
- `?page=user&action=favorites` - User favorites
- `?page=user&action=settings` - User settings

**Admin:**
- `?page=admin&action=dashboard` - Admin dashboard
- `?page=admin&action=randonnees` - Manage trips
- `?page=admin&action=createRandonnee` - Create trip (GET/POST)
- `?page=admin&action=buses` - Manage buses
- `?page=admin&action=participations` - Manage participations

---

## Adding New Features

### Feature Development Workflow

**1. Plan the Feature**
```
- User stories
- Required database changes
- UI/UX mockups
- API endpoints needed
```

**2. Database Schema**
```sql
-- Add new table if needed
ALTER TABLE existing_table ADD COLUMN new_column VARCHAR(100);
```

**3. Create Model**
```
controllers/MyFeatureModel.php
- Add data access methods
- Handle database operations
```

**4. Create Controller**
```
controllers/MyFeatureController.php
- Add action methods
- Handle business logic
```

**5. Create Views**
```
views/myfeature/index.php
views/myfeature/create.php
views/myfeature/edit.php
```

**6. Add Routing**
```php
// In index.php, add case for new page
case 'myfeature':
    $ctrl = new MyFeatureController();
    $action = $action ?: 'index';
    $ctrl->$action();
    break;
```

**7. Test**
- Manual testing
- Check error handling
- Verify database changes
- Test security

### Example: Adding a Review System

**1. Database:**
```sql
CREATE TABLE reviews (
    id_review INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_randonnee INT NOT NULL,
    rating INT (1-5),
    comment TEXT,
    date_created TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id_user),
    FOREIGN KEY (id_randonnee) REFERENCES randonnee(id_randonnee)
);
```

**2. Model (ReviewModel.php):**
```php
<?php
class ReviewModel {
    public static function create($data) { ... }
    public static function getByTrip($trip_id) { ... }
    public static function getByUser($user_id) { ... }
}
?>
```

**3. Controller (ReviewController.php):**
```php
<?php
class ReviewController {
    public function submit() { ... }
    public function delete() { ... }
}
?>
```

**4. Views:**
- `views/review/submit.php` - Review form
- `views/review/list.php` - Display reviews

**5. Integrate:**
- Add review section to trip details view
- Add review form in user dashboard
- Update trip details view to show average rating

---

## Testing

### Manual Testing Checklist

**Authentication:**
- [ ] New user registration works
- [ ] Login with correct credentials succeeds
- [ ] Login with wrong credentials fails
- [ ] Session persists across pages
- [ ] Logout clears session

**Core Features:**
- [ ] Can create trip (admin)
- [ ] Can edit trip (admin)
- [ ] Can view trip list (public)
- [ ] Can view trip details
- [ ] Can request participation (user)
- [ ] Can add to favorites
- [ ] Can approve/reject requests (admin)

**Data Validation:**
- [ ] Required fields enforced
- [ ] Email validation works
- [ ] Password confirmation validated
- [ ] Date validation (start before end)
- [ ] Capacity checks work
- [ ] Date conflict detection works

**Error Handling:**
- [ ] Handles missing database
- [ ] Handles database errors
- [ ] Displays user-friendly errors
- [ ] Handles missing files
- [ ] Handles invalid input

**Security:**
- [ ] SQL injection attempts fail
- [ ] XSS attempts are escaped
- [ ] CSRF protection (if implemented)
- [ ] Access control works
- [ ] File uploads are validated

### Browser Testing

Test on:
- [ ] Chrome latest
- [ ] Firefox latest
- [ ] Safari latest
- [ ] Edge latest
- [ ] Mobile browsers

---

## Performance Optimization

### Database Optimization

**Add Indexes:**
```sql
CREATE INDEX idx_user_email ON user(email);
CREATE INDEX idx_randonnee_region ON randonnee(region);
CREATE INDEX idx_participation_user ON participation(id_user);
```

**Query Optimization:**
```php
// ✅ Good - one query with join
$stmt = $db->prepare("
    SELECT r.*, u.nom 
    FROM participation p
    JOIN randonnee r ON r.id_randonnee = p.id_randonnee
    JOIN user u ON u.id_user = p.id_user
    WHERE p.id_user = ?
");

// ❌ Bad - multiple queries (N+1 problem)
foreach ($participations as $p) {
    $trip = RandonneeModel::findById($p['id_randonnee']);
    $user = UserModel::findById($p['id_user']);
}
```

### Caching

**Session caching:**
```php
// Cache trip data in session for current request
$_SESSION['cached_trip_' . $id] = $trip;
```

**File caching:**
```php
$cache_file = 'cache/trip_' . $id . '.php';
if (file_exists($cache_file) && time() - filemtime($cache_file) < 3600) {
    $trip = include $cache_file;
} else {
    $trip = RandonneeModel::findById($id);
    file_put_contents($cache_file, serialize($trip));
}
```

### Image Optimization

```php
// Reduce image file size on upload
if (isset($_FILES['image'])) {
    $image = $_FILES['image'];
    // Compress before saving
    $img = imagecreatefromjpeg($image['tmp_name']);
    imagejpeg($img, $target_path, 85);  // 85% quality
    imagedestroy($img);
}
```

---

## Deployment

### Production Checklist

**Before deployment:**
- [ ] Test all features thoroughly
- [ ] Remove debug code
- [ ] Update database credentials
- [ ] Set proper file permissions
- [ ] Enable error logging
- [ ] Disable error display (security)
- [ ] Set up HTTPS
- [ ] Configure backups
- [ ] Review security settings
- [ ] Test on production environment

**php.ini Settings (Production):**
```ini
display_errors = Off
log_errors = On
error_log = /var/log/php/errors.log
session.cookie_httponly = 1
session.use_only_cookies = 1
```

**File Permissions:**
```bash
chmod 755 /var/www/html/projet
chmod 755 /var/www/html/projet/public
chmod 777 /var/www/html/projet/public/uploads
chmod 644 /var/www/html/projet/config/db.php
```

### Deployment Process

```bash
1. Backup current database and files
2. Download latest application files
3. Copy files to production server
4. Run database migrations/updates
5. Test application functionality
6. Monitor error logs
7. Announce to users if needed
```

---

**Happy coding! Follow these guidelines to maintain code quality and security.**
