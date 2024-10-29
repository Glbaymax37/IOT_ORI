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

    <title>IOT SIMALAS</title>

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
            <li class="nav-item">
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

            <!-- Nav Item - Charts -->
            <li class="nav-item active">
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

                

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                            <!-- Nav Item - Messages -->
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
                            }
                            setInterval(updateTime, 1000);
                        </script>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $username?></span>
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

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Machine Monitoring</h1>
    <p class="mb-4">
    </p>

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-lg-9">

            <!-- Area Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Monitoring</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        
                        <!-- Row for Filament and Temperature -->
                        <div class="row">
                            <!-- Length of Filament used -->
                            <div class="col-xl-4 col-md-4 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Length of Filament Used</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">20&nbsp;cm</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-ruler fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Temperature -->
                            <div class="col-xl-4 col-md-4 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Temperature</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"> 
                                                <?php if (!empty($data_hasil['suhu'])): ?>
                                                <?php echo $data_hasil['suhu'] ?>&deg;C
                                                <?php else: ?>
                                                            Tidak ada data suhu
                                                <?php endif; ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-temperature-low fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <!-- Current -->
                            <div class="col-xl-4 col-md-4 mb-4">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Current</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php if (!empty($data_hasil['arus'])): ?>
                                                <?php echo $data_hasil['arus'] ?> A
                                                <?php else: ?>
                                                            Tidak ada data arus
                                                <?php endif; ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-plug fa-2x text-gray-300"></i>
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

            
                        </div>

                    <div class="row">
                         <!-- Time Remaining -->
                         <div class="col-xl-4 col-md-4 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Time Remaining
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"id="time_remaining">Memuat...</div>

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

                        <!-- Voltage -->
                        <div class="col-xl-4 col-md-4 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Voltage</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">    
                                                <?php if (!empty($data_hasil['tegangan'])): ?>
                                                <?php echo $data_hasil['tegangan'] ?> &nbsp;V
                                                <?php else: ?>
                                                            Tidak ada data tegangan
                                                <?php endif; ?>
                                               
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-bolt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <!-- Kelembapam -->
                         <div class="col-xl-4 col-md-4 mb-4">
                            <div class="card border-left-dark shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Humidity</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"> 
                                                <?php if (!empty($data_hasil['kelembapan'])): ?>
                                                <?php echo $data_hasil['kelembapan'] ?> %
                                                <?php else: ?>
                                                            Tidak ada data Kelembapan
                                                <?php endif; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-wind fa-2x text-gray-300"></i>
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










                    </div>
                    <hr>
                   

                </div>
            </div>
        </div>
    </div>
</div>
                            <!-- Bar Chart -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Bar Chart</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-bar">
                                        <canvas id="myBarChart"></canvas>
                                    </div>
                                    <hr>
                                    Styling for the bar chart can be found in the
                                    <code>/js/demo/chart-bar-demo.js</code> file.
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
    <script src="js/demo/chart-bar-demo.js"></script>

</body>

</html>