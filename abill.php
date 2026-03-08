<?php
session_start();
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointment_id'])) {
    $appointment_id = intval($_POST['appointment_id']);
} else {
    echo "Invalid request!";
    exit;
}

$sql = "SELECT a.appointment_id, c.name AS customer_name, c.email, c.phone_number, 
               s.service_name, s.service_type, s.price, a.appointment_date
        FROM Appointments a
        JOIN Customers c ON a.customer_id = c.customer_id
        JOIN Services s ON a.service_id = s.service_id
        WHERE a.appointment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "No appointment record found!";
    exit;
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Bill</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .bill-container {
            border: 2px solid #000;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            background: #f9f9f9;
        }
        h2 {
            text-align: center;
        }
        .details {
            margin-bottom: 10px;
        }
        .print-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: white;
            text-align: center;
            font-size: 16px;
            cursor: pointer;
            border: none;
            margin-top: 15px;
        }
        .print-btn:hover {
            background: #218838;
        }
        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="bill-container">
    <h1>Automobile Hub</h1>
    <h2>Appointment Bill</h2>
    <div class="details"><strong>Bill No:</strong> <?php echo $row['appointment_id']; ?></div>
    <div class="details"><strong>Customer Name:</strong> <?php echo $row['customer_name']; ?></div>
    <div class="details"><strong>Email:</strong> <?php echo $row['email']; ?></div>
    <div class="details"><strong>Phone:</strong> <?php echo $row['phone_number']; ?></div>
    <hr>
    <div class="details"><strong>Service Type:</strong> <?php echo $row['service_name'] . ' - ' . $row['service_type']; ?></div>
    <div class="details"><strong>Price:</strong> ₹ <?php echo number_format($row['price'], 2); ?></div>
    <div class="details"><strong>Appointment Date:</strong> <?php echo $row['appointment_date']; ?></div>
    <hr>
    <button class="print-btn" onclick="window.print()">Print Bill</button>
</div>

</body>
</html>