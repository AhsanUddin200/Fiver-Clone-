<?php
session_start();
include 'db.php';  // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<div style='background-color: #f44336; color: white; padding: 10px; text-align: center; border-radius: 5px;'>You must be logged in to book a gig.</div>";
    exit;
}

// Check if gig_id is passed
if (isset($_GET['gig_id'])) {
    $gig_id = $_GET['gig_id'];
    $user_id = $_SESSION['user_id'];  // Get logged-in user ID

    // Insert booking into the fiver_bookings table
    $stmt = $pdo->prepare("INSERT INTO fiver_bookings (gig_id, user_id, booking_date)
                           VALUES (?, ?, NOW())");
    $stmt->execute([$gig_id, $user_id]);

    // Success message with CSS styling
    echo "<div style='background-color: #4CAF50; color: white; padding: 10px; text-align: center; border-radius: 5px;'>
            Gig booked successfully! You can view your booking in your profile.</div>";
} else {
    echo "<div style='background-color: #f44336; color: white; padding: 10px; text-align: center; border-radius: 5px;'>Gig ID is missing!</div>";
    exit;
}
