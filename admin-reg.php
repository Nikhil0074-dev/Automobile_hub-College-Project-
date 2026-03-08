<?php
session_start();
include 'db.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Admin secret code - should be stored in a config file in production
define('ADMIN_SECRET_CODE', 'ADMIN123');

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_name = trim($_POST['admin_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $designation = $_POST['designation'];
    $password = $_POST['password'];
    $admin_code = $_POST['admin_code'];

    // Basic validation
    if (empty($admin_name) || empty($email) || empty($phone) || empty($password) || empty($admin_code)) {
        $error_message = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format!";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters!";
    } elseif ($admin_code !== ADMIN_SECRET_CODE) {
        $error_message = "Invalid Secret Code! Registration Denied.";
    } else {
        // Check if email already exists
        $check_email = $conn->prepare("SELECT admin_id FROM admin WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_result = $check_email->get_result();

        if ($check_result->num_rows > 0) {
            $error_message = "Email already exists! Try another.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO admin (admin_name, email, phone_number, designation, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $admin_name, $email, $phone, $designation, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>alert('Admin Registration successful!'); window.location.href='login.php';</script>";
                exit();
            } else {
                $error_message = "Error occurred! Try again.";
            }
            $stmt->close();
        }
        $check_email->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #FEF3C7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            display: flex;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 850px;
        }
        .image-section {
            background: orange;
            width: 45%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .image-section img {
            width: 100%;
            max-width: 320px;
        }
        .form-section {
            width: 55%;
            padding: 50px;
            text-align: center;
        }
        .form-section h2 {
            margin-bottom: 25px;
            font-size: 24px;
        }
        label {
            display: block;
            text-align: left;
            margin-top: 10px;
            font-weight: bold;
        }
        input, select, button {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border-radius: 5px;
            border: 2px solid black;
            font-size: 14px;
        }
        button {
            background-color: #f39c12;
            color: white;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            margin-top: 15px;
        }
        button:hover {
            background-color: #e67e22;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="image-section">
        <img src="media/car.png" alt="Car">
    </div>
    <div class="form-section">
        <h2>Admin Registration</h2>
        <form method="POST">
            <label for="admin_name">Full Name:</label>
            <input type="text" id="admin_name" name="admin_name" placeholder="Enter Full Name" required>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" placeholder="Enter Email Address" required>

            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" placeholder="Enter Phone Number" required>

            <label for="designation">Designation:</label>
            <select id="designation" name="designation" required>
                <option value="owner">Owner</option>
                <option value="manager">Manager</option>
                <option value="mechanic">Mechanic</option>
            </select>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter Password" required>

            <label for="admin_code">Admin Secret Code:</label>
            <input type="password" id="admin_code" name="admin_code" placeholder="Enter Admin Code" required>

            <button type="submit">Register</button>
        </form>
    </div>
</div>

</body>
</html>
