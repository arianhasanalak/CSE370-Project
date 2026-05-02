<?php
session_start();
include 'db.php';

if ($_POST) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check user credentials
    $res = $conn->query("SELECT * FROM User WHERE email='$email' AND password='$password'");

    if ($res->num_rows == 1) {
        $user = $res->fetch_assoc();

        // Store user ID in session
        $_SESSION['user_id'] = $user['user_id'];

        $uid = $user['user_id'];

        // Check if admin
        $admin = $conn->query("SELECT * FROM Admin WHERE user_id=$uid");

        if ($admin->num_rows == 1) {
            $_SESSION['role'] = 'admin';
        } else {
            $_SESSION['role'] = 'customer';
        }

        // Redirect to main page
        header("Location: index.php");
        exit();
    } else {
        echo "<p style='color:red;'>Invalid email or password</p>";
    }
}
?>

<h2>Login</h2>

<form method="POST">
    Email: <input type="text" name="email"><br><br>
    Password: <input type="password" name="password"><br><br>
    <button type="submit">Login</button>
</form>

<br>

<a href="register.php">Register as Customer</a>