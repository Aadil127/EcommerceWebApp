<?php
session_start();
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}

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