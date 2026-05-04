<?php
session_start();
include '../db.php';
include '../header.php';

$id = $_GET['id'];

if ($_POST) {
    $name = $_POST['name'];

    $conn->query("UPDATE Category SET category_name='$name' WHERE category_id=$id");

    header("Location: view.php");
    exit();
}

$res = $conn->query("SELECT * FROM Category WHERE category_id=$id");
$row = $res->fetch_assoc();
?>

<div class="card">
<h2>Edit Category</h2>

<form method="POST">
<input name="name" value="<?php echo $row['category_name']; ?>">
<button>Update</button>
</form>

<br>
<a href="view.php">Back</a>
</div>

<?php include '../footer.php'; ?>