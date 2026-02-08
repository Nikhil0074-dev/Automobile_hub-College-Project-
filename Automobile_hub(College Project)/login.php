<?php
session_start();

include 'db.php';

$error_message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; 

    if ($role === 'customer') {
        $select_customer = mysqli_query($conn, "SELECT * FROM `customers` WHERE email = '$email'") or die(mysqli_error($conn));

        if (mysqli_num_rows($select_customer) > 0) {
            $row = mysqli_fetch_assoc($select_customer);
            
            
            if ($row['password'] === $password) {
                $_SESSION['user_id'] = $row['customer_id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_phone'] = $row['phone_number'];
                $_SESSION['role'] = 'customer'; 
                header('location: index.php'); 
                exit();
            }
        }
    } elseif ($role === 'admin') {
       
        $select_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE email = '$email'") or die(mysqli_error($conn));

        if (mysqli_num_rows($select_admin) > 0) {
            $row = mysqli_fetch_assoc($select_admin);
            
            // Check if the password matches
            if ($row['password'] === $password) {
                $_SESSION['admin_id'] = $row['admin_id'];
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['role'] = 'admin'; 
                header('location: index.php'); 
                exit();
            }
        }
    }

    
    $error_message = 'Incorrect email, password, or role!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
            margin: 10px;
            background: black;
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        .site-title {
            font-size: 40px;
            font-weight: bold;
            text-align: center;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }
        nav ul li {
            margin: 0 10px;
        }
        nav ul li a {
            text-decoration: none;
        }
        nav ul li button {
            background-color: #f39c12;
            border: none;
            color: white;
            padding: 10px 10px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }
        nav ul li button:hover {
            background-color: #ffc267;
            font-size: 20px;
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
        h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        p {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        input[type="email"], input[type="password"], select {
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
            margin-bottom: 1rem;
            cursor: pointer;
        }
        button:hover {
            background-color: #fad8a0;
        }
        .error {
            color: red;
            margin-bottom: 1rem;
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
        <nav>
            <ul>
                <li><a href="chat.html"><button>Home</button></a></li>
                <li><a href="#contact"><button>Contact Us</button></a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <div class="image-section">
            <img src="media/car.png" alt="A black super car ">
         </div>
        <div class="form-section" id="login-form">
            <h2>Login</h2>
            <?php if ($error_message): ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" placeholder="Enter your email" required>

                <label for="password">Password</label>
                <input id="password" name="password" type="password" placeholder="Enter your password" required>

                <label for="role">Select Role</label>
                <select id="role" name="role" required>
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>

                <a href="reset.php">Forgot Password?</a>
                <button type="submit">Login</button>
            </form>
            <p class="toggle-form">
                Don't have an account?
                <a href="register.php">Sign Up</a>
            </p>
        </div>
    </div>
</body>
</html>
