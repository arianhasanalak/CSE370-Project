<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$uid = $_SESSION['user_id'];

// get customer_id
$res = $conn->query("SELECT customer_id FROM customer WHERE user_id=$uid");
$row = $res->fetch_assoc();

if (!$row) {
    echo "<div class='card'><h3>Customer not found!</h3></div>";
    include '../footer.php';
    exit();
}

$customer_id = $row['customer_id'];

// get equipment_id
$equipment_id = $_GET['equipment_id'] ?? '';

if (!$equipment_id) {
    echo "<div class='card'><h3>No equipment selected</h3></div>";
    include '../footer.php';
    exit();
}

// today's date (for restriction)
$today = date('Y-m-d');

// ================= SUBMIT =================
if (isset($_POST['confirm'])) {

    $rental_date = $_POST['rental_date'];
    $return_date = $_POST['return_date'];

    // validation
    if (!$rental_date || !$return_date) {
        echo "<div class='error'>Please select both dates</div>";
    } elseif ($rental_date < $today) {
        echo "<div class='error'>Rental date cannot be in the past</div>";
    } elseif ($return_date < $rental_date) {
        echo "<div class='error'>Return date must be after rental date</div>";
    } else {

        $sql = "INSERT INTO rental(customer_id, equipment_id, rental_date, return_date, status)
                VALUES($customer_id, $equipment_id, '$rental_date', '$return_date', 'Pending')";

        if ($conn->query($sql)) {

            $rental_id = $conn->insert_id;

            header("Location: ../payment/add.php?rental_id=$rental_id");
            exit();

        } else {
            echo "<div class='error'>ERROR: " . $conn->error . "</div>";
        }
    }
}
?>

<div class="card">
<h2>Confirm Rental</h2>

<form method="POST">

<label>Rental Date</label>
<input type="date" name="rental_date" min="<?php echo $today; ?>" required>

<label>Return Date</label>
<input type="date" name="return_date" min="<?php echo $today; ?>" required>

<br>
<button type="submit" name="confirm">Confirm Rental</button>

</form>

<br>
<a href="../equipment/view.php">Cancel</a>

</div>

<?php include '../footer.php'; ?>