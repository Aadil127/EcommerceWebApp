<?php
session_start();
if (!isset($_SESSION['userID'])) {
    var_dump($_SESSION);
    header("Location: index.php");
    exit();
}
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

include 'db.php';
include 'htmlElements/header.html';
include 'htmlElements/navbar.html';
include 'htmlElements/sidebar.html';


$prodcutIDArray = array_keys($_SESSION["cart"]);

if(count($prodcutIDArray) > 0){
    $placeholder = implode(",", array_fill(0, count($prodcutIDArray), "?"));
    $sql = "SELECT ID, Name, Price,ImgPath, Quantity, Category FROM products WHERE id IN ($placeholder)";

    $statement = $conn->prepare($sql);
    $dataType = str_repeat("i", count($prodcutIDArray));
    $statement->bind_param($dataType,...$prodcutIDArray);
    $statement->execute();
    $statement->store_result();

    $totalProducts = 0;
    $totalPrice = 0 ;

    if($statement->num_rows > 0){
        echo "<div class='productsContainer'>";
        $statement->bind_result($ProductID,$ProductName, $productPrice, $ProductImgPath, $ProductQuantityInStorage, $ProductCategory);
        while($statement->fetch()){
            $productQuantity = $_SESSION["cart"][$ProductID];
            $totalProducts += $productQuantity;
            $totalPrice += ($productQuantity * $productPrice);
            include 'cartCard.php';
        }
        echo "</div>";
        echo "<div class='cartResult' >";
        echo "<div id='totalProducts'>Total products : $totalProducts </div>";
        echo "<div id='totalPrice'>Total price :  $totalPrice  ₹</div>";
        echo "<div class='buyCart focusable' tabindex='0'>Buy<svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 -960 960 960' width='24px' fill='#000000'><path d='M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z'/></svg></div></div>";
    }
    $statement->close();
    $conn->close();
}
else{
    echo "
    <div class='emptyCart'>
        <h2>Cart is Empty</h2>
        <h3>Visit <a href='products.php' style='text-decoration:none;color:rgba(0, 115, 255)'>Products Page</a> to browse products</h3>
    </div>";
}



?>
<script src="cart.js"></script>

<?php
include 'htmlElements/footer.html';
?>
 
