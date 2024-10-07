<?php
include 'koneksi.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Mendapatkan data yang dikirimkan dari ESP8266
$tegangan = $_POST['tegangan'];
$arus = $_POST['arus'];
$daya = $_POST['daya'];
$energi = $_POST['energi']; 
$suhu = $_POST['suhu'];
$kelembapan = $_POST['kelembapan'];
$used = $_POST['used'];

// Menyimpan data ke database
$sql = "INSERT INTO sensor_data (tegangan, arus, daya, energi, suhu, kelembapan, used)
VALUES ('$tegangan', '$arus', '$daya', '$energi', '$suhu', '$kelembapan', '$used')";

if ($conn->query($sql) === TRUE) {
    echo "Data berhasil disimpan";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
