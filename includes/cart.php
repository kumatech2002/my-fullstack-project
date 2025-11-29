<?php
session_start();

// If cart doesn't exist in session, create empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}

// Load products
$products = json_decode(file_get_contents('products.json'), true);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2 class="page-title">Your Cart</h2>

<div class="cart-container">

<?php
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    foreach ($_SESSION['cart'] as $id => $qty) {
        $product = $products[$id];
        echo "
        <div class='cart-item'>
            <img src='{$product['image']}' />
            <h3>{$product['name']}</h3>
            <p>Quantity: $qty</p>
            <p>Price: $" . ($product['price'] * $qty) . "</p>
            <a href='cart.php?remove=$id'>Remove</a>
        </div>
        ";
    }
}
?>
</div>

</body>
</html>
