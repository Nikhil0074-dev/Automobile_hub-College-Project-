<?php
session_start();
include 'db.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_name = $_POST['admin_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $designation = $_POST['designation'];
    $password = $_POST['password']; // Plain text password (not recommended for security)
    $admin_code = $_POST['admin_code']; // Admin secret code

    // Check if the entered secret code is correct
    if ($admin_code !== "ADMIN123") {
        echo "<script>alert('Invalid Secret Code! Registration Denied.'); window.location.href='register.php';</script>";
        exit();
    }

    // Check if email already exists
    $check_email = $conn->prepare("SELECT admin_id FROM admin WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        echo "<script>alert('Email already exists! Try another.'); window.location.href='register.php';</script>";
        exit();
    }
    $check_email->close();

    
    $stmt = $conn->prepare("INSERT INTO admin (admin_name, email, phone_number, designation, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $admin_name, $email, $phone, $designation, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Admin Registration successful!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error occurred! Try again.');</script>";
    }
    
    $stmt->close();
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
