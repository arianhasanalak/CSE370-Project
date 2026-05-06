<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];
?>

<div class="card">
<h2>Payments</h2>

<table>
<tr>
<th>Payment ID</th>
<th>Equipment</th>
<th>Total Cost</th>
<th>Status</th>
<th>Method</th>

<?php
if ($role == 'admin') {
    echo "<th>Customer</th>";
}
?>

</tr>

<?php

if ($role == 'admin') {

    $res = $conn->query("
    SELECT
        p.payment_id,
        p.total_cost,
        u.name AS customer,
        e.name AS equipment,
        p.status,
        p.method

    FROM payment p
    JOIN rental r ON p.rental_id = r.rental_id
    JOIN customer c ON r.customer_id = c.customer_id
    JOIN user u ON c.user_id = u.user_id
    JOIN equipment e ON r.equipment_id = e.e_id
    ");

}
else {

    $cres = $conn->query("SELECT customer_id FROM customer WHERE user_id=$uid");
    $crow = $cres->fetch_assoc();

    $customer_id = $crow['customer_id'];

    $res = $conn->query("
    SELECT
        p.payment_id,
        p.total_cost,
        e.name AS equipment,
        p.status,
        p.method

    FROM payment p
    JOIN rental r ON p.rental_id = r.rental_id
    JOIN equipment e ON r.equipment_id = e.e_id

    WHERE r.customer_id = $customer_id
    ");
}

while($row = $res->fetch_assoc()) {

    echo "<tr>
    <td>{$row['payment_id']}</td>
    <td>{$row['equipment']}</td>
    <td>{$row['total_cost']}</td>
    <td>{$row['status']}</td>
    <td>{$row['method']}</td>";

    if ($role == 'admin') {
        echo "<td>{$row['customer']}</td>";
    }

    echo "</tr>";
}
?>

</table>
</div>

<?php include '../footer.php'; ?>