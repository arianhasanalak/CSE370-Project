<?php include '../db.php';

$sql = "SELECT * FROM Category";
$result = $conn->query($sql);

echo "<h2>Categories</h2>";
echo "<a href='add.php'>Add Category</a><br><br>";

while($row = $result->fetch_assoc()) {
    echo $row['category_name'];

    echo " | <a href='edit.php?id=".$row['category_id']."'>Edit</a>";
    echo " | <a href='delete.php?id=".$row['category_id']."'>Delete</a><br>";
}
?>
