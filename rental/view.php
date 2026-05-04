<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<div class="card">
<h2>Rentals</h2>

<a href="add.php"><button>Add Rental</button></a><br><br>

<table>
<tr>
<th>Customer</th>
<th>Equipment</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
$res = $conn->query("
SELECT r.rental_id, u.name AS customer, e.name AS equipment, r.status
FROM Rental r
JOIN Customer c ON r.customer_id = c.customer_id
JOIN User u ON c.user_id = u.user_id
JOIN Equipment e ON r.equipment_id = e.e_id
");

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