<?php include '../db.php';

if($_POST){
$c=$_POST['customer'];
$e=$_POST['equipment'];

$conn->query("INSERT INTO Rental(customer_id,equipment_id,status)
VALUES($c,$e,'Pending')");

header("Location:view.php");
}
?>

<form method="POST">
Customer ID:<input name="customer"><br>
Equipment ID:<input name="equipment"><br>
<button>Add</button>
</form>