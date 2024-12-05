<?php
include 'db.php';  // Include database connection

// Get the gig ID from the URL
if (isset($_GET['gig_id'])) {
    $gig_id = $_GET['gig_id'];

    // Fetch gig details and seller info
    $stmt = $pdo->prepare("SELECT g.id, g.title, g.description, g.price, g.delivery_time, g.thumbnail, u.username AS seller_name
                           FROM fiver_gigs g
                           JOIN fiver_user u ON g.user_id = u.id
                           WHERE g.id = ?");
    $stmt->execute([$gig_id]);
    $gig = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$gig) {
        echo "Gig not found!";
        exit;
    }
} else {
    echo "Gig ID is missing!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gig Details</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2C3E50;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        header nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
        }

        header nav a:hover {
            text-decoration: underline;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        .gig-detail-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin-top: 20px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .gig-detail {
            width: 65%;
        }

        .gig-title {
            font-size: 32px;
            color: #333;
            margin-bottom: 15px;
        }

        .gig-thumbnail {
            width: 100%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .gig-info {
            margin-bottom: 15px;
        }

        .gig-info strong {
            color: #333;
        }

        .gig-description {
            margin-bottom: 20px;
            color: #555;
        }

        .book-now-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #27AE60;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .book-now-btn:hover {
            background-color: #2ECC71;
        }

        .sidebar {
            width: 30%;
            padding: 20px;
            background-color: #ecf0f1;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
        }

        .sidebar .price {
            font-size: 22px;
            color: #27AE60;
            margin-bottom: 15px;
        }

        .sidebar .delivery-time {
            font-size: 16px;
            color: #555;
            margin-bottom: 15px;
        }

        footer {
            background-color: #2C3E50;
            color: white;
            text-align: center;
            padding: 15px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <div class="container">
        <h1 style="font-family: 'Arial', sans-serif; font-size: 48px; font-weight: bold; text-align: center; color: #fff; text-transform: uppercase; letter-spacing: 5px; margin-top: 20px; margin-bottom: 20px; position: relative; transition: all 0.3s ease;">
    Fiver
    <span style="content: ''; position: absolute; bottom: -10px; left: 0; width: 100%; height: 3px; background-color: #007bff; border-radius: 5px;"></span>
</h1>

            <nav>
            <a href="index.php" style="color: #fff; text-decoration: none; padding: 10px 20px; background-color: #1dbf73; border-radius: 5px; font-size: 16px; margin-right: 10px; transition: background-color 0.3s, transform 0.3s;">Home</a>
            <a href="create_gig.php" style="color: #fff; text-decoration: none; padding: 10px 20px; background-color: #3498db; border-radius: 5px; font-size: 16px; transition: background-color 0.3s, transform 0.3s;">Create Gig</a>
            </nav>
        </div>
    </header>

    <!-- Gig Details Section -->
     
    <div class="container">
        <div class="gig-detail-container">
            <div class="gig-detail">
                <h2 class="gig-title"><?php echo $gig['title']; ?></h2>
                <img class="gig-thumbnail" src="<?php echo $gig['thumbnail']; ?>" alt="Gig Thumbnail">
                <div class="gig-info">
                    <p><strong>Description:</strong> <?php echo $gig['description']; ?></p>
                    <p><strong>Seller:</strong> <?php echo $gig['seller_name']; ?></p>
                </div>
                <div class="gig-description">
                    <p><strong>Price:</strong> $<?php echo $gig['price']; ?></p>
                    <p><strong>Delivery Time:</strong> <?php echo $gig['delivery_time']; ?> days</p>
                </div>
                <a href="book_gig.php?gig_id=<?php echo $gig['id']; ?>" class="book-now-btn">Book Now</a>
            </div>

            <!-- Sidebar with Price & Delivery Info -->
            <div class="sidebar">
                <h3>Gig Info</h3>
                <p class="price">$<?php echo $gig['price']; ?></p>
                <p class="delivery-time"><strong>Delivery Time:</strong> <?php echo $gig['delivery_time']; ?> days</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    

</body>
</html>
