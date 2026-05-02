<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
}

echo "<h2>Welcome</h2>";

echo "Role: " . $_SESSION['role'] . "<br><br>";

echo "<a href='index.php'>Go to System</a><br>";
echo "<a href='logout.php'>Logout</a>";
?>
