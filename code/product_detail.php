<?php
session_start();
include 'includes/db_connect.php';

// Get product ID from URL and validate
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    // Invalid ID, redirect to home
    header("Location: index.php");
    exit;
}

// Fetch product from database
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    // Product not found, redirect to home
    header("Location: index.php");
    exit;
}

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qty = intval($_POST['quantity']);

    // Limit quantity by available stock
    if ($qty > $product['stock']) {
        $qty = $product['stock'];
        $_SESSION['message'] = "Only $qty item(s) available in stock. Added to cart.";
    } else {
        $_SESSION['message'] = "$qty item(s) added to your cart!";
    }

    if ($qty > 0) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $qty;

            // Ensure cart quantity does not exceed stock
            if ($_SESSION['cart'][$product_id] > $product['stock']) {
                $_SESSION['cart'][$product_id] = $product['stock'];
            }
        } else {
            $_SESSION['cart'][$product_id] = $qty;
        }
    }

    // Redirect once back to home to prevent form resubmission
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1><?= htmlspecialchars($product['name']) ?></h1>
    <img src="assets/images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    <p>Price: $<?= number_format($product['price'], 2) ?></p>
    <p>In Stock: <?= $product['stock'] ?></p>

    <form method="post">
        <label>Quantity: <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>"></label>
        <button type="submit">Add to Cart</button>
    </form>

    <a href="index.php">Back to Products</a>
</body>
</html>
