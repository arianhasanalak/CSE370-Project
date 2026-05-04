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
<h2>Equipment Ratings</h2>

<table>
<tr>
<th>Equipment</th>
<th>Average Rating</th>
</tr>

<?php
$res = $conn->query("
SELECT e.name, AVG(r.rating) AS avg_rating
FROM Review r
JOIN Equipment e ON r.equipment_id = e.e_id
GROUP BY e.name
");

while($row = $res->fetch_assoc()) {
echo "<tr>
<td>{$row['name']}</td>
<td>{$row['avg_rating']}</td>
</tr>";
}
?>

</table>

</div>

<?php include '../footer.php'; ?>