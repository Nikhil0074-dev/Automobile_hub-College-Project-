<?php
session_start();
include 'db.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        $error_message = "All required fields must be filled!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format!";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters!";
    } else {
        // Check if email already exists
        $check_stmt = $conn->prepare("SELECT customer_id FROM customers WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error_message = "Email already registered!";
        } else {
            // File upload handling with validation
            $upload_dir = "uploads/";
            
            // Validate file uploads
            if (isset($_FILES["adhar"]) && isset($_FILES["pan"])) {
                $allowed_types = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
                
                $adhar_type = $_FILES["adhar"]["type"];
                $pan_type = $_FILES["pan"]["type"];
                
                if (!in_array($adhar_type, $allowed_types)) {
                    $error_message = "Invalid Aadhar file type! Only PDF, JPG, PNG allowed.";
                } elseif (!in_array($pan_type, $allowed_types)) {
                    $error_message = "Invalid PAN file type! Only PDF, JPG, PNG allowed.";
                } else {
                    // Generate unique filenames
                    $adhar_filename = time() . '_adhar_' . basename($_FILES["adhar"]["name"]);
                    $pan_filename = time() . '_pan_' . basename($_FILES["pan"]["name"]);
                    $adhar_path = $upload_dir . $adhar_filename;
                    $pan_path = $upload_dir . $pan_filename;
                    
                    if (move_uploaded_file($_FILES["adhar"]["tmp_name"], $adhar_path) && 
                        move_uploaded_file($_FILES["pan"]["tmp_name"], $pan_path)) {
                        
                        // Hash the password
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        
                        // Insert into database with prepared statement
                        $stmt = $conn->prepare("INSERT INTO customers (name, email, phone_number, address, password, adhar, pan) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("sssssss", $name, $email, $phone, $address, $hashed_password, $adhar_path, $pan_path);
                        
                        if ($stmt->execute()) {
                            $success_message = "Registration successful!";
                            echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
                            exit();
                        } else {
                            $error_message = "Registration failed! Please try again.";
                        }
                        $stmt->close();
                    } else {
                        $error_message = "File upload failed!";
                    }
                }
            } else {
                $error_message = "Please upload both Aadhar and PAN documents!";
            }
        }
        $check_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
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
        <h2>Customer Registration</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Full Name:</label>
            <input type="text" name="name" placeholder="Full Name" required>

            <label for="email">Email Address:</label>
            <input type="email" name="email" placeholder="Email Address" required>

            <label for="phone">Phone Number:</label>
            <input type="text" name="phone" placeholder="Phone Number" required>

            <label for="address">Address:</label>
            <input type="text" name="address" placeholder="Address">

            <label for="password">Password:</label>
            <input type="password" name="password" placeholder="Password" required>

            <label>Aadhar Card:</label>
            <input type="file" name="adhar" required>

            <label>PAN Card:</label>
            <input type="file" name="pan" required>

            <button type="submit">Register</button>
        </form>
    </div>
</div>

</body>
</html>
