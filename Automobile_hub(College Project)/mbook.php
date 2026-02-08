<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION['admin_id'])) {
    die("Access denied. Admin not logged in.");
}

$admin_id = $_SESSION['admin_id'];

if (isset($_POST['update_status'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $new_status = $_POST['status'];

    $update_query = "UPDATE Appointments SET status = ?, admin_id = ? WHERE appointment_id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "sii", $new_status, $admin_id, $appointment_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: mbook.php");
    exit();
}


$query = "SELECT A.appointment_id, C.name, C.email, S.service_type, S.price, A.appointment_date, A.status, A.admin_id, Admin.admin_name
          FROM Appointments A
          JOIN Customers C USING (customer_id)
          JOIN Services S USING (service_id)
          LEFT JOIN Admin ON A.admin_id = Admin.admin_id
          ORDER BY A.appointment_date DESC";
$result = mysqli_query($conn, $query) or die("Query Failed: " . mysqli_error($conn));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointments</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }
        table { width: 90%; margin: auto; border-collapse: collapse; background: white; box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2); }
        th, td { padding: 15px; border: 1px solid #ddd; text-align: center; }
        th { background: #333; color: white; }
        .status { padding: 5px 10px; border-radius: 5px; }
        .booked { background: yellow; }
        .completed { background: green; color: white; }
        .canceled { background: red; color: white; }
        .btn { padding: 8px 12px; background: #007bff; color: white; border: none; cursor: pointer; text-decoration: none; border-radius: 5px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h2>Appointments</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Service</th>
            <th>Price</th>
            <th>Appointment Date</th>
            <th>Status</th>
            <th>Updated By</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['appointment_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['service_type']; ?></td>
                <td>₹<?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo $row['appointment_date']; ?></td>
                <td class="status <?php echo $row['status']; ?>"> <?php echo ucfirst($row['status']); ?> </td>
                <td><?php echo $row['admin_name'] ? $row['admin_name'] : 'Not updated'; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="appointment_id" value="<?php echo $row['appointment_id']; ?>">
                        <select name="status">
                            <option value="booked" <?php if ($row['status'] == 'booked') echo 'selected'; ?>>Booked</option>
                            <option value="completed" <?php if ($row['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                            <option value="canceled" <?php if ($row['status'] == 'canceled') echo 'selected'; ?>>Canceled</option>
                        </select>
                        <button type="submit" name="update_status" class="btn">Update</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
