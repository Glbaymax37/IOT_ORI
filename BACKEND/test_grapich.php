<?php
session_start(); 
$userid = $_SESSION["simalas_userid"]; 
$username = $_SESSION["simalas_nama"];
$userNIM = $_SESSION["simalas_NIM"];
$userPBL = $_SESSION["simalas_PBL"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Printer Management</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/justgage/1.4.0/justgage.min.js"></script>
    <style>
        /* Styles as you defined them earlier */
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
            align-items: center;
            font-size: 40px;
        }

        h1, h2 {
            color: #ffd700;
        }

        .gauge-container {
            width: 300px; /* Atur lebar sesuai kebutuhan */
            height: 200px; /* Atur tinggi sesuai kebutuhan */
            margin-top: 20px;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #333; 
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
            "Booking" => "booking.php",
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
        <?php
        echo "Welcome " . htmlspecialchars($username)."<br>";
        echo "NIM: ". htmlspecialchars($userNIM) ."<br>";
        echo "PBL: ". htmlspecialchars($userPBL) ."<br>";
        echo "Today is " . date("Y/m/d") . "<br>";
        echo "Your User ID is: " . htmlspecialchars($userid);
        ?>

        <!-- Gauge for Speedometer -->
        <div class="gauge-container">
            <div id="gauge" style="width: 100%; height: 100%;"></div>
        </div>

        <script>
            var g = new JustGage({
                id: "gauge",
                value: 75, // Ganti dengan nilai yang diinginkan
                min: 0,
                max: 100,
                title: "Speed",
                label: "km/h",
                gaugeWidthScale: 0.6,
                counter: true,
                showInnerShadow: true,
                gaugeColor: "#222",
                labelFontColor: "#ffd700",
                valueFontColor: "#ffd700",
                titleFontColor: "#ffd700",
            });
        </script>
    </div>

</body>
</html>
