<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fingerprint";

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengatur header untuk respons JSON
header('Content-Type: application/json');

// Ambil data template dari request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['auth_template']) && isset($_POST['user_id'])) {
        // Autentikasi sidik jari
        $auth_template = $_POST['auth_template'];
        $user_id = $_POST['user_id'];

        // Validasi user_id
        if (!is_numeric($user_id) || $user_id <= 0) {
            echo json_encode(array("error" => "ID pengguna tidak valid."));
            exit;
        }

        // Ambil template dari database
        $stmt = $conn->prepare("SELECT template FROM fingerprints WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $stmt->bind_result($stored_template);
            if ($stmt->fetch()) {
                // Bandingkan template yang diautentikasi dengan template yang disimpan
                if ($auth_template === $stored_template) {
                    echo json_encode(array("success" => "Autentikasi berhasil."));
                } else {
                    echo json_encode(array("error" => "Autentikasi gagal: template tidak cocok."));
                }
            } else {
                echo json_encode(array("error" => "Template tidak ditemukan untuk ID pengguna tersebut."));
            }
        } else {
            echo json_encode(array("error" => "Gagal mengambil template: " . $stmt->error));
        }

        $stmt->close();
    } else {
        echo json_encode(array("error" => "Parameter yang diperlukan tidak ada."));
    }
}

// Tutup koneksi
$conn->close();
?>