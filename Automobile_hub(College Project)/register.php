<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    if ($role == "admin") {
        header("Location: admin-reg.php");
    } else {
        header("Location: cust.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Role</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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
    width: 50%;
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
    font-size: 18px;
    font-weight:bold;
}
input, select, button {
    width: 100%;
    padding: 12px;
    margin-top: 12px;
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
        <h2>Register</h2>
        <form method="POST">
            <label for="role">Choose Role:</label>
            <select id="role" name="role" required>
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit">Next</button>
        </form>
    </div>
</div>

</body>
</html>
