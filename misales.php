<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    die("Access denied. Admin not logged in.");
}

$admin_id = $_SESSION['admin_id'];

if (isset($_POST['update_status'])) {
    $sale_id = intval($_POST['sale_id']);
    $new_status = $_POST['status'];
    
    $update_query = "UPDATE insurancesale SET status = ?, admin_id = ? WHERE sale_id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "sii", $new_status, $admin_id, $sale_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    header("Location: misales.php");
    exit();
}

$query = "SELECT S.sale_id, C.name, C.email, I.insurance_company, I.insurance_type, 
                 S.sale_price, S.sale_date, S.payment_method, S.status, A.admin_name 
          FROM insurancesale S
          JOIN customers C ON S.customer_id = C.customer_id
          JOIN insurance I ON S.insurance_id = I.insurance_id
          LEFT JOIN admin A ON S.admin_id = A.admin_id
          ORDER BY S.sale_date DESC";

$result = mysqli_query($conn, $query) or die("Query Failed: " . mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Sales</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            padding: 20px;
            text-align: center;
        }
        h2 {
            background-color: #333;
            color: white;
            padding: 20px 0;
            margin: 0 0 20px;
            font-size: 28px;
        }
        table {
            width: 100%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }
        th, td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #333;
            color: white;
        }
        .btn { 
            padding: 8px 12px; 
            background: #007bff; 
            color: white; 
            border: none; 
            cursor: pointer; 
            text-decoration: none; 
            border-radius: 5px; 
        }
        .btn:hover { 
            background: #0056b3; 
        }
        button{
            padding: 8px 12px; 
            background:rgb(255, 190, 10); 
            color: white; 
            border: none; 
            cursor: pointer; 
            text-decoration: none; 
            border-radius: 5px
        }
    </style>
</head>
<body>
    <h2>Insurance Sales</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Insurance Company</th>
            <th>Insurance Type</th>
            <th>Sale Price</th>
            <th>Sale Date</th>
            <th>Payment Method</th>
            <th>Sold By</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['sale_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['insurance_company']; ?></td>
                <td><?php echo $row['insurance_type']; ?></td>
                <td>₹<?php echo number_format($row['sale_price'], 2); ?></td>
                <td><?php echo $row['sale_date']; ?></td>
                <td><?php echo ucfirst($row['payment_method']); ?></td>
                <td><?php echo !empty($row['admin_name']) ? $row['admin_name'] : "Unknown"; ?></td>
                <td><?php echo ucfirst($row['status']); ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="sale_id" value="<?php echo $row['sale_id']; ?>">
                        <select name="status">
                            <option value="in process" <?php echo ($row['status'] == 'in process') ? 'selected' : ''; ?>>In process</option>
                            <option value="insured" <?php echo ($row['status'] == 'insured') ? 'selected' : ''; ?>>Insured</option>
                        </select>
                        <button type="submit" name="update_status" class="btn">Update</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
    <br>
    <button onclick="window.print()">Print</button>
</body>
</html>

