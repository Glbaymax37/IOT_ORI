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
        echo"<br>The following errors occured:<br><br>";
        echo $result;
        echo "</div>";

    }else{
        header("Location: login2.php");
        die;
    }
    
    $Nama = $_POST['Nama'];
    $NIM = $_POST['NIM'];
    $PBL = $_POST['PBL'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];

    }
?>
<html>

<head>
    <title>IOT Simalas</title>
    <style>
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
            background-color: #333333;
            height: 100vh;
            color: white;
            padding: 20px;
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

        .form-field input,
        .form-field select {
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
            <button class="button" id="signup-button">Login</button>
        </div>
    </div>

    <script>
        document.getElementById("signup-button").addEventListener("click", function () {
            window.location.href = "login2.php";
        });
    </script>

    <div id="content">
    <form id="signup-form" method="post" action="">
    <h2>Register</h2>
    <div class="form-field">
        <label for="name">Nama:</label>
        <input type="text" id="name" name="Nama" placeholder="Masukkan Nama" required>
    </div>
    <div class="form-field">
        <label for="nim">NIM:</label>
        <input type="text" id="nim" name="NIM" placeholder="Masukkan NIM" required>
    </div>

    <div class="form-field">
        <label for="PBL">PBL:</label>
        <input type="text" id="PBL" name="PBL" placeholder="PBL"required>
    </div>

    <div class="form-field">
        <label for="password">Create Password:</label>
        <input type="password" id="password" name="Password" placeholder="Password" required>
    </div>

    <div class="form-field">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Email"required>
    </div>

    <div class="form-field">
        <label for="gender">Jenis Kelamin:</label>
        <select id="gender" name="gender" required>
            <option value="male">Laki-laki</option>
            <option value="female">Perempuan</option>
        </select>
    </div>
    <div class="form-field">
        <input type="submit" value="Sign Up">
    </div>
</form>

    </div>

</body>

</html>
