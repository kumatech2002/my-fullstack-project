
<?php
session_start();
?>
<?php
$products = json_decode(file_get_contents('products.json'), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products - Naprose Fashion</title>
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

  <!-- Page title -->
  <h2 class="page-title">Our Products</h2>

  <div class="filters">
  <select id="category-filter">
    <option value="all">All Categories</option>
    <option value="Men">Men</option>
    <option value="Women">Women</option>
    <option value="Kids">Kids</option>
  </select>

  <select id="price-filter">
    <option value="all">All Prices</option>
    <option value="0-20">$0 - $20</option>
    <option value="20-50">$20 - $50</option>
    <option value="50-100">$50 - $100</option>
  </select>

  <select id="sort-filter">
    <option value="none">Sort By</option>
    <option value="low-high">Price: Low → High</option>
    <option value="high-low">Price: High → Low</option>
  </select>
</div>


  <!-- Product Grid -->
  <div class="product-grid">
    <?php foreach ($products as $product): ?>
      <div class="product-card">
        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
        <h3 class="product-title"><?php echo $product['name']; ?></h3>
        <p class="product-price">$<?php echo $product['price']; ?></p>

        <button class="view-details" 
                data-name="<?php echo $product['name']; ?>" 
                data-price="<?php echo $product['price']; ?>"
                data-desc="<?php echo $product['description']; ?>"
                data-image="<?php echo $product['image']; ?>">
            View Details
        </button>

        <button class="add-to-cart" data-id="<?php echo $product['id']; ?>">Add to Cart</button>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Modal (Hidden by default) -->
  <div id="product-modal" class="modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <img id="modal-image" src="" alt="">
      <h3 id="modal-title"></h3>
      <p id="modal-description"></p>
      <p id="modal-price"></p>
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>

