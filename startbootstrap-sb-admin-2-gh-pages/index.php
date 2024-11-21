<?php
session_start();

$userid = $_SESSION["simalas_userid"]; 
$username = $_SESSION["simalas_nama"];
$userNIM = $_SESSION["simalas_NIM"];
$userPBL = $_SESSION["simalas_PBL"];
$useremail = $_SESSION["simalas_email"];

include("classes/connect.php");
include("classes/login.php");
include("classes/createbooking.php");
include("classes/sensor_data.php");

if(isset($_SESSION["simalas_userid"])&& is_numeric($_SESSION["simalas_userid"]))
{
    $id = $_SESSION["simalas_userid"];
    $login = new Login();

    $login ->check_login($id);

    $result = $login->check_login($id);
    
    $booking = new Booking(); 
    $bookings = $booking->bookingByuser();

    
    $data_monitoring = new ESPData();
    $data_hasil = $data_monitoring -> getSensorDataBySessionUser();

    

    if($result){
       
    }
    else{

        header("Location: Login.php");
        die;
    }

}

else{
    header("Location: Login.php");
    die;
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

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                <img src="https://glatinone.github.io/barelangmrt.github.io/assets/img/BRAIL.png" alt="Logo" style="width:30px; height:auto; margin-right:10px;">
                </div>
                <div class="sidebar-brand-text mx-3">IOT SIMALAS <sup></sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

           
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
               
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="charts.php">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Monitoring</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="tables.php">
                    <i class="fas fa-fw fa-book-dead"></i>
                    <span>Booking</span></a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

                      <!-- Nav Item - Tables -->
         
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>




            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
            
                        <!-- Nav Item - Messages -->
                        <!-- WAKTU -->
                        <li class="nav-item mx-1">
                            <a class="nav-link" href="#" id="timeDisplay" style="border: none; width: 100%; padding: 15px; text-align: left; font-size: 20px; color: blue;">
                                TIME
                            </a>
                        </li>


                        <script>
                            function updateTime() {
                                var now = new Date();
                                var hours = now.getHours();
                                var minutes = now.getMinutes();
                                var seconds = now.getSeconds();

                                // Menambahkan '0' jika nilainya kurang dari 10
                                hours = hours < 10 ? '0' + hours : hours;
                                minutes = minutes < 10 ? '0' + minutes : minutes;
                                seconds = seconds < 10 ? '0' + seconds : seconds;

                                var timeString = hours + ':' + minutes + ':' + seconds;
                                document.getElementById('timeDisplay').textContent = timeString;

                                var xhr = new XMLHttpRequest();
                                xhr.open("POST", "fetch_time.php", true);
                                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                xhr.onreadystatechange = function () {
                                    if (xhr.readyState === XMLHttpRequest.DONE) {
                                        var response = JSON.parse(xhr.responseText);
                                        console.log(response);
                                    }
                                };
                                xhr.send("client_time=" + timeString);


                            }
                            setInterval(updateTime, 1000);
                            setInterval(sendTimeToServer, 1000);
                        </script>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->

                        <!-- NAMA USER BAGIAN ATAS -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"> <?php echo htmlspecialchars($username); ?> </span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    

                     <!-- PROFIL -->
                     <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Profile</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 35rem;"
                                            src="img/undraw_posting_photo.svg" alt="Profile Image">
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>NAMA:</strong> <?php echo htmlspecialchars($username); ?></li>
                                        <li class="list-group-item"><strong>NIM:</strong> <?php echo htmlspecialchars($userNIM); ?></li>
                                        <li class="list-group-item"><strong>PBL:</strong> <?php echo htmlspecialchars($userPBL); ?></li>
                                        <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($useremail); ?></li>
                                    </ul>
                                </div>
                            </div>


                    <!-- Approach -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Booking</h6>
                        </div>
                        <div class="card-body">
                            <?php
                            if ($bookings) {
                                // Improved table styling
                                echo "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>";
                                echo "<tr style='background-color: #f2f2f2;'>
                                        <th style='border: 1px solid #ddd; padding: 12px;'>Nama</th>
                                        <th style='border: 1px solid #ddd; padding: 12px;'>NIM</th>
                                        <th style='border: 1px solid #ddd; padding: 12px;'>PBL</th>
                                        <th style='border: 1px solid #ddd; padding: 12px;'>Tanggal</th>
                                        <th style='border: 1px solid #ddd; padding: 12px;'>Waktu Mulai</th>
                                        <th style='border: 1px solid #ddd; padding: 12px;'>Waktu Selesai</th>
                                    </tr>";

                                foreach ($bookings as $index => $row) {
                                    // Alternate row colors for better readability
                                    $rowColor = $index % 2 == 0 ? '#ffffff' : '#f9f9f9';
                                    $jamBooking = date('H:i', strtotime($row['JamBooking']));
                                    $jamSelesai = date('H:i', strtotime($row['JamSelesai']));

                                    $jamMulai = date('H', strtotime($row['JamBooking']));
                                    $jamUsai = date('H', strtotime($row['JamSelesai']));

                                    $menitMulai = ltrim(date('i', strtotime($row['JamBooking'])), '0');

                                    $menitSelesai = ltrim(date('i', strtotime($row['JamSelesai'])), '0');

                                    echo "<tr style='background-color: $rowColor;'>";
                                    echo "<td style='border: 1px solid #ddd; padding: 12px;'>" . $row['NAMA'] . "</td>";
                                    echo "<td style='border: 1px solid #ddd; padding: 12px;'>" . $row['NIM'] . "</td>";
                                    echo "<td style='border: 1px solid #ddd; padding: 12px;'>" . $row['PBL'] . "</td>";
                                    echo "<td style='border: 1px solid #ddd; padding: 12px;'>" . $row['Tanggal'] . "</td>";
                                    echo "<td style='border: 1px solid #ddd; padding: 12px;'>$jamBooking</td>";
                                    echo "<td style='border: 1px solid #ddd; padding: 12px;'>$jamSelesai</td>";
                                    echo "</tr>";
                                }

                                echo "</table>";
                            } else {
                                echo "Tidak ada data booking.";
                            }
                            ?>
                   <div class="col-lg-2 mb-4">
                    <button class="card bg-success text-white shadow" id="actionButton" style="border: none; width: 100%; padding: 15px; text-align: left;" data-toggle="modal" data-target="#activateMachineModal">
                        Aktifkan Mesin
                    </button>
                </div>
                <!-- Modal untuk memasukkan kata sandi -->
                <div class="modal fade" id="activateMachineModal" tabindex="-1" role="dialog" aria-labelledby="activateMachineLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="activateMachineLabel">Aktifkan Mesin</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Masukkan sandi Anda untuk mengaktifkan mesin.</p>
                                <div class="form-group">
                                    <label for="machinePasswordInput">Sandi</label>
                                    <input type="password" class="form-control" id="machinePasswordInput" placeholder="Masukkan sandi">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                <button class="btn btn-primary" id="activate_button">Aktifkan Mesin</button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                         document.addEventListener("DOMContentLoaded", function () {
                            var machine = false; // Status mesin, mulai dari OFF
                            var transferInterval; // Variable untuk menyimpan interval

                            // Fungsi untuk memperbarui jam
                            function updateClock() {
                                var now = new Date();
                                var hours = now.getHours();
                                var minutes = now.getMinutes();

                                // Mendapatkan waktu mulai dan selesai dari PHP
                                var startHour = <?php echo $jamMulai ?>;    
                                var startMinute = <?php echo $menitMulai ?>;  
                                var endHour = <?php echo $jamUsai ?>;     
                                var endMinute = <?php echo $menitSelesai ?>;    

                                // Aktifkan atau nonaktifkan tombol berdasarkan waktu
                                var buttonElement = document.getElementById("actionButton"); // Mendapatkan elemen tombol
                                if ((hours > startHour || (hours === startHour && minutes >= startMinute)) &&
                                    (hours < endHour || (hours === endHour && minutes < endMinute))) {
                                    buttonElement.disabled = false; // Aktif
                                } else {
                                    if (machine) {
                                        console.log("Waktu habis"); // Tambahkan log
                                        updateMachineStatus("OFF"); // Kirim status OFF 
                                        machine = false; // Set status mesin menjadi OFF
                                    }
                                    buttonElement.disabled = true; // Nonaktifkan tombol
                                }
                            }

                            // Fungsi untuk memulai pemindahan data
                            function startDataTransfer() {
                                transferInterval = setInterval(function () {
                                    var xhr = new XMLHttpRequest();
                                    xhr.open("POST", "move_data.php", true);
                                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                                    xhr.onreadystatechange = function () {
                                        if (xhr.readyState === XMLHttpRequest.DONE) {
                                            try {
                                                var response = JSON.parse(xhr.responseText);
                                                if (response.status === "success") {
                                                    console.log("Data successfully moved.");
                                                } else {
                                                    console.error("Data move failed.");
                                                }
                                            } catch (e) {
                                                console.error("Error parsing JSON:", e);
                                            }
                                        }
                                    };

                                    xhr.send(); // Kirim permintaan pemindahan data
                                }, 2000); // Kirim setiap 5 detik
                            }

                            function stopDataTransfer() {
                                clearInterval(transferInterval); // Hentikan interval
                                console.log("Data transfer stopped.");
                            }

                            // Fungsi untuk mengirim status ke server
                            function updateMachineStatus(statusmesin) {
                                console.log("Status yang akan dikirim: " + statusmesin); // Tambahkan log
                                var xhr = new XMLHttpRequest();
                                xhr.open("POST", "update_ssr_status.php", true);
                                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                
                                xhr.onreadystatechange = function () {
                                    if (xhr.readyState === XMLHttpRequest.DONE) {   
                                        console.log("Respons dari server:", xhr.responseText); // Tambahkan log untuk respons mentah
                                        try {
                                            var response = JSON.parse(xhr.responseText);
                                            console.log(response); // Tambahkan log untuk melihat respons
                                            alert(response.message); // Tampilkan pesan dari server
                                        } catch (e) {
                                            console.error("Error parsing JSON:", e);
                                            alert("Terjadi kesalahan saat memproses respons dari server.");
                                        }
                                    }
                                };
                                
                                xhr.send("statusmesin=" + statusmesin); // Kirim status
                            }
                            // Menambahkan event listener ke tombol aktivasi mesin
                            document.getElementById("activate_button").addEventListener("click", function () {
                                var passwordInput = document.getElementById("machinePasswordInput").value;

                                // Lakukan validasi password di sini jika diperlukan
                                if (passwordInput) {
                                    startDataTransfer();
                                    updateMachineStatus("ON");
                                    $('#activateMachineModal').modal('hide'); // Sembunyikan modal
                                    machine = true; // Set status mesin menjadi ON
                                } else {
                                    alert("Silakan masukkan kata sandi yang benar."); // Peringatan jika password tidak dimasukkan
                                }
                            });

                            // Memperbarui jam setiap detik
                            setInterval(updateClock, 1000);
                        });
                        </script>
    
                        <style>
                            #actionButton:disabled {
                                background-color: gray;
                                color: white;           
                                cursor: not-allowed;    
                                opacity: 0;           
                            }
                        </style>
                    </div>



                    <!-- Content Row -->
                    <div class="row">

                         <!-- Suhu Mesin -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Temperature
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="temperature">
                                                Tidak ada data suhu
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-temperature-low fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Voltage -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Voltage
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="voltage">
                                                Tidak ada data Tegangan
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-bolt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Time Remaining -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Time Remaining
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="time_remaining">Memuat...</div>

                                                </div>
                                                <div class="col">
                                              
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- length of filament used -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Length of Filament Used</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="filament">20&nbsp;cm</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-ruler fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script>
                                function fetchSensorData() {
                                    $.ajax({
                                        url: 'fetch_sensor.php', // Pastikan path ini benar
                                        type: 'GET',
                                        dataType: 'json',
                                        success: function(data) {
                                            console.log(data); // Debugging

                                            // Update elemen suhu
                                            if (data.Suhu) {
                                                $('#temperature').html(data.Suhu + '&deg;C');
                                            } else {
                                                $('#temperature').html('Tidak ada data suhu');
                                            }

                                            // Update elemen tegangan
                                            if (data.Tegangan) {
                                                $('#voltage').html(data.Tegangan + ' V');
                                            } else {
                                                $('#voltage').html('Tidak ada data Tegangan');
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('AJAX Error: ' + xhr.status + ' - ' + error);
                                            alert('Terjadi kesalahan: ' + xhr.status + ' - ' + error);
                                        }
                                    });
                                }

                                function fetchTimeRemaining() {
                                    $.ajax({
                                        url: 'fetch_time.php', // URL ke file PHP yang kita buat
                                        type: 'GET',
                                        dataType: 'json',
                                        success: function(data) {
                                            console.log(data); // Debugging

                                            // Update elemen time remaining
                                            if (data.status === 'ongoing') {
                                                $('#time_remaining').html(data.time_remaining); // Menampilkan waktu tersisa
                                            } else if (data.status === 'completed') {
                                                $('#time_remaining').html('Booking sudah selesai'); // Jika booking selesai
                                            } else {
                                                $('#time_remaining').html('' + data.message); // Jika terjadi kesalahan
                                            }

                                            // Tampilkan waktu server
                                            $('#server_time').html('Waktu Server: ' + data.server_time); // Pastikan ada elemen dengan ID server_time
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('AJAX Error: ' + xhr.status + ' - ' + error);
                                            alert('Terjadi kesalahan: ' + xhr.status + ' - ' + error);
                                        }
                                    });
                                }


                                $(document).ready(function() {
                                    fetchSensorData(); // Memanggil fungsi pertama kali saat halaman dimuat
                                    fetchTimeRemaining(); // Memanggil fungsi pertama kali saat halaman dimuat
                                    setInterval(fetchSensorData, 1000); // Memperbarui data sensor setiap detik
                                    setInterval(fetchTimeRemaining, 1000); // Memperbarui waktu setiap detik
                                });
                            </script>
                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                    Flow of Using a 3D Printer Machine</h6>
                                    
                                </div>
                                <!-- Cara Penggunaan 3D Printer -->
                                <div class="card-body">
                                    <div class="chart-area" style="height: 900px;">
                                    
                                        
                                    </div>
                                </div>
                            </div>
                        </div>



                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Simalas 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->



    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


<!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" id="logout_button">Logout</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById("logout_button").addEventListener("click", function() {
                window.location.href = "logout.php";
            });
        </script>


    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>