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
<h2>Categories</h2>

<a href="add.php"><button>Add Category</button></a><br><br>

<table>
<tr>
<th>Name</th>
<th>Action</th>
</tr>

<?php
$res = $conn->query("SELECT * FROM Category");

while($row = $res->fetch_assoc()) {
echo "<tr>
<td>{$row['category_name']}</td>
<td>
<a href='edit.php?id={$row['category_id']}'>Edit</a> |
<a href='delete.php?id={$row['category_id']}'>Delete</a>
</td>
</tr>";
}
?>

</table>

</div>

<?php include '../footer.php'; ?>
