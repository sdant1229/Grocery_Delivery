<?php
session_start();
include '../includes/db_connect.php';

// Clear cart
$_SESSION['cart'] = [];

// Step 1: Add items to cart
$_SESSION['cart'][1] = 2; // Apples
$_SESSION['cart'][2] = 1; // Milk

// Step 2: Perform checkout
foreach ($_SESSION['cart'] as $id => $qty) {
    mysqli_query($conn, "UPDATE products SET stock = GREATEST(stock - $qty, 0) WHERE id = $id");
}
unset($_SESSION['cart']);

// Step 3: Verify stock
$apple_stock = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock FROM products WHERE id = 1"))['stock'];
$milk_stock = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock FROM products WHERE id = 2"))['stock'];

echo "Integration Test - Stock after checkout:\n";
echo "Apples remaining: $apple_stock\n";
echo "Milk remaining: $milk_stock\n";
?>
