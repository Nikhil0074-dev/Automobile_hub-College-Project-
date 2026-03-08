<?php
session_start();
include 'db.php'; 

 if (!isset($_SESSION['admin_id'])) {
     header('Location: login.php');
     exit();
 }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css"> 
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }
        h2 {
            background-color: #333;
            color: white;
            padding: 20px 0;
            margin: 0;
            font-size: 28px;
        }
        .menu-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 30px;
        }
        .menu-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 250px;
            height: 150px;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
        }
        .menu-card:hover {
            transform: scale(1.1);
            background: #007bff;
            color: white;
        }
        .menu-card a {
            text-decoration: none;
            color: inherit;
            display: block;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <h2>Admin Dashboard</h2>
    <div class="menu-container">
        <div class="menu-card"><a href="mcustomer.php">View Customers</a></div>
        <div class="menu-card"><a href="mvehicle.php">Manage Vehicles</a></div>
        <div class="menu-card"><a href="mparts.php">Manage Parts</a></div>
        <div class="menu-card"><a href="mservice.php">Manage Services</a></div>
        <div class="menu-card"><a href="minsurance.php">Manage Insurances</a></div>
        <div class="menu-card"><a href="mbook.php">View Appointments</a></div>
        <div class="menu-card"><a href="mvsale.php">View Vehicle Sales</a></div>
        <div class="menu-card"><a href="mpsale.php">View Part Sales</a></div>
        <div class="menu-card"><a href="misales.php">View Insurance Sales</a></div>
        
    </div>
</body>
</html>