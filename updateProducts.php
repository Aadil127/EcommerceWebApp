<?php
session_start();
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}
define('ALLOW_INCLUDE', true);

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
