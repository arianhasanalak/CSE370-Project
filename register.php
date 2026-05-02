<?php
include 'db.php';

if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    // Insert into User
    $conn->query("INSERT INTO User(name,email,password,phone)
                  VALUES('$name','$email','$password','$phone')");

    $user_id = $conn->insert_id;

    // Insert into Customer
    $conn->query("INSERT INTO Customer(user_id) VALUES($user_id)");

    echo "Registration successful! <a href='login.php'>Login</a>";
}
?>

<h2>Register</h2>
<form method="POST">
Name: <input name="name"><br>
Email: <input name="email"><br>
Password: <input type="password" name="password"><br>
Phone: <input name="phone"><br>
<button>Register</button>
</form>