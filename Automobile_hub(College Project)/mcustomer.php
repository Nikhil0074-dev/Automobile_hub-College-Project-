<?php
session_start();
include 'db.php'; 

$sql = "SELECT customer_id, name, email, phone_number,address, adhar, pan, created_at FROM customers";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>
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
        img {
            width: 100px;
            height: auto;
            cursor: pointer;
        }
        iframe {
            width: 100px;
            height: 120px;
            border: none;
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
    <script>
        function openInNewTab(url) {
            window.open(url, '_blank').focus();
        }
    </script>
</head>
<body>
   
    <h2>Customer Management</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>address</th>
            <th>Aadhaar File</th>
            <th>PAN File</th>
        </tr>
        
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $adharPath = $row['adhar'];
                $panPath = $row['pan'];

                $adharExt = pathinfo($adharPath, PATHINFO_EXTENSION);
                if (in_array($adharExt, ['jpg', 'jpeg', 'png'])) {
                    $adharDisplay = "<img src='$adharPath' alt='Aadhaar Image' onclick='openInNewTab(\"$adharPath\")'>";
                } elseif ($adharExt === 'pdf') {
                    $adharDisplay = "<a href='$adharPath' target='_blank'>View Aadhaar</a>";
                } else {
                    $adharDisplay = "<a href='$adharPath' target='_blank'>View Aadhaar</a>";
                }

        
                $panExt = pathinfo($panPath, PATHINFO_EXTENSION);
                if (in_array($panExt, ['jpg', 'jpeg', 'png'])) {
                    $panDisplay = "<img src='$panPath' alt='PAN Image' onclick='openInNewTab(\"$panPath\")'>";
                } elseif ($panExt === 'pdf') {
                    $panDisplay = "<a href='$panPath' target='_blank'>View PAN</a>";
                } else {
                    $panDisplay = "<a href='$panPath' target='_blank'>View PAN</a>";
                }

                echo "<tr>
                        <td>{$row['customer_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone_number']}</td>
                        <td>{$row['address']}</td>
                        <td>$adharDisplay</td>
                        <td>$panDisplay</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No customers found.</td></tr>";
        }
        ?>

    </table><br>
    <button onclick="window.print()">Print</button>
</body>
</html>

<?php
$conn->close();
?>
