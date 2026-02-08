<?php
session_start();
include 'db.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Insurance</title>
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
        section {
            padding: 100px 20px;
            text-align: center;
            min-height: 100vh;
            position: relative;
        }
        
        .content {
            flex: 1;
            padding: 100px 20px 20px;
            text-align: center;
            position: relative;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
            margin-top: 80px;
        }
        .insurance {
            background:white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 50px;
            width: 300px;
            text-align: center;
            transition: transform 0.2s;
        }
        .insurance:hover {
            transform: scale(1.05);
        }
        .insurance img {
            width: 100%;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .insurance h3 {
            margin: 10px 0;
            color: #333;
        }
        .insurance p {
            padding: 0 10px;
            color: #666;
        }
        .buy-button {
            background-color: #f39c12;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 0;
            font-size: larger;
            transition: background-color 0.3s;
        }
        .buy-button:hover {
            background-color:rgb(246, 200, 126);
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
<video class="video-background" autoplay muted loop type='mp4'>
        <source src="media/background.mp4" >
    </video>
    <header>
        <a href="login.php" class="login-icon"><i class="fas fa-user"></i></a>
        <div class="site-title">Automobile Hub</div>
        <nav>
            <ul>
                <li><a href="index.php"><button>Home</button></a></li>
                <li><a href="index.php#services"><button>Services</button></a></li>
            </ul>
        </nav>
    </header>
    
    <div class="content">
        <div class="container">
        <?php
$sql = "SELECT * FROM Insurance";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($insurance = $result->fetch_assoc()) {
        $image = isset($insurance['image_url']) ? htmlspecialchars($insurance['image_url']) : ''; 
        $altText = htmlspecialchars($insurance['insurance_company']); 

        echo '<div class="insurance">';
        echo '<img src="media/' . $image . '" alt="' . $altText . '"height=300 width=300>';
        echo '<h3>' . htmlspecialchars($insurance['insurance_company']) . '<br>
         '. htmlspecialchars($insurance['insurance_type']) . '</h3>';
        echo '<p>Premium: ₹' . number_format((float)$insurance['premium'], 2) . '</p>';
        echo '<form action="insurancesell.php" method="POST">';
        echo '<input type="hidden" name="insurance_id" value="' . (int)$insurance['insurance_id'] . '">';
        echo '<button type="submit" class="buy-button">Buy</button>';
        echo '</form>';
        echo '</div>';
    }
} else {
    echo '<p>No Insurance available.</p>';
}

$conn->close();
?>

        </div>
    </div>
    
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
