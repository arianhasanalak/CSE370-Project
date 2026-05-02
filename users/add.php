<?php
include '../db.php';

if ($_POST) {
    $name=$_POST['name'];
    $email=$_POST['email'];
    $pass=$_POST['password'];
    $phone=$_POST['phone'];
    $role=$_POST['role'];

    $conn->query("INSERT INTO User(name,email,password,phone)
    VALUES('$name','$email','$pass','$phone')");

    $id = $conn->insert_id;

    if ($role=='admin') {
        $conn->query("INSERT INTO Admin(user_id) VALUES($id)");
    } else {
        $conn->query("INSERT INTO Customer(user_id) VALUES($id)");
    }

    header("Location:view.php");
}
?>

<form method="POST">
Name:<input name="name"><br>
Email:<input name="email"><br>
Password:<input name="password"><br>
Phone:<input name="phone"><br>
<select name="role">
<option value="customer">Customer</option>
<option value="admin">Admin</option>
</select>
<button>Add</button>
</form>