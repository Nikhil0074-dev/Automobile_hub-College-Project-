<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized access!");
}

$admin_id = $_SESSION['admin_id'];
if (isset($_GET['delete'])) {
    $vehicle_id = intval($_GET['delete']);
    $delete_query = "DELETE FROM Vehicles WHERE vehicle_id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $vehicle_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: mvehicle.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image_url = $_POST['image_url'];

    if (!empty($_POST['vehicle_id'])) {
        $vehicle_id = intval($_POST['vehicle_id']);
        $update_query = "UPDATE Vehicles SET make=?, model=?, price=?, stock=?, image_url=?, admin_id=? WHERE vehicle_id=?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "ssdssii", $make, $model, $price, $stock, $image_url, $admin_id, $vehicle_id);
    } else {
        $insert_query = "INSERT INTO Vehicles (make, model, price, stock, image_url, admin_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "ssdssi", $make, $model, $price, $stock, $image_url, $admin_id);
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: mvehicle.php");
    exit();
}

$query = "SELECT v.*, a.admin_name AS admin_name FROM Vehicles v 
          LEFT JOIN admin a ON v.admin_id = a.admin_id";
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
    <title>Manage Vehicles</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS file -->
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
    <script>
        function toggleForm() {
            let form = document.getElementById("vehicleForm");
            form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
        }

        function editVehicle(id, make, model, price, stock, image_url) {
            document.getElementById("vehicle_id").value = id;
            document.getElementById("make").value = make;
            document.getElementById("model").value = model;
            document.getElementById("price").value = price;
            document.getElementById("stock").value = stock;
            document.getElementById("image_url").value = image_url;
            document.getElementById("vehicleForm").style.display = "block";
        }
    </script>
</head>
<body>
    <h2>Manage Vehicles</h2>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Make</th>
            <th>Model</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Image</th>
            <th>Modified By</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['vehicle_id']; ?></td>
                <td><?php echo $row['make']; ?></td>
                <td><?php echo $row['model']; ?></td>
                <td>₹<?php echo $row['price']; ?></td>
                <td><?php echo $row['stock']; ?></td>
                <td><img src="media/<?php echo $row['image_url']; ?>" alt="Vehicle Image" width="100"></td>
                <td><?php echo $row['admin_name'] ? $row['admin_name'] : 'Unknown'; ?></td>
                <td>
                    <button class="btn edit-btn" onclick="editVehicle(<?php echo $row['vehicle_id']; ?>, '<?php echo $row['make']; ?>', '<?php echo $row['model']; ?>', <?php echo $row['price']; ?>, <?php echo $row['stock']; ?>, '<?php echo $row['image_url']; ?>')">Edit</button>
                    <a href="mvehicle.php?delete=<?php echo $row['vehicle_id']; ?>" class="btn" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <button class="btn" onclick="toggleForm()">Add New Vehicle</button>
    
    <div class="form-container" id="vehicleForm">
        <h3>Add / Edit Vehicle</h3>
        <form method="POST" action="">
            <input type="hidden" name="vehicle_id" id="vehicle_id">
            <label for="make">Make:</label>
            <input type="text" name="make" id="make" required>
            <label for="model">Model:</label>
            <input type="text" name="model" id="model" required>
            <label for="price">Price:</label>
            <input type="number" name="price" id="price" required>
            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" required>
            <label for="image_url">Image URL:</label>
            <input type="text" name="image_url" id="image_url" required>
            <input type="submit" value="Save">
        </form>
    </div>
</body>
</html>
