<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Clock with Button</title>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var clockElement = document.getElementById("clock");
            var hoursElement = document.getElementById("hours");
            var minutesElement = document.getElementById("minutes");
            var secondsElement = document.getElementById("seconds"); // Element untuk detik
            var buttonElement = document.getElementById("actionButton");

            function updateClock() {
                var now = new Date();
                var hours = now.getHours();
                var minutes = now.getMinutes();
                var seconds = now.getSeconds(); // Mendapatkan detik

                // Menampilkan jam, menit, dan detik
                hoursElement.innerHTML = "Hours: " + String(hours).padStart(2, '0');
                minutesElement.innerHTML = "Minutes: " + String(minutes).padStart(2, '0');
                secondsElement.innerHTML = "Seconds: " + String(seconds).padStart(2, '0'); // Menampilkan detik
                clockElement.innerHTML = "Current Time: " + now.toLocaleTimeString();

                // Atur waktu aktif tombol
                var startHour = 9;    // Jam mulai
                var startMinute = 0;  // Menit mulai
                var endHour = 20;     // Jam selesai
                var endMinute = 9;   // Menit selesai

                // Aktifkan atau nonaktifkan tombol berdasarkan waktu
                if ((hours > startHour || (hours === startHour && minutes >= startMinute)) &&
                    (hours < endHour || (hours === endHour && minutes < endMinute))) {
                    buttonElement.disabled = false; // Aktif
                } else {
                    buttonElement.disabled = true; // Nonaktif
                }
            }

            setInterval(updateClock, 1000);
        });
    </script>
</head>
<body>
    <h1>Current Time</h1>
    <div id="clock"></div>
    <div id="hours"></div>
    <div id="minutes"></div>
    <div id="seconds"></div> <!-- Menambahkan elemen untuk detik -->
    <button id="actionButton" disabled>Klik Saya</button>
</body>
</html>
