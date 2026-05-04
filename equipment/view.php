<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$role = $_SESSION['role'];
$category_id = $_GET['category_id'] ?? null;

// ================= GET CATEGORY NAME (BONUS) =================
$category_name = "";

if ($category_id) {
    $c = $conn->query("SELECT category_name FROM category WHERE category_id=$category_id");
    if ($c && $c->num_rows > 0) {
        $crow = $c->fetch_assoc();
        $category_name = $crow['category_name'];
    }
}
?>

<div class="card">

<h2>Equipment</h2>

<?php
// SHOW CATEGORY NAME
if ($category_id && $category_name) {
    echo "<h3>Category: $category_name</h3>";
}
?>

<a href="../category/view.php"><button>Back to Categories</button></a><br><br>

<?php
// ================= QUERY =================
if ($category_id) {

    $res = $conn->query("
    SELECT e.e_id, e.name, e.pay_per_day, c.category_name
    FROM equipment e
    JOIN category c ON e.category_id = c.category_id
    WHERE e.category_id = $category_id
    ");

} else {

    $res = $conn->query("
    SELECT e.e_id, e.name, e.pay_per_day, c.category_name
    FROM equipment e
    JOIN category c ON e.category_id = c.category_id
    ");
}
?>

<table>
<tr>
<th>Name</th>
<th>Category</th>
<th>Price</th>
<th>Action</th>
</tr>

<?php
while($r = $res->fetch_assoc()){

echo "<tr>
<td>{$r['name']}</td>
<td>{$r['category_name']}</td>
<td>{$r['pay_per_day']}</td>
<td>";

if ($role == 'admin') {
    echo "<a href='edit.php?id={$r['e_id']}'>Edit</a> |
          <a href='delete.php?id={$r['e_id']}'>Delete</a>";
} else {
    echo "<a href='../rental/add.php?equipment_id={$r['e_id']}'><button>Rent</button></a>";
}

echo "</td></tr>";
}
?>

</table>
</div>

<?php include '../footer.php'; ?>