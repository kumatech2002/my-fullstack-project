<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$products = json_decode(file_get_contents('../products.json'), true);
?>
<h2>Admin Dashboard</h2>

<a href="add_product.php">Add New Product</a>

<table>
  <tr><th>Name</th><th>Price</th><th>Category</th><th>Actions</th></tr>
  <?php foreach ($products as $p): ?>
    <tr>
      <td><?= $p['name'] ?></td>
      <td>$<?= $p['price'] ?></td>
      <td><?= $p['category'] ?></td>
      <td>
        <a href="edit_product.php?id=<?= $p['id'] ?>">Edit</a> |
        <a href="delete_product.php?id=<?= $p['id'] ?>">Delete</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
