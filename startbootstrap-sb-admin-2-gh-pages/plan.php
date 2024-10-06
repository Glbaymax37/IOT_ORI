<div class="col-lg-1 mb-4">
    <button class="card bg-success text-white shadow" id="actionButton" style="border: none; width: 100%; padding: 15px; text-align: left;" disabled data-toggle="modal" data-target="#passwordModal">
        Aktifkan Mesin
        <div class="text-white-50 small"></div>
    </button>
</div>

<!-- Modal untuk Meminta Kata Sandi -->
<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Masukkan Kata Sandi untuk Mengaktifkan Mesin</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="activateMachineForm">
                    <div class="form-group">
                        <label for="password">Kata Sandi:</label>
                        <input type="password" class="form-control" id="password" placeholder="Masukkan kata sandi" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="confirmActivateButton">Aktifkan Mesin</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var buttonElement = document.getElementById("actionButton");
        var confirmActivateButton = document.getElementById("confirmActivateButton");

        function updateClock() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();

            console.log("Current Time:", hours, minutes); // Menampilkan waktu saat ini

            var startHour = <?php echo $jamMulai?>;    
            var startMinute = <?php echo $menitMulai?>;  
            var endHour = <?php echo $jamUsai?>;     
            var endMinute = <?php echo $menitSelesai?>;    

            // Aktifkan atau nonaktifkan tombol berdasarkan waktu
            if ((hours > startHour || (hours === startHour && minutes >= startMinute)) &&
                (hours < endHour || (hours === endHour && minutes < endMinute))) {
                buttonElement.disabled = false; // Aktif
            } else {
                buttonElement.disabled = true; 
            }
        }

        confirmActivateButton.addEventListener("click", function() {
            var password = document.getElementById("password").value;
            
            // Ganti dengan logika verifikasi kata sandi yang sesuai
            if (password === "yourActualPassword") { // Ganti dengan cara verifikasi yang sesuai
                // Logika untuk mengaktifkan mesin
                alert("Mesin telah diaktifkan.");
                // Tutup modal setelah berhasil
                $('#passwordModal').modal('hide');
            } else {
                alert("Kata sandi salah. Silakan coba lagi.");
            }
        });

        setInterval(updateClock, 1000);
    });
</script>

<style>
    #actionButton:disabled {
        background-color: gray; /* Warna tombol saat dinonaktifkan */
        color: white;           
        cursor: not-allowed;    
        opacity: 0.5; /* Opasitas tombol saat dinonaktifkan */
    }
</style>
