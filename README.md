# Custom PHP MVC Framework Starter Guide

Welcome to the **Custom PHP MVC Framework**! This is a lightweight, clean, and high-performance Object-Oriented PHP framework designed using the Model-View-Controller architecture. It includes custom routing, standard database query building with PDO, role-based access control, layout rendering components, and a custom template compilation engine.

---

## Table of Contents

1. [Overview](#overview)
2. [Prerequisites](#prerequisites)
3. [Step-by-Step Installation](#step-by-step-installation)
4. [Configuration Details](#configuration-details)
5. [Super Admin Credentials](#super-admin-credentials)
6. [Accessing Documentation](#accessing-documentation)

---

## Overview

This framework is built to simplify PHP web application development by implementing core features from scratch:

- **Core Router (`Core.php`):** Inspects the request URL and maps it to the matching controller, method, and parameters.
- **Base Controller (`Controller.php`):** Facilitates loading models and compiling views using a lightweight custom template compiler (similar to Blade/Laravel syntax).
- **PDO Database Wrapper (`Database.php` & `QueryBuilder.php`):** Provides secure, chainable PDO queries to prevent SQL injections.
- **SessionHelper (`SessionHelper.php`):** Manages user sessions, logins, and handles role-based authentication rules.
- **ComponentFiles Helper (`ComponentFiles.php`):** Simplifies layout inclusion for dashboard headers, footers, sidebars, and breadcrumbs.

---

## Prerequisites

Before setting up the project, ensure you have the following installed on your local machine:

- **PHP:** Version 8.0 or higher
- **Web Server:** Apache (with `mod_rewrite` enabled) or Nginx
- **Database:** MySQL or MariaDB
- **Dependency Manager:** Composer

---

## Step-by-Step Installation

### 1. Clone the Project

Start by cloning the repository to your local web server root (e.g., `C:/xampp/htdocs/` for XAMPP users or `/var/www/html/` for Linux users):

```bash
git clone <repository-url> mvc-php
```

### 2. Create the Database & Import SQL Schema

1. Open your database administration tool (such as **phpMyAdmin** or MySQL CLI).
2. Create a new database, for example, `mvc_db`.
3. Import the default starter schema and seed file located at:
   `[app/config/mvc.sql](file:///c:/xampp/htdocs/mvc-php/app/config/mvc.sql)`

   This SQL file creates two essential tables:
   - `users` — Stores user accounts, profile details, and role assignments.
   - `login_logs` — Tracks user login activities, devices, and browser specifications.

### 3. Setup Environment Variables

1. Duplicate the template file `.env.example` in the root folder of the project.
2. Rename the copy to `.env`.
3. Open `.env` and fill in your database credentials:

   ```ini
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=your_password
   DB_NAME=mvc_db

   APP_ENV=development
   ```

### 4. Install Composer Dependencies

Run the following command in the project root directory to load the autoloader and composer packages:

```bash
composer install
```

---

## Configuration Details

### 1. App Configuration (`config.php`)

Open `[app/config/config.php](file:///c:/xampp/htdocs/mvc-php/app/config/config.php)` and customize the constant definitions:

- **`URLROOT`**: Change this to match your local or production URL path.
  ```php
  define('URLROOT', 'http://localhost/mvc-php');
  ```
- **`SITENAME`**: Set the custom title for your application.
  ```php
  define('SITENAME', 'My Application Name');
  ```

### 2. Front-End URL configuration (`define.js`)

The application defines a global JavaScript configuration object in `[public/javascript/define.js](file:///c:/xampp/htdocs/mvc-php/public/javascript/define.js)`. It is automatically bound to `window.URLROOT` using the PHP constant, but it has a fallback logic in case it's not set:

```javascript
URLROOT: window.URLROOT ||
  window.location.origin +
    "/" +
    (window.location.pathname.split("/")[1] || "mvc-php");
```

Ensure the fallback directory (`'mvc-php'`) matches your folder name if your backend `URLROOT` is empty or unset.

### 3. Layout Theme Switcher (`app-unminify.js`)

If you change your folder name or deployment path, the theme switcher script `[public/theme/theme-1/Admin/dist/assets/js/app-unminify.js](file:///c:/xampp/htdocs/mvc-php/public/theme/theme-1/Admin/dist/assets/js/app-unminify.js)` will dynamically resolve resource links using:

```javascript
const URLROOT =
  window.URLROOT ||
  window.location.origin +
    "/" +
    (window.location.pathname.split("/")[1] || "public");
```

No manual editing is required as it automatically references `window.URLROOT`.

---

## Super Admin Credentials

Once the `mvc.sql` is imported, you can log in to the system using the following default super admin account:

- **Email/Username**: ``
- **Password**: ``
- **User Role**: Super Admin (Role ID: `1`)

---

## Accessing Documentation

The project includes a comprehensive built-in documentation portal with guides for all core modules, routing systems, query builders, layout helpers, and access controllers.

- **Documentation URL:** `http://localhost/mvc-php/docs/index.html` (or `http://localhost/mvc-php/public/docs/index.html` depending on your URL rewrites).
- **Interactive Guides Available:**
  - `core.html` — Router patterns and Front Controller dispatch lifecycle.
  - `controller.html` — View rendering and compilation engine.
  - `database.html` — Query bindings using the PDO database class.
  - `querybuilder.html` — Dynamic SQL queries with chainable methods.
  - `helpers.html` — Data sanitization, hashing, and utility helper libraries.
  - `menu-render.html` — Dynamic role-based navigation sidebar.
  - `component-files.html` — Dashboard layout assembling (Header/Footer loader).
  - `session-helper.html` — Controller guards and role permission checks.
