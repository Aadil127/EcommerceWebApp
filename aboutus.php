<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}
include 'htmlElements/header.html';
include 'htmlElements/navbar.html';
include 'htmlElements/sidebar.html';
?>

<h1>About Us</h1>
<h4>We are both online and offline grocery store<br> we provide our best products to your doorstep within a day</h4>
<h4>Visit <a href='products.php' style='text-decoration:none;color:rgba(0, 115, 255)'>Products Page</a> to browse products</h4>

<?php
    include 'htmlElements/footer.html';
?>