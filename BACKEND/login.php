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
        // Redirect ke halaman dashboard jika login sukses
        header("Location: dashboard.php");
        die;
    }

    // Ambil nilai dari form
    $NIM = $_POST['NIM'];
    $Password = $_POST['Password'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('https://www.polibatam.ac.id/wp-content/uploads/2022/03/Technopreneur-Polibatam-1.jpg') no-repeat center center fixed;
            
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 300px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .login-container label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: left;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #f9f9f9;
            box-sizing: border-box;
        }

        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #084a9b;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }

        .login-container input[type="submit"]:hover {
            background: #4a8adb;
        }

        .login-container a {
            display: block;
            margin-top: 15px;
            color: #4f8bd4;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <form method="post">
        <label for="NIM">NIM:</label>
        <input name="NIM" value="<?php echo $NIM ?>" type="text" id="NIM" required>
        
        <label for="Password">Password:</label>
        <input name="Password" value="<?php echo $Password ?>" type="password" id="Password" required>
        
        <input type="submit" value="Login">
    </form>
    <a href="Signup.php">Don't have an account? Signup here</a>
</div>

</body>
</html>
