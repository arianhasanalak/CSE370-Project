<?php
session_start();
include '../db.php';
include '../header.php';

if ($_POST) {
    $customer = $_POST['customer'];
    $equipment = $_POST['equipment'];

    $conn->query("INSERT INTO Rental(customer_id,equipment_id,status)
                  VALUES($customer,$equipment,'Pending')");

    header("Location: view.php");
}

$cust = $conn->query("
SELECT c.customer_id, u.name 
FROM Customer c 
JOIN User u ON c.user_id = u.user_id
");

$equip = $conn->query("SELECT * FROM Equipment");
?>

<div class="card">
<h2>Add Rental</h2>

<form method="POST">

<select name="customer">
<?php while($c = $cust->fetch_assoc()) { ?>
<option value="<?php echo $c['customer_id']; ?>">
<?php echo $c['name']; ?>
</option>
<?php } ?>
</select>

<select name="equipment">
<?php while($e = $equip->fetch_assoc()) { ?>
<option value="<?php echo $e['e_id']; ?>">
<?php echo $e['name']; ?>
</option>
<?php } ?>
</select>

<button>Add Rental</button>
</form>
</div>

<?php include '../footer.php'; ?>