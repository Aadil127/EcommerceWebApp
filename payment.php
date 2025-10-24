<?php
session_start();
include 'db.php';
$data = json_decode(file_get_contents("php://input"), true);

if($data["purchased"] === "true"){
    
    $sql = "INSERT INTO cart (CartID, UsersID, ProductsID, Quantity, SubTotal) VALUES";
    $values = [];
    $parameters = [];
    $cartID = uniqid("cart_");
    $total = 0;
    foreach($_SESSION["cart"] as $productID => $productQuantity){

        $statement = $conn->prepare("SELECT ID, Price FROM products WHERE ID = ?");
        $statement->bind_param("i",$productID);
        $statement->execute();
        $statement->store_result();
        $statement->bind_result($productID1,$price);
        $statement->fetch();

        $values[] = "(?, ?, ?, ?, ?)";
        $parameters[] = $cartID;
        $parameters[] = $_SESSION["userID"];
        $parameters[] = $productID;
        $parameters[] = $productQuantity;
        $parameters[] = $price * $productQuantity;
        $total += $price * $productQuantity;
    }
    $sql .= implode(",", $values);
    $dataType = str_repeat("siiis", count($_SESSION["cart"]));

    $statement = $conn->prepare($sql);
    $statement->bind_param($dataType, ...$parameters);

    if($statement->execute()){

        foreach($_SESSION["cart"] as $productID => $productQuantity){
            $sql = "UPDATE products SET Quantity = Quantity - ? WHERE ID = ? ";
            $statement = $conn->prepare($sql);
            $statement->bind_param("ii", $productQuantity, $productID);
            $statement->execute();
        }

        $currentDateTime = date("Y-m-d H:i:s");
        $sql = "INSERT INTO orders (CartID, UsersID, Date, Total ) VALUES (?, ?, ?, ?)";
        $statement = $conn->prepare($sql);
        $statement->bind_param("sisi",$cartID, $_SESSION['userID'],  $currentDateTime, $total);

        $statement->execute();

        $_SESSION["cart"] = [];
        echo json_encode(["OrderPlaced" => "true"]);
    }
    else
        echo json_encode(["orederPlaced" => "false"]);

    $statement->close();
    $conn->close();

}

?>