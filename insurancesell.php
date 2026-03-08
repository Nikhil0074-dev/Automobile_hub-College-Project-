<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = " ";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insurance_id'])) {
    $customer_id = $_SESSION['user_id'];
    $insurance_id = intval($_POST['insurance_id']);
    
    $vehicle_no = trim($_POST['vehicle_no'] ?? '');
    $payment_method = $_POST['payment_method'] ?? '';

    if (empty($vehicle_no)) {
        $error = "Vehicle number is required.";
    } elseif (!in_array($payment_method, ['cash', 'card'])) {
        $error = "Invalid payment method.";
    } else { 
        
        $check_sql = "SELECT COUNT(*) FROM InsuranceSale WHERE vehicle_no = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $vehicle_no);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            $error = "This vehicle is already insured.";
        } else {
            
            $sql = "SELECT premium FROM Insurance WHERE insurance_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $insurance_id);
            $stmt->execute();
            $stmt->bind_result($sale_price);
            $stmt->fetch();
            $stmt->close();

            if ($sale_price) {
               
            $admin_id = $_SESSION['admin_id'] ?? 0;
            
            $insert_sql = "INSERT INTO InsuranceSale (customer_id, admin_id, insurance_id, vehicle_no, sale_price, payment_method) 
                           VALUES (?, ?, ?, ?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("iiisss", $customer_id, $admin_id, $insurance_id, $vehicle_no, $sale_price, $payment_method);

                if ($insert_stmt->execute()) {
                    echo "<script>
                            alert('Insurance purchase successful!');
                            window.location.href = 'insurance.php';
                          </script>";
                    exit();
                } else {
                    $error = "Error processing purchase.";
                }
                $insert_stmt->close();
            } else {
                $error = "Invalid insurance selection.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Purchase</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #f39c12, #f1c40f);
            text-align: center;
            padding: 50px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
        }
        h2 {
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background:rgb(244, 164, 5);
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }
        button:hover {
            background:rgb(244, 164, 5);
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Purchase Insurance</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="">
            <input type="hidden" name="insurance_id" value="<?php echo htmlspecialchars($_POST['insurance_id'] ?? ''); ?>">
            
            <label for="vehicle_no">Vehicle Number:</label>
            <input type="text" id="vehicle_no" name="vehicle_no" required>

            <label for="payment_method">Payment Method:</label>
            <select id="payment_method" name="payment_method" required>
                <option value="cash">Cash</option>
                <option value="card">Card</option>
            </select>

            <button type="submit">Confirm Purchase</button>
        </form>
    </div>
</body>
</html>
