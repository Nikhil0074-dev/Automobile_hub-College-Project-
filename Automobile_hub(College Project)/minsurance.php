<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id']; 

if (isset($_GET['delete'])) {
    $insurance_id = intval($_GET['delete']);
    $delete_query = "DELETE FROM Insurance WHERE insurance_id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $insurance_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: minsurance.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $insurance_company = trim($_POST['insurance_company']);
    $insurance_type = trim($_POST['insurance_type']);
    $premium = floatval($_POST['premium']);
    $image_url = !empty($_POST['image_url']) ? trim($_POST['image_url']) : 'placeholder.jpg'; 

    if (!empty($_POST['insurance_id'])) {
        $insurance_id = intval($_POST['insurance_id']);
        $update_query = "UPDATE Insurance SET insurance_company=?, insurance_type=?, premium=?, image_url=?, admin_id=? WHERE insurance_id=?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "ssdsii", $insurance_company, $insurance_type, $premium, $image_url, $admin_id, $insurance_id);
    } else {
        $insert_query = "INSERT INTO Insurance (insurance_company, insurance_type, premium, image_url, admin_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "ssdsi", $insurance_company, $insurance_type, $premium, $image_url, $admin_id);
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: minsurance.php");
    exit();
}

$query = "SELECT i.*, a.admin_name FROM Insurance i 
          LEFT JOIN admin a ON i.admin_id = a.admin_id";
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
    <title>Manage Insurances</title>
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
    <h2>Manage Insurances</h2>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Insurance Company</th>
            <th>Type</th>
            <th>Premium</th>
            <th>Image</th>
            <th>Admin</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['insurance_id']; ?></td>
                <td><?php echo htmlspecialchars($row['insurance_company']); ?></td>
                <td><?php echo htmlspecialchars($row['insurance_type']); ?></td>
                <td>₹<?php echo number_format($row['premium'], 2); ?></td>
                <td><img src="media/<?php echo htmlspecialchars($row['image_url']); ?>" alt="Insurance Image" width="100"></td>
                <td><?php echo htmlspecialchars($row['admin_name']); ?></td>
                <td>
                    <button class="btn edit-btn" onclick="editInsurance(<?php echo $row['insurance_id']; ?>, '<?php echo addslashes($row['insurance_company']); ?>', '<?php echo addslashes($row['insurance_type']); ?>', <?php echo $row['premium']; ?>, '<?php echo addslashes($row['image_url']); ?>')">Edit</button>
                    <a href="minsurance.php?delete=<?php echo $row['insurance_id']; ?>" class="btn" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
<br>
    <button class="btn" onclick="toggleForm()">Add New Insurance</button>
    
    <div class="form-container" id="insuranceForm">
        <h3>Add / Edit Insurance</h3>
        <form method="POST" action="">
            <input type="hidden" name="insurance_id" id="insurance_id">
            <label for="insurance_company">Insurance Company:</label>
            <input type="text" name="insurance_company" id="insurance_company" required>
            <label for="insurance_type">Type:</label>
            <input type="text" name="insurance_type" id="insurance_type" required>
            <label for="premium">Premium:</label>
            <input type="number" step="0.01" name="premium" id="premium" required>
            <label for="image_url">Image URL:</label>
            <input type="text" name="image_url" id="image_url">
            <input type="submit" value="Save">
        </form>
    </div>

    <script>
        function toggleForm() {
            document.getElementById("insuranceForm").style.display = "block";
        }

        function editInsurance(id, company, type, premium, image) {
            document.getElementById("insuranceForm").style.display = "block";
            document.getElementById("insurance_id").value = id;
            document.getElementById("insurance_company").value = company;
            document.getElementById("insurance_type").value = type;
            document.getElementById("premium").value = premium;
            document.getElementById("image_url").value = image;
        }
    </script>
</body>
</html>
