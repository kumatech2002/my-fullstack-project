<?php
$host = "localhost";
$user = "root"; 
$pass = "";
$dbname = "naprose_fashion";

// Create connection
$conn = new mysqli($host, $user, $pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

// Create products table
$conn->query("
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Add sample products
$conn->query("
INSERT IGNORE INTO products (name, price, description) VALUES
('Designer Blazer', 299.99, 'Premium quality designer blazer'),
('Evening Gown', 459.99, 'Elegant silk evening gown'),
('Cotton T-Shirt', 89.99, 'Comfortable premium cotton t-shirt')
");

echo "<h1>✅ Database Setup Complete!</h1>";
echo "<p>Your Naprose Fashion database is ready!</p>";
echo "<a href='products.php'>View Products →</a>";

$conn->close();
?>