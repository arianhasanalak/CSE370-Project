<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli("localhost", "root", "", "equipment_rental");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>