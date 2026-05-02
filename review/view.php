<?php include '../db.php';

$sql = "
SELECT u.name, e.name AS equipment, r.rating
FROM Review r
JOIN Customer c ON r.customer_id = c.customer_id
JOIN User u ON c.user_id = u.user_id
JOIN Equipment e ON r.equipment_id = e.e_id
";

$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
    echo $row['name']." rated ".$row['equipment']." (".$row['rating'].")<br>";
}
?>