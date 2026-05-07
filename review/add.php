<?php
session_start();
include '../db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$uid = $_SESSION['user_id'];

$equipment_id = $_GET['equipment_id'] ?? '';

if (!$equipment_id) {

    echo "
    <div class='card'>
        <h3>No equipment selected</h3>
    </div>
    ";

    include '../footer.php';
    exit();
}


if ($_POST) {

    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $conn->query("
    INSERT INTO review(equipment_id, user_id, rating, comment)
    VALUES($equipment_id, $uid, '$rating', '$comment')
    ");

    header("Location: view.php");
    exit();
}
?>

<div class="card">

<h2>Add Review</h2>

<form method="POST">

<label>Rating</label>

<select name="rating" required>

<option value="">Select Rating</option>

<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>

</select>

<br><br>

<label>Comment</label>

<textarea
name="comment"
rows="5"
required
></textarea>

<br><br>

<button type="submit">
Submit Review
</button>

</form>

</div>

<?php include '../footer.php'; ?>