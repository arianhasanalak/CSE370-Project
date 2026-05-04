<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$uid = $_SESSION['user_id'];

$res = $conn->query("SELECT customer_id FROM Customer WHERE user_id=$uid");
$row = $res->fetch_assoc();
$cid = $row['customer_id'];

$n = $conn->query("SELECT * FROM Notification WHERE customer_id=$cid");
?>

<div class="card">
<h2>Notifications</h2>

<?php
while($x = $n->fetch_assoc()){
echo "<p>".$x['message']." (".$x['status'].")</p>";

$conn->query("UPDATE Notification SET status='Read' WHERE notification_id=".$x['notification_id']);
}
?>

</div>

<?php include '../footer.php'; ?>