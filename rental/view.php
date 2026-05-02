<?php include '../db.php';

echo "<a href='add.php'>Add Rental</a><br><br>";

$res=$conn->query("
SELECT u.name,e.name AS eq,r.status,r.rental_id
FROM Rental r
JOIN Customer c ON r.customer_id=c.customer_id
JOIN User u ON c.user_id=u.user_id
JOIN Equipment e ON r.equipment_id=e.e_id
");

while($r=$res->fetch_assoc()){
echo $r['name']." rented ".$r['eq']." (".$r['status'].")";
echo " | <a href='delete.php?id=".$r['rental_id']."'>Delete</a><br>";
}
?>