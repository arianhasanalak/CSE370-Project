<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// admin only
if ($_SESSION['role'] != 'admin') {

    echo "
    <div class='card'>
        <h3>Access Denied</h3>
    </div>
    ";

    include '../footer.php';
    exit();
}

$id = $_GET['id'] ?? '';

if (!$id) {
    header("Location: view.php");
    exit();
}

// get category data
$res = $conn->query("
SELECT *
FROM category
WHERE category_id=$id
");

$row = $res->fetch_assoc();

if (!$row) {

    echo "
    <div class='card'>
        <h3>Category not found</h3>
    </div>
    ";

    include '../footer.php';
    exit();
}

// update
if ($_POST) {

    $name = $_POST['name'];

    $conn->query("
    UPDATE category
    SET category_name='$name'
    WHERE category_id=$id
    ");

    header("Location: view.php");
    exit();
}
?>

<div class="card">

<h2>Edit Category</h2>

<form method="POST">

<label>Category Name</label>

<input
type="text"
name="name"
value="<?php echo $row['category_name']; ?>"
required
>

<br><br>

<button type="submit">
Update Category
</button>

</form>

</div>

<?php include '../footer.php'; ?>