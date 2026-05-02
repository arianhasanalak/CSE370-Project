<?php include '../db.php';

$res=$conn->query("SELECT * FROM Equipment");

echo "<a href='add.php'>Add Equipment</a><br><br>";

while($r=$res->fetch_assoc()){
echo $r['name']." - ".$r['pay_per_day'];
echo " | <a href='edit.php?id=".$r['e_id']."'>Edit</a>";
echo " | <a href='delete.php?id=".$r['e_id']."'>Delete</a><br>";
}
?>