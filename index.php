<!DOCTYPE html>
<html>
<head>
    <title>Naprose Fashion - Home</title>
    <style>
        body { 
            font-family: Arial; 
            text-align: center; 
            padding: 50px; 
            background: #f5f5f5;
        }
        .welcome {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        .btn {
            background: #e8c4b8;
            color: #2c2c2c;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 10px;
        }
        .cart-link {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #e8c4b8;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            color: #2c2c2c;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    $cart_count = 0;
    if (isset($_SESSION['cart'])) {
        $cart_count = array_sum($_SESSION['cart']);
    }
    if ($cart_count > 0) {
        echo "<a href='cart.php' class='cart-link'>🛒 Cart ($cart_count)</a>";
    }
    ?>
    
    <div class="welcome">
        <h1>🛍️ Welcome to Naprose Fashion!</h1>
        <p>Your premium fashion destination</p>
        
        <div style="margin: 30px 0;">
            <a href="products.php" class="btn">View Products</a>
            <a href="cart.php" class="btn">View Cart</a>
            <a href="setup.php" class="btn">Setup Database</a>
        </div>
    </div>
</body>
</html>