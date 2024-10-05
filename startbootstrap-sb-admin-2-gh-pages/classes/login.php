<?php

class Login
{
    private $error = "";

    public function evaluate($data)
    {
        
        $NIM = addslashes($data['NIM']);
        $Password = addslashes($data['Password']);

        $query = "SELECT * FROM user WHERE NIM = '$NIM' LIMIT 1";
        
       
        $DB = new Database();
        $result = $DB->read($query);
       
        
        if ($result) {
            $row = $result[0];

            if (password_verify($Password, $row['Password'])) {
                $_SESSION['simalas_userid'] = $row['userid'];
                $_SESSION['simalas_nama'] = $row['Nama'];
                $_SESSION['simalas_NIM'] = $row['NIM'];
                $_SESSION['simalas_email'] = $row['email'];
                $_SESSION['simalas_PBL'] = $row['PBL'];
                $_SESSION['simalas_Tanggal'] = $row['Tanggal'];
            } else {
                $this->error = "Wrong password<br>";
            }
           
        } else {
           
            $this->error = "No such NIM was found<br>";
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