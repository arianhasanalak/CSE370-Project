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

<h2>Notifications</h2>

<table>

<tr>
<th>Message</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php

// ================= ADMIN =================
if ($role == 'admin') {

    $res = $conn->query("
    SELECT *
    FROM notification
    ORDER BY notification_id DESC
    ");
}

// ================= CUSTOMER =================
else {

    $res = $conn->query("
    SELECT *
    FROM notification
    WHERE user_id=$uid
    ORDER BY notification_id DESC
    ");
}

// ================= DISPLAY =================
while($row = $res->fetch_assoc()) {

echo "<tr>

<td>{$row['message']}</td>

<td>{$row['date']}</td>

<td>{$row['status']}</td>

<td>";

// ================= MARK AS READ =================
if ($row['status'] == 'Unread') {

    echo "
    <a href='read.php?id={$row['notification_id']}'>
    <button>Mark as Read</button>
    </a>

    <br><br>
    ";
}

// ================= DELETE =================
echo "
<a href='delete.php?id={$row['notification_id']}'>
<button>Delete</button>
</a>
";

echo "
</td>

</tr>";
}
?>

</table>

</div>

<?php include '../footer.php'; ?>