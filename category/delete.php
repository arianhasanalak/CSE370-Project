<?php include '../db.php';

$id = $_GET['id'];

$conn->query("DELETE FROM Category WHERE category_id=$id");

header("Location: view.php");
?>
