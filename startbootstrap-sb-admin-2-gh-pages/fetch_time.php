<?php
include("classes/connect.php");

session_start();

date_default_timezone_set('Asia/Jakarta'); 

header('Content-Type: application/json');

$database = new Database();

if (isset($_SESSION['simalas_userid'])) {
    $userid = $_SESSION['simalas_userid'];

   
    $now = new DateTime();

   
    $sql = "SELECT JamBooking, JamSelesai FROM Booking WHERE userid = '$userid' ORDER BY date DESC LIMIT 1";
    $result = $database->read($sql);

    if ($result) {
        $jamBooking = new DateTime($result[0]['JamBooking']);
        $jamSelesai = new DateTime($result[0]['JamSelesai']);

        if ($now >= $jamBooking && $now <= $jamSelesai) {
            $interval = $now->diff($jamSelesai);

            echo json_encode([
                'status' => 'ongoing',
                'time_remaining' => $interval->format('%H jam %I menit'),
                'server_time' => $now->format('Y-m-d H:i:s')
            ]);
        } elseif ($now > $jamSelesai) {
            echo json_encode([
                'status' => 'completed',
                'message' => 'Booking sudah selesai',
                'server_time' => $now->format('Y-m-d H:i:s')
            ]);
        } else {
            echo json_encode([
                'status' => 'not_started',
                'message' => '3D Belum dimulai',
                'server_time' => $now->format('Y-m-d H:i:s')
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'Warning',
            'message' => 'Tidak ada data',
            'server_time' => $now->format('Y-m-d H:i:s')
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Session tidak ditemukan',
        'server_time' => $now->format('Y-m-d H:i:s')
    ]);
}
?>
