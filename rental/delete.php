<?php
include '../db.php';

$id = $_GET['id'];

$conn->query("DELETE FROM Rental WHERE rental_id=$id");

header("Location: view.php");
?>