<?php
include "koneksi.php";

// Update status SSR jika ada request dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Toggle the status based on the current value
  $currentStatus = $_POST['current_status'];
  $newStatus = ($currentStatus == "ON") ? "OFF" : "ON";
  
  $sql = "UPDATE ssr_control SET status='$newStatus' WHERE id=1";
  $conn->query($sql);
}

// Ambil status SSR dari database
$sql = "SELECT status FROM ssr_control WHERE id=1";
$result = $conn->query($sql);
$status = $result->fetch_assoc()['status'];
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSR Control</title>
    <style>
        .button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            color: white;
        }
        .on {
            background-color: green;
        }
        .off {
            background-color: red;
        }
    </style>
</head>
<body>
    <h1>SSR Control</h1>
    <form method="post">
        <input type="hidden" name="current_status" value="<?php echo $status; ?>">
        <button type="submit" class="button <?php echo ($status == 'ON') ? 'on' : 'off'; ?>">
            <?php echo ($status == 'ON') ? 'Turn OFF' : 'Turn ON'; ?>
        </button>
    </form>
</body>
</html>
