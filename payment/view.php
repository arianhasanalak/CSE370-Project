<?php include '../db.php';

$sql = "
SELECT p.payment_id, p.status, r.rental_id
FROM Payment p
JOIN Rental r ON p.rental_id = r.rental_id
";

$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
    echo "Payment ".$row['payment_id']." - ".$row['status']."<br>";
}
?>