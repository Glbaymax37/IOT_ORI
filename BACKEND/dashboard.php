<?php
session_start();

$userid = $_SESSION["simalas_userid"]; 
$username = $_SESSION["simalas_nama"];
$userNIM = $_SESSION["simalas_NIM"];
$userPBL = $_SESSION["simalas_PBL"];

include("classes/connect.php");
include("classes/login.php");
include("classes/createbooking.php");

if(isset($_SESSION["simalas_userid"])&& is_numeric($_SESSION["simalas_userid"]))
{
    $id = $_SESSION["simalas_userid"];
    $login = new Login();

    $login ->check_login($id);

    $result = $login->check_login($id);
    
    $booking = new Booking(); 
    $bookings = $booking->bookingByuser();

    var_dump($result);

    if($result){

        echo "oke semua" ;
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
            height: calc(100vh - 65px); /* Adjusted to account for the top bar height */
            background-color: #2c2c2c;
            padding: 20px;
            position: fixed;
            top: 65px;
            left: 0;
            color: #ffd700;
            box-shadow: 4px 0 8px rgba(0, 0, 0, 0.2);
            overflow-y: auto; /* Ensure sidebar scrolls if content overflows */
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
            margin-left: 240px; /* Adjust to make space for the sidebar */
            padding: 40px;
            margin-top: 65px;
            background-color: #333;
            color: white;
            min-height: calc(100vh - 65px);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-size: 40px;
        }
        h1, h2 {
            color: #ffd700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        .navigation {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #333; /* Ensure background color consistency */
            color: white;
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
        <h2>Welcome, <?php echo htmlspecialchars($username); ?></h2>
        <p>Today is <?php echo date("Y/m/d"); ?></p>

        <!-- <table>
            <tr>
                <th>User ID</th>
                <th>NIM</th>
                <th>PBL</th>
            </tr>
            <tr>
                <td><?//php echo htmlspecialchars($userid); ?></td>
                <td><?//php echo htmlspecialchars($userNIM); ?></td>
                <td><?//php echo htmlspecialchars($userPBL); ?></td>
            </tr>
        </table> -->

        <?php
if ($bookings) {
    echo "<table border='1' cellspacing='0' cellpadding='10'>";
    echo "<tr>
            <th>Nama</th>
            <th>NIM</th>
            <th>PBL</th>
            <th>Tanggal</th>
            <th>Waktu Mulai</th>
            <th>Waktu Selesai</th>
          </tr>";
    
    foreach ($bookings as $row) {
        echo "<tr>";
        echo "<td>" . $row['NAMA'] . "</td>";
        echo "<td>" . $row['NIM'] . "</td>";
        echo "<td>" . $row['PBL'] . "</td>";
        echo "<td>" . $row['Tanggal'] . "</td>";
        echo "<td>" . $row['JamBooking'] . "</td>";
        echo "<td>" . $row['JamSelesai'] . "</td>";
        echo "</tr>";
    }

            echo "</table>";
        } else {
            echo "Tidak ada data booking.";
        }
    ?>
    
    </div>

</body>
</html>
