<?php
include "koneksi.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status']; 


    // Update status SSR di database
    $sql = "UPDATE ssr_status SET status='$status' WHERE id=1";

    if ($conn->query($sql) === TRUE) {
        echo "Status SSR berhasil diperbarui menjadi $status.";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
