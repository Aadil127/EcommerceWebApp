<?php
ob_start();
define('ALLOW_INCLUDE', true);
include 'db.php';
include 'htmlElements/header.html';
include 'htmlElements/loginnav.html';

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if($_POST['password'] !== $_POST['confirmedPassword'])
        $error = "Passwords did not match!";
    else {
        $username = $_POST['username'];
        $phone = $_POST['phone'];
        $phone = preg_replace('/\D+/', '', $phone);
        if (strlen($phone) > 10) $phone = substr($phone, -10);
        if (!preg_match('/^\d{10}$/', $phone)) die("Invalid phone number format.");

        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $address = $_POST['address'];

        $statement = $conn->prepare("INSERT INTO users (Name, Phone, Password, Address) VALUES (?, ?, ?, ?)");
        $statement->bind_param("ssss", $username,$phone, $password, $address);

        if ($statement->execute()) {
            header("Location: index.php");
            exit();
        }
        else
            echo "Error: " . $statement->error;
        $statement->close();
        $conn->close();
    }
}
?>

<form method="POST" onsubmit="return validatePasswords()">
    <input type="text" name="username" placeholder="Name" required>
    <input type="phone" name="phone" placeholder="Phone number" required>
    <input type="password" name="password" id="password" placeholder="Password" required>
    <input type="password" name="confirmedPassword" id="confirmedPassword" placeholder="Confirm password" required>
    <textarea name="address" rows="3" cols="40" placeholder="Address" required></textarea>
    <button type="submit">Register</button>
    <button onclick="window.location.href='login.php'">Login</button>
</form>

<?php if ($error) echo "<p style='color:red'>$error</p>"; ?>

<script>
    function validatePasswords(){
        let password = document.getElementById("password").value;
        let confirmedpassword = document.getElementById("confirmedPassword").value;
        if(password !== confirmedpassword){
            alert("Passwords do not match!");
            return false;
        }
        return true;
    }
</script>

<?php
include 'htmlElements/footer.html';
ob_end_flush();
?>
