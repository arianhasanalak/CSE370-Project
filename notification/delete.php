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

// ================= ADMIN =================
if ($role == 'admin') {

    $conn->query("
    DELETE FROM notification
    WHERE notification_id=$id
    ");

    header("Location: view.php");
    exit();
}

// ================= CUSTOMER =================
else {

    // get customer_id
    $cres = $conn->query("
    SELECT customer_id
    FROM customer
    WHERE user_id=$uid
    ");

    $crow = $cres->fetch_assoc();

    $customer_id = $crow['customer_id'];

    // verify ownership
    $check = $conn->query("
    SELECT notification_id
    FROM notification
    WHERE notification_id=$id
    AND customer_id=$customer_id
    ");

    if ($check->num_rows > 0) {

        $conn->query("
        DELETE FROM notification
        WHERE notification_id=$id
        ");
    }

    header("Location: view.php");
    exit();
}
?>