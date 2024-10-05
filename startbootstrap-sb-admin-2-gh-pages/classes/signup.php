<?php

class Signup
{

    private $error = "";
    public function evaluate($data)
    {

        foreach ($data as $key => $value) {
            
            if(empty($value)) {
                $this->error = $this->error . $key ."is empty!<br>";
            }
        }
        if($this->error == "") 
        {
            //no eror
            $this->create_user($data);

        }else{
            return $this->error;
        }

    }

    public function create_user($data)
    {   
        $Nama = isset($data['Nama']) ? $data['Nama'] : '';
        $NIM = isset($data['NIM']) ? $data['NIM'] : '';
        $PBL = isset($data['PBL']) ? $data['PBL'] : '';
        $Password = isset($data['Password']) ? $data['Password'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $gender = isset($data['gender']) ? $data['gender'] : '';

        $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);


        $url_addres= strtolower($Nama).".".strtolower($NIM);
        $userid = $this->create_userid();


        $query = "INSERT INTO user (userid, Nama, NIM, PBL, Password, email, gender, url_addres) 
          VALUES ('$userid', '$Nama', '$NIM', '$PBL', '$hashedPassword', '$email', '$gender', '$url_addres')";

        $DB = new Database();
        $DB->save($query);
        return $query;


    }

    private function create_userid()
    {
        $length = rand(4,10);
        $number = "";
        for ($i = 0; $i < $length; $i++){
            $new_rand = rand(0,9);
            $number .= $new_rand;
        }
        // echo "Generated UserID: $number<br>";
        return $number;   
    }

    private function create_url(){

    }
  


}

?>