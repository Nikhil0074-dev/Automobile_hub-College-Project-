<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    // File Handling
    $upload_dir = "uploads/";
    $adhar_path = $upload_dir . basename($_FILES["adhar"]["name"]);
    $pan_path = $upload_dir . basename($_FILES["pan"]["name"]);
    
    move_uploaded_file($_FILES["adhar"]["tmp_name"], $adhar_path);
    move_uploaded_file($_FILES["pan"]["tmp_name"], $pan_path);

    // Insert into database (without password hashing)
    $stmt = $conn->prepare("INSERT INTO customers (name, email, phone_number, address, password, adhar, pan) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $phone, $address, $password, $adhar_path, $pan_path);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
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
