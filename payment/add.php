<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// only admin OR allow customer (your choice)
$role = $_SESSION['role'];

// get rental from URL
$rental = $_GET['rental_id'] ?? '';

if (!$rental) {
    echo "<div class='card'><h3>No rental selected</h3></div>";
    include '../footer.php';
    exit();
}

if ($_POST) {

    $status = $_POST['status'];
    $method = $_POST['method'];

    // insert payment
    $conn->query("
    INSERT INTO payment(rental_id, status, method)
    VALUES($rental, '$status', '$method')
    ");

    // get customer_id
    $res = $conn->query("SELECT customer_id FROM rental WHERE rental_id=$rental");
    $row = $res->fetch_assoc();
    $customer_id = $row['customer_id'];

    // create notification
    $message = "Payment successful for Rental ID $rental";

    $conn->query("
    INSERT INTO notification(customer_id, message, date, status)
    VALUES($customer_id, '$message', NOW(), 'Unread')
    ");

    // update rental status (optional but good)
    $conn->query("UPDATE rental SET status='Completed' WHERE rental_id=$rental");

    echo "<p class='success'>Payment successful and notification sent!</p>";
}
?>

<div class="card">
<h2>Make Payment</h2>

<p>Rental ID: <?php echo $rental; ?></p>

<form method="POST">

<label>Status</label>
<select name="status">
<option value="Paid">Paid</option>
</select>

<label>Method</label>
<select name="method">
<option>Cash</option>
<option>Card</option>
<option>Online</option>
</select>

<br><br>
<button>Pay Now</button>

</form>

<br>
<a href="../rental/view.php">Back</a>

</div>

<?php include '../footer.php'; ?>