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

//GET RENTAL
$res = $conn->query("
SELECT *
FROM rental
WHERE rental_id=$id
");

$row = $res->fetch_assoc();

if (!$row) {
    header("Location: view.php");
    exit();
}

//CUSTOMER CHECK
if ($role != 'admin') {

    // get customer id from logged in user
    $cres = $conn->query("
    SELECT customer_id
    FROM customer
    WHERE user_id=$uid
    ");

    $crow = $cres->fetch_assoc();

    $customer_id = $crow['customer_id'];

    // only own rentals
    if ($row['customer_id'] != $customer_id) {

        echo "
        <script>
        alert('Access Denied');
        window.location='view.php';
        </script>
        ";

        exit();
    }
}

//CANCEL RENTAL
$conn->query("
UPDATE rental
SET status='Cancelled'
WHERE rental_id=$id
");

//MAKE EQUIPMENT AVAILABLE
$conn->query("
UPDATE equipment
SET availability='Available'
WHERE e_id={$row['equipment_id']}
");

header("Location: view.php");
exit();
?>