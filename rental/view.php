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
<h2>Rentals</h2>

<table>
<tr>
<th>Customer</th>
<th>Equipment</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php

//ADMIN
if ($role == 'admin') {

    $res = $conn->query("
    SELECT r.rental_id, u.name AS customer, e.name AS equipment, r.status
    FROM rental r
    JOIN customer c ON r.customer_id = c.customer_id
    JOIN user u ON c.user_id = u.user_id
    JOIN equipment e ON r.equipment_id = e.e_id
    ");

}

//CUSTOMER
else {

    $cRes = $conn->query("SELECT customer_id FROM customer WHERE user_id=$uid");
    $cRow = $cRes->fetch_assoc();

    if (!$cRow) {
        echo "<tr><td colspan='4'>No customer record found</td></tr>";
    } else {

        $customer_id = $cRow['customer_id'];

        $res = $conn->query("
        SELECT r.rental_id, e.name AS equipment, r.status
        FROM rental r
        JOIN equipment e ON r.equipment_id = e.e_id
        WHERE r.customer_id = $customer_id
        ");

        while($row = $res->fetch_assoc()) {

            echo "<tr>
            <td>You</td>
            <td>{$row['equipment']}</td>
            <td>{$row['status']}</td>
            <td>";

            if ($row['status'] != 'Completed') {
                echo "<a href='../payment/add.php?rental_id={$row['rental_id']}'><button>Pay</button></a>";
            } else {
                echo "Paid";
            }

            echo "</td></tr>";
        }
    }

    include '../footer.php';
    exit();
}

// ADMIN display
while($row = $res->fetch_assoc()) {

echo "<tr>
<td>{$row['customer']}</td>
<td>{$row['equipment']}</td>
<td>{$row['status']}</td>
<td>
<a href='delete.php?id={$row['rental_id']}'>Delete</a>
</td>
</tr>";
}
?>

</table>
</div>

<?php include '../footer.php'; ?>