<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];

$res = $conn->query("
SELECT *
FROM equipment
WHERE e_id=$id
");

$row = $res->fetch_assoc();

if (!$row) {

    echo "
    <div class='card'>
        <h3>Equipment not found</h3>
    </div>
    ";

    include '../footer.php';
    exit();
}

//SECURITY CHECK 
if ($_SESSION['role'] != 'admin' &&
    $row['owner_id'] != $_SESSION['user_id']) {

    echo "
    <div class='card'>
        <h3>Access Denied</h3>
    </div>
    ";

    include '../footer.php';
    exit();
}

if ($_POST) {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $sql = "
    UPDATE equipment
    SET
        name='$name',
        pay_per_day='$price',
        category_id='$category'
    WHERE e_id=$id
    ";

    if ($conn->query($sql)) {

        header("Location: view.php");
        exit();

    } else {

        echo "
        <div class='error'>
        ERROR: " . $conn->error . "
        </div>
        ";
    }
}
?>

<div class="card">

<h2>Edit Equipment</h2>

<form method="POST">

<label>Equipment Name</label>

<input
type="text"
name="name"
value="<?php echo $row['name']; ?>"
required
>

<label>Price Per Day</label>

<input
type="number"
name="price"
value="<?php echo $row['pay_per_day']; ?>"
required
>

<label>Category</label>

<select name="category">

<?php

$cres = $conn->query("SELECT * FROM category");

while($c = $cres->fetch_assoc()) {

    $selected = "";

    if ($c['category_id'] == $row['category_id']) {
        $selected = "selected";
    }

    echo "
    <option value='{$c['category_id']}' $selected>
    {$c['category_name']}
    </option>
    ";
}
?>

</select>

<br><br>

<button type="submit">
Update Equipment
</button>

</form>

</div>

<?php include '../footer.php'; ?>