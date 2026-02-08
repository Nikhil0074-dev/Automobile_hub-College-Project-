<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['user_id'];


if (!isset($_POST['part_id']) || empty($_POST['part_id'])) {
    echo "<p>Invalid part selection.</p>";
    exit();
}

$part_id = intval($_POST['part_id']);

$sql = "SELECT * FROM Parts WHERE part_id = ? AND stock > 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $part_id);
$stmt->execute();
$result = $stmt->get_result();
$part = $result->fetch_assoc();

if (!$part) {
    echo "<p>Part not found or out of stock.</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Part</title>
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
        .part-details {
            text-align: left;
            margin-bottom: 20px;
        }
        .part-details p {
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
        <div class="part-details">
            <p><strong>Part Type:</strong> <?php echo htmlspecialchars($part['part_type']); ?></p>
            <p><strong>Compatibility:</strong> <?php echo htmlspecialchars($part['compatibility']); ?></p>
            <p><strong>Price:</strong> ₹<?php echo number_format($part['price'], 2); ?></p>
        </div>
        <form method="POST">
            <input type="hidden" name="part_id" value="<?php echo $part_id; ?>">
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
    
    $sale_sql = "INSERT INTO PartSales (customer_id, part_id, sale_price, payment_method) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sale_sql);
    $stmt->bind_param("iids", $customer_id, $part_id, $part['price'], $payment_method);
    $stmt->execute();
    
    $update_stock_sql = "UPDATE Parts SET stock = stock - 1 WHERE part_id = ?";
    $stmt = $conn->prepare($update_stock_sql);
    $stmt->bind_param("i", $part_id);
    $stmt->execute();
    
    echo "<script>alert('Payment successful! Thank you for your purchase.'); window.location.href='part.php';</script>";
    exit();
}
?>
