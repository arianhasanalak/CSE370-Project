<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$uid = $_SESSION['user_id'];

// get customer_id
$cres = $conn->query("
SELECT customer_id 
FROM customer 
WHERE user_id=$uid
");

$crow = $cres->fetch_assoc();

if (!$crow) {
    echo "<div class='card'><h3>Customer not found</h3></div>";
    include '../footer.php';
    exit();
}

$customer_id = $crow['customer_id'];

// get equipment_id
$equipment_id = $_GET['equipment_id'] ?? '';

if (!$equipment_id) {
    echo "<div class='card'><h3>No equipment selected</h3></div>";
    include '../footer.php';
    exit();
}

// check if customer rented this equipment before
$check = $conn->query("
SELECT *
FROM rental
WHERE customer_id=$customer_id
AND equipment_id=$equipment_id
AND status='Completed'
");

if ($check->num_rows == 0) {

    echo "
    <div class='card'>
        <h3>You can only review equipment you rented.</h3>
    </div>
    ";

    include '../footer.php';
    exit();
}

// submit review
if (isset($_POST['submit'])) {

    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $sql = "
    INSERT INTO review(customer_id, equipment_id, rating, comment)
    VALUES($customer_id, $equipment_id, '$rating', '$comment')
    ";

    if ($conn->query($sql)) {

        echo "
        <div class='card'>
            <h3>Review Added Successfully!</h3>
        </div>
        ";

    } else {

        echo "
        <div class='card'>
            <h3>ERROR: " . $conn->error . "</h3>
        </div>
        ";
    }
}
?>

<div class="card">

<h2>Add Review</h2>

<form method="POST">

<label>Rating</label>

<select name="rating" required>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
</select>

<label>Comment</label>
<input type="text" name="comment" required>

<br>

<button type="submit" name="submit">
Submit Review
</button>

</form>

</div>

<?php include '../footer.php'; ?>