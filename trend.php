<?php
session_start();


$history = $_SESSION['history'] ?? array();
$nama = $_SESSION['nama'] ?? 'Mahasiswa';

$labels = array();
$data_points = array();

if (!empty($history)) {
    foreach ($history as $index => $record) {
        $labels[] = "Test " . ($index + 1); 
        $data_points[] = $record['score'];
    }
}

$labels_json = json_encode($labels);
$data_points_json = json_encode($data_points);
?>

<!DOCTYPE html>
<html>
<head>
<title>Tren Mood</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">

<style>
body {margin:0; font-family:'Orbitron'; background:linear-gradient(-45deg,#ff0099,#493240,#00ffff,#ff9900);
background-size:400% 400%; animation:gradientBG 15s ease infinite;
color:white; display:flex; justify-content:center; align-items:center; height:100vh;}
@keyframes gradientBG{0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}

.container {background:rgba(0,0,0,0.7); width:80%; max-width: 700px; padding:30px; border-radius:20px; text-align:center; box-shadow:0 0 30px #00ffff;}
h1 {text-shadow:0 0 5px #ff00ff;}
.chart-container {height: 400px; width: 100%; margin: 20px auto;}
button {padding:12px 25px; border:none; border-radius:12px; background:#ff00ff; color:white; font-size:16px; cursor:pointer; box-shadow:0 0 10px #ff00ff; transition:0.3s; margin-top:25px;}
button:hover {background:#00ffff; color:#000; transform:scale(1.1); box-shadow:0 0 20px #00ffff;}
</style>
</head>

<body>
<div class="container">
<h1>Grafik Tren Mood - <?= $nama ?></h1>
<p>Riwayat Analisis Mood Anda (Persentase)</p>

<div class="chart-container">
    <canvas id="moodChart"></canvas>
</div>

<a href="index.php"><button>Mulai Tes Baru</button></a>
</div>

<script>
    const labels = <?= $labels_json ?>;
    const dataPoints = <?= $data_points_json ?>;

    const data = {
        labels: labels,
        datasets: [{
            label: 'Persentase Mood (%)',
            data: dataPoints,
            fill: false,
            borderColor: '#00ffff', 
            tension: 0.3, 
            backgroundColor: '#ff00ff', 
            borderWidth: 3
        }]
    };

    const config = {
        type: 'line',
        data: data,
        options: {
            scales: {
                y: {
                    min: 0,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Skor Mood (%)',
                        color: 'white'
                    },
                    ticks: {
                        color: 'white'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Sesi Tes',
                        color: 'white'
                    },
                    ticks: {
                        color: 'white'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: 'white'
                    }
                }
            }
        }
    };

    if (dataPoints.length > 0) {
        new Chart(
            document.getElementById('moodChart'),
            config
        );
    } else {
        document.querySelector('.chart-container').innerHTML = "<p>Lakukan minimal dua sesi tes untuk melihat tren mood.</p>";
    }
</script>

</body>
</html>