<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];

$id = $_GET['id'] ?? '';

if (!$id) {
    header("Location: view.php");
    exit();
}

// ================= GET NOTIFICATION =================
$res = $conn->query("
SELECT *
FROM notification
WHERE notification_id=$id
");

$row = $res->fetch_assoc();

if (!$row) {
    header("Location: view.php");
    exit();
}

// ================= SECURITY =================
if ($role != 'admin') {

    if ($row['user_id'] != $uid) {

        echo "
        <script>
        alert('Access Denied');
        window.location='view.php';
        </script>
        ";

        exit();
    }
}

// ================= UPDATE STATUS =================
$conn->query("
UPDATE notification
SET status='Read'
WHERE notification_id=$id
");

// ================= REDIRECT =================
header("Location: view.php");
exit();
?>