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
<h2>Payments</h2>

<a href="add.php"><button>Add Payment</button></a><br><br>

<table>
<tr>
<th>Payment ID</th>
<th>Rental ID</th>
<th>Status</th>
<th>Method</th>
</tr>

<?php
$res = $conn->query("
SELECT p.payment_id, p.status, p.method, r.rental_id
FROM Payment p
JOIN Rental r ON p.rental_id = r.rental_id
");

while($row = $res->fetch_assoc()) {
echo "<tr>
<td>{$row['payment_id']}</td>
<td>{$row['rental_id']}</td>
<td>{$row['status']}</td>
<td>{$row['method']}</td>
</tr>";
}
?>

</table>

<h3>Total Revenue</h3>

<?php
$res2 = $conn->query("
SELECT SUM(e.pay_per_day) AS total_revenue
FROM Payment p
JOIN Rental r ON p.rental_id = r.rental_id
JOIN Equipment e ON r.equipment_id = e.e_id
WHERE p.status = 'Paid'
");

$row2 = $res2->fetch_assoc();
echo "Total Revenue: ".$row2['total_revenue'];
?>

</div>

<?php include '../footer.php'; ?>