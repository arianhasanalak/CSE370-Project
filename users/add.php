<?php
session_start();
include '../db.php';
include '../header.php';

if ($_SESSION['role'] != 'admin') {
    echo "Access denied";
    exit();
}

if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    $conn->query("INSERT INTO User(name,email,password,phone)
                  VALUES('$name','$email','$password','$phone')");

    $id = $conn->insert_id;

    if ($role == 'admin') {
        $conn->query("INSERT INTO Admin(user_id) VALUES($id)");
    } else {
        $conn->query("INSERT INTO Customer(user_id) VALUES($id)");
    }

    header("Location: view.php");
}
?>

<div class="card">
<h2>Add User</h2>

<form method="POST">
<input name="name" placeholder="Name">
<input name="email" placeholder="Email">
<input name="password" placeholder="Password">
<input name="phone" placeholder="Phone">

<select name="role">
<option value="customer">Customer</option>
<option value="admin">Admin</option>
</select>

<button>Add</button>
</form>
</div>

<?php include '../footer.php'; ?>