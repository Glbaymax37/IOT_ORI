<?php
include ("classes/connect.php");

// Membuat instance dari class Database
$db = new Database();

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

// Menggunakan method save dari class Database untuk menyimpan data
if ($db->save($sql)) {
    echo "Data berhasil disimpan";
} else {
    echo "Error: Data tidak dapat disimpan.";
}
?>
