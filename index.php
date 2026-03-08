<?php
session_start();
include 'db.php'; 
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automobile Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <video class="video-background" autoplay muted loop>
        <source src="media/background.mp4">
    </video>
<header>
    <a href="login.php" class="login-icon"><i class="fas fa-user"></i></a>
    <div class="site-title">Automobile Hub</div>
    <nav>
        <ul>
            <li><a href="#home"><button>Home</button></a></li>
            <li><a href="#about"><button>About Us</button></a></li>
            <li><a href="#featured"><button>Featured Car</button></a></li>
            <li><a href="#services"><button>Services</button></a></li>
            <li><a href="logout.php"><button>Log Out</button></a></li>
            <li><a href="contact.php"><button>Contact Us</button></a></li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'customer') : ?>
              <li><a href="menu.php"><button><i class="fas fa-bars"></i></button></a></li>
            <?php endif; ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                <li><a href="admin.php"><button>Admin</button></a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
    
    <section class="home">
        <h2>Welcome to Automobile Hub</h2>
        <a href="#about"><button class="explore-btn">Explore</button></a>
    </section>
    <section id="about">
      <div class="about">
        <div class="content">
            <img src="media/about.png" alt="About Us" height=300px width=300px>
            <h3>About Us</h3>
            <p>We are committed to providing top-quality services and products to our customers. With years of experience in the industry, we strive for excellence and customer satisfaction.</p>
            <p>Our team works hard to ensure that every experience with us is seamless, professional, and enjoyable. Join us on our journey to make a difference!</p>
        </div>
      </div>
    </section>

    <section id="featured">
        <h2>Featured Cars</h2>
        <div class="slider">
            <div class="slides">
                <?php
                $sql = "SELECT * FROM Vehicles WHERE status = 'available' and type = 'featured'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="slide">';
                        echo '<div class="slide-content">'; // Single block for image and info
                        echo '<img src="media/'.$row["image_url"].'" alt="' . $row["make"] . ' ' . $row["model"] . '">';
                        echo '<div class="info">';
                        echo '<strong> Name:' . $row["make"] . ' ' . $row["model"] . '</strong><br>';
                        echo '<strong>Price: ₹</strong>' . number_format($row["price"], 2) . '<br>';
                        echo '<strong>Mileage:</strong> ' . number_format($row["mileage"]) . ' km<br>';
                        echo '<strong>Color: </strong>' . ucfirst($row["color"]) . '<br>';

                        echo '<form action="vehiclebuy.php" method="POST">';
                        echo '<input type="hidden" name="vehicle_id" value="' . (int)$row['vehicle_id'] . '">';
                        echo '<button type="submit" class="buy-button">Buy</button>';
                        echo '</form>';
                       
                        echo '</div>';
                        echo '</div>'; 
                        echo '</div>'; 
                    }
                } else {
                    echo "<p>No featured vehicles available.</p>";
                }
                $conn->close();
                ?>
            </div>
            <button class="prev" onclick="changeSlide(-1)">&#10094;</button>
            <button class="next" onclick="changeSlide(1)">&#10095;</button>
        </div>
    </section> 
    <section id="services">
        <h2>Our Services</h2>
        <div class="services-container">
            <div class="service-item">
                <i class="fas fa-bolt"></i>
                <p>Buy Vehicle</p>
                <a href="vehiclesell.php"><button>Explore</button></a>
              </div>
            <div class="service-item">
              <i class="fas fa-tools"></i>
              <p>Repair</p>
              <a href="repair.php"><button>Explore</button></a>
            </div>
            <div class="service-item">
              <i class="fas fa-cogs"></i>
              <p>Maintenance</p>
              <a href="maintainance.php"><button>Explore</button></a>
            </div>
            <div class="service-item">
              <i class="fas fa-car"></i>
              <p>Vehicle Parts</p>
              <a href="part.php"><button>Explore</button></a>
            </div>
            <div class="service-item">
              <i class="fas fa-charging-station"></i>
              <p>EV Charging Station</p>
              <a href="ev.php"><button>Explore</button></a>
            </div>
            <div class="service-item">
              <i class="fas fa-paint-brush"></i>
              <p>Customization</p>
              <a href="customization.php"><button>Explore</button></a>
            </div>
            <div class="service-item">
              <i class="fas fa-shield-alt"></i>
              <p>Insurance</p>
              <a href="insurance.php"><button>Explore</button></a>
            </div>
            <div class="service-item">
              <i class="fas fa-headset"></i>
              <p>24/7 Support</p>
              <a href="contact.php"><button>Explore</button></a>
            </div>
        </div>
    </section> 
   
<section id="faq">
    <div class="faq-container">
        <h2>Frequently Asked Questions (FAQ)</h2>
    
        <div class="faq-item">
          <button class="faq-question">What services do you offer?</button>
          <div class="faq-answer">
            <p>We offer a wide range of services, including car repair,car sell,vehicle maintainance.</p>
          </div>
        </div>
    
        <div class="faq-item">
          <button class="faq-question">How can I contact customer support?</button>
          <div class="faq-answer">
            <p>You can reach our customer support team by emailing us at automobilehub@gmail.com or by calling (123) 456-7890.</p>
          </div>
        </div>
    
        <div class="faq-item">
          <button class="faq-question">What is your return policy?</button>
          <div class="faq-answer">
            <p>We offer a 30-day return policy on most products. To initiate a return, please contact our support team.</p>
          </div>
        </div>
    
        <div class="faq-item">
          <button class="faq-question">How do I track my order ?</button>
          <div class="faq-answer">
            <p>Once your order has shipped, you will receive a tracking number via email. You can use this number on our tracking page to monitor your order's progress.</p>
          </div>
        </div>
    
        <div class="faq-item">
          <button class="faq-question">Can I modify or cancel my order after placing it?</button>
          <div class="faq-answer">
            <p>If you need to make changes to or cancel your order, please contact us within 24 hours of placing your order. After this period, we may not be able to accommodate changes, but we'll do our best to assist you.</p>
          </div>
        </div>
    
        <div class="faq-item">
          <button class="faq-question">What payment methods do you accept?</button>
          <div class="faq-answer">
            <p>We accept major credit cards (Visa, MasterCard, American Express), UPI, and COD.</p>
          </div>
        </div>
    
        <div class="faq-item">
          <button class="faq-question">How can I reset my password?</button>
          <div class="faq-answer">
            <p>If you’ve forgotten your password, simply click on the “Forgot Password” link on the login page. You will receive an email with instructions on how to reset it.</p>
          </div>
        </div>
    
        <div class="faq-item">
          <button class="faq-question">Are your products covered by a warranty?</button>
          <div class="faq-answer">
            <p>Yes, all of our products come with a 6 months to 1-year warranty. If you experience any issues with your product, please reach out to our support team for assistance.</p>
          </div>
        </div>
    
        <div class="faq-item">
          <button class="faq-question">How can I provide feedback on my experience?</button>
          <div class="faq-answer">
            <p>We would love to hear from you! You can submit feedback via our contact page or leave a review on our product pages.</p>
          </div>
        </div>
    
      </div>
</section>


    <section id="contact">
        
        <div class="container">
          <h2>Contact Us</h2>
            <form class="contact-form">
              <label for="name">Full Name:</label>
              <input type="text" id="name" name="name" placeholder="Enter your name" required>
        
              <label for="email">Email Address:</label>
              <input type="email" id="email" name="email" placeholder="Enter your email" required>
        
              <label for="Number">Phone Number:</label>
              <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>

              <label for="message">Message:</label>
              <textarea id="message" name="message" rows="5" placeholder="Enter your message" required></textarea>
        
              <button type="submit">Submit</button>
            </form>
        
            <div class="map-container">
              <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3748.6019796495725!2d73.83234700000001!3d20.025213!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bddea72feacf5a9%3A0x11a7a53be2e2ec40!2sK.K.Wagh%20College%20of%20Agricultural%20Engineering%20%26%20Technology%20Nashik!5e0!3m2!1sen!2sus!4v1738920092837!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
               
            </div>
          </div>
    </section>
    
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
    <script src="script.js"></script>
</body>
 </html>