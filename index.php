    <?php
    session_start();
    include 'db.php';  // Include database connection

    // Fetch all gigs from the database
    $stmt = $pdo->prepare("SELECT g.id, g.title, g.thumbnail, g.price, g.category, u.username AS seller_name
                        FROM fiver_gigs g
                        JOIN fiver_user u ON g.user_id = u.id
                        ORDER BY g.created_at DESC");

    $stmt->execute();
    $gigs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all categories for filters
    $categories_stmt = $pdo->prepare("SELECT DISTINCT category FROM fiver_gigs");
    $categories_stmt->execute();
    $categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if the user is logged in
    $user_logged_in = isset($_SESSION['user_id']);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Fiver - Homepage</title>
        <link rel="stylesheet" href="styles.css"> <!-- External CSS file (Optional) -->
        <style>
            /* Reset default styles */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
        }

        /* Header styles */
        header {
            background-color: #28a745;  /* Light green background */
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Logo section */
        .logo {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
            cursor: pointer;
        }

        /* Navigation menu */
        nav {
            display: flex;
            gap: 20px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #dcdcdc; /* Light hover effect */
        }

        /* Login/Signup button */
        .auth-buttons a {
            color: white;
            background-color: #006400;  /* Darker green button */
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .auth-buttons a:hover {
            background-color: #004d00;  /* Darker on hover */
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                text-align: center;
            }
            .logo {
                margin-bottom: 10px;
            }
            nav {
                flex-direction: column;
                gap: 10px;
            }
        }

            .create-gig-btn {
                background-color: #1dbf73   ;
                color: white;
                padding: 10px 20px;
                border-radius: 5px;
                font-size: 16px;
                text-decoration: none;
                transition: background-color 0.3s ease;
            }

            .create-gig-btn:hover {
                background-color: #d78c00;
            }

            /* Gig container styles */
            .gig-container {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                padding: 20px;
            }

            .gig-card {
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                width: calc(33% - 20px);
                margin-bottom: 20px;
                transition: transform 0.3s ease;
            }

            .gig-card:hover {
                transform: translateY(-10px);
            }

            .gig-card img {
                width: 100%;
                height: 200px;
                object-fit: cover;
            }

            .gig-card .info {
                padding: 15px;
            }

            .gig-card .info h3 {
                font-size: 18px;
                margin-bottom: 10px;
            }

            .gig-card .info p {
                color: #555;
                font-size: 16px;
                margin-bottom: 10px;
            }

            .gig-card .info .price {
                font-size: 20px;
                font-weight: bold;
                color: #333;
            }

            /* Responsive design */
            @media (max-width: 768px) {
                .gig-card {
                    width: calc(50% - 20px);
                }
            }

            @media (max-width: 480px) {
                .gig-card {
                    width: 100%;
                }

                header {
                    flex-direction: column;
                    align-items: flex-start;
                }

                header nav {
                    flex-direction: column;
                    gap: 10px;
                    margin-top: 10px;
                }
                /* Button Styling */
                .button-container {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .create-btn {
            background-color: #1dbf73;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background-color 0.3s, transform 0.3s;
            text-align: center;
        }

        .create-btn:hover {
            background-color: #16a44b;
            transform: translateY(-3px);
        }
                


            }
        </style>
    </head>
    <body>

    <!-- Header Section -->
    <header>
        <div class="logo">Fiver</div>
        <nav>
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
            <?php if ($user_logged_in): ?>
                <a href="profile.php">Profile</a>
            <?php endif; ?>
        </nav>
        <?php if ($user_logged_in): ?>
            <a href="create_gig.php" class="create-gig-btn">Create Gig</a>
        <?php else: ?>
            <a href="login.php" class="create-gig-btn">Login to Create Gig</a>
        <?php endif; ?>
    </header>

    <!-- Gig Listings Section -->
<!-- Category Filter -->
<section class="filter-section" style="background-color: #fff; padding: 20px; text-align: center; margin: 20px 0; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <form method="GET" action="index.php" style="display: inline-block; text-align: left;">
        <label for="category" style="font-size: 18px; margin-right: 10px;">Filter by Category:</label>
        <select name="category" id="category" style="padding: 8px 12px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; margin-right: 20px;">
            <option value="">All Categories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['category']); ?>" <?php echo isset($category_filter) && $category_filter == $category['category'] ? 'selected' : ''; ?>>
    <?php echo htmlspecialchars($category['category']); ?>
</option>

            <?php endforeach; ?>
        </select>
        <button type="submit" style="background-color: #1dbf73; color: #fff; padding: 10px 20px; font-size: 16px; border: none; border-radius: 4px; cursor: pointer;">Apply Filter</button>
    </form>
</section>

    <div class="gig-container">
        <?php foreach ($gigs as $gig): ?>
            <div class="gig-card">
                <img src="<?php echo $gig['thumbnail']; ?>" alt="Gig Image">
                <div class="info">
                <h3 style="font-size: 1.4rem; color: #333; margin-bottom: 10px;">
        <?php echo htmlspecialchars($gig['title']); ?>
    </h3>
    <p style="color: #555; margin-bottom: 5px;">
        By: <?php echo htmlspecialchars($gig['seller_name']); ?>
    </p>
    <p style="color: #555; margin-bottom: 5px;">
        Category: <?php echo htmlspecialchars($gig['category']); ?>
    </p>
    <p style="font-size: 1.2rem; font-weight: bold; color: #1dbf73; margin-bottom: 5px;">
        $<?php echo number_format($gig['price'], 2); ?>
    </p>
    <p style="color: #555; margin-bottom: 15px;">
        Seller: <?php echo $gig['seller_name']; ?>
    </p>
    <a href="gig_detail.php?gig_id=<?php echo $gig['id']; ?>" 
    style="background-color: #1dbf73; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-size: 0.9rem; transition: background-color 0.3s, transform 0.3s; text-align: center; display: inline-block; margin-right: 10px;">
    View Details
    </a>
    <a href="book_gig.php?gig_id=<?php echo $gig['id']; ?>" 
    style="background-color: #1dbf73; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-size: 0.9rem; transition: background-color 0.3s, transform 0.3s; text-align: center; display: inline-block;">
    Book Now
    </a>    
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    </body>
    </html>
