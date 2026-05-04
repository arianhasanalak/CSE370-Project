<?php
session_start();
include '../db.php';
include '../header.php';
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
$res = $conn->query("SELECT * FROM category");

while($row = $res->fetch_assoc()) {

echo "<tr>
<td>{$row['category_name']}</td>
<td>
<a href='../equipment/view.php?category_id={$row['category_id']}'>
<button>View Equipment</button>
</a>
</td>
</tr>";
}
?>

</table>
</div>

<?php include '../footer.php'; ?>