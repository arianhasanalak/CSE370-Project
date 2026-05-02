<?php include '../db.php';

$id = $_GET['id'];

if ($_POST) {
    $name = $_POST['name'];

    $conn->query("UPDATE Category SET category_name='$name' WHERE category_id=$id");

    header("Location: view.php");
}

$result = $conn->query("SELECT * FROM Category WHERE category_id=$id");
$row = $result->fetch_assoc();
?>

<h2>Edit Category</h2>
<form method="POST">
Name: <input name="name" value="<?php echo $row['category_name']; ?>"><br>
<button>Update</button>
</form>