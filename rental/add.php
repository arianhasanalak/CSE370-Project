<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$uid = $_SESSION['user_id'];

//GET CUSTOMER ID
$cres = $conn->query("
SELECT customer_id
FROM customer
WHERE user_id=$uid
");

$crow = $cres->fetch_assoc();

if (!$crow) {

    echo "
    <div class='card'>
    <h3>Customer account not found.</h3>
    </div>
    ";

    include '../footer.php';
    exit();
}

$customer_id = $crow['customer_id'];

//GET EQUIPMENT
$equipment_id = $_GET['equipment_id'] ?? '';

if (!$equipment_id) {

    echo "
    <div class='card'>
    <h3>No equipment selected.</h3>
    </div>
    ";

    include '../footer.php';
    exit();
}

// CHECK EQUIPMENT 
$check = $conn->query("
SELECT *
FROM equipment
WHERE e_id=$equipment_id
");

if ($check->num_rows == 0) {

    echo "
    <div class='card'>
    <h3>Invalid rental.</h3>
    </div>
    ";

    include '../footer.php';
    exit();
}

$equipment = $check->fetch_assoc();

//ALREADY RENTED
if ($equipment['availability'] == 'Rented') {

    echo "
    <div class='card'>
    <h3>Equipment already rented.</h3>
    </div>
    ";

    include '../footer.php';
    exit();
}

// FORM SUBMIT
if ($_POST) {

    $rental_date = $_POST['rental_date'];
    $return_date = $_POST['return_date'];

    // insert rental
    $conn->query("
    INSERT INTO rental(
        customer_id,
        equipment_id,
        rental_date,
        return_date,
        status
    )
    VALUES(
        $customer_id,
        $equipment_id,
        '$rental_date',
        '$return_date',
        'Pending'
    )
    ");

    // make unavailable
    $conn->query("
    UPDATE equipment
    SET availability='Rented'
    WHERE e_id=$equipment_id
    ");

    header("Location: view.php");
    exit();
}
?>

<div class="card">

<h2>Confirm Rental</h2>

<p>
Are you sure you want to rent:
<b><?php echo $equipment['name']; ?></b>?
</p>

<form method="POST">

<label>Rental Date</label>
<input type="date" name="rental_date" required>

<br><br>

<label>Return Date</label>
<input type="date" name="return_date" required>

<br><br>

<button type="submit">
Confirm Rental
</button>

</form>

<br>

<a href="../equipment/view.php">
<button>Cancel</button>
</a>

</div>

<?php include '../footer.php'; ?>