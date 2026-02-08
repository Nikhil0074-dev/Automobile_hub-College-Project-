<?php
session_start();
include 'db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$customer_id = $_SESSION['user_id'];

if (!isset($_POST['vehicle_id']) || empty($_POST['vehicle_id'])) {
    echo "<p>Invalid vehicle selection.</p>";
    exit();
}

$vehicle_id = intval($_POST['vehicle_id']);

$sql = "SELECT * FROM Vehicles WHERE vehicle_id = ? AND status = 'available'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vehicle_id);
$stmt->execute();
$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();

if (!$vehicle) {
    echo "<p>Vehicle not found or already sold.</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Vehicle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h2 {
            color: #444;
        }
        .vehicle-details {
            text-align: left;
            margin-bottom: 20px;
        }
        .vehicle-details p {
            font-size: 18px;
            margin: 5px 0;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: #f39c12;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #e08e0b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Billing Details</h2>
        <div class="vehicle-details">
            <p><strong>Make & Model:</strong> <?php echo htmlspecialchars($vehicle['make']) . " " . htmlspecialchars($vehicle['model']); ?></p>
            <p><strong>Price:</strong>₹<?php echo number_format($vehicle['price'], 2); ?></p>
            <p><strong>Color:</strong> <?php echo htmlspecialchars($vehicle['color']); ?></p>
            <p><strong>Transmission:</strong> <?php echo htmlspecialchars($vehicle['transmission']); ?></p>
            <p><strong>Fuel Type:</strong> <?php echo htmlspecialchars($vehicle['fuel_type']); ?></p>
        </div>
        <form method="POST">
            <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
            <label for="payment_method">Select Payment Method:</label>
            <select name="payment_method" required>
                <option value="card">Card</option>
                <option value="cash">Cash</option>
            </select>
            <button type="submit">Confirm Purchase</button>
        </form>
    </div>
</body>
</html>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['payment_method'])) {
    $payment_method = $_POST['payment_method'];
    
   
    if (!in_array($payment_method, ['cash', 'card'])) {
        echo "<script>alert('Invalid payment method selected.');</script>";
        exit();
    }
    
    $sale_sql = "INSERT INTO VehicleSales (customer_id, vehicle_id, sale_price, payment_method) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sale_sql);
    $stmt->bind_param("iids", $customer_id, $vehicle_id, $vehicle['price'], $payment_method);
    $stmt->execute();
    
    $update_stock_sql = "UPDATE Vehicles SET stock = stock - 1 WHERE vehicle_id = ?";
    $stmt = $conn->prepare($update_stock_sql);
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    
    $check_stock_sql = "SELECT stock FROM Vehicles WHERE vehicle_id = ?";
    $stmt = $conn->prepare($check_stock_sql);
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $stock_result = $stmt->get_result();
    $stock_data = $stock_result->fetch_assoc();
    
    if ($stock_data['stock'] <= 0) {
        $mark_sold_sql = "UPDATE Vehicles SET status = 'sold' WHERE vehicle_id = ?";
        $stmt = $conn->prepare($mark_sold_sql);
        $stmt->bind_param("i", $vehicle_id);
        $stmt->execute();
    }
    
    echo "<script>alert('Payment successful! Thank you for your purchase.'); window.location.href='index.php';</script>";
    exit();
}
?>
