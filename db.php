<?php

$config = require "C:/xampp/myConfig/config.php";

$conn = new mysqli($config["serverName"], $config["userName"], $config["password"], $config["database"]);

if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);
?>
