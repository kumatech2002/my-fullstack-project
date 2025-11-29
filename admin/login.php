<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if ($user === "admin" && $pass === "12345") {
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
        exit;
    }

    $error = "Invalid credentials";
}
?>
<form method="POST">
  <h2>Admin Login</h2>
  <input type="text" name="username" placeholder="Username" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button>Login</button>
  <p><?php echo $error ?? ""; ?></p>
</form>
