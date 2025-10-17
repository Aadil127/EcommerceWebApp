<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}
ob_start();
include 'db.php';

include 'htmlElements/adminHeader.html';
include 'htmlElements/adminNavbar.html';
include 'htmlElements/adminSidebar.html';

?>

<!-- <div id="editStockInfo" class="focusable" tabindex="0">
    <div id="editStockInfoHeading">
        Edit Stock Info
    </div>
    <svg id="editStockInfoDark" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#888"><path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37ZM580-140h38l121-122-37-37-122 121v38Zm141-141-19-18 37 37-18-19Z"/></svg>
    <svg id="editStockInfoLight" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffff"><path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37ZM580-140h38l121-122-37-37-122 121v38Zm141-141-19-18 37 37-18-19Z"/></svg>
</div> -->


<?php

$sql = "SELECT * FROM products ORDER BY Category,ID";

$statement = $conn->query($sql);

if($statement->num_rows > 0){
    $previousCategory = "";
    echo "<div class='tableContainer'>";
    while($row = $statement->fetch_assoc()){
        if($previousCategory === ""){
            echo "
            <h2 class='categoryHeading'>" . $row["Category"] . "</h2>
            <table>
            <tr>
                <th> ID </th>
                <th> Name </th>
                <th>Purchased Price ₹ </th>
                <th> Selling Price ₹ </th>
                <th> Starting Quantity </th>
                <th> Quantity </th>
            </tr>";
        }
        elseif($previousCategory !== $row["Category"] && $previousCategory !== ""){
            echo "</table>
            <h2 class='categoryHeading'>" . $row["Category"] . "</h2>
            <table>
            <tr>
                <th> ID </th>
                <th> Name </th>
                <th>Purchased Price ₹ </th>
                <th> Selling Price ₹ </th>
                <th> Starting Quantity </th>
                <th> Quantity </th>
            </tr>";
        }
        echo "<tr class='contentRow'>
            <td>" . $row["ID"] . "</td>
            <td>" . $row["Name"] . "</td>
            <td>" . $row["PurchasedPrice"] . "</td>
            <td>" . $row["Price"] . "</td>
            <td>" . $row["StartingQuantity"] . "</td>
            <td>" . $row["Quantity"] . "</td>
        </tr>";
        $previousCategory = $row["Category"];
    }
    echo "</table>";
    echo "</div>";
}


$statement->close();
$conn->close();

?>


<?php

include 'htmlElements/footer.html';
ob_end_clean();
?>