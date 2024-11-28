<?php
include("classes/connect.php");

$db = new Database(); // Membuat instance dari kelas Database
error_log("Koneksi database berhasil.");

// Log data POST yang diterima
error_log("Data POST: " . print_r($_POST, true));

$status = isset($_POST['statusmesin']) ? $_POST['statusmesin'] : null;

if ($status !== null) {
    error_log("Status diterima: " . $status);

    $query = "UPDATE data_sensor SET statusmesin = ? WHERE id = (SELECT MAX(id) FROM data_sensor)";
    
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param("s", $status);
        $result = $stmt->execute();
        
        error_log("Hasil eksekusi: " . ($result ? "Berhasil" : "Gagal"));

        if ($result) {
            error_log("Pembaruan berhasil untuk status: " . $status);
            echo json_encode(["status" => "success", "message" => "Status berhasil diperbarui menjadi " . $status . "."]);
        } else {
            error_log("Gagal memperbarui status: " . $stmt->error);
            echo json_encode(["status" => "error", "message" => "Gagal memperbarui status: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        error_log("Error preparing statement: " . $db->connect()->error . " | Query: " . $query);
        echo json_encode(["status" => "error", "message" => "Error preparing statement: " . $db->connect()->error]);
    }
} else {
    error_log("Status tidak diterima.");
    echo json_encode(["status" => "error", "message" => "Status tidak diterima."]);
}

$db->close(); // Menutup koneksi
?>