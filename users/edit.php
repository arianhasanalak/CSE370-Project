<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];
$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Customer can only edit own account
if ($role != 'admin' && $uid != $id) {
    echo "<div class='card'><h3>Access Denied</h3></div>";
    include '../footer.php';
    exit();
}

// Update
if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $conn->query("UPDATE User 
                  SET name='$name', email='$email', phone='$phone' 
                  WHERE user_id=$id");

    header("Location: view.php");
    exit();
}

// Fetch data
$res = $conn->query("SELECT * FROM User WHERE user_id=$id");
$row = $res->fetch_assoc();
?>

<div class="card">
<h2>Edit User</h2>

<form method="POST">
<input name="name" value="<?php echo $row['name']; ?>">
<input name="email" value="<?php echo $row['email']; ?>">
<input name="phone" value="<?php echo $row['phone']; ?>">

<button>Update</button>
</form>

<br>
<a href="view.php">Back</a>
</div>

<?php include '../footer.php'; ?>