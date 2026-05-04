<?php
session_start();
include '../db.php';
include '../header.php';

if ($_POST) {
    $name = $_POST['name'];

    $conn->query("INSERT INTO Category(category_name) VALUES('$name')");

    header("Location: view.php");
}
?>

<div class="card">
<h2>Add Category</h2>

<form method="POST">
<input name="name" placeholder="Category Name">
<button>Add</button>
</form>
</div>

<?php include '../footer.php'; ?>