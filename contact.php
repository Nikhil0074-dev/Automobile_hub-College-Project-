<?php
session_start();
include 'db.php'; 

$result = $conn->query("SELECT admin_name, designation, email, phone_number FROM admin WHERE designation IN ('owner', 'mechanic', 'manager')");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>24/7 Contact Support</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
         html {
          scroll-behavior: smooth;
        }
        body {
            margin: auto;
            padding: auto;
            font-family: Arial, sans-serif;
            background: white;
            color:black;
        }
        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        header {
           margin:10px 10px;
            background: black;
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position:fixed;
            width: 97%;
            height:fit-content;
            top: 10;
            z-index: 1000;
        }
        .site-title {
            font-size: 60px;
            font-weight: bold;
            text-align: center;
            text-decoration: dashed;
            flex-grow: 1;
        }
        .login-icon {
            font-size: 24px;
            color: white;
            text-decoration: none;
            margin-right: auto;
            background:#f39c12;
            padding: 10px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content:center;
            width: 30px;
            height: 30px;
        }
        nav {
            margin-left: auto;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display:flex;
            justify-content: flex-end;
        }
        nav ul li {
            margin: 0 10px;
        }
        nav ul li a {
            text-decoration:none;
        }
        nav ul li button {
            background-color: #f39c12;
            border:2px;
            color: white;
            padding: 10px 10px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            cursor:pointer;
         
        }
        nav ul li button:hover {
            background-color:#ffc267;
            font-size:20px ;
        }
    
        .contact-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
            margin-top: 80px;
        }
        .contact-card {
            background:rgb(246, 166, 38);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 280px;
        }
        .contact-card h3 {
            margin: 0;
            color:black;
        }
        .contact-card p {
            margin: 10px 0;
        }
        .contact-card a {
            text-decoration: none;
            color: black;
            font-weight: bold;
        }
        .contact-card a:hover {
            color: black;
        }
        .footer {
         background: black;
         padding: 16px 8px; 
         position: relative;
         bottom: 0;
         width: 100%;
         max-width: 100%;
         box-sizing: border-box;
         overflow: hidden;
}

.footer .box-container {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    max-width: 1200px; 
    margin: 0 auto; 
    padding:  20px; 
}

.footer .box-container .box {
    flex: 1;
    min-width: 240px; 
    margin: 8px; 
}

.footer .box-container .box h3 {
    font-size: 24px; 
    color: white;
    margin-bottom: 8px; 
}

.footer .box-container .box a {
    display: block;
    font-size: 16px; 
    color: whitesmoke;
    margin-bottom: 15px; 
    text-decoration: none;
}

.footer .box-container .box a i {
    color: white;
    margin-right: 5px; 
}

.footer .box-container .box a:hover i {
    font-size: 19px; 
}

.footer .credit {
    text-align: center;
    padding: 16px; 
    padding-top: 24px; 
    margin-top: 16px; 
    border-top: 1px solid white;
    font-size: 19px; 
    color: white;
}

    </style>
</head>
<body>
<video class="video-background" autoplay muted loop>
    <source src="media/background.mp4" type="video/mp4">
</video>
<header>
    <a href="login.php" class="login-icon"><i class="fas fa-user"></i></a>
    <div class="site-title">Automobile Hub</div>
    <nav>
        <ul>
            <li><a href="chat.php"><button>Home</button></a></li>
        </ul>
    </nav>
</header>
<br>
<br>
<br>
<br>
<div class="contact-container">
  <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="contact-card">
            <h3>Name:<?= htmlspecialchars($row['admin_name']) ?></h3>
            <p><strong>Designation:<?= ucfirst(htmlspecialchars($row['designation'])) ?></strong></p>
            <p><i class="fas fa-envelope"></i><a href="mailto:<?= htmlspecialchars($row['email']) ?>">Email: <?= htmlspecialchars($row['email']) ?></a></p>
            <p><i class="fas fa-phone"></i><a href="tel:<?= htmlspecialchars($row['phone_number']) ?>">Phone: <?= htmlspecialchars($row['phone_number']) ?></a></p>
        </div>
    <?php } ?>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br><br>
<br>
<br>
<br>
<footer class="footer">
        <div class="box-container">
            <div class="box">
                <h3>About Us</h3>
                <a href="#"><i class="fas fa-angle-right"></i> Our Story</a>
                <a href="#"><i class="fas fa-angle-right"></i> Team</a>
                <a href="#"><i class="fas fa-angle-right"></i> Careers</a>
            </div>
            <div class="box">
                <h3>Contact Us</h3>
                <a href="#"><i class="fas fa-angle-right"></i> Email</a>
                <a href="#"><i class="fas fa-angle-right"></i> Phone</a>
                <a href="#"><i class="fas fa-angle-right"></i> Address</a>
            </div>
            <div class="box">
                <h3>Follow Us</h3>
                <a href="#"><i class="fab fa-facebook-f"></i> Facebook</a>
                <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
                <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
            </div>
            <div class="box">
                <h3>Quick Links</h3>
                <a href="#"><i class="fas fa-angle-right"></i> Home</a>
                <a href="#"><i class="fas fa-angle-right"></i> Services</a>
                <a href="#"><i class="fas fa-angle-right"></i> Blog</a>
                <a href="#"><i class="fas fa-angle-right"></i> Contact</a>
            </div>
        </div>
        <div class="credit">
            &copy; 2025 Automobile Hub. All rights reserved.
        </div>
    </footer>
</body>
</html>
