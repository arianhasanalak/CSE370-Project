<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}


if ($_SESSION['role'] != 'admin') {
    header("Location: view.php");
    exit();
}

$id = $_GET['id'] ?? '';

if ($id) {

    $conn->query("
    DELETE FROM review
    WHERE review_id=$id
    ");
}

header("Location: view.php");
exit();
?>