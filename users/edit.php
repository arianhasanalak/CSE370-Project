<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];
$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];

// CUSTOMER can only edit own account
if ($role != 'admin' && $uid != $id) {
    echo "Access denied";
    exit();
}

if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $conn->query("UPDATE User 
                  SET name='$name', email='$email', phone='$phone' 
                  WHERE user_id=$id");

    header("Location: view.php");
}

$res = $conn->query("SELECT * FROM User WHERE user_id=$id");
$row = $res->fetch_assoc();
?>

<h2>Edit User</h2>
<form method="POST">
Name: <input name="name" value="<?php echo $row['name']; ?>"><br>
Email: <input name="email" value="<?php echo $row['email']; ?>"><br>
Phone: <input name="phone" value="<?php echo $row['phone']; ?>"><br>
<button>Update</button>
</form>