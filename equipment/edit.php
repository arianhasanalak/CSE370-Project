<?php
session_start();
include '../db.php';
include '../header.php';

$id = $_GET['id'];

if ($_POST) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $conn->query("UPDATE Equipment 
                  SET name='$name', pay_per_day='$price', category_id='$category'
                  WHERE e_id=$id");

    header("Location: view.php");
    exit();
}

$res = $conn->query("SELECT * FROM Equipment WHERE e_id=$id");
$row = $res->fetch_assoc();

$cat = $conn->query("SELECT * FROM Category");
?>

<div class="card">
<h2>Edit Equipment</h2>

<form method="POST">
<input name="name" value="<?php echo $row['name']; ?>">
<input name="price" value="<?php echo $row['pay_per_day']; ?>">

<select name="category">
<?php while($c = $cat->fetch_assoc()) { ?>
<option value="<?php echo $c['category_id']; ?>"
<?php if($c['category_id'] == $row['category_id']) echo "selected"; ?>>
<?php echo $c['category_name']; ?>
</option>
<?php } ?>
</select>

<button>Update</button>
</form>

<br>
<a href="view.php">Back</a>
</div>

<?php include '../footer.php'; ?>