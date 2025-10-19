<?php
session_start();
ob_start();
include 'db.php';
include 'htmlElements/header.html';
include 'htmlElements/loginnav.html';

$error="";
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $statement = $conn->prepare("SELECT ID, Name, Phone, Password FROM users WHERE Phone = ?");
    $statement->bind_param("i", $phone);
    $statement->execute();
    $statement->store_result();

    if($statement->num_rows() == 1){
        $statement->bind_result($ID, $Name, $Phone, $HashedPassword);
        $statement->fetch();

        if(password_verify($password, $HashedPassword)){
            $_SESSION['username'] = $Name;
            $_SESSION['userID'] = $ID;

            if($ID == $config["adminID"]){
                header("Location: adminDashboard.php");
            }
            else{
                header("Location: products.php");
            }
            exit();
        }
        else{
            $error = "Invalid password";
        }
    }
    else{
        $error = "No user found";
    }
    $statement->close();
    $conn->close();
}
if($error !== ""){
    echo "<script>alert('$error');</script>";
}
?>


<form method="POST">
    <input type="phone" name="phone" placeholder="Phone number" required>
    <input type="password" name="password" id="password" placeholder="Password" required>
    <button type="submit">Login</button>
    <button onclick="window.location.href='signup.php'">signup</button>
</form>

<?php
include 'htmlElements/footer.html';
ob_end_flush();
?>

