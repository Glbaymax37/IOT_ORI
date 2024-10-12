<?php
include("classes/connect.php");

session_start(); // Pastikan session dimulai

$database = new Database();

// Pastikan session mengandung ID booking, misalnya 'simalas_userid'
if (isset($_SESSION['simalas_userid'])) {
    $userid = $_SESSION['simalas_userid'];

    // Query untuk mengambil JamSelesai berdasarkan user ID
    $sql = "SELECT JamSelesai FROM Booking WHERE userid = '$userid' ORDER BY date DESC LIMIT 1";
    $result = $database->read($sql); // Menggunakan metode read dari class Database

    if ($result) {
        // Ambil JamSelesai dari hasil query
        $jamSelesai = $result[0]['JamSelesai'];

        // Waktu sekarang
        $now = new DateTime();

        // Waktu selesai booking
        $timeEnd = new DateTime($jamSelesai);

        // Hitung selisih waktu
        $interval = $now->diff($timeEnd);

        // Jika booking belum selesai, kembalikan selisih waktu dalam format jam dan menit
        if ($now < $timeEnd) {
            echo json_encode([
                'status' => 'ongoing',
                'time_remaining' => $interval->format('%H jam %I menit')
            ]);
        } else {
            echo json_encode([
                'status' => 'completed',
                'message' => 'Booking sudah selesai'
            ]);
        }
    } else {
        // Jika tidak ada data ditemukan
        echo json_encode([
            'status' => 'error',
            'message' => 'Data booking tidak ditemukan'
        ]);
    }
} else {
    // Jika session tidak ada, kembalikan pesan error
    echo json_encode([
        'status' => 'error',
        'message' => 'Session tidak ditemukan'
    ]);
}
?>
