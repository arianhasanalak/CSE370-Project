<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// admin only
if ($_SESSION['role'] != 'admin') {
    header("Location: view.php");
    exit();
}

$id = $_GET['id'] ?? '';

if (!$id) {
    header("Location: view.php");
    exit();
}

//CHECK ADMIN
$checkAdmin = $conn->query("
SELECT *
FROM admin
WHERE user_id=$id
");

// if user is admin -> block delete
if ($checkAdmin->num_rows > 0) {

    echo "
    <script>
    alert('Admin accounts cannot be deleted.');
    window.location='view.php';
    </script>
    ";

    exit();
}

//DELETE CUSTOMER
$conn->query("
DELETE FROM customer
WHERE user_id=$id
");

// DELETE USER 
$conn->query("
DELETE FROM user
WHERE user_id=$id
");

header("Location: view.php");
exit();
?>