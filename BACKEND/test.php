<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Mesin</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container {
        max-width: 800px;
        width: 100%;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    h2 {
        margin-bottom: 10px;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 10px;
        
    }

    input[type="text"], select, input[type="button"] {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 100%;
        max-width: 300px;
    }

    input[type="button"] {
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
        border: none;
        max-width: 300px;
    }

    input[type="button"]:hover {
        background-color: #45a049;
    }

    table {
        width: 100%;
        max-width: 300px;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table, th, td {
        border: 1px solid black;
    }

    th, td {
        padding: 10px;
        text-align: left;
    }
</style>

</head>
<body>

<div id="content">
    <form id="signup-form" method="post" action="">
    <h2>Booking Mesin</h2>
    
    <label for="tanggal_pinjam">Tanggal Peminjaman:</label>
        <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" required>
    <div class="form-field">

    <label for="waktu_mulai">Waktu Booking:</label>
    <input type="time" id="waktu_mulai" name="waktu_mulai" required>

    <label for="waktu_selesai">Waktu Selesai:</label>
    <input type="time" id="waktu_selesai" name="waktu_selesai" required><br><br>
    <input type="submit" value="Submit">
    </div>
</form>

    </div>

</body>
</html>
