# Automobile Hub - College Project

A comprehensive PHP-based Automobile Management System for buying vehicles, parts, insurance, and booking services.

## Features

### User Roles
- **Customer**: Browse vehicles, purchase parts, buy insurance, book services
- **Admin**: Manage inventory, view sales, manage appointments

### Core Features
1. **Vehicle Management**
   - Browse available vehicles
   - Featured vehicles showcase
   - Purchase vehicles with multiple payment options

2. **Parts Store**
   - Browse vehicle spare parts
   - Check compatibility
   - Purchase parts with stock management

3. **Services**
   - Repair services booking
   - Maintenance packages
   - EV charging station
   - Vehicle customization

4. **Insurance**
   - Multiple insurance providers
   - Comprehensive & Third Party coverage
   - Easy purchase process

5. **User Dashboard**
   - View purchase history
   - Track appointments
   - Download invoices

## Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 8.x
- **Database**: MySQL/MariaDB
- **Server**: XAMPP/WAMP (Apache)

## Installation

1. Install XAMPP or WAMP server
2. Start Apache and MySQL services
3. Create a database named `automobile_hub`
4. Import `automobile_hub.sql` to the database
5. Copy project files to `htdocs` (XAMPP) or `www` (WAMP)
6. Update database credentials in `db.php` if needed

## Default Credentials

### Admin
- Email: abc@gmail.com
- Password: 123456

### Customer
- Email: abc@gmail.com
- Password: pratik123

## Project Structure

```
Automobile_hub/
├── index.php          # Main landing page
├── login.php          # User authentication
├── register.php       # User registration
├── admin.php          # Admin dashboard
├── menu.php           # Customer dashboard
├── db.php             # Database connection
├── vehiclebuy.php     # Vehicle purchase
├── vehiclesell.php    # Vehicle listing
├── part.php           # Parts store
├── partsell.php       # Parts purchase
├── insurance.php      # Insurance listing
├── insurancesell.php  # Insurance purchase
├── repair.php         # Repair services
├── maintainance.php   # Maintenance services
├── customization.php  # Customization services
├── ev.php             # EV charging
├── styles.css         # Main stylesheet
├── script.js          # JavaScript
├── automobile_hub.sql # Database schema
└── media/             # Images and videos
```

## Database Tables

- `admin` - Admin users
- `customers` - Registered customers
- `vehicles` - Vehicle inventory
- `parts` - Spare parts inventory
- `services` - Available services
- `insurance` - Insurance plans
- `vehiclesales` - Vehicle purchase records
- `partsales` - Parts purchase records
- `insurancesale` - Insurance purchase records
- `appointments` - Service appointments

## Security Features

- Password hashing (PHP password_hash)
- SQL injection protection (Prepared statements)
- Input validation and sanitization
- Session-based authentication
- File upload validation

#
