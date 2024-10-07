<?php
class Booking {
    private $error = "";

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

            $query = "INSERT INTO Booking (userid, NAMA, NIM, PBL, Tanggal, JamBooking, JamSelesai, date) 
                      VALUES ('$userid', '$username', '$userNIM', '$userPBL', '$tanggal', '$jamBooking', '$jamSelesai', NOW())";

            $DB = new Database();
            $DB->save($query);
        } else {
            return $this->error; 
        }
    }

    public function getAllBookings() {
        $query = "SELECT NAMA, NIM, PBL, Tanggal, JamBooking, JamSelesai FROM Booking";
        $DB = new Database();
        return $DB->read($query); 
    }

    public function bookingByuser(){
        if (isset($_SESSION["simalas_userid"]) && is_numeric($_SESSION["simalas_userid"])) {
            $userid = $_SESSION['simalas_userid']; 
            $query = "SELECT NAMA, NIM, PBL, Tanggal, JamBooking, JamSelesai FROM Booking WHERE userid = '$userid'";
            $DB = new Database();
            return $DB->read($query); 
        } else {
            return false; 
        }
    }


    public function getError() {
        return $this->error;
    }
}
?>