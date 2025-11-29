<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$products = json_decode(file_get_contents('../products.json'), true);

if ($_POST) {
    $id = count($products) + 1;

    $imageName = time() . "_" . $_FILES["image"]["name"];
    move_uploaded_file($_FILES["image"]["tmp_name"], "../uploads/" . $imageName);

    $products[$id] = [
        "id" => $id,
        "name" => $_POST['name'],
        "price" => $_POST['price'],
        "category" => $_POST['category'],
        "image" => "uploads/" . $imageName,
        "description" => $_POST['description']
    ];

    file_put_contents('../products.json', json_encode($products, JSON_PRETTY_PRINT));

    header("Location: dashboard.php");
    exit;
}
?>

<form method="POST" enctype="multipart/form-data">
  <input name="name" placeholder="Product Name"><br>
  <input name="price" placeholder="Price"><br>
  <input name="category" placeholder="Category"><br>
  <textarea name="description" placeholder="Description"></textarea><br>
  <input type="file" name="image"><br>
  <button>Add Product</button>
</form>
