<?php
session_start();
require "includes/database_connect.php"; // Connect to the database

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Check if the property_id is passed via the URL
if (!isset($_GET['property_id'])) {
    // Redirect to the homepage if no property_id is found
    header("Location: index.php");
    exit();
}
$property_id = $_GET['property_id']; // Get the property ID from the URL
$user_id = $_SESSION['user_id'];     // Get the logged-in user's ID from the session

// Insert the booking details into the database
$sql = "INSERT INTO booked_properties (property_id, user_id, booking_date) VALUES (?, ?, NOW())";
$stmt = mysqli_prepare($con, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ii", $property_id, $user_id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Booking successful
        echo "Booking successful! Redirecting to the confirmation page...";
        header("Refresh: 3; URL=booking_success.php"); // Redirect after 3 seconds
    } else {
        // Error in booking
        echo "Error: Unable to book property. Please try again later.";
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Error: " . mysqli_error($con);
}

// Close the database connection
mysqli_close($con);
?>