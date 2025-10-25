<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}
ob_start();
define('ALLOW_INCLUDE', true);
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


$sql = "SELECT DATE_FORMAT(Date, '%Y-%m-%d') AS date, COUNT(*) AS total_orders FROM orders GROUP BY DATE_FORMAT(Date, '%Y-%m-%d') ORDER BY date";
$statement = $conn->query($sql);

$data = [];
if($statement->num_rows > 0){
    while($row = $statement->fetch_assoc()){
        $data[$row["date"]] = $row["total_orders"];
    }
}

$statement->close();
$conn->close();
    
    
?>

<div id="canvasContainer">
    <canvas id="barChart">Currently can not show data</canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    const rootStyles = getComputedStyle(document.documentElement);
    const textcolor = rootStyles.getPropertyValue("--textcolor").trim();

    const chartData = <?php echo json_encode($data); ?>;
    console.log(chartData);

    const labels = Object.keys(chartData);
    const values = Object.values(chartData);

    const ctx = document.getElementById('barChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: "Orders",
                data: values,
                backgroundColor: 'rgba(0, 0, 0, 0.1)',
                borderColor: 'rgba(0, 200, 200,0.5)',
                borderWidth: 2,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            scales: {
                x : {
                    title:{
                        display: true,
                        text: "Date",
                    }
                },
                y: { 
                    title:{
                        display: true,
                        text: "Orders",
                    },
                    beginAtZero: true 
                }
            }
        }
    });
    </script>

<?php

include 'htmlElements/footer.html';
ob_end_flush();
?>
