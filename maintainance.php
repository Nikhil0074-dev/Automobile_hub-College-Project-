<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id    = $_SESSION['user_id'];
$user_name  = $_SESSION['user_name'] ?? 'Guest';
$user_phone = $_SESSION['user_phone'] ?? 'N/A';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_id = $_POST['service_id'];
    $date       = $_POST['date'];
    $time       = $_POST['time'];
    
    $appointment_datetime = $date . ' ' . $time;
    
    $current_datetime = new DateTime();
    $appointment_dt   = new DateTime($appointment_datetime);
    if ($appointment_dt < $current_datetime) {
        echo "<script>alert('The selected date and time is in the past. Please select a future date and time.');</script>";
        exit();
    }
    
    $stmt = $conn->prepare("SELECT price FROM Services WHERE service_id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $stmt->bind_result($price);
    if (!$stmt->fetch()) {
        die("<script>alert('Error: Service not found!');</script>");
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO Appointments (customer_id, service_id, appointment_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $service_id, $appointment_datetime);
    if ($stmt->execute()) {
        echo "<script>alert('Appointment booked successfully! Total Bill: $$price');</script>";
    } else {
        echo "<script>alert('Booking failed. Please try again later.');</script>";
    }
    $stmt->close();
}

$services = $conn->query("SELECT service_id, service_name, price FROM Services WHERE service_type = 'maintainance'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Maintainance Packages</title>
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
section {
    top:20px;
    padding: 100px 20px;
    text-align: center;
    min-height: 100vh;
    position: relative;
}
    h1 {
      font-size:40px;
      text-align: center;
    }
    .container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-top: 20px;
    }
    /* Service card styles */
    .card {
      background: #ffc937;
      border: 2px solid black;
      border-radius: 10px;
      padding: 20px;
      width: 300px;
      text-align: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .card h3 {
      margin: 0 0 10px;
    }
    .card p {
      margin: 5px 0;
    }
    .card button {
      background-color:#f39c12;
      border: none;
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
    }
    .card button:hover {
      background-color:rgb(248, 180, 71);
    }
   
    .form {
      display: none; /* Hidden by default */
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.5);
    }
    .form-content {
      background-color: #fff;
      margin: 5% auto;
      padding: 20px;
      border-radius: 10px;
      width: 400px;
      position: relative;
    }
    .close {
      position: absolute;
      top: 10px;
      right: 20px;
      font-size: 24px;
      font-weight: bold;
      cursor: pointer;
    }
    .form-content h2 {
      margin-top: 0;
    }
    .form-content .form-group {
      margin-bottom: 15px;
      text-align: left;
    }
    .form-content label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    .form-content input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .form-content button {
      width: 100%;
      padding: 10px;
      background-color: #28a745;
      border: none;
      color: white;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }
    .form-content button:hover {
      background-color: #218838;
    }
    .footer {
    background: black;
    padding: 16px 8px;
    position: fixed;
    bottom: 20px;  
    left: 20px;    
    right: 20px;  
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
}

.footer .box-container {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
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
    margin-bottom: 5px;
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
<video class="video-background" autoplay muted loop type="video/mp4">
        <source src="media/background.mp4">
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
<section>
  <h1>Maintainance Packages</h1>
  <div class="container">
    <?php while($row = $services->fetch_assoc()): ?>
      <div class="card">
        <h3><?php echo htmlspecialchars($row['service_name']); ?></h3>
        <p>Price: ₹<?php echo $row['price']; ?></p>
        <button onclick="openBookingForm('<?php echo $row['service_id']; ?>', '<?php echo addslashes($row['service_name']); ?>', '<?php echo $row['price']; ?>')">Buy</button>
      </div>
    <?php endwhile; ?>
  </div>
 

 
  <div id="bookingform" class="form">
    <div class="form-content">
      <span class="close" onclick="closeBookingForm()">&times;</span>
      <h2>Book Repair Service</h2>
      <form method="POST" id="bookingForm" onsubmit="return validateBookingForm();">
        <div class="form-group">
          <label>Name:</label>
          <input type="text" value="<?php echo htmlspecialchars($user_name); ?>" readonly>
        </div>
        <div class="form-group">
          <label>Phone:</label>
          <input type="text" value="<?php echo htmlspecialchars($user_phone); ?>" readonly>
        </div>
        <div class="form-group">
          <label>Service:</label>
          <input type="text" id="serviceName" readonly>
        </div>
        <div class="form-group">
          <label>Price:</label>
          <input type="text" id="servicePrice" readonly>
        </div>
        <div class="form-group">
          <label>Date:</label>
          <input type="date" name="date" id="bookingDate" required min="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="form-group">
          <label>Time:</label>
          <input type="time" name="time" id="bookingTime" required>
        </div>
        <input type="hidden" name="service_id" id="serviceId">
        <button type="submit">Book Appointment</button>
      </form>
    </div>
  </div>

  <script>
    function openBookingForm(serviceId, serviceName, servicePrice) {
      document.getElementById('serviceId').value = serviceId;
      document.getElementById('serviceName').value = serviceName;
      document.getElementById('servicePrice').value = servicePrice;
      document.getElementById('bookingform').style.display = 'block';
    }

    function closeBookingForm() {
      document.getElementById('bookingform').style.display = 'none';
    }

    function validateBookingForm() {
      var dateInput = document.getElementById('bookingDate').value;
      var timeInput = document.getElementById('bookingTime').value;
      if (!dateInput || !timeInput) {
        alert('Please select both date and time.');
        return false;
      }
      
      var selectedDateTime = new Date(dateInput + 'T' + timeInput);
      var now = new Date();
      
      if (selectedDateTime < now) {
        alert('The selected date and time is in the past. Please choose a future date and time.');
        return false;
      }
      return true;
    }

    window.onclick = function(event) {
      var modal = document.getElementById('bookingform');
      if (event.target == modal) {
        modal.style.display = 'none';
      }
    };
  </script>
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
