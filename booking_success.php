<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booking Success</title>
</head>
<body>
    <h1>Booking Successful!</h1>
    <p>Your booking has been successfully recorded. <a href="dashboard.php">Go to Dashboard</a></p>
</body>
</html>
