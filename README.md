# Pathirana Motors - Billing System

## Project Overview

The **Pathirana Motors Billing System** is a simple and secure web-based platform that allows administrators to manage billing operations. This system is built using **PHP**, **Tailwind CSS**, **JavaScript**, and **MongoDB**. It features user authentication and the ability to generate bills in PDF format, ensuring smooth business operations for Pathirana Motors.

### Key Features:
- **User Authentication**: Only authorized users can log in and access the system.
- **Responsive Design**: The application is designed to work seamlessly across devices using Tailwind CSS.
- **Modular Components**: The navbar and footer are separated into individual files for easy maintenance.
- **PDF Bill Generation**: Bills can be generated and downloaded as PDF files.
- **MongoDB Integration**: MongoDB is used as the database for storing billing records and user data.

## Tech Stack

- **Frontend**: 
  - HTML5
  - Tailwind CSS
  - JavaScript (Vanilla)
  
- **Backend**: 
  - PHP (without a framework)

- **Database**: 
  - MongoDB
  
- **PDF Generation**: 
  - TCPDF (or any other PHP PDF library)
  
- **Version Control**: 
  - Git
  
- **Deployment**: 
  - Can be deployed on any PHP-supported server or locally using XAMPP/LAMP.

## Project Structure

```bash
├── public/
│   ├── css/
│   │   └── tailwind.css         # Tailwind CSS file
│   ├── js/
│   │   └── main.js              # JavaScript file for interactivity
│   ├── img/
│   └── index.php                # Login page
│
├── app/
│   ├── views/
│   │   ├── navbar.php           # Navbar (included in all pages)
│   │   ├── footer.php           # Footer (included in all pages)
│   │   ├── dashboard.php        # Dashboard (after login)
│   │   └── billing.php          # Billing management page
│   ├── includes/
│   │   └── auth.php             # Authentication logic
│   └── config/
│       └── database.php         # MongoDB connection configuration
│
├── README.md                    # Project documentation
├── .env                         # Environment variables (MongoDB, etc.)
└── .gitignore                   # Git ignore file
