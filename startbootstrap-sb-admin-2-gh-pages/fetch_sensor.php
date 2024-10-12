<?php
include("classes/connect.php");

$database = new Database();
$sql = "SELECT Tegangan, Arus, Daya, Suhu, Kelembapan, Energi FROM data_sensor ORDER BY date DESC LIMIT 1";
$result = $database->read($sql); // Menggunakan metode read dari class Database

if ($result) {
    echo json_encode($result[0]); // Mengambil data pertama dari array hasil
} else {
    echo json_encode([]);
}
?>
