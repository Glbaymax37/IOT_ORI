<?php
class Booking {
    private $error = "";
    private $DB; // Tambahkan properti untuk menyimpan objek Database

    public function __construct() {
        $this->DB = new Database(); // Inisialisasi objek Database di konstruktor
    }

    public function evaluate($data) {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                $this->error .= $key . " is empty!<br>";
            }
        }
        return $this->error;
    }

    public function create_booking(&$userid, $username, $userNIM, $userPBL, $data) {
        $this->error = $this->evaluate($data);

        if ($this->error == "") {
            $tanggal = addslashes($data['tanggal_pinjam']);
            $jamBooking = addslashes($data['waktu_mulai']);
            $jamSelesai = addslashes($data['waktu_selesai']);

            // Memeriksa apakah ada booking yang bertabrakan
            if ($this->isBookingConflict($tanggal, $jamBooking, $jamSelesai)) {
                $this->error = "Jam Yang Anda Pilih Telah di BOOKING!";
                return $this->error; 
            }
            $currentDate = date('Y-m-d');
            if (strtotime($tanggal) < strtotime($currentDate)) {
                $this->error .= "Tanggal booking tidak valid. Harus tanggal sekarang atau setelahnya.<br>";
                return $this->error; // Kembalikan error jika ada
            }

            $query = "INSERT INTO Booking (userid, NAMA, NIM, PBL, Tanggal, JamBooking, JamSelesai, date) 
                      VALUES ('$userid', '$username', '$userNIM', '$userPBL', '$tanggal', '$jamBooking', '$jamSelesai', NOW())";

            $this->DB->save($query); // Gunakan objek $DB yang sudah diinisialisasi
        } else {
            return $this->error; 
        }
    }

    private function isBookingConflict($tanggal, $jamBooking, $jamSelesai) {
        $query = "SELECT * FROM Booking 
                  WHERE Tanggal = '$tanggal' 
                  AND (
                      (JamBooking <= '$jamSelesai' AND JamSelesai >= '$jamBooking')
                  )";

        $result = $this->DB->read($query);

        // Cek jika $result adalah array
        if (is_array($result)) {
            // Jika ada hasil, berarti ada konflik
            return count($result) > 0;
        } else {
            // Jika query gagal, log kesalahan (opsional)
            error_log("Query failed: " . $query);
            return false; // Jika terjadi kesalahan, anggap tidak ada konflik
        }
    }

    public function getAllBookings() {
        $query = "SELECT NAMA, NIM, PBL, Tanggal, JamBooking, JamSelesai FROM Booking";
        return $this->DB->read($query); 
    }

    public function bookingByuser(){
        if (isset($_SESSION["simalas_userid"]) && is_numeric($_SESSION["simalas_userid"])) {
            $userid = $_SESSION['simalas_userid']; 
            $query = "SELECT NAMA, NIM, PBL, Tanggal, JamBooking, JamSelesai FROM Booking WHERE userid = '$userid'";
            return $this->DB->read($query); 
        } else {
            return false; 
        }
    }

    public function getError() {
        return $this->error;
    }
}
?>
