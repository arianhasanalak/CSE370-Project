<?php
session_start();
include '../db.php';

$id = $_GET['id'];

// Optional: also remove notification
$conn->query("DELETE FROM Notification WHERE message LIKE '%Rental ID $id%'");

$conn->query("DELETE FROM Payment WHERE payment_id=$id");

header("Location: view.php");
?>