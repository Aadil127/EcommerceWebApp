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
        $catagory = $_POST["catagory"];
        $quatity = $_POST["quatity"];
        $pruchasedPrice = $_POST["pruchasedPrice"];
        $price = $_POST["price"];

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if(!in_array($ext, $allowed)) die("Error: Only JPG, PNG, GIF files are allowed.");

        // if($fileSize > 2 * 1024 * 1024) die("Error: File size must be less than 2MB.");

        $uploadDir = "uploads/";
        if(!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $newFileName = uniqid("img_", true) . "." . $ext;
        $destination = $uploadDir . $newFileName;

        if(move_uploaded_file($fileTmp, $destination)) {
            // echo "success";

            $statement = $conn->prepare("INSERT INTO products(Name, Price, ImgPath, Quantity, Category, PurchasedPrice, StartingQuantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $statement->bind_param("sisisii", $productName,$price, $destination, $quatity, $catagory, $pruchasedPrice, $quatity);
            if($statement->execute()){
                ?><script>alert("Sucessfuly added product")</script><?php

                header("Location: " . $_SERVER["PHP_SELF"] . "?success=1");
                exit();
            }
        }
        else 
            echo "Error: Could not move file to uploads folder.";
    }
    else
        echo "Error: " . $_FILES['image']['error'];
}

$conn->close();
?>

<script>
window.addEventListener("load", function() {
  document.querySelectorAll("form").forEach(form => form.reset());
});
</script>

<form action="editProducts.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="productName" placeholder="Name of product" required>
    <input type="text" name="catagory" placeholder="Catagory of product" required>
    <input type="number" name="quatity" placeholder="product quatity" required>
    <input type="number" name="pruchasedPrice" placeholder="purchase price of product" required>
    <input type="number" name="price" placeholder="Selling price of product" required>

    <div class="imageUpload">
        <input type="file" id="imageInput" name="image" accept="image/*" required>
        <label for="imageInput" id="imageLabel">Choose image</label>
    </div>
    <button type="submit">Upload</button>
</form>

<?php
include 'htmlElements/footer.html';
ob_end_flush();
?>
