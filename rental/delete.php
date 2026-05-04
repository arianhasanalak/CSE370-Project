<?php
session_start();
include '../db.php';

$id = $_GET['id'];

// Check if payment exists
$check = $conn->query("SELECT * FROM Payment WHERE rental_id=$id");

if ($check->num_rows > 0) {
    echo "Cannot delete: Payment exists for this rental.";
    exit();
}

$conn->query("DELETE FROM Rental WHERE rental_id=$id");

header("Location: view.php");
?>