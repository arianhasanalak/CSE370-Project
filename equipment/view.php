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
<h2>Equipment</h2>

<a href="add.php"><button>Add Equipment</button></a><br><br>

<table>
<tr>
<th>Name</th>
<th>Category</th>
<th>Price</th>
<th>Action</th>
</tr>

<?php
$res = $conn->query("
SELECT e.e_id, e.name, e.pay_per_day, c.category_name
FROM Equipment e
JOIN Category c ON e.category_id = c.category_id
");

while($r = $res->fetch_assoc()){
echo "<tr>
<td>{$r['name']}</td>
<td>{$r['category_name']}</td>
<td>{$r['pay_per_day']}</td>
<td>
<a href='edit.php?id={$r['e_id']}'>Edit</a> |
<a href='delete.php?id={$r['e_id']}'>Delete</a>
</td>
</tr>";
}
?>

</table>
</div>

<?php include '../footer.php'; ?>