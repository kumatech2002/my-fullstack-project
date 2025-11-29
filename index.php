<?php include 'header.php'; ?>

<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Naprose Fashion</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <!-- Navigation -->
  <nav class="navbar">
    <div class="logo">Naprose Fashion</div>
    <div class="menu-toggle" id="mobile-menu">&#9776;</div>
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="product.php">Products</a></li>
      <li><a href="cart.php">Cart</a></li>
    </ul>
  </nav>

  <!-- Search -->
  <div class="search-container">
    <input type="text" id="search" placeholder="Search products...">
  </div>

  <!-- Product Grid -->
  <div class="product-grid">
    <?php
    // Example: load products from JSON file
    $products = json_decode(file_get_contents('products.json'), true);
    foreach($products as $product):
    ?>
      <div class="product-card">
        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
        <h3 class="product-title"><?php echo $product['name']; ?></h3>
        <p class="product-price">$<?php echo $product['price']; ?></p>
        <button class="add-to-cart" data-id="<?php echo $product['id']; ?>">Add to Cart</button>
      </div>
    <?php endforeach; ?>
  </div>

  <script src="script.js"></script>
</body>
</html>
