<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$role = $_SESSION['role'];
$uid = $_SESSION['user_id'];
?>

<div class="card">

<h2>Reviews</h2>

<table>

<tr>
<th>Equipment</th>
<th>Customer</th>
<th>Rating</th>
<th>Comment</th>
<th>Action</th>
</tr>

<?php

//ADMIN
if ($role == 'admin') {

    $res = $conn->query("
    SELECT 
        rv.review_id,
        rv.user_id,
        e.name AS equipment,
        u.name AS customer,
        rv.rating,
        rv.comment

    FROM review rv

    JOIN user u
    ON rv.user_id = u.user_id

    JOIN equipment e
    ON rv.equipment_id = e.e_id
    ");
}

//CUSTOMER
else {

    $res = $conn->query("
    SELECT 
        rv.review_id,
        rv.user_id,
        e.name AS equipment,
        u.name AS customer,
        rv.rating,
        rv.comment

    FROM review rv

    JOIN user u
    ON rv.user_id = u.user_id

    JOIN equipment e
    ON rv.equipment_id = e.e_id

    WHERE rv.user_id = $uid
    ");
}

// DISPLAY
while($row = $res->fetch_assoc()) {

echo "<tr>

<td>{$row['equipment']}</td>

<td>{$row['customer']}</td>

<td>{$row['rating']}/5</td>

<td>{$row['comment']}</td>

<td>

<a href='delete.php?id={$row['review_id']}'>
<button>Delete</button>
</a>

</td>

</tr>";
}
?>

</table>

</div>

<?php include '../footer.php'; ?>