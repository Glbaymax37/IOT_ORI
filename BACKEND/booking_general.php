<?php
session_start();

$userid = $_SESSION["simalas_userid"]; 
$username = $_SESSION["simalas_nama"];
$userNIM = $_SESSION["simalas_NIM"];
$userPBL = $_SESSION["simalas_PBL"];
$tanggalbooking = $_SESSION["tanggal_booking"];


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

    var_dump($result);


    if ($result) {
        echo "oke semua";
    }
    else{

        header("Location: Login2.php");
        die;
    }
}
else{
    header("Location: Login2.php");
    die;
}
$error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Evaluasi data yang diterima
    $error = $booking->evaluate($_POST);

    if ($error != "") {
        echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
        echo "<br>The following errors occurred:<br><br>";
        echo $error;
        echo "</div>";
    } else {
        // Data valid, buat booking
        $result = $booking->create_booking($id, $username, $userNIM, $userPBL, $_POST); 
        header("Location: booking_general.php"); 
        die;
    }
}

// Ambil semua booking setelah form diproses
$hasil = $booking->getAllBookings(); // Pastikan objek booking sudah diciptakan
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Printer Management</title>
    <style>
        #bar {
            height: 65px;
            background-color: #222;
            color: #ffd700;
            font-size: 32px;
            font-weight: bold;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000; 
            margin-left: -10px;
        }
        #logout_button {
            margin-left: 1000px; 
        }
        
        #bar-buttons {
            font-size: 20px;
            margin-left: 20px;
        }

        .button {
            background-color: yellow;
            color: black;
            border: none; 
            padding: 10px 20px;
            cursor: pointer;
            margin-left: 10px;
            border-radius: 5px;
        }

        #sidebar {
            width: 220px;
            height: calc(100vh - 65px);
            background-color: #2c2c2c;
            padding: 20px;
            position: fixed;
            top: 65px;
            left: 0;
            color: #ffd700;
            box-shadow: 4px 0 8px rgba(0, 0, 0, 0.2);
            overflow-y: auto; 
        }

        #sidebar .menu-item {
            margin-bottom: 20px;
        }

        #sidebar .menu-item button {
            background-color: #444;
            color: #ffd700;
            border: none;
            padding: 12px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            text-align: left;
            font-size: 18px;
            transition: background-color 0.3s, color 0.3s;
        }

        #sidebar .menu-item button:hover {
            background-color: #ffd700;
            color: #222;
        }

        /* Style for the main content */
        #content {
            margin-left: 240px; 
            padding: 40px;
            margin-top: 65px;
            background-color: #333;
            color: white;
            min-height: calc(100vh - 65px);
            display: flex;
            flex-direction: column;
            align-items: center; /* Center the items vertically */
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; 
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #333; 
            color: white;
        }
        form {
        display: flex;
        flex-direction: column;
        gap: 10px;
        
        }
        
    </style>
</head>
<body>

    <!-- Top Bar -->
    <div id="bar">
        <div id="title">3D Printer Management</div>
        <div id="bar-buttons">
            <button class="button" id="logout_button">Logout</button>
        </div>

        <script>
            document.getElementById("logout_button").addEventListener("click", function() {
                window.location.href = "logout.php";
            });
        </script>
    </div>

    <!-- Sidebar -->
    <div id="sidebar">
        <?php
        // Menu items
        $menu_items = [
            "Dashboard" => "dashboard.php",
            "Booking" => "booking_general.php",
            "Settings" => "#"
        ];

        $current_page = basename($_SERVER['PHP_SELF']);

        foreach ($menu_items as $item => $link) {
            $active = ($current_page == $link) ? "active" : "";
            echo '<div class="menu-item">';
            echo '<button class="button ' . $active . '" onclick="location.href=\'' . $link . '\'">' . $item . '</button>';
            echo '</div>';
        }
        ?>
    </div>

    <!-- Main Content -->
    <div id="content">
        <h2>Data Pengguna</h2>
        <table>
            <tr>
                <th>NAMA</th>
                <th>NIM</th>
                <th>PBL</th>
                <th>Tanggal Peminjaman</th>
                <th>Jam Peminjaman</th>
                <th>Estimasi Selesai</th>


            </tr>

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
          </table>  
          <form id="signup-form" method="post" action="">
    <h2>Booking Mesin</h2>

    <label for="tanggal_pinjam">Tanggal Peminjaman:</label>
    <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" required>

    <label for="waktu_mulai">Waktu Booking:</label>
    <input type="time" id="waktu_mulai" name="waktu_mulai" required>

    <label for="waktu_selesai">Waktu Selesai:</label>
    <input type="time" id="waktu_selesai" name="waktu_selesai" required><br><br>

    <input type="submit" value="Submit">
</form>


    </div>

</body>
</html>
