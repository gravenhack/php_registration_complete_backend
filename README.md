# php_registration_complete_backend
PHP user registration form with email validation and secure password handling

# Simple PHP Registration Form

This project is a PHP user registration form with server-side validation.  
It allows users to register with a name, email, and secure password, while ensuring the email is unique.

---

## Features

- Name validation (minimum 3 characters, only letters and some special characters)
- Password validation:
  - Minimum 8 characters
  - At least one uppercase letter
  - At least one lowercase letter
  - At least one number
  - At least one special character
- Email validation
- Confirm password matches the original password
- Check that email is not already registered
- Secure password storage using `password_hash`

---

## Requirements

- PHP 7.x or higher
- MySQL / MariaDB
- Web server (Apache, Nginx, etc.)

---
CREATE DATABASE yourdbname;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL
);
Usage

Fill in the form with your name, email, password, and password confirmation.

Click "Register".

If all fields are valid, the user will be added to the database.

Error messages will be displayed if:

Name is invalid

Email is invalid

Password is not secure

Email is already registered

Password and confirmation do not match

Author: DOHA Cadnel Toussaint / Gravenhack


