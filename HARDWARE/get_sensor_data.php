<?php
include 'koneksi.php';

$sql = "SELECT voltage, current, power, temperature, humidity, used_fila FROM sensor_data ORDER BY timestamp DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode([]);
}

$conn->close();
?>
