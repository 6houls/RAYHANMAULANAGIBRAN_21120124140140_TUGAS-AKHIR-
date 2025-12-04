<?php
session_start();


if (isset($_POST['nama']) && isset($_POST['nim'])) {
    $_SESSION['nama'] = htmlspecialchars($_POST['nama']); 
    $_SESSION['nim'] = htmlspecialchars($_POST['nim']);
} elseif (!isset($_SESSION['nama']) || !isset($_SESSION['nim'])) {
    header("Location: index.php");
    exit;
}


$avatars = [
    "char1.png",
    "char2.png",
    "char3.png",
];
?>

<!DOCTYPE html>
<html>
<head>
<title>Pilih Avatar 🌟</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
/* CSS Disesuaikan */
body {margin:0; font-family:'Orbitron',sans-serif; background:linear-gradient(-45deg,#ff0099,#493240,#00ffff,#ff9900); background-size:400% 400%; animation:gradientBG 15s ease infinite; color:white; display:flex; justify-content:center; align-items:center; height:100vh;}
@keyframes gradientBG {0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}
.container {background: rgba(0,0,0,0.7); padding:40px; border-radius:20px; box-shadow:0 0 30px #00ffff; text-align:center; width:600px;}
h2 {text-shadow:0 0 5px #00ffff,0 0 10px #ff00ff;}
p {margin-bottom: 25px;} 
.avatar-grid {display:flex; justify-content:space-around; margin-top:30px;}
.avatar-option {
    width:120px; 
    height:120px; 
    border:5px solid transparent; 
    border-radius:15px; 
    cursor:pointer; 
    transition:0.3s; 
    object-fit: cover;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
}
.avatar-option:hover {border-color:#ff00ff; box-shadow:0 0 20px #ff00ff;}
.selected {
    border-color:#00ffff!important; 
    box-shadow:0 0 30px #00ffff!important, 0 0 50px #ff00ff!important; 
    transform:scale(1.1);
}
button{padding:12px 25px;border:none;border-radius:12px;background:#ff00ff;color:white;font-size:16px;cursor:pointer; box-shadow:0 0 10px #ff00ff; transition:0.3s;text-shadow:0 0 3px #00ffff; margin-top:30px;}
button:hover{background:#00ffff;color:#000; box-shadow:0 0 20px #00ffff; transform:scale(1.05);}
button:disabled {
    background: #444; 
    box-shadow: none; 
    cursor: not-allowed;
    color: #aaa;
    transform: none;
}
</style>
<script>
    function selectAvatar(url, event) {
        document.getElementById('avatar-url').value = url;
        
        document.querySelectorAll('.avatar-option').forEach(img => img.classList.remove('selected'));
        event.target.classList.add('selected');
        
        document.getElementById('lanjut-btn').disabled = false;
    }
</script>
</head>
<body>

<div class="container">
<h2>Halo, <?= $_SESSION['nama'] ?>!</h2>
<p>Pilih Avatar Anime Anda untuk memulai sesi:</p>

<form action="pertanyaan.php" method="POST">
    <input type="hidden" name="avatar" id="avatar-url" required>
    
    <div class="avatar-grid">
        <?php foreach($avatars as $url): ?>
            <img src="<?= $url ?>" class="avatar-option" onclick="selectAvatar('<?= $url ?>', event)" alt="Avatar Anime">
        <?php endforeach; ?>
    </div>
    
    <button type="submit" id="lanjut-btn" disabled>Lanjut ke Pertanyaan 🚀</button>
</form>

</div>

</body>
</html>