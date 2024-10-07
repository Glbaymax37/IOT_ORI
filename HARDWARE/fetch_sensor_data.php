<?php
include 'koneksi.php';
// Mengambil data terbaru dari database
$sql = "SELECT tegangan, arus, daya, energi, suhu, kelembapan, used FROM sensor_data ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

// Memeriksa apakah data tersedia
if ($result->num_rows > 0) {
    echo "<table><tbody>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['tegangan'] . "</td>";
        echo "<td>" . $row['arus'] . "</td>";
        echo "<td>" . $row['daya'] . "</td>";
        echo "<td>" . $row['energi'] . "</td>";
        echo "<td>" . $row['suhu'] . "</td>";
        echo "<td>" . $row['kelembapan'] . "</td>";
        echo "<td>" . $row['used'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} else {
    echo "No data available";
}

$conn->close();
?>
