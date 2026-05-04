<?php
session_start();
include 'db.php';

if ($_POST) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $res = $conn->query("SELECT * FROM User WHERE email='$email' AND password='$password'");

    if ($res->num_rows == 1) {
        $user = $res->fetch_assoc();

        $_SESSION['user_id'] = $user['user_id'];

        $uid = $user['user_id'];

        $admin = $conn->query("SELECT * FROM Admin WHERE user_id=$uid");

        if ($admin->num_rows == 1) {
            $_SESSION['role'] = 'admin';
        } else {
            $_SESSION['role'] = 'customer';
        }

        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid email or password";
    }
}

include 'header.php';
?>

<div class="card">
<h2>Login</h2>

<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST">
<input name="email" placeholder="Email">
<input type="password" name="password" placeholder="Password">
<button>Login</button>
</form>

<br>
<a href="register.php">Register as Customer</a>
</div>

<?php include 'footer.php'; ?>