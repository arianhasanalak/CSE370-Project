<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];

echo "<h2>Users</h2>";

// ADMIN: can see all users
if ($role == 'admin') {

    $res = $conn->query("
    SELECT u.user_id, u.name, u.email,
    CASE WHEN a.user_id IS NOT NULL THEN 'Admin' ELSE 'Customer' END AS role
    FROM User u
    LEFT JOIN Admin a ON u.user_id = a.user_id
    ");

    echo "<a href='add.php'>Add User</a><br><br>";

    while($row = $res->fetch_assoc()) {
        echo $row['name']." (".$row['role'].") - ".$row['email'];
        echo " | <a href='edit.php?id=".$row['user_id']."'>Edit</a>";
        echo " | <a href='delete.php?id=".$row['user_id']."'>Delete</a><br>";
    }
}

// CUSTOMER: can see only own profile
else {

    $res = $conn->query("SELECT * FROM User WHERE user_id=$uid");
    $row = $res->fetch_assoc();

    echo "<b>Your Profile</b><br>";
    echo $row['name']." - ".$row['email'];

    echo " | <a href='edit.php?id=".$row['user_id']."'>Edit</a>";
    echo " | <a href='delete.php?id=".$row['user_id']."'>Delete</a>";
}
?>