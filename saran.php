<?php
session_start();

if (!isset($_SESSION['saran_mode'])) {
    header("Location: index.php");
    exit;
}

$stabil = [
"Pertahankan pola hidup sehat",
"Jaga tidur yang cukup",
"Terus lakukan hobi positif",
"Berinteraksi dengan teman & keluarga",
"Kelola waktu dengan baik",
"Fokus pada hal baik",
"Jaga pikiran positif",
"Berolahraga ringan",
"Bernapas teratur",
"Tetap produktif"
];

$turun = [
"Dengarkan musik santai","Ambil napas panjang","Jalan sebentar",
"Minum air hangat","Kurangi HP","Meditasi 5 menit","Cari udara segar",
"Olahraga ringan","Nonton film favorit","Tulis perasaanmu",
"Berbicara dengan teman","Istirahat sejenak","Mandi air hangat",
"Jauh dari hal toxic","Rapikan kamar","Minum minuman favorit",
"Coba journaling","Coba tidur sebentar","Ciptakan suasana nyaman","Lakukan hobi"
];

$saran = ($_SESSION['saran_mode']=="stabil" ? $stabil : $turun);
$judul = ($_SESSION['saran_mode']=="stabil" ? "Pertahankan Mood Positifmu" : "Tingkatkan Moodmu Segera!");
?>

<!DOCTYPE html>
<html>
<head>
<title>Saran Mood</title>

<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>

body {
    margin:0; 
    font-family:'Orbitron'; 
    background:linear-gradient(-45deg,#ff0099,#493240,#00ffff,#ff9900);
    background-size:400% 400%; 
    animation:gradientBG 15s ease infinite;
    color:white; 
    display:flex; 
    justify-content:center; 
    align-items:center; 
    height:100vh;
}
@keyframes gradientBG{0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}

.container {
    background:rgba(0,0,0,0.7); 
    padding:30px; 
    width:700px; 
    max-height: 80vh; 
    overflow-y: auto; 
    border-radius:20px; 
    box-shadow:0 0 30px #ff00ff; 
    text-align:center;
}
h1 {text-shadow:0 0 5px #00ffff; margin-bottom: 25px;}

.saran-grid {
    display: flex;
    flex-wrap: wrap; 
    justify-content: center; 
    gap: 15px; 
    margin: 20px 0;
}
.saran-item {
    background: rgba(255, 255, 255, 0.1);
    padding: 10px 15px;
    border-radius: 8px;
    width: 200px; 
    text-align: center;
    font-size: 0.9em;
    font-weight: bold;
    box-shadow: 0 0 5px rgba(0, 255, 255, 0.5);
    transition: 0.2s;
}
.saran-item:hover {
    background: rgba(0, 255, 255, 0.2);
    transform: translateY(-2px);
}

.back-button {
    padding:12px 25px; 
    border:none; 
    border-radius:12px; 
    background:#ff00ff; 
    color:white; 
    font-size:16px; 
    cursor:pointer; 
    box-shadow:0 0 10px #ff00ff; 
    transition:0.3s; 
    margin-top:30px; 
    text-decoration: none; 
    display: inline-block;
}
.back-button:hover{
    background:#00ffff;
    color:#000; 
    box-shadow:0 0 20px #00ffff; 
    transform:scale(1.05);
}
</style>

</head>

<body>
<div class="container">
<h1><?= $judul ?></h1>

<div class="saran-grid">
    <?php foreach($saran as $s): ?>
        <div class="saran-item"><?= $s ?></div>
    <?php endforeach; ?>
</div>

<a href="index.php" class="back-button">Selesai</a>
</div>
</body>
</html>