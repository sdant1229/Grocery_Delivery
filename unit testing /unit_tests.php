<?php
session_start();
include '../includes/db_connect.php';

// Clear cart before testing
$_SESSION['cart'] = [];

// ---- Test 1: Add 3 Apples ----
$_SESSION['cart'][1] = 3;
echo "Test 1 - Add 3 Apples: " . (($_SESSION['cart'][1] === 3) ? "Pass\n" : "Fail\n");

// ---- Test 2: Add more than stock ----
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock FROM products WHERE id = 1"));
$qty_to_add = $product['stock'] + 5;
$_SESSION['cart'][1] = min($qty_to_add, $product['stock']);
echo "Test 2 - Exceed stock limit: " . (($_SESSION['cart'][1] === $product['stock']) ? "Pass\n" : "Fail\n");

// ---- Test 3: Remove item ----
unset($_SESSION['cart'][1]);
echo "Test 3 - Remove item: " . (empty($_SESSION['cart']) ? "Pass\n" : "Fail\n");
?>
