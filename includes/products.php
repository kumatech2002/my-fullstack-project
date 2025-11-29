
<?php
session_start();
?>
<?php include 'includes/db_connect.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Products - Naprose Fashion</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .product { 
            border: 1px solid #ddd; 
            padding: 20px; 
            margin: 10px; 
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>🛍️ Our Products</h1>
    
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
                <button>Add to Cart</button>
            </div>";
        }
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
    
    <br>
    <a href="index.php">← Back to Home</a>
</body>
</html>