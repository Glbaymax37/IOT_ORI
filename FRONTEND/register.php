<?php
    include("classes/connect.php");
    include("classes/signup.php");

    $Nama = "";
    $NIM = "";
    $PBL = "";
    $gender = "";
    $email = "";

    if($_SERVER['REQUEST_METHOD']== 'POST'){
        $signup = new Signup();
        $result = $signup->evaluate($_POST);

        if ($result !="") {
            echo "<div style= 'text-align:center;font-size:12px;color:white;background-color:grey;'>";
            echo"<br>The following errors occurred:<br><br>";
            echo $result;
            echo "</div>";
        } else {
            header("Location: login.php");
            die;
        }

        $Nama = $_POST['Nama'];
        $NIM = $_POST['NIM'];
        $PBL = $_POST['PBL'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for background -->
    <style>
    /* Tambahkan ini untuk membuat latar belakang kartu transparan */
    .card-full-bg {
        background-color: rgba(255, 255, 255, 0.10); /* Warna putih dengan transparansi 80% */
        background-image: url('https://www.cncf.io/wp-content/uploads/2022/12/hero_bg-1024x794.png'); /* Sesuaikan path */
        background-size: cover;
        background-position: center;
        border: none; /* Hapus border */
        box-shadow: none; /* Hapus shadow */
        border-radius: 10px; /* Tambahkan border-radius untuk efek melengkung */
    }

    .card-body {
        background-color: rgba(255, 255, 255, 0.4); /* Transparansi sedikit pada isi kartu */
        border-radius: 10px; /* Tambahkan border-radius pada bagian dalam kartu */
    }
</style>


    
</head>

<body class="bg-gradient-primary">

    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5 card-full-bg"> <!-- Added card-full-bg class -->
            <div class="card-body p-8">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="p-2">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user" method="POST" action="" id="registrationForm">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="exampleInputName" name="Nama" placeholder="Nama Lengkap" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName" name="NIM" placeholder="NIM" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="exampleLastName" name="PBL" placeholder="PBL" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="exampleInputEmail" name="email" placeholder="Email Address" required>
                                </div>
                                <div class="form-group">
                                    <label for="genderSelect"></label>
                                    <select class="form-control" id="genderSelect" name="gender">
                                        <option value="Laki-Laki">Laki-Laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" id="exampleInputPassword" name="Password" placeholder="Password" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" id="exampleRepeatPassword" name="repeat_password" placeholder="Repeat Password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                                <hr>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="passwordMismatchModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Kata Sandi Tidak Cocok</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Kata sandi yang Anda masukkan tidak cocok. Silakan coba lagi.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        // Menangani pengiriman formulir
        document.getElementById("registrationForm").addEventListener("submit", function(event) {
            const password = document.getElementById("exampleInputPassword").value;
            const confirmPassword = document.getElementById("exampleRepeatPassword").value;

            if (password !== confirmPassword) {
                event.preventDefault(); 
                $('#passwordMismatchModal').modal('show'); 
            }
        });
    </script>

</body>

</html>
