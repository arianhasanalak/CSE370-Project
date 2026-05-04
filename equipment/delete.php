<?php
session_start();
include '../db.php';

$id = $_GET['id'];

// Check if equipment is rented
$check = $conn->query("SELECT * FROM Rental WHERE equipment_id=$id");

if ($check->num_rows > 0) {
    echo "Cannot delete: Equipment is currently rented.";
    exit();
}

$conn->query("DELETE FROM Equipment WHERE e_id=$id");

header("Location: view.php");
?>