<?php
session_start();

// Clear the cart
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
    $_SESSION['message'] = "Cart cleared successfully!";
}

// Redirect back to home page
header("Location: index.php");
exit();
?>
