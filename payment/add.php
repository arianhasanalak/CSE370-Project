<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}


if ($_SESSION['role'] != 'admin') {
    echo "<div class='card'><h3>Access Denied</h3></div>";
    include '../footer.php';
    exit();
}

if ($_POST) {

    $rental = $_POST['rental'];
    $status = $_POST['status'];
    $method = $_POST['method'];

    $conn->query("INSERT INTO Payment(rental_id,status,method)
                  VALUES($rental,'$status','$method')");

    $res = $conn->query("SELECT customer_id FROM Rental WHERE rental_id=$rental");
    $row = $res->fetch_assoc();
    $customer_id = $row['customer_id'];

    $message = "Your payment for Rental ID $rental is $status";

    $conn->query("INSERT INTO Notification(message, date, status, customer_id)
                  VALUES('$message', NOW(), 'Unread', $customer_id)");

    echo "<p style='color:green;'>Payment added and notification sent!</p>";
}

$rentals = $conn->query("SELECT rental_id FROM Rental");
?>

<div class="card">
<h2>Add Payment</h2>

<form method="POST">

<label>Rental</label>
<select name="rental">
<?php while($r = $rentals->fetch_assoc()) { ?>
<option value="<?php echo $r['rental_id']; ?>">
Rental ID: <?php echo $r['rental_id']; ?>
</option>
<?php } ?>
</select>

<label>Status</label>
<select name="status">
<option value="Paid">Paid</option>
<option value="Pending">Pending</option>
</select>

<label>Method</label>
<select name="method">
<option>Cash</option>
<option>Card</option>
<option>Online</option>
</select>

<br><br>
<button type="submit">Add Payment</button>

</form>

<br>
<a href="view.php">Back to Payments</a>

</div>

<?php include '../footer.php'; ?>