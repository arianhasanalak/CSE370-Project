<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<h2>Equipment Rental System</h2>

<p>Role: <?php echo $_SESSION['role']; ?></p>
<a href="logout.php">Logout</a>

<ul>
<li><a href="users/view.php">Users</a></li>
<li><a href="category/view.php">Categories</a></li>
<li><a href="equipment/view.php">Equipment</a></li>
<li><a href="rental/view.php">Rentals</a></li>
<li><a href="payment/view.php">Payments</a></li>
<li><a href="review/view.php">Reviews</a></li>
<li><a href="notification/view.php">Notifications</a></li>
</ul>