<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'header.php';
?>

<div class="card">
<h2>Dashboard</h2>

<p><b>Role:</b> <?php echo $_SESSION['role']; ?></p>

<a href="users/view.php">Users</a><br>
<a href="category/view.php">Categories</a><br>
<a href="equipment/view.php">Equipment</a><br>
<a href="rental/view.php">Rentals</a><br>
<a href="payment/view.php">Payments</a><br>
<a href="review/view.php">Reviews</a><br>
<a href="notification/view.php">Notifications</a><br>
<br>
<a href="logout.php">Logout</a>
</div>

<?php include 'footer.php'; ?>