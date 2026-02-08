<?php
session_start();
include 'db.php'; 

$admin_id = $_SESSION['admin_id'];


if (isset($_GET['delete'])) {
    $service_id = intval($_GET['delete']);
    $delete_query = "DELETE FROM Services WHERE service_id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $service_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: mservice.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_name = $_POST['service_name'];
    $price = $_POST['price'];
    $service_type = $_POST['service_type'];

    if (!empty($_POST['service_id'])) {
        
        $service_id = intval($_POST['service_id']);
        $update_query = "UPDATE Services SET service_name=?, price=?, service_type=?, admin_id=? WHERE service_id=?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "sdsii", $service_name, $price, $service_type, $admin_id, $service_id);
    } else {
        
        $insert_query = "INSERT INTO Services (service_name, price, service_type, admin_id) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "sdsi", $service_name, $price, $service_type, $admin_id);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: mservice.php");
    exit();
}


$query = "SELECT s.*, a.admin_name AS admin_name FROM Services s 
          JOIN Admin a ON s.admin_id = a.admin_id";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
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
            width: 90%;
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
        .form-container {
            width: 50%;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: left;
            display: none;
        }
        .form-container label, .form-container input {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        .form-container input[type="submit"] {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <h2>Manage Services</h2>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Service Name</th>
            <th>Price</th>
            <th>Service Type</th>
            <th>Last Modified By</th> 
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['service_id']; ?></td>
                <td><?php echo $row['service_name']; ?></td>
                <td>₹<?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo ucfirst($row['service_type']); ?></td>
                <td><?php echo $row['admin_name']; ?></td> 
                <td>
                    <button class="btn edit-btn" onclick="editService(<?php echo $row['service_id']; ?>, '<?php echo $row['service_name']; ?>', <?php echo $row['price']; ?>, '<?php echo $row['service_type']; ?>')">Edit</button>
                    <a href="mservice.php?delete=<?php echo $row['service_id']; ?>" class="btn" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <button class="btn" onclick="toggleForm()">Add New Service</button>
    
    <div class="form-container" id="serviceForm">
        <h3>Add / Edit Service</h3>
        <form method="POST" action="">
            <input type="hidden" name="service_id" id="service_id">
            <label for="service_name">Service Name:</label>
            <input type="text" name="service_name" id="service_name" required>
            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" required>
            <label for="service_type">Service Type:</label>
            <select name="service_type" id="service_type" required>
                <option value="repair">Repair</option>
                <option value="ev">EV</option>
                <option value="maintainance">Maintenance</option>
                <option value="customization">Customization</option>
            </select>
            <input type="submit" value="Save">
        </form>
    </div>

    <script>
        function toggleForm() {
            document.getElementById("serviceForm").style.display = "block";
            document.getElementById("service_id").value = "";
            document.getElementById("service_name").value = "";
            document.getElementById("price").value = "";
            document.getElementById("service_type").value = "repair";
        }

        function editService(id, name, price, type) {
            document.getElementById("serviceForm").style.display = "block";
            document.getElementById("service_id").value = id;
            document.getElementById("service_name").value = name;
            document.getElementById("price").value = price;
            document.getElementById("service_type").value = type;
        }
    </script>
</body>
</html>
