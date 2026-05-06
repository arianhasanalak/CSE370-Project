<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$rental = $_GET['rental_id'] ?? null;

if (!$rental) {
    echo "<div class='card'><h3>Invalid rental.</h3></div>";
    include '../footer.php';
    exit();
}

$info = $conn->query("
SELECT
    r.rental_date,
    r.return_date,
    e.pay_per_day,
    e.name AS equipment

FROM rental r
JOIN equipment e ON r.equipment_id = e.e_id
WHERE r.rental_id = $rental
");

$data = $info->fetch_assoc();

$days = (strtotime($data['return_date']) - strtotime($data['rental_date'])) / (60 * 60 * 24);

if ($days <= 0) {
    $days = 1;
}

$total = $days * $data['pay_per_day'];

if ($_POST) {

    $status = $_POST['status'];
    $method = $_POST['method'];

    $conn->query("
    INSERT INTO payment(rental_id, status, method, total_cost)
    VALUES($rental, '$status', '$method', '$total')
    ");

    $res = $conn->query("SELECT customer_id, equipment_id FROM rental WHERE rental_id=$rental");
    $row = $res->fetch_assoc();

    $customer_id = $row['customer_id'];
    $equipment_id = $row['equipment_id'];

    $message = "Payment successful for Rental ID $rental";

    $conn->query("
    INSERT INTO notification(customer_id, message, date, status)
    VALUES($customer_id, '$message', NOW(), 'Unread')
    ");

    $conn->query("UPDATE rental SET status='Completed' WHERE rental_id=$rental");

    $conn->query("UPDATE equipment SET availability='Available' WHERE e_id=$equipment_id");

    echo "<p class='success'>Payment successful!</p>";
}
?>

<div class="card">
<h2>Make Payment</h2>

<p><b>Equipment:</b> <?php echo $data['equipment']; ?></p>
<p><b>Total Cost:</b> <?php echo $total; ?></p>

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

</div>

<?php include '../footer.php'; ?>