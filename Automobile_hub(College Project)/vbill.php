<?php
session_start();
include 'db.php'; // Database connection


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sale_id'])) {
    $sale_id = intval($_POST['sale_id']);
}

$sql = "SELECT vs.sale_id, c.name AS customer_name, c.email, c.phone_number, 
               v.make, v.model, v.price, vs.sale_date, vs.payment_method 
        FROM Vehiclesales vs
        JOIN Customers c ON vs.customer_id = c.customer_id
        JOIN Vehicles v ON vs.vehicle_id = v.vehicle_id
        WHERE vs.sale_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $sale_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "No sale record found!";
    exit;
}

$row = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Sale Bill</title>
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
    <h2>Vehicle Bill</h2>
    <div class="details"><strong>Bill No:</strong> <?php echo $row['sale_id']; ?></div>
    <div class="details"><strong>Customer Name:</strong> <?php echo $row['customer_name']; ?></div>
    <div class="details"><strong>Email:</strong> <?php echo $row['email']; ?></div>
    <div class="details"><strong>Phone:</strong> <?php echo $row['phone_number']; ?></div>
    <hr>
    <div class="details"><strong>Vehicle:</strong> <?php echo $row['make'] . ' ' . $row['model']; ?></div>
    <div class="details"><strong>Price:</strong> ₹ <?php echo number_format($row['price'], 2); ?></div>
    <div class="details"><strong>Sale Date:</strong> <?php echo $row['sale_date']; ?></div>
    <div class="details"><strong>Payment Method:</strong> <?php echo ucfirst($row['payment_method']); ?></div>
    <hr>
    <button class="print-btn" onclick="window.print()">Print Bill</button>
</div>

</body>
</html>