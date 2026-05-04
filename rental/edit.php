<?php
session_start();
include '../db.php';
include '../header.php';

$id = $_GET['id'];

if ($_POST) {
    $status = $_POST['status'];

    $conn->query("UPDATE Rental SET status='$status' WHERE rental_id=$id");

    header("Location: view.php");
    exit();
}

$res = $conn->query("SELECT * FROM Rental WHERE rental_id=$id");
$row = $res->fetch_assoc();
?>

<div class="card">
<h2>Edit Rental</h2>

<form method="POST">

<select name="status">
<option <?php if($row['status']=="Pending") echo "selected"; ?>>Pending</option>
<option <?php if($row['status']=="Approved") echo "selected"; ?>>Approved</option>
<option <?php if($row['status']=="Completed") echo "selected"; ?>>Completed</option>
</select>

<button>Update</button>
</form>

<br>
<a href="view.php">Back</a>
</div>

<?php include '../footer.php'; ?>