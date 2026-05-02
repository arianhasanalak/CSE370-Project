<?php include '../db.php';

$id = $_GET['id'];

if ($_POST) {
    $name = $_POST['name'];
    $price = $_POST['price'];

    $sql = "UPDATE Equipment SET name='$name', pay_per_day='$price' WHERE e_id=$id";
    $conn->query($sql);

    header("Location: view.php");
}

$result = $conn->query("SELECT * FROM Equipment WHERE e_id=$id");
$row = $result->fetch_assoc();
?>

<form method="POST">
Name: <input name="name" value="<?php echo $row['name']; ?>"><br>
Price: <input name="price" value="<?php echo $row['pay_per_day']; ?>"><br>
<button>Update</button>
</form>