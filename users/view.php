<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];
?>

<div class="card">
<h2>Users</h2>

<?php

if ($role == 'admin') {

    $res = $conn->query("
    SELECT u.user_id, u.name, u.email,
    CASE WHEN a.user_id IS NOT NULL THEN 'Admin' ELSE 'Customer' END AS role
    FROM User u
    LEFT JOIN Admin a ON u.user_id = a.user_id
    ");

    echo "<a href='add.php'><button>Add User</button></a><br><br>";

    echo "<table>
    <tr><th>Name</th><th>Email</th><th>Role</th><th>Action</th></tr>";

    while($row = $res->fetch_assoc()) {
        echo "<tr>
        <td>{$row['name']}</td>
        <td>{$row['email']}</td>
        <td>{$row['role']}</td>
        <td>
        <a href='edit.php?id={$row['user_id']}'>Edit</a> |
        <a href='delete.php?id={$row['user_id']}'>Delete</a>
        </td>
        </tr>";
    }

    echo "</table>";

    // GROUP BY
    echo "<h3>Total Rentals per Customer</h3>";
    $res2 = $conn->query("
    SELECT u.name, COUNT(r.rental_id) AS total_rentals
    FROM Rental r
    JOIN Customer c ON r.customer_id = c.customer_id
    JOIN User u ON c.user_id = u.user_id
    GROUP BY u.name
    ");

    while($row = $res2->fetch_assoc()) {
        echo $row['name']." - ".$row['total_rentals']." rentals<br>";
    }

    // LEFT JOIN
    echo "<h3>Inactive Customers</h3>";
    $res3 = $conn->query("
    SELECT u.name
    FROM User u
    JOIN Customer c ON u.user_id = c.user_id
    LEFT JOIN Rental r ON c.customer_id = r.customer_id
    WHERE r.rental_id IS NULL
    ");

    while($row = $res3->fetch_assoc()) {
        echo $row['name']."<br>";
    }

} else {

    $res = $conn->query("SELECT * FROM User WHERE user_id=$uid");
    $row = $res->fetch_assoc();

    echo "<p><b>Your Profile</b></p>";
    echo $row['name']." - ".$row['email'];
}
?>

</div>

<?php include '../footer.php'; ?>