<?php
session_start();

include("classes/connect.php");
include("classes/login.php");

$NIM = "";
$Password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = new Login();
    $result = $login->evaluate($_POST);

    if ($result != "") {
        echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
        echo "<br>The following errors occurred:<br><br>";
        echo $result;
        echo "</div>";
    } else {
        header("Location: dashboard.php");
        die;
    }

    $NIM = $_POST['NIM'];
    $Password = $_POST['Password']; // Konsisten dengan form HTML
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN IOT Simalas</title>
    <style>
        /* Styles sesuai yang ada */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('https://www.polibatam.ac.id/wp-content/uploads/2022/03/Technopreneur-Polibatam-1.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }

        #bar {
            height: 70px;
            background-color: black;
            color: yellow;
            font-size: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        #bar-buttons {
            font-size: 20px;
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

        .button:hover {
            background-color: orange;
        }

        #content {
            background-color: rgba(51, 51, 51, 0.8); /* Add transparency for better visibility */
            height: calc(100vh - 70px); /* Adjust height to account for the navbar */
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #signup-form {
            background-color: #444444;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 300px;
        }

        #signup-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-field {
            margin-bottom: 15px;
        }

        .form-field label {
            display: block;
            margin-bottom: 5px;
        }

        .form-field input {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .form-field input[type="submit"] {
            background-color: yellow;
            color: black;
            border: none;
            cursor: pointer;
            font-weight: bold;
            margin-top: 20px;
        }

        .form-field input[type="submit"]:hover {
            background-color: orange;
        }
    </style>
</head>

<body>

<div id="bar">
    <div id="title">IOT Simalas</div>
    <div id="bar-buttons">
        <button class="button" id="signup-button">Signup</button>
    </div>
</div>

<script>
    // Redirect ke halaman signup saat tombol di klik
    document.getElementById("signup-button").addEventListener("click", function() {
        window.location.href = "Signup.php";
    });
</script>

<div id="content">
    <form id="signup-form" method="post">
        <h2>Login</h2>
        <div class="form-field">
            <label for="NIM">NIM:</label>
            <input name="NIM" value="<?php echo $NIM ?>" type="text" id="NIM" required>
        </div>
        <div class="form-field">
            <label for="Password">Password:</label>
            <input name="Password" value="<?php echo $Password ?>" type="password" id="Password" required>
        </div>
        <div class="form-field">
            <input type="submit" id="login_button" value="LOGIN">
        </div>
    </form>
</div>

</body>
</html>
