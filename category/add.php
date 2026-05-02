<?php include '../db.php';

if ($_POST) {
    $name = $_POST['name'];

    $conn->query("INSERT INTO Category (category_name) VALUES ('$name')");

    header("Location: view.php");
}
?>

<h2>Add Category</h2>
<form method="POST">
Name: <input name="name"><br>
<button>Add</button>
</form>