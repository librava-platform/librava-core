# Librava Core

Backend API and web interface for Librava - A digital library management system.

## Overview

Librava Core is a RESTful API backend built with PHP and MySQL. It provides endpoints for managing digital books, user authentication, and file uploads. The system supports both web interface access and mobile app integration.

## Features

- **RESTful API**: Clean API design for CRUD operations
- **Authentication**: Token-based authentication system
- **File Management**: Handle book covers and PDF uploads
- **Database**: MySQL database with optimized queries
- **Security**: Input validation, SQL injection prevention, CSRF protection
- **CORS Support**: Cross-origin resource sharing for mobile apps
- **Multi-format**: Supports images (JPEG, PNG, WEBP) and PDF files

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- mod_rewrite enabled
- 50MB+ file upload support (for PDFs)

## Installation

### Quick Setup

1. Clone the repository:
```bash
git clone https://github.com/librava-platform/librava-core.git
cd librava-core
```

2. Configure your web server to point to the project directory

3. Create a MySQL database:
```bash
mysql -u root -p
CREATE DATABASE librava CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

4. Import the database schema:
```bash
mysql -u root -p librava < install.sql
```

5. Configure database connection:
```php
// includes/config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'librava');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('BASE_URL', 'https://yourdomain.com/');
```

6. Set permissions:
```bash
chmod 755 uploads/
chmod 755 uploads/covers/
chmod 755 uploads/pdfs/
```

7. Access your installation at `https://yourdomain.com/`

## Configuration

### Database Setup

The `install.sql` file creates two tables:
- `admins`: User authentication
- `books`: Book information and file paths

Default admin credentials:
- Username: `admin`
- Password: `admin123`

**⚠️ Change these credentials immediately after installation!**

### File Upload Limits

Edit `includes/config.php`:
```php
define('MAX_COVER_SIZE', 5 * 1024 * 1024); // 5MB
define('MAX_PDF_SIZE', 50 * 1024 * 1024); // 50MB
```

Also update `php.ini`:
```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
```

### Base URL

Set your domain in `includes/config.php`:
```php
define('BASE_URL', 'https://yourdomain.com/');
```

## API Documentation

### Authentication

#### Login
```http
POST /api/index.php?action=login
Content-Type: application/json

{
  "username": "admin",
  "password": "admin123"
}
```

Response:
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "id": 1,
    "username": "admin",
    "token": "generated_token_here"
  }
}
```

### Books

#### Get All Books
```http
GET /api/index.php?action=books
```

#### Get Single Book
```http
GET /api/index.php?action=books&id=1
```

#### Create Book
```http
POST /api/index.php?action=books
Authorization: Bearer {token}
Content-Type: multipart/form-data

title: "Book Title"
author: "Author Name"
description: "Book description"
category: "Category"
cover: [file]
pdf: [file]
```

#### Update Book
```http
POST /api/index.php?action=books&id=1
Authorization: Bearer {token}
Content-Type: multipart/form-data

_method: "PUT"
title: "Updated Title"
...
```

#### Delete Book
```http
POST /api/index.php?action=books&id=1
Authorization: Bearer {token}
Content-Type: application/x-www-form-urlencoded

_method=DELETE
```

## Project Structure

```
librava-core/
├── api/
│   ├── index.php       # API router
│   ├── auth.php        # Authentication handlers
│   └── books.php       # Book CRUD handlers
├── includes/
│   ├── config.php      # Configuration
│   ├── db.php          # Database connection
│   └── helpers.php     # Helper functions
├── uploads/
│   ├── covers/         # Book cover images
│   └── pdfs/           # PDF files
├── views/
│   └── layout/         # HTML templates
├── .htaccess           # Apache configuration
├── index.php           # Web interface
└── install.sql         # Database schema
```

## Security Features

- Token-based authentication with expiration
- SQL injection prevention (prepared statements)
- XSS protection (input sanitization)
- CSRF protection
- File type validation
- File size limits
- Secure file uploads
- Password hashing

## Development

### Testing

Test the API using the included test file:
```bash
# Open in browser
http://yourdomain.com/api_test.html
```

Or use curl:
```bash
# Get books
curl http://yourdomain.com/api/index.php?action=books

# Login
curl -X POST http://yourdomain.com/api/index.php?action=login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"admin123"}'
```

### Database Migrations

If you need to modify the database structure, create a backup first:
```bash
mysqldump -u root -p librava > backup.sql
```

## Deployment

### Production Checklist

- [ ] Change default admin password
- [ ] Update BASE_URL in config.php
- [ ] Set proper database credentials
- [ ] Configure SSL certificate
- [ ] Set secure file permissions (755 for directories, 644 for files)
- [ ] Disable error reporting in config.php
- [ ] Enable HTTPS-only mode
- [ ] Set up automated backups
- [ ] Configure firewall rules

### Hosting Requirements

- PHP 7.4+
- MySQL 5.7+
- 1GB RAM minimum
- 10GB storage (depending on library size)
- SSL certificate
- Backup system

## Mobile App Integration

This API is designed to work with the [Librava Mobile](https://github.com/librava-platform/librava-mobile) Android application.

Configure the mobile app to use your API URL in the Constants.java file.

## Troubleshooting

### 404 Errors
Enable mod_rewrite in Apache and ensure .htaccess is working.

### File Upload Errors
Check PHP upload limits and directory permissions.

### Database Connection Issues
Verify credentials in config.php and ensure MySQL is running.

### CORS Errors
CORS headers are configured in config.php. Adjust if needed.

## Contributing

This is a university project. Contributions for educational purposes are welcome.

## License

This project is part of an academic assignment at Shahid Chamran University of Kerman.

## Author

**Mohammad Taha Abdinasab**  
Computer Software Engineering Student  
Shahid Chamran University of Kerman

## Acknowledgments

- PHP community
- MySQL documentation
- Stack Overflow contributors

## Support

For issues and feature requests, use the [Issues](https://github.com/librava-platform/librava-core/issues) section.

---

**Note**: This is a student project created for educational purposes. Not recommended for production use without additional security hardening.
