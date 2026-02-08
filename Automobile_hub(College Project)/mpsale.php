<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION['admin_id'])) {
    die("Access denied. Admin not logged in.");
}

$admin_id = $_SESSION['admin_id'];

if (isset($_POST['update_delivery'])) {
    $sale_id = intval($_POST['sale_id']);
    $new_status = $_POST['delivery_status'];
    
    $update_query = "UPDATE PartSales SET delivery = ?, admin_id = ? WHERE sale_id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "sii", $new_status, $admin_id, $sale_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: mpsale.php");
    exit();
}

$query = "SELECT PS.sale_id, C.name, C.email, P.part_type, PS.sale_price, PS.sale_date, PS.payment_method, PS.delivery, A.admin_name
          FROM PartSales PS
          JOIN Customers C ON PS.customer_id = C.customer_id
          JOIN Parts P ON PS.part_id = P.part_id
          LEFT JOIN Admin A ON PS.admin_id = A.admin_id
          ORDER BY PS.sale_date DESC";
$result = mysqli_query($conn, $query) or die("Query Failed: " . mysqli_error($conn));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Part Sales</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }
        table { width: 90%; margin: auto; border-collapse: collapse; background: white; box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2); }
        th, td { padding: 15px; border: 1px solid #ddd; text-align: center; }
        th { background: #333; color: white; }
        .delivered { background: green; color: white; padding: 5px; border-radius: 5px; }
        .not_delivered { background: red; color: white; padding: 5px; border-radius: 5px; }
        .btn { padding: 8px 12px; background: #007bff; color: white; border: none; cursor: pointer; text-decoration: none; border-radius: 5px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h2>Part Sales</h2>
    <table>
        <tr>
            <th>Sale ID</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Part Type</th>
            <th>Sale Price</th>
            <th>Sale Date</th>
            <th>Payment Method</th>
            <th>Sold By</th>
            <th>Delivery Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['sale_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['part_type']; ?></td>
                <td>₹<?php echo number_format($row['sale_price'], 2); ?></td>
                <td><?php echo $row['sale_date']; ?></td>
                <td><?php echo ucfirst($row['payment_method']); ?></td>
                <td><?php echo !empty($row['admin_name']) ? $row['admin_name'] : "Unknown"; ?></td>
                <td>
                    <span class="<?php echo $row['delivery'] == 'delivered' ? 'delivered' : 'not_delivered'; ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $row['delivery'])); ?>
                    </span>
                </td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="sale_id" value="<?php echo $row['sale_id']; ?>">
                        <select name="delivery_status">
                            <option value="delivered" <?php echo $row['delivery'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                            <option value="not_delivered" <?php echo $row['delivery'] == 'not_delivered' ? 'selected' : ''; ?>>Not Delivered</option>
                        </select>
                        <button type="submit" name="update_delivery" class="btn">Update</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
