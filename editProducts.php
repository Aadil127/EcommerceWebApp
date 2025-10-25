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


if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        
        $fileName = $_FILES["image"]["name"];
        $fileTmp  = $_FILES["image"]["tmp_name"];
        $fileSize = $_FILES["image"]["size"];
        $fileType = $_FILES["image"]["type"];
        $productName = $_POST["productName"];
        $category = $_POST["category"];
        $quatity = $_POST["quatity"];
        $pruchasedPrice = $_POST["pruchasedPrice"];
        $price = $_POST["price"];

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if(!in_array($ext, $allowed)) die("Error: Only JPG, PNG, GIF files are allowed.");

        $uploadDir = "uploads/";
        if(!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $newFileName = uniqid("img_", true) . "." . $ext;
        $destination = $uploadDir . $newFileName;

        if(move_uploaded_file($fileTmp, $destination)) {

            $statement = $conn->prepare("INSERT INTO products(Name, Price, ImgPath, Quantity, Category, PurchasedPrice, StartingQuantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $statement->bind_param("sisisii", $productName,$price, $destination, $quatity, $category, $pruchasedPrice, $quatity);
            if($statement->execute()){
                ?><script>alert("Sucessfuly added product")</script><?php

                header("Location: " . $_SERVER["PHP_SELF"] . "?success=1");
                exit();
            }
            $statement->close();
        }
        else 
            echo "Error: Could not move file to uploads folder.";
    }
    else
        echo "Error: " . $_FILES['image']['error'];
}

if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET["productId"])){
    $productName = $_GET["productName"];
    $productId = $_GET["productId"];

    $statement = $conn->prepare("SELECT ImgPath FROM products WHERE ID = ? AND Name = ?");
    $statement->bind_param("is", $productId, $productName);
    $statement->execute();

    $result = $statement->get_result();
    $row = $result->fetch_assoc();
    if (is_file($row["ImgPath"])){
        unlink($row["ImgPath"]);
    }

    $statement = $conn->prepare("DELETE FROM products WHERE ID = ? AND Name = ?");
    $statement->bind_param("is", $productId, $productName);
    $statement->execute();
    $statement->close();
}

$conn->close();
?>

<form action="editProducts.php" method="POST" enctype="multipart/form-data">
    <h3>Add New Product</h3>
    <input type="text" name="productName" placeholder="Name of product" required>
    <input type="text" name="category" placeholder="Category of product" required>
    <input type="number" name="quatity" placeholder="product quatity" required>
    <input type="number" name="pruchasedPrice" placeholder="purchase price of product" required>
    <input type="number" name="price" placeholder="Selling price of product" required>

    <div class="imageUpload">
        <input type="file" id="imageInput" name="image" accept="image/*" required>
        <label for="imageInput" id="imageLabel">Choose image</label>
    </div>
    <button type="submit">Upload</button>
    <h4 id="ImgInputName"></h4>
</form>


<form action="editProducts.php" method="GET" onsubmit="confirmSubmit(event)">
    <h3>Remove Product</h3>
    <input type="number" name="productId" placeholder="Enter the product Id" required>
    <input type="text" name="productName" placeholder="Enter the product Name" required>
    <button type="submit">Submit</button>
</form>

<script>
    window.addEventListener("load", function() {
    document.querySelectorAll("form").forEach(form => form.reset());
    });

    function confirmSubmit(event){
        const confirmed = confirm("Are you sure you want to remove product?");
        if (!confirmed) {
            event.preventDefault();
        }
    }


    const imageInput = document.getElementById("imageInput");
    const imageLabel = document.getElementById("imageLabel");
    const ImgInputName = document.getElementById("ImgInputName");

    imageInput.addEventListener('change', function() {
    if (imageInput.files.length > 0) {
        imageLabel.style.backgroundColor = "#5e755f";
    
        ImgInputName.textContent = "Img Name : " + imageInput.files[0].name;
    }
    else {
        imageLabel.style.backgroundColor = "#4CAF50";
        ImgInputName.textContent = '';
    }
});
</script>

<?php
include 'htmlElements/footer.html';
ob_end_flush();
?>
