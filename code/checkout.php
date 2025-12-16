<?php
session_start();
include 'includes/db_connect.php';

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_SESSION['cart'])) {
        // Reduce stock in database
        foreach ($_SESSION['cart'] as $id => $qty) {
            $id = intval($id);
            $qty = intval($qty);
            mysqli_query($conn, "UPDATE products SET stock = GREATEST(stock - $qty, 0) WHERE id = $id");
        }
        unset($_SESSION['cart']); // Clear cart
        $success = true;
    } else {
        $_SESSION['message'] = "Your cart is empty!";
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>Checkout</h1>

    <?php if ($success): ?>
        <p>Order successfully placed!</p>
        <a href="index.php">Back to Home</a>
    <?php else: ?>
        <form method="post">
            <label>Name: <input type="text" name="name" required></label><br>
            <label>Address: <input type="text" name="address" required></label><br>
            <label>City: <input type="text" name="city" required></label><br>
            <label>State: <input type="text" name="state" required></label><br>
            <label>Zip: <input type="text" name="zip" required></label><br>
            <label>Phone: <input type="text" name="phone" required></label><br>
            <button type="submit">Confirm Order</button>
        </form>
    <?php endif; ?>
</body>
</html>
