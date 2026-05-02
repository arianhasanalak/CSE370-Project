<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];
$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];

// CUSTOMER can only delete own account
if ($role != 'admin' && $uid != $id) {
    echo "Access denied";
    exit();
}

// Delete role tables first
$conn->query("DELETE FROM Admin WHERE user_id=$id");
$conn->query("DELETE FROM Customer WHERE user_id=$id");

// Delete user
$conn->query("DELETE FROM User WHERE user_id=$id");

header("Location: ../login.php");
?>