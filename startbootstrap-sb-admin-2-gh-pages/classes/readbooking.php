<?php

class Check
{
    private $error = "";

    public function evaluate($data)
    {
        $Nama = addslashes($data['Nama']); 
        $NIM = addslashes($data['NIM']); 
        $Password = addslashes($data['Password']);

        $query = "SELECT * FROM Booking WHERE Nama = '$Nama' LIMIT 1";

       
        $DB = new Database();
        $result = $DB->read($query);
       
        
        if ($result) {
            $row = $result[0];

                $_SESSION['simalas_nama'] = $row['Nama'];
                $_SESSION['simalas_NIM'] = $row['NIM'];
                $_SESSION['simalas_PBL'] = $row['PBL'];
                $_SESSION['tanggal_booking'] = $row['Tanggal'];
                $_SESSION['jam_booking'] = $row['JamBooking'];
                $_SESSION['simalas_PBL'] = $row['JamSelesai'];
        }
        return $this->error;
    }
    
    public function check_login($id){

        $query =  "SELECT * FROM user WHERE userid = '$id' LIMIT 1";
       
        
  
        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            
            return true;
        
        }
        else {
            return false;
        }
  
    }

}