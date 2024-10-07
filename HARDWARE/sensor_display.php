<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sensor 3D Printing</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-custom {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.3s;
        }
        .card-custom:hover {
            transform: scale(1.05);
        }
        .sensor-box {
            background-color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .sensor-box h3 {
            margin-bottom: 15px;
            color: #333;
        }
        .sensor-box p {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .update-time {
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
        .row {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Monitoring Data Sensor Mesin 3D Printing</h1>
        
        <div class="row">
            <!-- Box for Tegangan -->
            <div class="col-md-4">
                <div class="sensor-box card-custom">
                    <h3>Tegangan (V)</h3>
                    <p id="tegangan">-</p>
                </div>
            </div>

            <!-- Box for Arus -->
            <div class="col-md-4">
                <div class="sensor-box card-custom">
                    <h3>Arus (A)</h3>
                    <p id="arus">-</p>
                </div>
            </div>

            <!-- Box for Daya -->
            <div class="col-md-4">
                <div class="sensor-box card-custom">
                    <h3>Daya (W)</h3>
                    <p id="daya">-</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Box for Energi -->
            <div class="col-md-4">
                <div class="sensor-box card-custom">
                    <h3>Energi (kWh)</h3>
                    <p id="energi">-</p>
                </div>
            </div>

            <!-- Box for Suhu -->
            <div class="col-md-4">
                <div class="sensor-box card-custom">
                    <h3>Suhu (°C)</h3>
                    <p id="suhu">-</p>
                </div>
            </div>

            <!-- Box for Kelembapan -->
            <div class="col-md-4">
                <div class="sensor-box card-custom">
                    <h3>Kelembapan (%)</h3>
                    <p id="kelembapan">-</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Box for Panjang Filament Digunakan -->
            <div class="col-md-4 mx-auto">
                <div class="sensor-box card-custom">
                    <h3>Filament Digunakan (m)</h3>
                    <p id="used">-</p>
                </div>
            </div>
        </div>

        <!-- Update time -->
        <div class="text-center update-time">
            <p id="last-update">Last update: -</p>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
   <!-- Custom Script -->
<script>
    // Fungsi untuk mengambil data sensor secara berkala (setiap 5 detik)
    function fetchData() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "fetch_sensor_data.php", true);
        xhr.onload = function () {
            if (this.status === 200) {
                // Log untuk debug response dari server
                console.log("Response Text:", this.responseText);

                // Parse HTML response ke dalam DOM
                var parser = new DOMParser();
                var doc = parser.parseFromString(this.responseText, 'text/html');
                var firstRow = doc.querySelector('tbody tr');  

                if (firstRow) {
                    var cells = firstRow.querySelectorAll('td');
                    document.getElementById('tegangan').innerText = cells[0].innerText;
                    document.getElementById('arus').innerText = cells[1].innerText;
                    document.getElementById('daya').innerText = cells[2].innerText;
                    document.getElementById('energi').innerText = cells[3].innerText; 
                    document.getElementById('suhu').innerText = cells[4].innerText;
                    document.getElementById('kelembapan').innerText = cells[5].innerText;
                    document.getElementById('used').innerText = cells[6].innerText;

                    // Update waktu
                    var currentTime = new Date().toLocaleTimeString();
                    document.getElementById('last-update').innerText = "Last update: " + currentTime;
                }
            }
        };
        xhr.send();
    }

    // Interval untuk memperbarui data setiap 5 detik
    setInterval(fetchData, 5000);

    // Memanggil fungsi fetchData saat halaman pertama kali dimuat
    window.onload = fetchData;
</script>
</body>
</html>
