<?php
session_start();
include 'db.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];

echo "<div class='container'>";
echo "<h2>Dashboard</h2>";

/* ================= ADMIN DASHBOARD ================= */
if ($role == 'admin') {

    $totalRentals = $conn->query("SELECT COUNT(*) as t FROM rental")->fetch_assoc()['t'];
    $revenue = $conn->query("SELECT COUNT(*) as t FROM payment WHERE status='Paid'")->fetch_assoc()['t'];

    echo "
    <div class='dashboard'>
        <div class='card stat'>
            <h3>Total Rentals</h3>
            <p>$totalRentals</p>
        </div>

        <div class='card stat'>
            <h3>Payments Done</h3>
            <p>$revenue</p>
        </div>
    </div>
    ";

    echo "<div class='card'><h3>Recent Rentals</h3>";

    $res = $conn->query("
    SELECT u.name, e.name AS equipment, r.status
    FROM rental r
    JOIN customer c ON r.customer_id = c.customer_id
    JOIN user u ON c.user_id = u.user_id
    JOIN equipment e ON r.equipment_id = e.e_id
    ORDER BY r.rental_id DESC LIMIT 5
    ");

    echo "<table><tr><th>User</th><th>Equipment</th><th>Status</th></tr>";

    while($row = $res->fetch_assoc()) {
        echo "<tr>
        <td>{$row['name']}</td>
        <td>{$row['equipment']}</td>
        <td>{$row['status']}</td>
        </tr>";
    }

    echo "</table></div>";
}

/* ================= CUSTOMER DASHBOARD ================= */
else {

    // get customer_id
    $c = $conn->query("SELECT customer_id FROM customer WHERE user_id=$uid");
    $crow = $c->fetch_assoc();
    $customer_id = $crow['customer_id'];

    $myRentals = $conn->query("SELECT COUNT(*) as t FROM rental WHERE customer_id=$customer_id")->fetch_assoc()['t'];
    $pending = $conn->query("SELECT COUNT(*) as t FROM rental WHERE customer_id=$customer_id AND status!='Completed'")->fetch_assoc()['t'];

    echo "
    <div class='dashboard'>
        <div class='card stat'>
            <h3>My Rentals</h3>
            <p>$myRentals</p>
        </div>

        <div class='card stat'>
            <h3>Pending Payments</h3>
            <p>$pending</p>
        </div>
    </div>
    ";

    echo "<div class='card'><h3>My Recent Rentals</h3>";

    $res = $conn->query("
    SELECT e.name AS equipment, r.status
    FROM rental r
    JOIN equipment e ON r.equipment_id = e.e_id
    WHERE r.customer_id = $customer_id
    ORDER BY r.rental_id DESC LIMIT 5
    ");

    echo "<table><tr><th>Equipment</th><th>Status</th></tr>";

    while($row = $res->fetch_assoc()) {
        echo "<tr>
        <td>{$row['equipment']}</td>
        <td>{$row['status']}</td>
        </tr>";
    }

    echo "</table></div>";
}

echo "</div>";

include 'footer.php';
?>