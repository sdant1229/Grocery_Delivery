<?php
session_start();
include 'includes/db_connect.php'; // Database connection

// Sanitize input
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);

// Validate quantity
if ($quantity <= 0) {
    $_SESSION['message'] = "Invalid quantity.";
    header('Location: index.php');
    exit;
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get current stock for the product
$stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    $_SESSION['message'] = "Product not found.";
    header('Location: index.php');
    exit;
}

$available_stock = $result['stock'];

// Current quantity in cart
$current_in_cart = $_SESSION['cart'][$product_id] ?? 0;

// Calculate allowed quantity
$allowed_quantity = min($quantity, $available_stock - $current_in_cart);

if ($allowed_quantity <= 0) {
    $_SESSION['message'] = "No more stock available for this item.";
} else {
    $_SESSION['cart'][$product_id] = $current_in_cart + $allowed_quantity;

    if ($allowed_quantity < $quantity) {
        $_SESSION['message'] =
            "Only $allowed_quantity item(s) added due to limited stock.";
    } else {
        $_SESSION['message'] =
            "$allowed_quantity item(s) added to your cart!";
    }
}

// Redirect back to index
header('Location: index.php');
exit;
?>
