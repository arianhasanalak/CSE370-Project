<?php include '../db.php';

$id = $_GET['id'];

$conn->query("DELETE FROM Equipment WHERE e_id=$id");

header("Location: view.php");
?>