# Grocery_Delivery
A simple Grocery Delivery System built with PHP and MySQL for my class demo.

**Author:** Spencer Dant  
**Project:** Grocery Delivery System  
**Demo URL:** http://localhost/grocery-system/index.php  

---

## **Description**

This is a simple grocery delivery web application built with PHP and MySQL. Users can:

- Browse products with price and stock information
- Add products to a cart
- Update quantities or remove items from the cart
- Checkout and automatically reduce stock
- See messages confirming actions (e.g., item added, stock limit)

The system automatically manages cart quantities and prevents users from adding more items than are available in stock.

---

## **System Requirements**

- XAMPP (Apache + MySQL) installed
- PHP 8.x
- Web browser
- MySQL database

---

## **Setup Instructions**

1. **Clone or copy project folder** to your XAMPP `htdocs` directory:  
C:\xampp\htdocs\grocery-system

2. **Start XAMPP** and ensure **Apache** and **MySQL** are running.

3. **Create the database**:

- Open **phpMyAdmin**: `http://localhost/phpmyadmin/`
- Run the SQL script `database.sql` included in the project. This will create the `grocery_db` database and the `products` table with sample data.

4. **Configure database connection**:

- Open `includes/db_connect.php`  
- Update username/password if needed (default XAMPP MySQL: username=`root`, password=`""`)

5. **Open the application** in your browser:
http://localhost/grocery-system/index.php

---

## **File Structure**
grocery-system/
│
├─ index.php # Main product listing
├─ product_detail.php # Product detail & add to cart
├─ cart.php # View and update cart
├─ add_to_cart.php # Add items to cart
├─ checkout.php # Checkout process
├─ clear_cart.php # Empty cart
├─ includes/
│ └─ db_connect.php # Database connection
├─ assets/
│ ├─ style.css # Styles
│ └─ images/ # Product images
└─ database.sql # Database setup script
