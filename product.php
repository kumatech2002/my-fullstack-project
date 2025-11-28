<?php
session_start(); // Add this line at the top

// Database connection
$host = "localhost";
$user = "root"; 
$pass = "";
$dbname = "naprose_fashionn";

$conn = new mysqli ($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'] ?? 1;
    
    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Add item to cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    
    echo "<script>alert('Product added to cart!');</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Products - Naprose Fashion</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .product { 
            background: white; 
            padding: 20px; 
            margin: 15px 0; 
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn {
            background: #e8c4b8;
            color: #2c2c2c;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        .cart-info {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .quantity-input {
            width: 60px;
            padding: 5px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="cart-info">
        <h1>📦 Our Products</h1>
        <div>
            <a href="cart.php" class="btn">🛒 View Cart 
                <?php 
                $cart_count = 0;
                if (isset($_SESSION['cart'])) {
                    $cart_count = array_sum($_SESSION['cart']);
                }
                if ($cart_count > 0) echo "($cart_count)";
                ?>
            </a>
            <a href="index.php" class="btn">← Home</a>
        </div>
    </div>
    
    <?php
    // Get products from database
    $result = $conn->query("SELECT * FROM products");
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "
            <div class='product'>
                <h3>{$row['name']}</h3>
                <p><strong>Price:</strong> \${$row['price']}</p>
                <p>{$row['description']}</p>
                <form method='POST' style='display: inline;'>
                    <input type='hidden' name='product_id' value='{$row['id']}'>
                    <input type='number' name='quantity' value='1' min='1' class='quantity-input'>
                    <button type='submit' name='add_to_cart' class='btn'>Add to Cart</button>
                </form>
            </div>";
        }
    } else {
        echo "<p>No products found. <a href='setup.php'>Run setup first</a></p>";
    }
    
    $conn->close();
    ?>
</body>
</html>
