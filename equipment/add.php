<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$uid = $_SESSION['user_id'];

if ($_POST) {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $sql = "
    INSERT INTO equipment(name, pay_per_day, category_id, owner_id)
    VALUES('$name', '$price', '$category', '$uid')
    ";

    if ($conn->query($sql)) {
        header("Location: view.php");
        exit();
    } else {
        echo "<div class='error'>ERROR: " . $conn->error . "</div>";
    }
}
?>

<div class="card">
<h2>Add Equipment</h2>

<form method="POST">

<label>Equipment Name</label>
<input type="text" name="name" required>

<label>Price Per Day</label>
<input type="number" name="price" required>

<label>Category</label>

<select name="category">

<?php
$res = $conn->query("SELECT * FROM category");

while($row = $res->fetch_assoc()) {

    echo "
    <option value='{$row['category_id']}'>
    {$row['category_name']}
    </option>
    ";
}
?>

</select>

<br>
<button type="submit">Add Equipment</button>

</form>

</div>

<?php include '../footer.php'; ?>