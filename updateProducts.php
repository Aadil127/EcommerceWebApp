<?php
session_start();
include 'db.php';
$data = json_decode(file_get_contents("php://input"), true);

foreach ($data as $tableName => $rows) {
    foreach ($rows as $id => $fields) {
        $setParts = [];
        foreach ($fields as $field => $value) {
            $setParts[] = "$field = '" . $conn->real_escape_string($value) . "'";
        }
        $setString = implode(", ", $setParts);
        $sql = "UPDATE products SET $setString WHERE ID = " . intval($id);
        $conn->query($sql);
    }
}

$conn->close();
echo "Updated successfully";
?>
