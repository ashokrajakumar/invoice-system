# Invoice & Budget Management System

## Project Overview

The Invoice & Budget Management System is a web application developed using Laravel and MySQL. It helps manage customers, suppliers, products, invoices, budgets, expenses, and payments in one place.

## Technologies Used

- PHP
- Laravel
- MySQL
- HTML
- CSS
- Bootstrap
- JavaScript

## Features

- Dashboard with business summaries
- Customer management
- Supplier management
- Product management
- Sales invoice management
- Purchase invoice management
- Budget management
- Expense tracking
- Payment management
- Reports with date filters

## Requirements

Install the following software before setting up the project:

- PHP (a version compatible with the Laravel version used)
- Composer
- MySQL
- XAMPP
- Git (optional, for cloning the repository)

## Installation and Setup

### 1. Clone the Repository

```bash
git clone https://github.com/ashokrajakumar/invoice-system.git
```

Navigate to the project directory:

```bash
cd invoice-system
```

Alternatively, download the project from GitHub and extract it.

### 2. Install Dependencies

Run:

```bash
composer install
```

### 3. Configure the Environment

Copy `.env.example` to `.env`.

On Windows Command Prompt:

```bash
copy .env.example .env
```

Update the database configuration in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=invoice_system
DB_USERNAME=root
DB_PASSWORD=
```

Update the database name and credentials according to your local MySQL configuration.

### 4. Create the Database

1. Start Apache and MySQL from XAMPP.
2. Open phpMyAdmin.
3. Create a database named `invoice_system`, or use the database name configured in `.env`.

### 5. Generate the Application Key

```bash
php artisan key:generate
```

### 6. Run Database Migrations

```bash
php artisan migrate
```

This creates the required database tables.

### 7. Clear the Application Cache

```bash
php artisan optimize:clear
```

### 8. Start the Development Server

```bash
php artisan serve
```

Open the application in your browser:

http://127.0.0.1:8000

## Database Configuration

The application uses MySQL to store customer, supplier, product, invoice, budget, expense, and payment information.

Configure the database connection in the `.env` file before running migrations.

## Project Structure

```text
invoice-system/
├── app/
│   ├── Http/Controllers/
│   └── Models/
├── database/
│   └── migrations/
├── resources/
│   └── views/
├── routes/
│   └── web.php
├── public/
├── .env.example
├── composer.json
└── README.md
```

## Notes

- Do not upload the `.env` file or database credentials to GitHub.
- Keep `.env.example` updated with the required configuration keys, without real passwords or secrets.
- Run migrations before using the application.
- If the project requires sample records, document how to create them.

## Submission

GitHub Repository: https://github.com/ashokrajakumar/invoice-system.git