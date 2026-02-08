<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'] ?? 'Guest';
$user_phone = $_SESSION['user_phone'] ?? 'N/A';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_phone = $_SESSION['user_phone'];


$services = $conn->query("SELECT service_id, service_type, price FROM Services");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_id = $_POST['service'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $stmt = $conn->prepare("SELECT price FROM Services WHERE service_id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $stmt->bind_result($price);
    if (!$stmt->fetch()) {
        die("Error: Selected service does not exist!");
    }
    $stmt->close();
    
    $stmt = $conn->prepare("INSERT INTO Appointments (customer_id, service_id, appointment_date) VALUES (?, ?, ?)");
    $appointment_datetime = $date . ' ' . $time;
    $stmt->bind_param("iis", $user_id, $service_id, $appointment_datetime);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Appointment booked successfully! Total Bill: $$price');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Book Your Service</h2>
        <form method="POST">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" value="<?php echo $user_name; ?>" readonly>
            </div>

            <div class="form-group">
                <label>Phone:</label>
                <input type="text" value="<?php echo $user_phone; ?>" readonly>
            </div>

            <div class="form-group">
                <label>Service:</label>
                <select name="service" required>
                    <option value="" disabled selected>Select a service</option>
                    <?php while ($row = $services->fetch_assoc()): ?>
                        <option value="<?php echo $row['service_id']; ?>">
                            <?php echo $row['service_type']; ?> ₹<?php echo $row['price']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
            <label>Date:</label>
            <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label>Time:</label>
                <input type="time" name="time" required>
            </div>

            <button type="submit">Book Appointment</button>
        </form>
    </div>
</body>
</html>
