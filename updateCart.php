<?php
session_start();
$data = json_decode(file_get_contents("php://input"), true);
$productId= $data["productId"];
$productQuantity = $data["ProductQuantity"];

//in session cart array will contain productID : quantity
if(isset($_SESSION["cart"][$productId])){

    if($productQuantity === "remove") unset($_SESSION["cart"][$productId]);
    elseif($_SESSION["cart"][$productId] > 1 && $_SESSION["cart"][$productId] < 99 ) $_SESSION["cart"][$productId] += $productQuantity;
    elseif($_SESSION["cart"][$productId] === 1 && $productQuantity === 1) $_SESSION["cart"][$productId] += 1;
    elseif($_SESSION["cart"][$productId] === 99 && $productQuantity === -1) $_SESSION["cart"][$productId] -= 1;
    
}
else
    $_SESSION["cart"][$productId] = 1;

$totalProducts = 0;
foreach($_SESSION['cart'] as $productQuantityA){
    $totalProducts += $productQuantityA;
}

if(isset($_SESSION["cart"][$productId]))
    echo json_encode(["productID" => $productId, "Quantity" => $_SESSION["cart"][$productId], "totalProducts" => $totalProducts]);
else
    echo json_encode(["productID" => "removed", "totalProducts" => $totalProducts]);

?>