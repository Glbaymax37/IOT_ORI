<?php
include "koneksi.php";

// Ambil status SSR dari database
$sql = "SELECT status FROM ssr_control WHERE id=1";
$result = $conn->query($sql);
$status = $result->fetch_assoc()['status'];

echo $status; // Outputkan status (ON atau OFF)
$conn->close();
?>
