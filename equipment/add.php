<?php
session_start();
include '../db.php';
include '../header.php';

if ($_POST) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $conn->query("INSERT INTO Equipment(name,pay_per_day,category_id)
                  VALUES('$name','$price','$category')");

    header("Location: view.php");
}

$cat = $conn->query("SELECT * FROM Category");
?>

<div class="card">
<h2>Add Equipment</h2>

<form method="POST">
<input name="name" placeholder="Equipment Name">
<input name="price" placeholder="Price per day">

<select name="category">
<?php while($c = $cat->fetch_assoc()) { ?>
<option value="<?php echo $c['category_id']; ?>">
<?php echo $c['category_name']; ?>
</option>
<?php } ?>
</select>

<button>Add</button>
</form>
</div>

<?php include '../footer.php'; ?>