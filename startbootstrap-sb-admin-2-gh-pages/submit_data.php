<?php

include("classes/connect.php");

// Buat objek dari class Database
$db = new Database();

// Cek apakah tombol telah ditekan
if(isset($_POST['submit'])) {
    // Menghasilkan nilai random
    $tegangan = rand(180, 240);  // Nilai tegangan random antara 180V - 240V
    $arus = rand(1, 10);         // Nilai arus random antara 1A - 10A
    $daya = $tegangan * $arus;   // Menghitung daya (Watt)
    $energi = rand(100, 500);    // Energi random antara 100Wh - 500Wh
    $suhu = rand(20, 40);        // Suhu random antara 20°C - 40°C
    $kelembapan = rand(30, 70);  // Kelembapan random antara 30% - 70%
    $used = rand(0, 1);          // Digunakan atau tidak (0 atau 1)

    // Query untuk menyimpan data ke database
    $sql = "INSERT INTO sensor_data (tegangan, arus, daya, energi, suhu, kelembapan, used)
            VALUES ('$tegangan', '$arus', '$daya', '$energi', '$suhu', '$kelembapan', '$used')";

    // Gunakan method save dari class Database untuk menyimpan data
    if ($db->save($sql)) {
        echo "Data berhasil disimpan";
    } else {
        echo "Error: " . $sql;
    }
}
?>

<!-- Form dengan tombol untuk menyimpan data -->
<form method="post">
    <button type="submit" name="submit">Simpan Data Random</button>
</form>
