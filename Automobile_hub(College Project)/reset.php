<?php
session_start();
include 'db.php'; 

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
e
    $select = mysqli_query($conn, "SELECT * FROM `customers` WHERE name = '$username' AND email = '$email'") or die('query failed');
    
    if (mysqli_num_rows($select) > 0) {
        if (isset($_POST['new_password'])) {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password === $confirm_password) {
                mysqli_query($conn, "UPDATE `customers` SET password = '$new_password' WHERE name = '$username' AND email = '$email'") or die('query failed');

                $success_message = "Your password has been reset successfully!";
                header("Location: login.php"); 
                exit(); 
            } else {
                $error_message = "Passwords do not match!";
            }
        }
    } else {
        $error_message = "No user found with that username and email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #FEF3C7;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        header {
            margin: 10px 10px;
            background: black;
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            height: fit-content;
            top: 0;
            z-index: 1000;
        }
        .site-title {
            font-size: 60px;
            font-weight: bold;
            text-align: center;
            text-decoration: dashed;
            flex-grow: 1;
        }
        h2 {
            margin-top: 20px;
        }
        .error {
            color: red;
            margin-bottom: 1rem;
        }
        .success {
            color: green;
            margin-bottom: 1rem;
        }
        .container {
            display: flex;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
            margin-top: 20px; 
        }
        .form-section, .image-section {
            padding: 2rem;
            width: 50%;
        }
        .form-section {
            background-color: white;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .image-section {
            background-color: #F39C12;
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #D1D5DB;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #f39c12;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
        }
        button:hover {
            background-color: #fad8a0;
        }
        .image-section img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <header>
        <div class="site-title">Automobile Hub</div>
    </header>
    <div class="container">
        <div class="image-section">
            <img src="media/car.png" alt="A black super car with a transparent background">
        </div>
        <div class="form-section" id="reset-form">
            <h2>Reset Password</h2>
            <?php if ($error_message): ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <?php if ($success_message): ?>
                <div class="success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" placeholder="Enter your username" required>

                <label for="email">Email</label>
                <input id="email" name="email" type="email" placeholder="Enter your email" required>

                <label for="new_password">New Password</label>
                <input id="new_password" name="new_password" type="password" placeholder="Enter new password" required>

                <label for="confirm_password">Confirm Password</label>
                <input id="confirm_password" name="confirm_password" type="password" placeholder="Confirm new password" required>

                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>