<?php
session_start();
include '../db.php';

$id = $_GET['id'];
 
$check = $conn->query("SELECT * FROM Equipment WHERE category_id=$id");

if ($check && $check->num_rows > 0) {
    echo "Cannot delete: Category is used by equipment.";
    exit();
}

$conn->query("DELETE FROM Category WHERE category_id=$id");

header("Location: view.php");
exit();
?>