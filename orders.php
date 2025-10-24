<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}
include 'db.php';

include 'htmlElements/adminHeader.html';
include 'htmlElements/adminNavbar.html';
include 'htmlElements/adminSidebar.html';


$sql = "SELECT users.Name as userName, users.Address as userAddress, users.Phone as userPhone,
cart.CartID as cartID, products.Name as productName,products.ID as productID, cart.Quantity as productQuantity, cart.SubTotal as subTotal, orders.Date as orderDate, orders.ID as orderID, orders.Total as TotalAmount FROM cart
INNER JOIN users ON cart.UsersID = users.ID
INNER JOIN products ON cart.ProductsID = products.ID
INNER JOIN orders ON cart.CartID = orders.CartID";

$statement = $conn->query($sql);

$cartIdArray = [];
$orderDetails = [];

if($statement->num_rows > 0){
    echo "<div class='tableContainer'><table id='ordersTable'>
        <tr>
            <th> Order ID </th>
            <th> Name </th>
            <th> Date </th>
            <th> Amount ₹ </th>
        </tr>";
    while($row = $statement->fetch_assoc()){
        if(!in_array($row["cartID"], $cartIdArray)){
            echo "<tr class='contentRow' tabindex='0'  data-cartid=" . $row["cartID"] . ">
                <td>" . $row["orderID"] . "</td>
                <td>" . $row["userName"] . "</td>
                <td>" . $row["orderDate"] . "</td>
                <td>" . $row["TotalAmount"] . "</td>
            </tr>";
            $cartIdArray[] = $row["cartID"];

            unset($products);
            $products[$row["productID"]] =[
                "productName" => $row["productName"],
                "productQuantity" => $row["productQuantity"],
                "subTotal" => $row["subTotal"]
            ];
            $orderDetails[$row["cartID"]] = [
                "userName" => $row["userName"],
                "userAddress" => $row["userAddress"],
                "userPhone" => $row["userPhone"],
                "productQuantity" => $row["productQuantity"],
                "cartID" => $row["cartID"],
                "orderDate" => $row["orderDate"],
                "orderID" => $row["orderID"],
                "TotalAmount"=> $row["TotalAmount"],
                "products" => $products
            ];
        }
        else{
            $orderDetails[$row["cartID"]]["products"][$row["productID"]] = [
                "productName" => $row["productName"],
                "productQuantity" => $row["productQuantity"],
                "subTotal" => $row["subTotal"]
            ];
        }
        
    }
    echo "</table><div>";
}

$statement->close();
$conn->close();

?>

<div id="moreOrderDetail"></div>

<script>

    const orderDetails = <?php echo json_encode($orderDetails); ?>;
    console.log(orderDetails);


    document.querySelectorAll(".contentRow").forEach(tr => {
        tr.addEventListener("click", function(){
            showDetails(tr.dataset.cartid);
        });
        tr.addEventListener("keydown",function(event){
        if(event.key == "Enter") showDetails(tr.dataset.cartid);
    });
    });

    function showDetails(cartid){
        const moreOrderDetail = document.getElementById("moreOrderDetail");

        if(cartid){
            moreOrderDetail.classList.add("active")
            let table = `
            <div class='moreOrderDetailHeading'>Name : ${orderDetails[cartid].userName}</div>
            <div class='moreOrderDetailHeading'>Phone : ${orderDetails[cartid].userPhone}</div>
            <div class='moreOrderDetailHeading'>Address : ${orderDetails[cartid].userAddress}</div>
            <div class='tableContainer'>
            <table class='moreOrderDetailTable'>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Sub total</th>
                </tr>
            `;
            for (const productID in orderDetails[cartid].products) {
                const product = orderDetails[cartid].products[productID];
                table += `<tr class='contentRow'>
                    <td>${product.productName}</td>
                    <td>${product.productQuantity}</td>
                    <td>${product.subTotal}</td>
                </tr>`;
            }
            table += `
            <tr class='contentRow'>
                <th colspan=2 style="text-align:right">Total Amount</th>
                <td>${orderDetails[cartid].TotalAmount}</td>
            </tr>
            </table>
            <div>`;
            moreOrderDetail.innerHTML = table;
        }
        else moreOrderDetail.classList.remove("active");
    }


    document.addEventListener("click", (event) => {
        if (!document.querySelector("table").contains(event.target) && !document.querySelector("#moreOrderDetail").contains(event.target)) {
            showDetails(false);
        }
    });

</script>

<?php

include 'htmlElements/footer.html';

?>
