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

if ($role != 'admin' && $uid != $id) {
    echo "Access denied";
    exit();
}

$res = $conn->query("
SELECT r.* FROM Rental r
JOIN Customer c ON r.customer_id = c.customer_id
WHERE c.user_id = $id
");

if ($res->num_rows > 0) {
    echo "Cannot delete: User has rental records.";
    exit();
}

$conn->query("DELETE FROM Admin WHERE user_id=$id");
$conn->query("DELETE FROM Customer WHERE user_id=$id");

$conn->query("DELETE FROM User WHERE user_id=$id");

if ($uid == $id) {
    session_destroy();
    header("Location: ../login.php");
} else {
    header("Location: view.php");
}
?>