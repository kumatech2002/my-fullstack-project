
<?php
session_start();
?>
<?php
// Database configuration
$host = "localhost";
$username = "root"; 
$password = "";
$database = "naprose_fashion";

// Create connection
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$conn->query("CREATE DATABASE IF NOT EXISTS $database");
$conn->select_db($database);

// Create only the essential tables
$conn->query("
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Add only 3 sample products
$conn->query("
INSERT IGNORE INTO products (name, price, description, image) VALUES
('Designer Blazer', 299.99, 'Premium quality designer blazer for formal occasions', 'blazer.jpg'),
('Evening Gown', 459.99, 'Elegant silk evening gown for special events', 'gown.jpg'),
('Cotton T-Shirt', 89.99, 'Comfortable premium cotton t-shirt', 'tshirt.jpg')
");

// That's it! Database is ready
?>