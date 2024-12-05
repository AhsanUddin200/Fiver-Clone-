<?php
include 'db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user data from the database
    $stmt = $pdo->prepare("SELECT * FROM fiver_user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Start session and store user details
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Redirect to homepage or gig creation page
        header('Location: index.php');
        exit();
    } else {
        echo "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
         /* Logo styling */
         .logo-container {
    margin-bottom: 20px;
    text-align: center;  /* This will center the content inside the container */
}

.logo-container img {
    max-width: 150px; /* Adjust size */
    height: auto;
    margin: 0 auto; /* Ensures the image is centered within the container */
    display: block; /* Makes the image behave as a block element to allow centering */
}

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        input {
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
    <form method="POST" action="login.php">
        

  <div class="logo-container">
            <img src="data:image/png;base64,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" alt="Fiver Logo"> <!-- Replace 'path_to_logo.png' with the correct path to your logo -->
        </div>  
    <h2 style="text-align: center; margin-top: 50px;">Login</h2>

        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Log In</button>
    </form>
</div>

</body>
</html>
