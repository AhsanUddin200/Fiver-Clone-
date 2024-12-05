<?php
include 'db.php';  // Database connection
session_start();  // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in first!";
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Check if the user already has a gig
$stmt = $pdo->prepare("SELECT COUNT(*) FROM fiver_gigs WHERE user_id = ?");
$stmt->execute([$user_id]);
$gig_count = $stmt->fetchColumn();

// If the user already has a gig, prevent creating another one
if ($gig_count > 0) {
    echo "You already have a gig. You cannot create a new one.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $delivery_time = $_POST['delivery_time'];
    $category = $_POST['category'];

    // Handle file upload for gig thumbnail
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $file_name = $_FILES['thumbnail']['name'];
        $file_tmp = $_FILES['thumbnail']['tmp_name'];
        $file_type = $_FILES['thumbnail']['type'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        // Check if the file is an image
        if (in_array($file_type, $allowed_types)) {
            $target_dir = "uploads/";  // Upload directory
            $target_file = $target_dir . basename($file_name);

            // Move the uploaded file to the server
            if (move_uploaded_file($file_tmp, $target_file)) {
                $thumbnail_path = $target_file;  // Save the path to the database

                // Insert gig details into the database
                $stmt = $pdo->prepare("INSERT INTO fiver_gigs (user_id, title, description, price, delivery_time, category, thumbnail) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$user_id, $title, $description, $price, $delivery_time, $category, $thumbnail_path]);

                echo "Gig created successfully!";
                exit();
            } else {
                echo "Error uploading file.";
                exit();
            }
        } else {
            echo "Only image files are allowed!";
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Gig</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f7fc;
        }
        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <form method="POST" enctype="multipart/form-data">
        <h2>Create a New Gig</h2>

        <input type="text" name="title" placeholder="Gig Title" required>
        <textarea name="description" placeholder="Gig Description" required></textarea>
        <input type="number" name="price" placeholder="Price" required>
        <input type="number" name="delivery_time" placeholder="Delivery Time (days)" required>
        <select name="category" required>
            <option value="">Select Category</option>
            <option value="Web Development">Web Development</option>
            <option value="Design">Design</option>
            <option value="Marketing">Marketing</option>
            <option value="Writing">Writing</option>
            <option value="Writing">Editing</option>
        </select>
        <input type="file" name="thumbnail" required>

        <button type="submit">Create Gig</button>
    </form>
</div>

</body>
</html>
