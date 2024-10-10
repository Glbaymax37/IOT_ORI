<?php
session_start();
include("classes/connect.php");
include("classes/sensor_data.php");

$data_mentah = new ESPData(); // Menggunakan objek yang benar

// Pastikan user telah login
if (isset($_SESSION["simalas_userid"]) && is_numeric($_SESSION["simalas_userid"])) {
    header('Content-Type: application/json'); // Set header untuk JSON

    // Memindahkan data ke tabel baru dan menyimpan hasilnya
    $data = $data_mentah->moveDataToNewTable(); // Memanggil metode pada objek yang benar

    // Memeriksa apakah data berhasil dipindahkan
    if ($data === false) {
        echo json_encode(["status" => "error", "message" => "Failed to fetch data."]);
    } else {
        echo json_encode(["status" => "success", "data" => $data]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
}
?>
