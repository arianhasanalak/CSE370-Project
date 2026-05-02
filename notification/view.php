<?php
session_start();
include '../db.php';

$uid=$_SESSION['user_id'];

$res=$conn->query("SELECT customer_id FROM Customer WHERE user_id=$uid");
$row=$res->fetch_assoc();
$cid=$row['customer_id'];

$n=$conn->query("SELECT * FROM Notification WHERE customer_id=$cid");

while($x=$n->fetch_assoc()){
echo $x['message']." (".$x['status'].")<br>";

$conn->query("UPDATE Notification SET status='Read' WHERE notification_id=".$x['notification_id']);
}
?>