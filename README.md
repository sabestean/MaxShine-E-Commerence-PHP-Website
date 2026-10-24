# MaxShine-E-Commerence-PHP-Website
# 🛒 CleanCart

CleanCart is a web-based shopping/cart management system built with **PHP and MySQL**. It includes separate functionality for administrators and users, with the application designed to run locally using **XAMPP**.

## 🚀 Features

- 👤 User registration and login
- 🔐 Dynamic user authentication
- 🛠️ Admin dashboard
- 📦 Product/cart management
- 🗄️ MySQL database integration
- 🖥️ PHP-based backend
- ⚡ Runs locally using XAMPP
- 📊 Admin and user-side interfaces

## 🔑 Login System

### Admin Login

The admin dashboard currently uses a **static login**:

| Field | Value |
|---|---|
| Email | `admin@gmail.com` |
| Password | `admin123` |

> **Security Note:** These credentials are included for local/demo purposes. For production use, replace the static credentials with secure authentication, hashed passwords, and proper role-based access control.

### User Login

User authentication is **dynamic**.

Users create their own account credentials through the user-side registration system. The username/email and password used for login depend on the account created by each user.

## 🗄️ Database

CleanCart uses **MySQL** as its database.

### Database Name

```text
cleancart
```

The database should be imported into **phpMyAdmin** before running the application.

## 💻 Requirements

Before running CleanCart, install:

- PHP
- MySQL
- phpMyAdmin
- A modern web browser

## ⚙️ Installation & Setup

### 1. Install XAMPP

Download and install XAMPP if it is not already installed.

Start the following services from the XAMPP Control Panel:

```text
Apache
MySQL
```

### 2. Copy the Project

Copy the CleanCart project folder into the XAMPP `htdocs` directory.

Example:

```text
C:\xampp\htdocs\cleancart
```

### 3. Create the Database

Open your browser and go to:

```text
http://localhost/phpmyadmin
```

Create a new database named:

```text
cleancart
```

### 4. Import the Database

In phpMyAdmin:

1. Select the `cleancart` database.
2. Click **Import**.
3. Select the project's `.sql` database file.
4. Click **Import** or **Go**.
5. Wait for the import to complete.

The database tables should now be available under the `cleancart` database.

## 🔌 Database Configuration

Make sure the PHP database connection is configured for your local XAMPP MySQL installation.

A typical local configuration is:

```php
$host = "localhost";
$username = "root";
$password = "";
$database = "cleancart";
```

If your project already contains a database connection file, update the database name there to:

```text
cleancart
```

Your configuration may be different if you have changed the default XAMPP MySQL settings.

## ▶️ Running the Project

After starting **Apache** and **MySQL**, open:

```text
http://localhost/cleancart/
```

If the project has a different entry file or folder structure, open the corresponding URL.

## 👨‍💼 Admin Dashboard

Use the following credentials to access the admin dashboard:

```text
Email: admin@gmail.com
Password: admin123
```

The admin account is currently static and is intended for the local/demo version of the project.

## 👤 User Side

Users can register through the user-side interface.

After registration, users can log in using the credentials they created.

Example:

```text
Email: user@example.com
Password: UserPassword
```

The actual user credentials depend on the accounts created in the application.

## 🔐 Security Considerations

This project is configured primarily for **local development/demo purposes**.

Before deploying CleanCart to a production server, consider implementing:

- Password hashing using PHP `password_hash()`
- Password verification using `password_verify()`
- Secure session management
- Prepared SQL statements
- Input validation and sanitization
- CSRF protection
- Secure admin authentication
- Environment variables for database credentials
- Removal of default/static admin credentials
- HTTPS

**Do not use the example admin password in a production environment.**

## 🛠️ Technologies Used

| Technology | Purpose |
|---|---|
| PHP | Backend/server-side development |
| MySQL | Database |
| phpMyAdmin | Database management |
| HTML | Page structure |
| CSS | Styling |
| JavaScript | Client-side functionality |
| XAMPP | Local development environment |

## 📁 Project Setup Summary

```text
1. Install XAMPP
        ↓
2. Start Apache + MySQL
        ↓
3. Copy CleanCart into C:\xampp\htdocs\
        ↓
4. Open phpMyAdmin
        ↓
5. Create database: cleancart
        ↓
6. Import the project's .sql file
        ↓
7. Check database connection
        ↓
8. Open http://localhost/cleancart/
        ↓
9. Login as Admin or register as User
```

## 📌 Important

CleanCart is currently configured for a local XAMPP environment.

The database name must be:

```text
cleancart
```

The current admin credentials are:

```text
admin@gmail.com
admin123
```

User credentials are created dynamically through the user registration system.

## 📄 License

This project is intended for educational, development, and demonstration purposes. Add an appropriate license if you plan to distribute or modify the project publicly.
