<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}
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
            <table class='categoryTable' data-Category=" . $row['Category'] . ">
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
            <table class='categoryTable' data-Category=" . $row['Category'] . ">
            <tr>
                <th> ID </th>
                <th> Name </th>
                <th>Purchased Price ₹ </th>
                <th> Selling Price ₹ </th>
                <th> Starting Quantity </th>
                <th> Quantity </th>
            </tr>";
        }
        echo "<tr class='contentRow' data-id=" . $row["ID"] . ">
            <td>" . $row["ID"] . "</td>
            <td contenteditable='true' data-field='Name'>" . $row["Name"] . "</td>
            <td contenteditable='true' data-field='PurchasedPrice'>" . $row["PurchasedPrice"] . "</td>
            <td contenteditable='true' data-field='Price'>" . $row["Price"] . "</td>
            <td contenteditable='true' data-field='StartingQuantity'>" . $row["StartingQuantity"] . "</td>
            <td contenteditable='true' data-field='Quantity'>" . $row["Quantity"] . "</td>
        </tr>";
        $previousCategory = $row["Category"];
    }
    echo "</table>";
    echo "</div>";
}


$statement->close();
$conn->close();


?>

<button id="saveChanges">Save Changes</button>
<button id="cancelChanges">Cancel Changes</button>


<script>
const tables = document.querySelectorAll(".categoryTable");
const saveBtn = document.getElementById("saveChanges");
const cancelBtn = document.getElementById("cancelChanges");

const originalValues = new Map();
const changedData = {}; // { category: { ID: { field: value } } }
const invalidCells = new Set();

tables.forEach(table => {
  table.querySelectorAll("td[contenteditable]").forEach(td => {
    originalValues.set(td, td.innerText.trim());
  });
});

function validateCell(td) {
    const field = td.dataset.field;
    const value = td.innerText.trim();
    td.style.outline = "";

    if(field === "Name" && value === ""){
        td.style.outline = "2px solid red";
        invalidCells.add(td);
        return false;
    }
    else invalidCells.delete(td);

    if((field === "PurchasedPrice" || field === "Price" || field === "StartingQuantity" || field === "Quantity") && (value === "" || isNaN(value))){
        td.style.outline = "2px solid red";
        invalidCells.add(td);
        return false;
    }
    else invalidCells.delete(td);

  return true;
}

tables.forEach(table => {
    const category = table.dataset.category;

    table.querySelectorAll("td[contenteditable]").forEach(td => {
        td.addEventListener("input", function () {
            const currentValue = this.innerText.trim();
            const originalValue = originalValues.get(this);
            const row = this.closest("tr");
            const id = row.dataset.id;
            const field = this.dataset.field;

            validateCell(this);

            if (currentValue !== originalValue) {
                this.style.backgroundColor = "rgba(0,255,100,0.25)";
                if (!changedData[category]) changedData[category] = {};
                if (!changedData[category][id]) changedData[category][id] = {};
                changedData[category][id][field] = currentValue;
            }
            else {
                this.style.backgroundColor = "";
                if (changedData[category] && changedData[category][id]) {
                    delete changedData[category][id][field];
                    if (Object.keys(changedData[category][id]).length === 0)
                        delete changedData[category][id];
                    if (Object.keys(changedData[category]).length === 0)
                        delete changedData[category];
                }
            }
        });
    });
});

saveBtn.addEventListener("click", () => {
    let allValid = true;
    tables.forEach(table => {
        table.querySelectorAll("td[contenteditable]").forEach(td => {
            if (!validateCell(td)) allValid = false;
        });
    });

    if (!allValid) {
        alert("Please fix highlighted red fields before saving.");
        return;
    }

    if (Object.keys(changedData).length === 0) {
        alert("No changes to save.");
        return;
    }

    fetch("updateProducts.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(changedData)
    })
    .then(res => res.text())
    .then(res => {
        console.log(res);
        alert("All category changes saved!");
        for (const [td] of originalValues.entries()) {
            originalValues.set(td, td.innerText.trim());
            td.style.backgroundColor = "";
            td.style.outline = "";
        }
        for (const cat in changedData) delete changedData[cat];
    })
    .catch(err => console.error(err));
});

cancelBtn.addEventListener("click", () => {
    if (!confirm("Discard all unsaved changes?")) return;

    for (const [td, val] of originalValues.entries()) {
        td.innerText = val;
        td.style.backgroundColor = "";
        td.style.outline = "";
    }

    for (const cat in changedData) delete changedData[cat];
    invalidCells.clear();

    alert("All changes reverted.");
});
</script>

<?php


include 'htmlElements/footer.html';

?>