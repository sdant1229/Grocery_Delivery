<?php
session_start();
include 'includes/db_connect.php';

// Remove item
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
    }
    header("Location: cart.php");
    exit;
}

// Update quantities
if (isset($_POST['update_cart'])) {
    $cart_valid = true;

    foreach ($_POST['quantities'] as $id => $qty) {
        $id = intval($id);
        $qty = intval($qty);

        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
            continue;
        }

        // Check stock before updating
        $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result && $qty > $result['stock']) {
            $_SESSION['cart'][$id] = $result['stock'];
            $cart_valid = false;
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }

    header("Location: cart.php");
    exit;
}

// Fetch cart products
$cart_products = [];
$total_price = 0;
$cart_valid = true;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    if ($ids) {
        $sql = "SELECT * FROM products WHERE id IN ($ids)";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $requested_qty = $_SESSION['cart'][$row['id']];
            $available_stock = $row['stock'];

            // Auto-correct quantity if it exceeds stock
            if ($requested_qty > $available_stock) {
                $_SESSION['cart'][$row['id']] = $available_stock;
                $requested_qty = $available_stock;
                $cart_valid = false;
            }

            $row['quantity'] = $requested_qty;
            $row['subtotal'] = $row['quantity'] * $row['price'];
            $total_price += $row['subtotal'];
            $cart_products[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>Your Cart</h1>

    <?php if (empty($cart_products)): ?>
        <p>Your cart is empty.</p>
        <a href="index.php">Continue Shopping</a>
    <?php else: ?>
        <?php if (!$cart_valid): ?>
            <p style="color:red;">
                Some quantities were adjusted due to limited stock. Please review before checkout.
            </p>
        <?php endif; ?>

        <form method="post">
            <table>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Stock</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($cart_products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td>
                            <input type="number" name="quantities[<?= $product['id'] ?>]" value="<?= $product['quantity'] ?>" min="1">
                        </td>
                        <td>
                            <?= $product['stock'] ?>
                            <?php if ($product['quantity'] >= $product['stock']): ?>
                                <span style="color:red;">(Max)</span>
                            <?php endif; ?>
                        </td>
                        <td>$<?= number_format($product['subtotal'], 2) ?></td>
                        <td>
                            <a href="cart.php?remove=<?= $product['id'] ?>">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <p>Total: $<?= number_format($total_price, 2) ?></p>
            <button type="submit" name="update_cart">Update Cart</button>
        </form>

        <?php if ($cart_valid): ?>
            <a href="checkout.php">Proceed to Checkout</a>
        <?php endif; ?>

        <a href="index.php">Continue Shopping</a>
    <?php endif; ?>
</body>
</html>
