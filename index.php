<?php
session_start();

session_unset();
session_destroy();
session_start(); 
?>

<!DOCTYPE html>
<html>
<head>
<title>Analisis Mood - Identitas</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
body {margin:0; font-family:'Orbitron',sans-serif; background:linear-gradient(-45deg,#ff0099,#493240,#00ffff,#ff9900); background-size:400% 400%; animation:gradientBG 15s ease infinite; color:white; display:flex; justify-content:center; align-items:center; height:100vh;}
@keyframes gradientBG {0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}
.container {background: rgba(0,0,0,0.7); padding:40px; border-radius:20px; box-shadow:0 0 30px #00ffff; text-align:center; width:500px;}
h2 {text-shadow:0 0 5px #00ffff,0 0 10px #ff00ff;}
input[type=text]{width:100%; padding:12px; margin:10px 0; border-radius:12px; border:none; outline:none; background:rgba(255,255,255,0.1); color:white;}
button{padding:12px 25px;border:none;border-radius:12px;background:#ff00ff;color:white;font-size:16px;cursor:pointer; box-shadow:0 0 10px #ff00ff; transition:0.3s;text-shadow:0 0 3px #00ffff;}
button:hover{background:#00ffff;color:#000; box-shadow:0 0 20px #00ffff; transform:scale(1.05);}
</style>
</head>
<body>

<div class="container">
<h2>Identifikasi Mahasiswa</h2>
<form action="avatar.php" method="POST">
    <input type="text" name="nama" placeholder="Nama Mahasiswa" required>
    <input type="text" name="nim" placeholder="NIM Mahasiswa" required>
    <button type="submit" style="margin-top:20px;">Lanjut</button>
</form>
</div>

</body>
</html>