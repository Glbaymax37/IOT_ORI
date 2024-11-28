<?php
include("classes/connect.php"); // Pastikan ini mengarah ke file yang benar

// Membuat instance dari kelas Database
$db = new Database();

// Query untuk mendapatkan status dari database
$query = "SELECT statusmesin FROM data_sensor ORDER BY id DESC LIMIT 1"; // Ganti dengan query yang sesuai
$result = $db->read($query); // Menggunakan metode read dari kelas Database

if ($result !== false && count($result) > 0) {
    // Jika ada hasil, ambil status
    $statusmesin = $result[0]['statusmesin'];
    echo json_encode(["statusmesin" => $statusmesin]);
} else {
    echo json_encode(["statusmesin" => "error", "message" => "Gagal mengambil status."]);
}
 status."]);
}
?>