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
    if (isset($_POST['template']) && isset($_POST['user_id']) && isset($_POST['user_name'])) {
        $fingerprint_template = $_POST['template'];
        $user_id = $_POST['user_id'];
        $user_name = $_POST['user_name'];

        // Validasi user_id
        if (!is_numeric($user_id) || $user_id <= 0) {
            echo json_encode(array("error" => "ID pengguna tidak valid."));
            exit;
        }

        // Siapkan query dengan prepared statement
        $stmt = $conn->prepare("INSERT INTO fingerprints (user_id, user_name, template) VALUES (?, ?, ?)");

        if ($stmt === false) {
            die("Gagal mempersiapkan statement: " . $conn->error);
        }

        // Bind parameter
        $stmt->bind_param("iss", $user_id, $user_name, $fingerprint_template);

        // Eksekusi statement
        if ($stmt->execute()) {
            echo json_encode(array("success" => "Template sidik jari berhasil disimpan."));
        } else {
            echo json_encode(array("error" => "Gagal menyimpan template: " . $stmt->error));
        }

        // Tutup statement
        $stmt->close();
    } else {
        echo json_encode(array("error" => "Parameter yang diperlukan tidak ada."));
    }
}

// Tutup koneksi
$conn->close();
?>