<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); 
    exit();
}

$admin_id = $_SESSION['admin_id']; 

if (isset($_GET['delete'])) {
    $part_id = intval($_GET['delete']);
    $delete_query = "DELETE FROM Parts WHERE part_id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $part_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: mparts.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $part_type = $_POST['part_type'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $compatibility = $_POST['compatibility'];
    $image_url = $_POST['image_url'] ?: 'placeholder.jpg';

    if (!empty($_POST['part_id'])) {
        $part_id = intval($_POST['part_id']);
        $update_query = "UPDATE Parts SET part_type=?, price=?, stock=?, compatibility=?, image_url=?, admin_id=? WHERE part_id=?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "sdissii", $part_type, $price, $stock, $compatibility, $image_url, $admin_id, $part_id);
    } else {
        $insert_query = "INSERT INTO Parts (part_type, price, stock, compatibility, image_url, admin_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "sdissi", $part_type, $price, $stock, $compatibility, $image_url, $admin_id);
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: mparts.php");
    exit();
}

$query = "SELECT p.*, a.admin_name AS admin_name FROM Parts p 
          JOIN admin a ON p.admin_id = a.admin_id";
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
    <title>Manage Parts</title>
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
    <h2>Manage Parts</h2>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Part Type</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Compatibility</th>
            <th>Image</th>
            <th>Admin</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['part_id']; ?></td>
                <td><?php echo $row['part_type']; ?></td>
                <td>₹<?php echo $row['price']; ?></td>
                <td><?php echo $row['stock']; ?></td>
                <td><?php echo $row['compatibility']; ?></td>
                <td><img src="media/<?php echo $row['image_url']; ?>" alt="Part Image" width="100"></td>
                <td><?php echo $row['admin_name']; ?></td>
                <td>
                    <button class="btn edit-btn" onclick="editPart(<?php echo $row['part_id']; ?>, '<?php echo $row['part_type']; ?>', <?php echo $row['price']; ?>, <?php echo $row['stock']; ?>, '<?php echo $row['compatibility']; ?>', '<?php echo $row['image_url']; ?>')">Edit</button>
                    <a href="mparts.php?delete=<?php echo $row['part_id']; ?>" class="btn" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <button class="btn" onclick="toggleForm()">Add New Part</button>
    
    <div class="form-container" id="partForm">
        <h3>Add / Edit Part</h3>
        <form method="POST" action="">
            <input type="hidden" name="part_id" id="part_id">
            <label for="part_type">Part type:</label>
            <input type="text" name="part_type" id="part_type" required>
            <label for="price">Price:</label>
            <input type="number" name="price" id="price" required>
            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" required>
            <label for="compatibility">Compatibility:</label>
            <input type="text" name="compatibility" id="compatibility" required>
            <label for="image_url">Image URL:</label>
            <input type="text" name="image_url" id="image_url">
            <input type="submit" value="Save">
        </form>
    </div>

    <script>
        function toggleForm() {
            document.getElementById("partForm").style.display = "block";
        }

        function editPart(id, type, price, stock, compatibility, image) {
            document.getElementById("partForm").style.display = "block";
            document.getElementById("part_id").value = id;
            document.getElementById("part_type").value = type;
            document.getElementById("price").value = price;
            document.getElementById("stock").value = stock;
            document.getElementById("compatibility").value = compatibility;
            document.getElementById("image_url").value = image;
        }
    </script>
</body>
</html>
