<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$uid = $_SESSION['user_id'];

$rental_id = $_GET['rental_id'] ?? '';

if (!$rental_id) {

    echo "
    <div class='card'>
        <h3>Invalid Rental</h3>
    </div>
    ";

    include '../footer.php';
    exit();
}

// GET RENTAL 
$res = $conn->query("
SELECT 
    r.rental_id,
    r.rental_date,
    r.return_date,
    r.equipment_id,
    e.name AS equipment,
    e.pay_per_day

FROM rental r

JOIN equipment e
ON r.equipment_id = e.e_id

WHERE r.rental_id = $rental_id
");

if ($res->num_rows == 0) {

    echo "
    <div class='card'>
        <h3>Rental not found</h3>
    </div>
    ";

    include '../footer.php';
    exit();
}

$data = $res->fetch_assoc();

//CALCULATE DAYS
$start = strtotime($data['rental_date']);
$end = strtotime($data['return_date']);

$days = ($end - $start) / (60 * 60 * 24);

if ($days <= 0) {
    $days = 1;
}

// TOTAL COST
$total = $days * $data['pay_per_day'];

//PAYMENT SUBMIT
if ($_POST) {

    $status = $_POST['status'];
    $method = $_POST['method'];

    //  INSERT PAYMENT 
    $conn->query("
    INSERT INTO payment(
        status,
        method,
        rental_id,
        total_cost
    )
    VALUES(
        '$status',
        '$method',
        $rental_id,
        $total
    )
    ");

    // CREATE NOTIFICATION 
    $message = "
    Payment completed for {$data['equipment']}.
    Total Cost: $total
    ";

    $conn->query("
    INSERT INTO notification(
        message,
        date,
        status,
        user_id
    )
    VALUES(
        '$message',
        CURDATE(),
        'Unread',
        $uid
    )
    ");

    //  COMPLETE RENTAL 
    $conn->query("
    UPDATE rental
    SET status='Completed'
    WHERE rental_id=$rental_id
    ");

    // MAKE EQUIPMENT AVAILABLE 
    $conn->query("
    UPDATE equipment
    SET availability='Available'
    WHERE e_id={$data['equipment_id']}
    ");

    header("Location: view.php");
    exit();
}
?>

<div class="card">

<h2>Payment</h2>

<p>
<b>Equipment:</b>
<?php echo $data['equipment']; ?>
</p>

<p>
<b>Rental Days:</b>
<?php echo $days; ?>
</p>

<p>
<b>Total Cost:</b>
<?php echo $total; ?>
</p>

<form method="POST">

<label>Status</label>

<select name="status" required>
<option value="Paid">Paid</option>
</select>

<br><br>

<label>Payment Method</label>

<select name="method" required>

<option value="">Select Method</option>

<option value="Cash">Cash</option>
<option value="Card">Card</option>
<option value="Online">Online</option>

</select>

<br><br>

<button type="submit">
Confirm Payment
</button>

</form>

</div>

<?php include '../footer.php'; ?>