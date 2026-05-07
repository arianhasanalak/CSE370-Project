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

<h2>Notifications</h2>

<table>

<tr>
<th>ID</th>
<th>Message</th>
<th>Date</th>
<th>Status</th>

<?php
if ($role == 'admin') {
    echo "<th>Customer</th>";
}
?>

<th>Action</th>

</tr>

<?php

if ($role == 'admin') {

    $res = $conn->query("
    SELECT 
        n.notification_id,
        n.message,
        n.date,
        n.status,
        u.name AS customer

    FROM notification n

    JOIN customer c
    ON n.customer_id = c.customer_id

    JOIN user u
    ON c.user_id = u.user_id

    ORDER BY n.notification_id DESC
    ");
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

    $res = $conn->query("
    SELECT 
        notification_id,
        message,
        date,
        status

    FROM notification

    WHERE customer_id = $customer_id

    ORDER BY notification_id DESC
    ");
}

// ================= DISPLAY =================
while($row = $res->fetch_assoc()) {

echo "<tr>

<td>{$row['notification_id']}</td>

<td>{$row['message']}</td>

<td>{$row['date']}</td>

<td>{$row['status']}</td>";

if ($role == 'admin') {
    echo "<td>{$row['customer']}</td>";
}

echo "
<td>
<a href='delete.php?id={$row['notification_id']}'>
<button>Delete</button>
</a>
</td>
";

echo "</tr>";
}
?>

</table>

</div>

<?php include '../footer.php'; ?>