-- Database
CREATE DATABASE IF NOT EXISTS grocery_system;
USE grocery_system;

-- Products table
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  stock INT NOT NULL,
  image VARCHAR(100)
);

-- Sample products
INSERT INTO products (name, description, price, stock, image) VALUES
('Apples', 'Fresh Red Apples', 2.99, 50, 'apple.jpg'),
('Bananas', 'Yellow ripe bananas', 1.20, 40, 'banana.jpg'),
('Milk', '1L Fresh Milk', 1.49, 30, 'milk.jpg'),
('Bread', 'Whole Wheat Bread', 3.50, 20, 'bread.jpg'),
('Eggs', 'Dozen farm-fresh eggs', 2.99, 25, 'eggs.jpg'),
('Cheese', 'Cheddar Cheese 200g', 4.50, 15, 'cheese.jpg'),
('Orange Juice', '1L fresh orange juice', 3.99, 20, 'orange_juice.jpg'),
('Tomatoes', 'Fresh red tomatoes 1kg', 2.50, 35, 'tomatoes.jpg'),
('Chicken Breast', 'Boneless chicken breast 1kg', 7.99, 10, 'chicken.jpg'),
('Cereal', 'Breakfast cereal 500g', 4.99, 20, 'cereal.jpg');

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status VARCHAR(20) NOT NULL,
    created_at DATETIME NOT NULL
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
