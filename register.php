<?php
include 'db.php';

if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    $conn->query("INSERT INTO user(name,email,password,phone)
                  VALUES('$name','$email','$password','$phone')");

    $user_id = $conn->insert_id;

    $conn->query("INSERT INTO customer(user_id) VALUES($user_id)");

    header("Location: login.php");
}

include 'header.php';
?>

<div class="card">
<h2>Register</h2>

<form method="POST">
<input name="name" placeholder="Name">
<input name="email" placeholder="Email">
<input type="password" name="password" placeholder="Password">
<input name="phone" placeholder="Phone">
<button>Register</button>
</form>
</div>

<?php include 'footer.php'; ?>