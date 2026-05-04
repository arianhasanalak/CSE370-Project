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

// ================= ADMIN =================
if ($role == 'admin') {

    $res = $conn->query("
    SELECT u.user_id, u.name, u.email, u.phone,
    CASE WHEN a.user_id IS NOT NULL THEN 'Admin' ELSE 'Customer' END AS role
    FROM user u
    LEFT JOIN admin a ON u.user_id = a.user_id
    ");

    echo "<a href='add.php'><button>Add User</button></a><br><br>";

    echo "<table>
    <tr><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Action</th></tr>";

    while($row = $res->fetch_assoc()) {
        echo "<tr>
        <td>{$row['name']}</td>
        <td>{$row['email']}</td>
        <td>{$row['phone']}</td>
        <td>{$row['role']}</td>
        <td>
        <a href='edit.php?id={$row['user_id']}'>Edit</a> |
        <a href='delete.php?id={$row['user_id']}'>Delete</a>
        </td>
        </tr>";
    }

    echo "</table>";
}


// ================= CUSTOMER =================
else {

    $res = $conn->query("SELECT * FROM user WHERE user_id=$uid");
    $row = $res->fetch_assoc();

    echo "<h3>Your Profile</h3>";

    echo "<table>
    <tr><th>Field</th><th>Value</th></tr>
    <tr><td>Name</td><td>{$row['name']}</td></tr>
    <tr><td>Email</td><td>{$row['email']}</td></tr>
    <tr><td>Phone</td><td>{$row['phone']}</td></tr>
    </table>";

    echo "<br>
    <a href='edit.php?id={$row['user_id']}'><button>Edit Profile</button></a>";
}
?>

</div>

<?php include '../footer.php'; ?>