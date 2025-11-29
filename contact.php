<?php
// contact.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Naprose Fashion</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>Contact Naprose Fashion</h1>
        <nav>
            <a href="index.php">Home</a> |
            <a href="about.php">About</a> |
            <a href="contact.php">Contact</a>
        </nav>
    </header>

    <main>
        <section class="contact-section">
            <h2>Get in Touch</h2>
            <p>If you have any questions, feel free to contact us:</p>

            <form action="contact_submit.php" method="POST">
                <label for="name">Name:</label><br>
                <input type="text" name="name" id="name" required><br><br>

                <label for="email">Email:</label><br>
                <input type="email" name="email" id="email" required><br><br>

                <label for="message">Message:</label><br>
                <textarea name="message" id="message" rows="5" required></textarea><br><br>

                <button type="submit">Send Message</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Naprose Fashion. All Rights Reserved.</p>
    </footer>
</body>
</html>
