<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Bar Example</title>
    <style>
        .progress-container {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 5px;
            margin: 20px 0;
        }

        .progress-bar {
            height: 30px;
            width: 0;
            background-color: #4caf50;
            border-radius: 5px;
            text-align: center;
            line-height: 30px;
            color: white;
        }
    </style>
</head>
<body>
    <h2>Progress Bar Example</h2>
    <div class="progress-container">
        <div class="progress-bar" id="progressBar">0%</div>
    </div>

    <button onclick="startProgress()">Start Progress</button>

    <script>
        function startProgress() {
            let progressBar = document.getElementById('progressBar');
            let width = 0;
            let interval = setInterval(function() {
                if (width >= 100) {
                    clearInterval(interval);
                } else {
                    width++;
                    progressBar.style.width = width + '%';
                    progressBar.innerHTML = width + '%';
                }
            }, 100); // kecepatan progress, 100ms per increment
        }
    </script>
</body>
</html>
