document.addEventListener("DOMContentLoaded", function () {
                            var buttonElement = document.getElementById("actionButton");
                            var transferInterval; // Variable untuk menyimpan interval

                            // Fungsi untuk memperbarui jam
                            function updateClock() {
                                var now = new Date();
                                var hours = now.getHours();
                                var minutes = now.getMinutes();

                                console.log("Current Time:", hours, minutes); // Menampilkan waktu saat ini

                                // Mendapatkan waktu mulai dan selesai dari PHP
                                var startHour = <?php echo $jamMulai ?>;    
                                var startMinute = <?php echo $menitMulai ?>;  
                                var endHour = <?php echo $jamUsai ?>;     
                                var endMinute = <?php echo $menitSelesai ?>;    

                                // Aktifkan atau nonaktifkan tombol berdasarkan waktu
                                if ((hours > startHour || (hours === startHour && minutes >= startMinute)) &&
                                    (hours < endHour || (hours === endHour && minutes < endMinute))) {
                                    buttonElement.disabled = false; // Aktif
                                } else {
                                    buttonElement.disabled = true; // Nonaktif
                                    clearInterval(transferInterval); // Hentikan proses pemindahan data jika tombol nonaktif
                                }
                            }

                            // Fungsi untuk memulai pemindahan data
                            function startDataTransfer() {
                                transferInterval = setInterval(function () {
                                    var xhr = new XMLHttpRequest();
                                    xhr.open("POST", "move_data.php", true);
                                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                                    xhr.onreadystatechange = function () {
                                        if (xhr.readyState === XMLHttpRequest.DONE) {
                                            try {
                                                var response = JSON.parse(xhr.responseText);
                                                if (response.status === "success") {
                                                    console.log("Data successfully moved.");
                                                } else {
                                                    console.error("Data move failed.");
                                                }
                                            } catch (e) {
                                                console.error("Error parsing JSON:", e);
                                            }
                                        }
                                    };

                                    xhr.send(); // Kirim permintaan pemindahan data
                                }, 5000); // Kirim setiap 5 detik (sesuaikan interval jika diperlukan)
                            }

                            // Fungsi untuk menghentikan pemindahan data
                            function stopDataTransfer() {
                                clearInterval(transferInterval); // Hentikan interval
                                console.log("Data transfer stopped.");
                            }

                            // Menambahkan event listener ke tombol
                            buttonElement.addEventListener("click", function () {
                                if (!buttonElement.disabled) { // Pastikan tombol aktif
                                    alert("Mesin diaktifkan");
                                    startDataTransfer(); // Mulai proses pemindahan data
                                }
                            });

                            // Memperbarui jam setiap detik
                            setInterval(updateClock, 1000);
                        });                  