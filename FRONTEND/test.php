<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Execute on Button Press</title>
</head>
<body>
    <button id="startBtn">Mulai Eksekusi</button>
    <button id="stopBtn">Berhenti Eksekusi</button>
    <p id="counter">0</p>

    <script>
        let interval;
        let count = 0;

        document.getElementById('startBtn').addEventListener('click', function() {
            interval = setInterval(function() {
                count++;
                document.getElementById('counter').innerText = count;
                // Bagian program yang akan dijalankan berulang
                console.log("Program dijalankan berulang ke: " + count);
            }, 1000); // Fungsi akan dijalankan setiap 1000 ms (1 detik)
        });

        document.getElementById('stopBtn').addEventListener('click', function() {
            clearInterval(interval); // Menghentikan eksekusi berulang
            console.log("Eksekusi berulang dihentikan");
        });
    </script>
</body>
</html>
