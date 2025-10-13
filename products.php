<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';
include 'htmlElements/header.html';
include 'htmlElements/navbar.html';
include 'htmlElements/sidebar.html';
?>

<div id="categoryMenu" class="focusable" tabindex="0">
    <div id="CategoryMenuHeading">
        Category
    </div>
    <svg id="categoryMenuDark" xmlns="http://www.w3.org/2000/svg" height="26px" viewBox="0 -960 960 960" width="26px" fill="#000000"><path d="M400-240v-80h160v80H400ZM240-440v-80h480v80H240ZM120-640v-80h720v80H120Z"/></svg>
    <svg id="categoryMenuLight" xmlns="http://www.w3.org/2000/svg" height="26px" viewBox="0 -960 960 960" width="26px" fill="#ffffff"><path d="M400-240v-80h160v80H400ZM240-440v-80h480v80H240ZM120-640v-80h720v80H120Z"/></svg>
</div>

<?php
echo "<div id='categoryList' inert='true'>";

    $catagoriesArray = $conn->query("SELECT DISTINCT(Category) FROM products");
    while ($row = $catagoriesArray->fetch_assoc()) {
        echo "<div class='categoryLink'>
        <a href='products.php?category=" . urlencode($row['Category']) . "'>" .
        htmlspecialchars($row['Category']) . "</a>
      </div>";
    }
        
echo "</div>";


if(isset($_GET['category']))
    $category = $_GET['category'];
else
    $category = 'drinks';

$statement = $conn->prepare("SELECT ID, Name, Price,ImgPath, Quantity, Category FROM products WHERE Category = ?");
$statement->bind_param("s", $category);
$statement->execute();
$statement->store_result();


echo "<div class='productsContainer'>";
if($statement->num_rows > 0){
    $statement->bind_result($ProductID,$ProductName, $productPrice, $ProductImgPath, $ProductQuantityInStorage, $ProductCategory);
    while($statement->fetch()) {
        $alreadyInCart = false;
        if(isset($_SESSION["cart"][$ProductID])){
            $alreadyInCart = true;
        }
        include 'productCard.php';
    }
}
echo "</div>";

$statement->close();
$conn->close();


?>
<script src="cart.js"></script>
<?php

include 'htmlElements/footer.html';
?>