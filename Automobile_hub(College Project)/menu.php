<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch vehicle purchases
$vehicleSalesQuery = "SELECT vs.sale_id, v.make, v.model, vs.sale_price, vs.sale_date, vs.payment_method, vs.delivery 
                      FROM vehiclesales vs
                      JOIN vehicles v ON vs.vehicle_id = v.vehicle_id
                      WHERE vs.customer_id = ?";
$stmt = $conn->prepare($vehicleSalesQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$vehicleSales = $stmt->get_result();

// Fetch spare part purchases
$partSalesQuery = "SELECT ps.sale_id, p.part_type, ps.sale_price, ps.sale_date, ps.payment_method, ps.delivery 
                   FROM partsales ps
                   JOIN parts p ON ps.part_id = p.part_id
                   WHERE ps.customer_id = ?";
$stmt = $conn->prepare($partSalesQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$partSales = $stmt->get_result();

// Fetch insurance purchases
$insuranceSalesQuery = "SELECT ins.sale_id, i.insurance_company, i.insurance_type, ins.sale_price, ins.sale_date, ins.payment_method 
                        FROM insurancesale ins
                        JOIN insurance i ON ins.insurance_id = i.insurance_id
                        WHERE ins.customer_id = ?";
$stmt = $conn->prepare($insuranceSalesQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$insuranceSales = $stmt->get_result();

// Fetch appointments
$appointmentsQuery = "SELECT a.appointment_id, s.service_name, s.price, a.appointment_date, a.status 
                      FROM appointments a
                      JOIN services s ON a.service_id = s.service_id
                      WHERE a.customer_id = ?";
$stmt = $conn->prepare($appointmentsQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$appointments = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background-color: #f8f9fa; }
        .container { max-width: 900px; margin: auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .header { background: #007BFF; color: white; padding: 10px; font-size: 24px; }
        .home-btn { float: right; color: white; text-decoration: none; background: #28a745; padding: 5px 10px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; display: none; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .toggle-btn { margin-top: 10px; padding: 10px; cursor: pointer; background: #007BFF; color: white; border: none; border-radius: 5px; }
        .toggle-btn:hover { background: #0056b3; }
        .download-btn { background: #28a745; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer; }
        .download-btn:hover { background: #218838; }
    </style>
    <script>
        function toggleTable(id) {
            var table = document.getElementById(id);
            table.style.display = (table.style.display === "none" || table.style.display === "") ? "table" : "none";
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="header">Customer Dashboard <a href="index.php" class="home-btn">Home</a></div>
        
        <button class="toggle-btn" onclick="toggleTable('vehicleTable')">View Vehicle Purchases</button>
        <table id="vehicleTable">
            <tr>
                <th>Make</th>
                <th>Model</th>
                <th>Sale Price</th>
                <th>Sale Date</th>
                <th>Payment Method</th>
                <th>Delivery Status</th>
                <th>Invoice</th>
            </tr>
            <?php while ($row = $vehicleSales->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['make']) ?></td>
                    <td><?= htmlspecialchars($row['model']) ?></td>
                    <td>$<?= htmlspecialchars($row['sale_price']) ?></td>
                    <td><?= htmlspecialchars($row['sale_date']) ?></td>
                    <td><?= htmlspecialchars($row['payment_method']) ?></td>
                    <td><?= htmlspecialchars($row['delivery']) ?></td>
                    <td>
                        <form action="vbill.php" method="POST">
                            <input type="hidden" name="sale_id" value="<?= $row['sale_id'] ?>">
                            <button type="submit" class="download-btn">Download</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <button class="toggle-btn" onclick="toggleTable('partTable')">View Part Purchases</button>
        <table id="partTable">
            <tr>
                <th>Part Type</th>
                <th>Sale Price</th>
                <th>Sale Date</th>
                <th>Payment Method</th>
                <th>Delivery Status</th>
                <th>Invoice</th>
            </tr>
            <?php while ($row = $partSales->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['part_type']) ?></td>
                    <td>$<?= htmlspecialchars($row['sale_price']) ?></td>
                    <td><?= htmlspecialchars($row['sale_date']) ?></td>
                    <td><?= htmlspecialchars($row['payment_method']) ?></td>
                    <td><?= htmlspecialchars($row['delivery']) ?></td>
                    <td>
                        <form action="pbill.php" method="POST">
                            <input type="hidden" name="sale_id" value="<?= $row['sale_id'] ?>">
                            <button type="submit" class="download-btn">Download</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <button class="toggle-btn" onclick="toggleTable('insuranceTable')">View Insurance Purchases</button>
        <table id="insuranceTable">
            <tr>
                <th>Company</th>
                <th>Type</th>
                <th>Sale Price</th>
                <th>Sale Date</th>
                <th>Payment Method</th>
                <th>Invoice </th>
            </tr>
            <?php while ($row = $insuranceSales->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['insurance_company']) ?></td>
                    <td><?= htmlspecialchars($row['insurance_type']) ?></td>
                    <td>$<?= htmlspecialchars($row['sale_price']) ?></td>
                    <td><?= htmlspecialchars($row['sale_date']) ?></td>
                    <td><?= htmlspecialchars($row['payment_method']) ?></td>
                    <td>
                        <form action="ibill.php" method="POST">
                            <input type="hidden" name="sale_id" value="<?= $row['sale_id'] ?>">
                            <button type="submit" class="download-btn">Download</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <button class="toggle-btn" onclick="toggleTable('appointmentTable')">View Appointments</button>
        <table id="appointmentTable">
            <tr>
                <th>Service</th>
                <th>Price</th>
                <th>Appointment Date</th>
                <th>Status</th>
                <th>Invoice</th>
            </tr>
            <?php while ($row = $appointments->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['service_name']) ?></td>
                    <td>$<?= htmlspecialchars($row['price']) ?></td>
                    <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <form action="abill.php" method="POST">
                            <input type="hidden" name="appointment_id" value="<?= $row['appointment_id'] ?>">
                            <button type="submit" class="download-btn">Download</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
