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

$category_id = $_GET['category_id'] ?? null;
$search = $_GET['search'] ?? '';

//CATEGORY NAME
$category_name = "";

if ($category_id) {

    $c = $conn->query("
    SELECT category_name
    FROM category
    WHERE category_id=$category_id
    ");

    if ($c && $c->num_rows > 0) {

        $crow = $c->fetch_assoc();
        $category_name = $crow['category_name'];
    }
}
?>

<div class="card">

<h2>Equipment</h2>

<?php
if ($category_id && $category_name) {
    echo "<h3>Category: $category_name</h3>";
}
?>

<a href="../category/view.php">
<button>Back to Categories</button>
</a>

<br><br>

<a href="add.php">
<button>Add Equipment</button>
</a>

<br><br>

<!-- SEARCH -->
<form method="GET">

<?php
if ($category_id) {
    echo "<input type='hidden' name='category_id' value='$category_id'>";
}
?>

<input
type="text"
name="search"
placeholder="Search equipment..."
value="<?php echo $search; ?>"
>

<button type="submit">Search</button>

</form>

<br>

<?php

//QUERY
$query = "
SELECT 
    e.e_id,
    e.name,
    e.pay_per_day,
    e.owner_id,
    e.availability,
    c.category_name

FROM equipment e

JOIN category c
ON e.category_id = c.category_id

WHERE 1=1
";

if ($category_id) {
    $query .= " AND e.category_id = $category_id";
}

if ($search) {
    $query .= " AND e.name LIKE '%$search%'";
}

$res = $conn->query($query);
?>

<table>

<tr>
<th>Name</th>
<th>Category</th>
<th>Price Per Day</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php

while($r = $res->fetch_assoc()) {

echo "<tr>

<td>{$r['name']}</td>

<td>{$r['category_name']}</td>

<td>{$r['pay_per_day']}</td>

<td>{$r['availability']}</td>

<td>";

//ADMIN
if ($role == 'admin') {

    echo "
    <a href='edit.php?id={$r['e_id']}'>Edit</a>
    |
    <a href='delete.php?id={$r['e_id']}'>Delete</a>
    ";
}

//CUSTOMER
else {

    // RENT BUTTON
    if ($r['availability'] == 'Available') {

        echo "
        <a href='../rental/add.php?equipment_id={$r['e_id']}'>
        <button>Rent</button>
        </a>
        ";

    } else {

        echo "Currently Rented";
    }

    // REVIEW BUTTON
    echo "
    <br><br>

    <a href='../review/add.php?equipment_id={$r['e_id']}'>
    <button>Review</button>
    </a>
    ";

    // OWNER CONTROLS
    if ($r['owner_id'] == $uid) {

        echo "
        <br><br>

        <a href='edit.php?id={$r['e_id']}'>
        <button>Edit My Equipment</button>
        </a>

        <br><br>

        <a href='delete.php?id={$r['e_id']}'>
        <button>Delete My Equipment</button>
        </a>
        ";
    }
}

echo "</td>
</tr>";

//SHOW REVIEWS
$reviews = $conn->query("
SELECT 
    u.name,
    rv.rating,
    rv.comment

FROM review rv

JOIN user u
ON rv.user_id = u.user_id

WHERE rv.equipment_id = {$r['e_id']}
");

if ($reviews->num_rows > 0) {

    echo "
    <tr>
    <td colspan='5'>
    <b>Reviews:</b><br>
    ";

    while($rev = $reviews->fetch_assoc()) {

        echo "
        <p>
        <b>{$rev['name']}</b>
        ({$rev['rating']}/5):
        {$rev['comment']}
        </p>
        ";
    }

    echo "</td></tr>";
}
}
?>

</table>

</div>

<?php include '../footer.php'; ?>