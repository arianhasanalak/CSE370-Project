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

// ================= ADMIN =================
if ($role == 'admin') {

    $res = $conn->query("
    SELECT 
        r.rental_id,
        r.customer_id,
        r.equipment_id,
        r.status,
        u.name AS customer,
        e.name AS equipment

    FROM rental r

    JOIN customer c
    ON r.customer_id = c.customer_id

    JOIN user u
    ON c.user_id = u.user_id

    JOIN equipment e
    ON r.equipment_id = e.e_id
    ");
}

// ================= CUSTOMER =================
else {

    // get customer id
    $cres = $conn->query("
    SELECT customer_id
    FROM customer
    WHERE user_id=$uid
    ");

    $crow = $cres->fetch_assoc();

    $customer_id = $crow['customer_id'];

    $res = $conn->query("
    SELECT 
        r.rental_id,
        r.customer_id,
        r.equipment_id,
        r.status,
        e.name AS equipment

    FROM rental r

    JOIN equipment e
    ON r.equipment_id = e.e_id

    WHERE r.customer_id = $customer_id
    ");
}

// ================= DISPLAY =================
while($row = $res->fetch_assoc()) {

echo "<tr>";

// customer/admin name
if ($role == 'admin') {
    echo "<td>{$row['customer']}</td>";
} else {
    echo "<td>You</td>";
}

echo "
<td>{$row['equipment']}</td>

<td>{$row['status']}</td>

<td>
";

// ================= PAYMENT BUTTON =================
if ($row['status'] == 'Pending') {

    echo "
    <a href='../payment/add.php?rental_id={$row['rental_id']}'>
    <button>Pay</button>
    </a>

    <br><br>

    <a href='cancel.php?id={$row['rental_id']}'>
    <button>Cancel</button>
    </a>
    ";
}

// ================= COMPLETED =================
elseif ($row['status'] == 'Completed') {

    echo "
    Paid

    <br><br>

    <a href='delete.php?id={$row['rental_id']}'>
    <button>Delete</button>
    </a>
    ";
}

// ================= CANCELLED =================
elseif ($row['status'] == 'Cancelled') {

    echo "
    Cancelled

    <br><br>

    <a href='delete.php?id={$row['rental_id']}'>
    <button>Delete</button>
    </a>
    ";
}

echo "
</td>
</tr>";
}
?>

</table>

</div>

<?php include '../footer.php'; ?>