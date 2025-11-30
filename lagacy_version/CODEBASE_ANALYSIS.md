# Wheelder Project - Deep Code Structure Analysis

## 📋 Table of Contents
1. [Project Overview](#project-overview)
2. [Architecture Pattern](#architecture-pattern)
3. [Directory Structure](#directory-structure)
4. [Core Components](#core-components)
5. [Database Architecture](#database-architecture)
6. [Authentication & Authorization](#authentication--authorization)
7. [Routing System](#routing-system)
8. [Application Modules](#application-modules)
9. [API Structure](#api-structure)
10. [Dependencies & Technologies](#dependencies--technologies)
11. [Security Considerations](#security-considerations)
12. [Data Flow](#data-flow)

---

## 🎯 Project Overview

**Wheelder** is a multi-application PHP platform built with a modular architecture. It serves as a platform for multiple applications (currently focused on Education) with shared authentication, database, and core services.

**Key Characteristics:**
- Multi-tenant application platform
- Modular application structure (apps/edu, apps/work, apps/personal)
- Shared authentication system
- Centralized database configuration
- Custom routing system
- Stripe payment integration
- AI-powered learning features (OpenAI integration)

---

## 🏗️ Architecture Pattern

### **Hybrid MVC with Layered Architecture**

The project follows a **modified MVC pattern** with additional layers:

```
┌─────────────────────────────────────┐
│         Presentation Layer          │
│    (Views, UI Components, Routes)    │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│         Controller Layer            │
│  (Business Logic, Request Handling) │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│         Service Layer               │
│  (Database, Stripe, File Handling)  │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│         Data Access Layer           │
│    (Database Connection, Models)    │
└─────────────────────────────────────┘
```

**Pattern Details:**
- **Views**: PHP templates with HTML/CSS/JS
- **Controllers**: Handle business logic, extend base Controller class
- **Models**: Database abstraction layer (Database class)
- **Services**: Traits (StripeController, CrudController, Metadata)
- **APIs**: RESTful endpoints for AJAX requests

---

## 📁 Directory Structure

```
wheelder/
├── index.php                 # Main entry point & router configuration
├── Router.php               # Custom routing class
├── top.php                  # Session & global initialization
├── landing.php              # Landing page
├── default.php              # Default/home page
│
├── pool/                    # Shared/core resources
│   ├── api/                 # API endpoints
│   │   ├── logsAPI.php      # Authentication API
│   │   ├── profileAPI.php   # User profile API
│   │   ├── settingsAPI.php  # Settings API
│   │   ├── filesAPI.php     # File upload API
│   │   └── ...
│   │
│   ├── auth/                # Authentication views
│   │   ├── login.php
│   │   ├── signup.php
│   │   ├── verification.php  # OTP verification
│   │   ├── forgot_password.php
│   │   └── ...
│   │
│   ├── config/              # Configuration files
│   │   ├── db_config.php    # Database configuration
│   │   ├── database.php     # Database abstraction class
│   │   └── db_setup.php     # Database migrations
│   │
│   ├── libs/                # Core libraries
│   │   ├── controllers/      # Base controllers
│   │   │   ├── Controller.php      # Base controller
│   │   │   ├── LogsController.php   # Auth controller
│   │   │   ├── StripeController.php # Payment trait
│   │   │   ├── CrudController.php   # CRUD operations trait
│   │   │   └── Metadata.php        # Metadata trait
│   │   │
│   │   ├── composer/       # Composer dependencies
│   │   └── Router.php      # Router implementation
│   │
│   └── dev/                 # Development tools
│
├── apps/                    # Application modules
│   └── edu/                 # Education application
│       ├── api/             # Education-specific APIs
│       │   ├── open_ai.php          # OpenAI integration
│       │   ├── noteAPI.php          # Notes API
│       │   ├── blogAPI.php          # Blog API
│       │   └── ...
│       │
│       ├── controllers/     # Education controllers
│       │   ├── Controller.php        # Base (extends pool)
│       │   ├── QuestionController.php
│       │   ├── BlogController.php
│       │   ├── NoteController.php
│       │   └── ...
│       │
│       ├── config/          # Education config
│       │   └── db_config.php
│       │
│       └── ui/              # User interface
│           ├── views/       # View templates
│           │   ├── learn/   # Learning module
│           │   ├── blogs/  # Blog module
│           │   ├── notes/  # Notes module
│           │   └── ...
│           │
│           ├── layouts/     # Layout templates
│           └── assets/     # Static assets (CSS, JS, images)
│
└── storage/                 # File storage
```

---

## 🔧 Core Components

### 1. **Router System** (`Router.php`)

**Purpose**: Custom routing implementation for clean URLs

**Features:**
- Auto-detects base path (works in subdirectories)
- Route registration with callbacks
- 404 handling
- Path normalization

**Usage Pattern:**
```php
$router->route('/path', function() {
    require 'file.php';
});
```

### 2. **Database Layer**

#### **Configuration** (`pool/config/db_config.php`)
- Multi-environment support (localhost, production, testing)
- Host-based database selection
- Both mysqli and PDO connections
- Environment detection via `checkHost()`

**Database Classes:**
- `config`: Database configuration and connection
- `Database`: Database abstraction (extends config)
- `Migration`: Database schema management

#### **Connection Methods:**
- `connectDb()`: Returns mysqli connection
- `connectDbPDO()`: Returns PDO connection

### 3. **Base Controller** (`pool/libs/controllers/Controller.php`)

**Inheritance Chain:**
```
Controller extends Database
    ├── Uses StripeController (trait)
    ├── Uses CrudController (trait)
    └── Uses Metadata (trait)
```

**Key Methods:**
- `run_query($sql)`: Execute SQL queries
- `run_query_pdo($sql, $params)`: PDO prepared statements
- `encrypt()/decrypt()`: Data encryption
- `alert_redirect()`: JavaScript redirects
- `handleFileUpload()`: File management

### 4. **Authentication System**

#### **LogsController** (`pool/libs/controllers/LogsController.php`)
**Responsibilities:**
- User registration (`store()`)
- User login (`login()`)
- Email verification (`verify()`, `email_verified()`)
- Password reset (`reset_password()`)
- OTP generation and validation
- Profile management

#### **Authentication Flow:**
```
1. Signup → Store user → Generate OTP → Send email
2. Verification → Validate OTP → Mark email verified
3. Login → Check credentials → Check email verified → Create session
4. Session → Store user data → Redirect to default app
```

#### **Session Variables:**
- `$_SESSION['auth']`: User ID
- `$_SESSION['user_id']`: User ID
- `$_SESSION['email']`: User email
- `$_SESSION['default_app']`: Default application (1=edu, 2=work, 3=personal)
- `$_SESSION['log']`: Login status (1=logged in, 0=logged out)

---

## 🗄️ Database Architecture

### **Main Tables**

#### **users** (Primary User Table)
```sql
- id (PRIMARY KEY)
- email, password
- first_name, last_name
- phone, dob, country, state, city
- role, sub_role
- email_verified (INT)
- otp (for verification)
- default_app (1=edu, 2=work, 3=personal)
- profile_status, profile_image
- selected_topics (TEXT)
- subscription_id
- business_type, user_type
- created, modified, last_login
- referral_code
```

#### **Other Key Tables:**
- `files`: File storage metadata
- `storage`: User storage/content
- `notes`: User notes
- `blogs`: Blog posts
- `blogs_query`: Blog search queries
- `financial_profile`: Stripe payment profiles
- `notifications`: User notifications
- `website_traffic`: Analytics

### **Database Connection Strategy**

**Multi-Environment Support:**
```php
Host Detection → checkHost() → Returns host number (0-4)
    ↓
Host Number → Maps to database config
    ↓
Connection → mysqli or PDO
```

**Environment Mapping:**
- `1`: Local development (localhost)
- `2`: Local development (alternative)
- `3`: Production (wheelder.com)
- `4`: Testing server
- `0`: Default (falls back to localhost for local dev)

---

## 🔐 Authentication & Authorization

### **Registration Process:**
1. User submits signup form → `/signup`
2. Form POSTs to `/log_api` with `signup` parameter
3. `LogsController->check_user()`: Check if email exists
4. `LogsController->store()`: Insert new user
5. `LogsController->verify()`: Generate OTP, send email
6. Redirect to `/verification`

### **Login Process:**
1. User submits login form → `/login`
2. Form POSTs to `/log_api` with `login` parameter
3. `LogsController->login()`: Validate credentials
4. `LogsController->email_verification_checkup()`: Check email verified
5. Create session variables
6. Redirect based on `default_app`:
   - `1` → `/edu` (Education)
   - `2` → `/work` (Work)
   - `3` → `/personal` (Personal)

### **Email Verification:**
- OTP-based verification
- 6-digit random OTP
- Stored in `users.otp`
- Email sent via PHP `mail()`
- Verification page: `/verification`

### **Password Reset:**
- Forgot password → Generate OTP → Email OTP
- Reset password → Validate OTP → Update password

---

## 🛣️ Routing System

### **Route Definition** (`index.php`)

**Route Categories:**

#### **Authentication Routes:**
- `/login` → `pool/auth/login.php`
- `/signup` → `pool/auth/signup.php`
- `/logout` → `pool/auth/logout.php`
- `/forgot_pass` → `pool/auth/forgot_password.php`
- `/reset_pass` → `pool/auth/reset_pass.php`
- `/verification` → `pool/auth/verification.php`

#### **API Routes:**
- `/log_api` → `pool/api/logsAPI.php` (Auth API)
- `/edu_note` → `apps/edu/api/noteAPI.php`
- `/edu_blog` → `apps/edu/api/blogAPI.php`
- `/edu_search_api` → `apps/edu/api/open_ai.php`

#### **Application Routes:**
- `/learn` → `apps/edu/ui/views/learn/app_main.php`
- `/blog` → `apps/edu/ui/views/blogs/app.php`
- `/profile` → Profile page
- `/profile_setup` → Profile configuration

#### **Admin/Setup Routes:**
- `/setup` → Database setup
- `/edu_db_setup` → Education DB setup
- `/backup` → Database backup
- `/dev` → Development tools

---

## 📱 Application Modules

### **Education App** (`apps/edu/`)

**Purpose**: AI-powered learning platform

**Features:**
- AI question answering (OpenAI GPT)
- Image generation (DALL-E integration)
- Note-taking system
- Blog/CMS system
- Learning progress tracking

**Key Controllers:**
- `QuestionController`: Handle learning questions
- `BlogController`: Blog management
- `NoteController`: Note management
- `HomeController`: Dashboard

**Key Views:**
- `/learn`: Main learning interface
- `/blog`: Blog listing
- `/blog/cms`: Blog management
- `/notes`: Notes interface

**AI Integration:**
- OpenAI API (`open_ai.php`)
- GPT-3.5-turbo-16k model
- DALL-E for image generation
- Custom prompts for educational context

---

## 🔌 API Structure

### **API Endpoint Pattern:**
```
POST /log_api?action=login
POST /edu_note?action=create
GET /edu_blog?action=list
```

### **Main APIs:**

#### **logsAPI.php** (Authentication)
- `POST login`: User login
- `POST signup`: User registration
- `POST verify`: OTP verification
- `POST forgot_password`: Password reset request
- `POST reset_pass`: Password reset
- `POST complete_profile`: Profile completion

#### **noteAPI.php** (Notes)
- Create, read, update, delete notes
- Note search and filtering

#### **blogAPI.php** (Blogs)
- Blog CRUD operations
- Blog publishing
- Blog search

#### **open_ai.php** (AI Integration)
- `POST`: Send user question
- Returns: AI response + generated image

### **API Response Format:**
- JSON responses
- Error handling
- CORS headers (where needed)

---

## 📦 Dependencies & Technologies

### **Composer Packages** (`pool/libs/composer/composer.json`)

```json
{
  "stripe/stripe-php": "^10.5",      // Payment processing
  "bramus/router": "~1.6",           // Alternative router
  "klein/klein": "^2.1",             // Lightweight router
  "smarty/smarty": "^4.3",           // Template engine
  "react/react": "1.3",              // ReactPHP
  "react/http": "^1.9",              // HTTP server
  "react/socket": "^1.12",           // WebSocket support
  "react/event-loop": "^1.4"         // Event loop
}
```

### **Frontend Technologies:**
- **Bootstrap 5**: UI framework
- **Font Awesome**: Icons
- **jQuery**: JavaScript library (implied)
- **Vanilla JavaScript**: Custom scripts

### **Backend Technologies:**
- **PHP 7.4+**: Server-side language
- **MySQL/MariaDB**: Database
- **mysqli/PDO**: Database drivers
- **cURL**: HTTP requests (OpenAI API)

### **External Services:**
- **OpenAI API**: AI responses and image generation
- **Stripe**: Payment processing
- **Email (PHP mail())**: Email notifications

---

## 🔒 Security Considerations

### **Current Security Measures:**
✅ Session management
✅ CSRF token generation (in Learn module)
✅ Email verification (OTP)
✅ Password reset flow

### **Security Concerns:**
⚠️ **SQL Injection Vulnerabilities:**
- Many queries use string concatenation instead of prepared statements
- Example: `"SELECT * FROM users WHERE email='$email'"`
- **Recommendation**: Migrate to prepared statements

⚠️ **Password Storage:**
- Passwords stored in plain text (visible in `login()` method)
- **Recommendation**: Use password hashing (password_hash/password_verify)

⚠️ **API Key Exposure:**
- OpenAI API key hardcoded in `open_ai.php`
- Stripe keys in `StripeController.php`
- **Recommendation**: Move to environment variables

⚠️ **XSS Protection:**
- Some outputs may not be escaped
- **Recommendation**: Use `htmlspecialchars()` consistently

⚠️ **Session Security:**
- No session regeneration on login
- **Recommendation**: Implement session fixation protection

---

## 🔄 Data Flow

### **User Registration Flow:**
```
User → /signup (View)
    → POST /log_api (signup)
    → LogsController->check_user()
    → LogsController->store()
    → LogsController->verify() → Generate OTP
    → Send Email
    → Redirect /verification
    → User enters OTP
    → POST /log_api (verify)
    → LogsController->email_verified()
    → Redirect /login
```

### **User Login Flow:**
```
User → /login (View)
    → POST /log_api (login)
    → LogsController->login()
    → LogsController->email_verification_checkup()
    → Create Session
    → Redirect based on default_app
        → /learn (if default_app = 1)
        → /work (if default_app = 2)
        → /personal (if default_app = 3)
```

### **AI Question Flow:**
```
User → /learn (View)
    → Enter question
    → POST /edu_search_api (open_ai.php)
    → OpenAI API call
    → Generate image (DALL-E)
    → Return JSON response
    → Display in UI
```

### **Database Query Flow:**
```
Controller Method
    → run_query($sql)
    → connectDb()
    → checkHost()
    → Get database config
    → mysqli connection
    → Execute query
    → Return result
```

---

## 🎨 UI/UX Architecture

### **View Structure:**
- **Layouts**: Header, footer, navigation (`pool/auth/`, `apps/edu/ui/layouts/`)
- **Views**: Page-specific templates (`apps/edu/ui/views/`)
- **Assets**: CSS, JS, images (`apps/edu/ui/assets/`)

### **Styling:**
- Bootstrap 5 for responsive design
- Custom CSS files
- Font Awesome for icons
- Responsive breakpoints

### **JavaScript:**
- Inline scripts in views
- External JS files (`app.js`)
- AJAX for API calls
- Form validation

---

## 📊 Key Design Patterns

### **1. Inheritance Pattern:**
```
Database (base)
    ↓
Controller (extends Database)
    ↓
LogsController, QuestionController, etc. (extend Controller)
```

### **2. Trait Pattern:**
- `StripeController`: Payment functionality
- `CrudController`: CRUD operations
- `Metadata`: Metadata handling

### **3. Factory Pattern:**
- Database connection factory (based on host)
- Router factory (auto-detects base path)

### **4. Singleton-like Pattern:**
- Database connections (reused)
- Session management

---

## 🚀 Deployment Considerations

### **Environment Configuration:**
- Database config changes based on `HTTP_HOST`
- Local development: `localhost` → local DB
- Production: `wheelder.com` → production DB

### **File Structure:**
- Works in subdirectories (base path detection)
- Relative paths using `$_SERVER['DOCUMENT_ROOT']`

### **Dependencies:**
- Composer autoloader required
- PHP extensions: mysqli, PDO, cURL, OpenSSL

---

## 📝 Development Notes

### **Code Organization:**
- ✅ Modular structure (apps separated)
- ✅ Shared core (pool/)
- ✅ Clear separation of concerns
- ⚠️ Some code duplication (multiple db_config.php files)
- ⚠️ Inconsistent naming conventions

### **Best Practices:**
- ✅ OOP structure
- ✅ Separation of views and logic
- ⚠️ Need for prepared statements
- ⚠️ Need for password hashing
- ⚠️ Need for environment variables

### **Testing:**
- Database setup scripts available
- Backup functionality included
- Development tools in `/dev`

---

## 🔮 Future Improvements

1. **Security:**
   - Implement prepared statements everywhere
   - Add password hashing
   - Move API keys to environment variables
   - Add rate limiting
   - Implement CSRF protection globally

2. **Code Quality:**
   - Reduce code duplication
   - Standardize naming conventions
   - Add PHPDoc comments
   - Implement error logging

3. **Architecture:**
   - Consider framework adoption (Laravel, Symfony)
   - Implement proper MVC separation
   - Add service layer abstraction
   - Implement dependency injection

4. **Features:**
   - Add unit tests
   - Implement API versioning
   - Add caching layer
   - Implement queue system for emails

---

## 📚 Summary

**Wheelder** is a **modular PHP platform** with:
- ✅ Multi-application architecture
- ✅ Custom routing system
- ✅ Shared authentication
- ✅ Database abstraction layer
- ✅ Payment integration (Stripe)
- ✅ AI-powered features (OpenAI)
- ⚠️ Security improvements needed
- ⚠️ Code quality improvements needed

The project demonstrates a **functional multi-tenant platform** with room for security and code quality enhancements.

---

*Analysis Date: 2024*
*Project: Wheelder*
*Version: Prototype*

