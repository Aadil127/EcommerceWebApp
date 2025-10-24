<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}
ob_start();
include 'db.php';

include 'htmlElements/adminHeader.html';
include 'htmlElements/adminNavbar.html';
include 'htmlElements/adminSidebar.html';

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
ob_end_flush();
?>
