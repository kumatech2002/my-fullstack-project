 <?php
session_start();

// Database connection
$host = "localhost";
$user = "root"; 
$pass = "";
$dbname = "naprose_fashionn";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle quantity updates
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    if ($quantity <= 0) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Handle remove item
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
}

// Handle clear cart
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart - Naprose Fashion</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .cart-container { 
            background: white; 
            padding: 20px; 
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .cart-item { 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .btn {
            background: #e8c4b8;
            color: #2c2c2c;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 5px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-danger { background: #ff6b6b; color: white; }
        .btn-success { background: #4CAF50; color: white; }
        .quantity-input { width: 60px; padding: 5px; }
        .cart-total { 
            font-size: 1.2em; 
            font-weight: bold; 
            text-align: right; 
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }
        .empty-cart { text-align: center; padding: 40px; color: #666; }
    </style>
</head>
<body>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>🛒 Shopping Cart</h1>
        <div>
            <a href="products.php" class="btn">← Continue Shopping</a>
            <a href="index.php" class="btn">🏠 Home</a>
        </div>
    </div>
    
    <div class="cart-container">
        <?php
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            echo '
            <div class="empty-cart">
                <h2>Your cart is empty</h2>
                <p>Add some products to get started!</p>
                <a href="products.php" class="btn">Browse Products</a>
            </div>';
        } else {
            $total = 0;
            
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                // Get product details from database
                $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();
                
                if ($product) {
                    $subtotal = $product['price'] * $quantity;
                    $total += $subtotal;
                    
                    echo "
                    <div class='cart-item'>
                        <div style='flex: 2;'>
                            <h3>{$product['name']}</h3>
                            <p>\${$product['price']} each</p>
                        </div>
                        
                        <div style='flex: 1; text-align: center;'>
                            <form method='POST' style='display: inline;'>
                                <input type='hidden' name='product_id' value='{$product_id}'>
                                <input type='number' name='quantity' value='{$quantity}' min='0' class='quantity-input'>
                                <button type='submit' name='update_quantity' class='btn'>Update</button>
                            </form>
                        </div>
                        
                        <div style='flex: 1; text-align: right;'>
                            <strong>\${$subtotal}</strong>
                            <form method='POST' style='display: inline; margin-left: 10px;'>
                                <input type='hidden' name='product_id' value='{$product_id}'>
                                <button type='submit' name='remove_item' class='btn btn-danger'>Remove</button>
                            </form>
                        </div>
                    </div>";
                }
            }
            
            echo "
            <div class='cart-total'>
                <p>Total: \${$total}</p>
                <div style='margin-top: 20px;'>
                    <form method='POST' style='display: inline;'>
                        <button type='submit' name='clear_cart' class='btn btn-danger'>Clear Cart</button>
                    </form>
                    <button class='btn btn-success' onclick='alert(\"Checkout feature coming soon!\")'>Checkout</button>
                </div>
            </div>";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>

