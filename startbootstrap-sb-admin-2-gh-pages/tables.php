<?php
session_start();

$userid = $_SESSION["simalas_userid"]; 
$username = $_SESSION["simalas_nama"];
$userNIM = $_SESSION["simalas_NIM"];
$userPBL = $_SESSION["simalas_PBL"];



$tanggal = "";
$jamBooking = "";
$jamSelesai = "";

include("classes/connect.php");
include("classes/login.php");
include("classes/createbooking.php");

// Membuat objek Booking
$booking = new Booking(); 

if (isset($_SESSION["simalas_userid"]) && is_numeric($_SESSION["simalas_userid"])) {
    $id = $_SESSION["simalas_userid"];
    $login = new Login();
    $login->check_login($id);
    
    $result = $login->check_login($id);

    


    if ($result) {
        
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
$error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Evaluasi data yang diterima
    $error = $booking->evaluate($_POST);

    if ($error != "") {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('errorMessage').innerHTML = '" . addslashes($error) . "';
                    $('#errorModal').modal('show');
                });
              </script>";
    } else {
        // Data valid, buat booking
        $result = $booking->create_booking($id, $username, $userNIM, $userPBL, $_POST); 

        // Jika ada error saat pembuatan booking, tampilkan error
        if ($result != "") {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('errorMessage').innerHTML = '" . addslashes($result) . "';
                        $('#errorModal').modal('show');
                    });
                  </script>";
        } else {
            header("Location: tables.php"); 
            die;
        }
    }
}


$hasil = $booking->getAllBookings();
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>IOT SIMALAS Booking</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
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
            <li class="nav-item">
                <a class="nav-link" href="charts.php">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Monitoring</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item active">
                <a class="nav-link" href="tables.php">
                    <i class="fas fa-fw fa-book-dead"></i>
                    <span>Booking</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

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
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                
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
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"> <?php echo htmlspecialchars($username); ?></span>
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
                    <h1 class="h3 mb-2 text-gray-800">Table Peminjaman</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Peminjaman</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>NIM</th>
                                            <th>PBL</th>
                                            <th>Tanggal</th>
                                            <th>Jam Booking</th>
                                            <th>Jam Selesai</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    <?php 
            if ($hasil) { // Pastikan hasil tidak kosong
                foreach ($hasil as $row) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["NAMA"]) . "</td>
                            <td>" . htmlspecialchars($row["NIM"]) . "</td>
                            <td>" . htmlspecialchars($row["PBL"]) . "</td>
                            <td>" . htmlspecialchars($row["Tanggal"]) . "</td>
                            <td>" . htmlspecialchars($row["JamBooking"]) . "</td>
                            <td>" . htmlspecialchars($row["JamSelesai"]) . "</td>
                            
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
            }
            ?> 
                                        
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    

                </div>
                
                <div class="col-lg-6">

                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Booking Form</h6>
                                </div>
                                <div class="card-body"style ="height: 450px;">
                                    <p></p>

                                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

                        <form id="signup-form" method="post" action="">
                            <h2>Booking Mesin</h2>

                            <div class="form-group">
                                <label for="tanggal_pinjam">Tanggal Peminjaman:</label>
                                <div class="input-icon">
                                    <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                                    <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="waktu_mulai">Waktu Booking:</label>
                                <div class="input-icon">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    <input type="time" id="waktu_mulai" name="waktu_mulai" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="waktu_selesai">Waktu Selesai:</label>
                                <div class="input-icon">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    <input type="time" id="waktu_selesai" name="waktu_selesai" required>
                                </div>
                            </div>

                            <input type="submit" value="Submit">
                        </form>

                        <style>
                            .form-group {
                                margin-bottom: 15px;
                                position: relative;
                            }
                            
                            .input-icon {
                                display: flex;
                                align-items: center;
                            }

                            .input-icon i {
                                margin-right: 10px; /* Space between icon and input */
                            }

                            input[type="date"],
                            input[type="time"] {
                                padding: 10px;
                                font-size: 16px;
                                border: 1px solid #ccc;
                                border-radius: 5px;
                                width: 100%; /* Makes the input full width */
                                box-sizing: border-box; /* Ensures padding is included in width */
                            }

                            input[type="submit"] {
                                background-color: #007BFF;
                                color: white;
                                padding: 10px 15px;
                                border: none;
                                border-radius: 5px;
                                cursor: pointer;
                            }

                            input[type="submit"]:hover {
                                background-color: #0056b3;
                            }
                        </style>

                            <div class="my-2"></div>
                                </div>
                            </div>

                        </div>
                
                

        </div>
        
        
        
            
            


            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy;Simalas 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
     <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="errorMessage">
                    <!-- Pesan error akan muncul di sini -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


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
                    <a class="btn btn-primary"id="logout_button" href="login.php">Logout</a>
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
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>