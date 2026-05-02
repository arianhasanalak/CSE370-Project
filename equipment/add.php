<?php include '../db.php';

if($_POST){
$name=$_POST['name'];
$price=$_POST['price'];

$conn->query("INSERT INTO Equipment(name,pay_per_day)
VALUES('$name','$price')");

header("Location:view.php");
}
?>

<form method="POST">
Name:<input name="name"><br>
Price:<input name="price"><br>
<button>Add</button>
</form>