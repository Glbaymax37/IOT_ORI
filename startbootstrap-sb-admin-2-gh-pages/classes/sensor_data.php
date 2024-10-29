<?php
class ESPData {
    private $error = "";

    public function evaluate($data) {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                $this->error .= $key . " is empty!<br>";
            }
        }
        return $this->error;
    }

    // Fungsi untuk mengambil semua data dari ESP
    public function getAllESPData() {
        $query = "SELECT tegangan, arus, suhu, kelembapan, timestamp FROM sensor_data";
        $DB = new Database();
        return $DB->read($query); 
    }

    public function moveDataToNewTable() {
        // Ambil data dari session
        if (isset($_SESSION["simalas_userid"], $_SESSION["simalas_nama"], $_SESSION["simalas_NIM"])) {
            $userid = $_SESSION["simalas_userid"];
            $username = $_SESSION["simalas_nama"];
            $userNIM = $_SESSION["simalas_NIM"];
        } else {
            $this->error .= "Session data is missing.<br>";
            return false;
        }
    
        // Ambil data dari tabel sensor_data
        $sensorData = $this->getAllESPData();
    
        if (!empty($sensorData)) {
            $DB = new Database();
    
            foreach ($sensorData as $data) {
                $tegangan = $data['tegangan'];
                $arus = $data['arus'];
                $suhu = $data['suhu'];
                $kelembapan = $data['kelembapan'];
                $timestamp = $data['timestamp'];
    
                // Cek apakah nama dan NIM sudah ada di tabel data_sensor
                $checkQuery = "SELECT * FROM data_sensor WHERE userid = '$userid' AND NAMA = '$username' AND NIM = '$userNIM'";
                $result = $DB->read($checkQuery);
    
                if ($result && count($result) > 0) {
                    // Jika nama dan NIM sudah ada, lakukan update
                    $updateQuery = "UPDATE data_sensor SET tegangan = '$tegangan', arus = '$arus', suhu = '$suhu', kelembapan = '$kelembapan', date = '$timestamp' 
                                    WHERE userid = '$userid' AND NAMA = '$username' AND NIM = '$userNIM'";
                    $DB->save($updateQuery);
                    // Logging setelah update
                    error_log("Updated data for user: $username, NIM: $userNIM at " . date('Y-m-d H:i:s'));
                } else {
                    // Jika nama dan NIM belum ada, lakukan insert
                    $insertQuery = "INSERT INTO data_sensor (userid, NAMA, NIM, tegangan, arus, suhu, kelembapan, date) 
                                    VALUES ('$userid', '$username', '$userNIM', '$tegangan', '$arus', '$suhu', '$kelembapan', '$timestamp')";
                    $DB->save($insertQuery);
                    // Logging setelah insert
                    error_log("Inserted data for user: $username, NIM: $userNIM at " . date('Y-m-d H:i:s'));
                }
            }
            return true;
        } else {
            $this->error .= "No sensor data found.<br>";
            return false;
        }
    }
    
    public function getSensorDataBySessionUser() {
        if (isset($_SESSION['simalas_userid'])) {
            $userid = $_SESSION['simalas_userid'];
    
            // Query untuk mengambil data suhu, tegangan, arus, dan daya berdasarkan userid dari session
            $query = "SELECT suhu, tegangan, arus, kelembapan FROM data_sensor WHERE userid = '$userid'";
            $DB = new Database();
            $result = $DB->read($query); // Menggunakan metode read dari kelas Database
    
            if ($result && count($result) > 0) {
                return $result[0]; // Mengembalikan data sensor
            } else {
                return null; // Tidak ada data sensor
            }
        } else {

            
            return null; // User belum login
        }
    }

   
    public function getError() {
        return $this->error;
    }
    public function updateSSRStatus($status) {
    session_start(); // Ensure session is started

    // Validate status (e.g., can only be "ON" or "OFF")
    if (!in_array($status, ['ON', 'OFF'])) {
        $this->error .= "Invalid status value.<br>";
        return false;
    }

    // Retrieve user ID from session
    if (isset($_SESSION["simalas_userid"])) {
        $userid = $_SESSION["simalas_userid"];
    } else {
        $this->error .= "Session data is missing.<br>";
        return false;
    }

    // If the status is not set, use the default value "OFF"
    if (empty($status)) {
        $status = 'OFF'; // Set default status to OFF
    }

    // Update SSR status in database
    $query = "UPDATE Statusmesin SET status = ? WHERE userid = '$userid";
    $DB = new Database();

    if ($DB->save($query, [$status, $userid])) {
        return true; 
    } else {
        $this->error .= "Error updating status: ";
        return false; 
    }
}
}
?>
