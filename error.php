<?php
session_start();

// --- CONFIG: change if your MySQL user/password differ ---
$host   = 'localhost';
$user   = 'root';
$pass   = '';          // XAMPP default is empty string
$dbname = 'naprose_fashionnn';
// ---------------------------------------------------------

// Enable mysqli exceptions for cleaner error handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Connect to MySQL server (no DB specified so we can create it if missing)
    $conn = new mysqli($host, $user, $pass);
    $conn->set_charset('utf8mb4');

    // Create database if it doesn't exist
    $conn->query("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Select the database
    $conn->select_db($dbname);

    // Create products table if not exists
    $createTableSQL = "
        CREATE TABLE IF NOT EXISTS `products` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            `description` TEXT,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $conn->query($createTableSQL);

    // Insert sample products if table empty (safe check)
    $resCheck = $conn->query("SELECT COUNT(*) AS cnt FROM `products`");
    $row = $resCheck->fetch_assoc();
    if ((int)$row['cnt'] === 0) {
        $stmt = $conn->prepare("INSERT INTO `products` (`name`, `price`, `description`) VALUES (?, ?, ?)");
        $samples = [
            ['Classic Tee', 19.99, 'Comfortable cotton t-shirt.'],
            ['Denim Jacket', 59.99, 'Stylish denim jacket for all seasons.'],
            ['Silk Scarf', 24.50, 'Lightweight silk scarf with pattern.'],
        ];
        foreach ($samples as $p) {
            $stmt->bind_param('sds', $p[0], $p[1], $p[2]);
            $stmt->execute();
        }
        $stmt->close();
    }

} catch (mysqli_sql_exception $ex) {
    // If something irrecoverable happened, show friendly message and stop
    echo "<h2>Database error</h2>";
    echo "<p>Could not prepare or create the database/tables. Details (for dev):</p>";
    echo "<pre>" . htmlspecialchars($ex->getMessage()) . "</pre>";
    exit;
}

// Handle Add to Cart (simple session-based cart)
if (isset($_POST['add_to_cart'])) {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    if ($product_id > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
        // small feedback — you can change to nicer UX later
        echo "<script>alert('Product added to cart!'); window.location = window.location.href;</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Products - Naprose Fashion</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .product { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .btn { background: #e8c4b8; color: #2c2c2c; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin: 5px; text-decoration: none; display:inline-block;}
        .cart-info { background: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .quantity-input { width: 60px; padding: 5px; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="cart-info">
        <h1>📦 Our Products</h1>
        <div>
            <a href="cart.php" class="btn">🛒 View Cart
                <?php
                $cart_count = 0;
                if (isset($_SESSION['cart'])) $cart_count = array_sum($_SESSION['cart']);
                if ($cart_count > 0) echo " ($cart_count)";
                ?>
            </a>
            <a href="index.php" class="btn">← Home</a>
        </div>
    </div>

    <?php
    // Fetch products (use prepared statement for safety if you later add filters)
    try {
        $result = $conn->query("SELECT id, name, price, description FROM products ORDER BY created_at DESC");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // escape output to avoid XSS
                $name = htmlspecialchars($row['name']);
                $price = number_format($row['price'], 2);
                $desc = nl2br(htmlspecialchars($row['description']));
                $id = (int)$row['id'];

                echo <<<HTML
                <div class="product">
                    <h3>{$name}</h3>
                    <p><strong>Price:</strong> \${$price}</p>
                    <p>{$desc}</p>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="product_id" value="{$id}">
                        <input type="number" name="quantity" value="1" min="1" class="quantity-input">
                        <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                    </form>
                </div>
                HTML;
            }
        } else {
            echo "<p>No products found. (This should not happen; table exists but empty.)</p>";
        }
    } catch (mysqli_sql_exception $ex) {
        echo "<p>Error fetching products: " . htmlspecialchars($ex->getMessage()) . "</p>";
    }

    $conn->close();
    ?>
</body>
</html>

<!-- //cart -->
<?php
session_start();

// --- CONFIG: change if your MySQL user/password differ ---
$host   = 'localhost';
$user   = 'root';
$pass   = '';          // XAMPP default is empty string
$dbname = 'naprose_fashionn';
// ---------------------------------------------------------

// Enable mysqli exceptions
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Connect to MySQL server without selecting DB so we can create it if missing
    $conn = new mysqli($host, $user, $pass);
    $conn->set_charset('utf8mb4');

    // Create database if it doesn't exist
    $conn->query("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Select the database
    $conn->select_db($dbname);

    // Create products table if not exists (keeps schema consistent)
    $createTableSQL = "
        CREATE TABLE IF NOT EXISTS `products` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            `description` TEXT,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $conn->query($createTableSQL);

} catch (mysqli_sql_exception $ex) {
    // Friendly error for dev; in production log the error instead of echoing
    echo "<h2>Database error</h2>";
    echo "<pre>" . htmlspecialchars($ex->getMessage()) . "</pre>";
    exit;
}

// Helper: redirect after POST to avoid resubmissions
function redirect_back() {
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle POST actions first (no output before header)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update quantity
    if (isset($_POST['update_quantity'])) {
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

        if ($quantity <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
        redirect_back();
    }

    // Remove item
    if (isset($_POST['remove_item'])) {
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
        redirect_back();
    }

    // Clear cart
    if (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = [];
        redirect_back();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shopping Cart - Naprose Fashion</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .cart-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .cart-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #eee; }
        .btn { background: #e8c4b8; color: #2c2c2c; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; margin: 0 5px; text-decoration: none; display: inline-block; }
        .btn-danger { background: #ff6b6b; color: white; }
        .btn-success { background: #4CAF50; color: white; }
        .quantity-input { width: 60px; padding: 5px; }
        .cart-total { font-size: 1.2em; font-weight: bold; text-align: right; margin-top: 20px; padding-top: 20px; border-top: 2px solid #eee; }
        .empty-cart { text-align: center; padding: 40px; color: #666; }
    </style>
</head>
<body>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
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
        // Prepare statement outside loop for efficiency
        $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $pid = (int)$product_id;
            $stmt->bind_param("i", $pid);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();

            if (!$product) {
                // Product no longer exists — remove from cart
                unset($_SESSION['cart'][$pid]);
                continue;
            }

            $price = (float)$product['price'];
            $subtotal = $price * (int)$quantity;
            $total += $subtotal;

            // safe output
            $name = htmlspecialchars($product['name']);
            $priceFmt = number_format($price, 2);
            $subtotalFmt = number_format($subtotal, 2);

            echo "
            <div class='cart-item'>
                <div style='flex:2;'>
                    <h3>{$name}</h3>
                    <p>\${$priceFmt} each</p>
                </div>

                <div style='flex:1; text-align:center;'>
                    <form method='POST' style='display:inline;'>
                        <input type='hidden' name='product_id' value='{$pid}'>
                        <input type='number' name='quantity' value='".(int)$quantity."' min='0' class='quantity-input'>
                        <button type='submit' name='update_quantity' class='btn'>Update</button>
                    </form>
                </div>

                <div style='flex:1; text-align:right;'>
                    <strong>\${$subtotalFmt}</strong>
                    <form method='POST' style='display:inline; margin-left:10px;'>
                        <input type='hidden' name='product_id' value='{$pid}'>
                        <button type='submit' name='remove_item' class='btn btn-danger'>Remove</button>
                    </form>
                </div>
            </div>";
        }
        if (isset($stmt)) $stmt->close();

        $totalFmt = number_format($total, 2);

        echo "
        <div class='cart-total'>
            <p>Total: \${$totalFmt}</p>
            <div style='margin-top:20px;'>
                <form method='POST' style='display:inline;'>
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
