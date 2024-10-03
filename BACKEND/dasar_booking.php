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
            margin-left:1000px; 
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

        /* Style for the sidebar menu */
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
            background-color: #333;
            color: white;
            min-height: calc(100vh - 65px); /* Ensure content area adjusts with the top bar height */
            margin-top: 65px; /* Ensure content starts below the top bar */
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
        <div id="title"><?php echo "3D Printer Management"; ?></div>

        <div id="bar-buttons">
            <button class="button" id="logout_button">Logout</button>
        </div>

        <script>
            // JavaScript to redirect to the login page
            document.getElementById("logout_button").addEventListener("click", function() {
                window.location.href = "login2.php";
            });
        </script>
    </div>

    <!-- Sidebar -->
    <div id="sidebar">
        <?php 
        // Menu items
        $menu_items = [
            "Dashboard" => "dashboard.php",
            "Booking" => "#",
            "Settings" => "#"
        ];

        foreach ($menu_items as $item => $link) {
            echo '<div class="menu-item">';
            echo '<button class="button" onclick="location.href=\'' . $link . '\'">' . $item . '</button>';
            echo '</div>';
        }
        ?>
    </div>

    <!-- Main Content -->
    <div id="content">
        <h1>3D Printer Management</h1>

        <?php
        // Declaring Variables
        $courseName = "3D Printer Booking";
        $studentNames = ["Abdi"];

        // Example of a PHP Array with keys
        $Time = [
            "Alice" => "13.20",
        ];

        // Function to display booking times
        function displayGrades($Time) {
            echo "<h2>Booking List</h2>";
            echo "<table>";
            echo "<tr><th>Booking</th><th>Time</th></tr>";
            foreach ($Time as $student => $Time) {
                echo "<tr><td>" . $student . "</td><td>" . $Time . "</td></tr>";
            }
            echo "</table>";
        }

        displayGrades($Time);

        // Form for adding new student
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['student_name'])) {
            $newStudent = $_POST['student_name'];
            array_push($student, $newStudent);
            echo "<h2>New Student Added: $newStudent</h2>";
            displayStudents($student);
        }
        ?>

        <h2>Booking</h2>
        <form method="post" action="">
            <label for="student_name">Student Name:</label>
            <input type="text" id="student_name" name="student_name" required>
            <input type="submit" value="Add Student">
        </form>
    </div>

</body>

</html>
