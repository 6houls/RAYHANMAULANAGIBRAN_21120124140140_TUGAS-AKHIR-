<?php
session_start();

// Daftar Pertanyaan (Disalin dari pertanyaan.php untuk analisis)
$pertanyaan_data = array(
    array("q" => "Bagaimana perasaanmu saat bangun tidur?"),
    array("q" => "Bagaimana tingkat semangatmu hari ini?"),
    array("q" => "Apakah kamu merasa mampu menyelesaikan tugas hari ini?"),
    array("q" => "Seberapa baik kamu fokus selama 1 jam terakhir?"),
    array("q" => "Seberapa sering kamu merasa pikiranmu kacau hari ini?"),
    array("q" => "Bagaimana suasana hatimu secara umum hari ini?"),
    array("q" => "Seberapa berat hari yang kamu jalani?"),
    array("q" => "Seberapa puas kamu dengan produktivitasmu hari ini?"),
    array("q" => "Bagaimana tingkat energimu saat ini (skala 1-4)?"),
    array("q" => "Bagaimana kondisi emosimu saat ini?"),
    array("q" => "Seberapa baik interaksimu dengan orang lain hari ini?"),
    array("q" => "Apakah kamu merasa stres hari ini?"),
    array("q" => "Apakah kamu merasa cemas hari ini?"),
    array("q" => "Seberapa nyaman kamu dengan diri sendiri hari ini?"),
    array("q" => "Bagaimana kondisi mentalmu saat ini?"),
    array("q" => "Apakah kamu merasa semuanya berjalan sesuai rencana?"),
    array("q" => "Seberapa sering kamu merasa tenang hari ini?"),
    array("q" => "Seberapa rileks tubuhmu sekarang?"),
    array("q" => "Bagaimana motivasimu saat ini?"),
    array("q" => "Apakah kamu merasa hari ini lebih baik dari kemarin?"),
);

$avatar = $_SESSION['avatar'] ?? 'default.png'; 
$score = round($_SESSION['scorePercent'] ?? 0);
$nama = $_SESSION['nama'] ?? 'Mahasiswa';
$nim = $_SESSION['nim'] ?? '00000000';
$answers = $_SESSION['answers'] ?? [];


// =========================================================
// ✅ LOGIKA FITUR BARU: AREA PERHATIAN (SKOR TERENDAH)
// =========================================================
$area_perhatian = [];

// Loop melalui jawaban dan identifikasi skor terendah (1 atau 2)
if (!empty($answers)) {
    foreach ($answers as $index => $jawaban) {
        // Asumsi: Jawaban index 0 adalah pertanyaan 1, dst.
        if ($jawaban <= 2) {
            $area_perhatian[] = [
                'q_text' => $pertanyaan_data[$index]['q'],
                'score' => $jawaban
            ];
        }
    }
}
// Urutkan berdasarkan skor terendah (skor 1 diletakkan di atas)
usort($area_perhatian, function($a, $b) {
    return $a['score'] <=> $b['score'];
});

// Ambil maksimal 3 area untuk ditampilkan
$top_areas = array_slice($area_perhatian, 0, 3);
// =========================================================


$feedback = array();

if ($score >= 75) {
    $kategori = "Sangat Baik";
    $warna = "#00ff00"; // Ganti ke hijau untuk Sangat Baik
    $feedback = array(
        "Quote: 'Kunci untuk hidup bahagia adalah memiliki sesuatu untuk dilakukan, sesuatu untuk dicintai, dan sesuatu untuk diharapkan.' - Thomas Chalmers",
        "Fakta: Orang dengan mood positif cenderung lebih kreatif dan mampu memecahkan masalah dengan efektif.",
        "Quote: 'Kebahagiaan bukan sesuatu yang sudah jadi. Ia datang dari tindakan Anda sendiri.' - Dalai Lama"
    );
} elseif ($score >= 50) {
    $kategori = "Baik";
    $warna = "#ffff00"; 
    $feedback = array(
        "Quote: 'Kemajuan kecil setiap hari akan menambah hasil yang besar.' - Satya Nani",
        "Fakta: Mood yang stabil memungkinkan Anda membuat keputusan yang lebih rasional dan terukur.",
        "Quote: 'Jangan biarkan kemarin menghabiskan terlalu banyak hari ini.' - Will Rogers"
    );
} elseif ($score >= 30) {
    $kategori = "Kurang Baik";
    $warna = "#ffa500"; 
    $feedback = array(
        "Quote: 'Setiap hari adalah kesempatan baru. Anda dapat membangun kembali apa pun yang Anda inginkan.' - Anonim",
        "Fakta: Studi menunjukkan mendengarkan musik yang menenangkan selama 15 menit dapat menurunkan kadar kortisol (hormon stres).",
        "Quote: 'Hal tersulit untuk dilakukan adalah tidak melakukan apa-apa ketika Anda merasa cemas.' - David Allen"
    );
} else {
    $kategori = "Buruk";
    $warna = "#ff0000"; 
    $feedback = array(
        "Quote: 'Ambil napas. Hari ini, Anda telah melakukan yang terbaik yang Anda bisa.' - Anonim",
        "Fakta: Menghabiskan 10-15 menit di alam terbuka terbukti dapat meningkatkan energi dan mengurangi perasaan negatif.",
        "Quote: 'Bukan gunung di depan kita yang harus kita taklukkan, tetapi gunung di dalam diri kita sendiri.' - Edmund Hillary"
    );
}

$selected_feedback = $feedback[array_rand($feedback)];


$_SESSION['history'][] = array(
    'date' => date('Y-m-d H:i:s'),
    'score' => $score,
    'kategori' => $kategori
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Hasil Mood</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">

<style>
body {margin:0; font-family:'Orbitron'; background:linear-gradient(-45deg,#ff0099,#493240,#00ffff,#ff9900);
background-size:400% 400%; animation:gradientBG 15s ease infinite;
color:white; display:flex; justify-content:center; align-items:center; height:100vh;}
@keyframes gradientBG{0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}

.container {background:rgba(0,0,0,0.7); width:550px; padding:40px; border-radius:20px; text-align:center; box-shadow:0 0 30px <?= $warna ?>; border:2px solid <?= $warna ?>;}
h1 {text-shadow:0 0 10px <?= $warna ?>;}
.score-display {font-size: 5em; font-weight: 700; color: <?= $warna ?>; margin: 10px 0;}
.category {font-size: 1.5em; margin-bottom: 20px;} 

.feedback {
    background: rgba(255, 255, 255, 0.1);
    padding: 15px;
    border-radius: 10px;
    margin-top: 25px;
    font-style: italic;
    color: #00ffff;
}

/* Gaya baru untuk area perhatian */
.attention-area {
    margin-top: 30px;
    border-top: 1px solid rgba(255, 255, 255, 0.3);
    padding-top: 20px;
    text-align: left;
    color: #ff9900;
}
.attention-area h3 {
    text-align: center;
    color: #ff00ff;
    text-shadow: 0 0 5px #ff00ff;
}
.attention-area ul {
    list-style: none;
    padding: 0;
    margin: 10px 0;
}
.attention-area ul li {
    background: rgba(255, 255, 255, 0.05);
    padding: 8px;
    border-radius: 5px;
    margin-bottom: 5px;
    border-left: 3px solid #ff9900;
}


button {padding:12px 25px; border:none; border-radius:12px; background:#ff00ff; color:white; font-size:16px; cursor:pointer; box-shadow:0 0 10px #ff00ff; transition:0.3s; margin-top:15px;}
button:hover {background:#00ffff; color:#000; transform:scale(1.1); box-shadow:0 0 20px #00ffff;}
.avatar-result {position:absolute; top:20px; right:20px; width:80px; border-radius:15px; object-fit:cover; box-shadow:0 0 10px <?= $warna ?>;}
</style>
</head>

<body>

<div class="container">
<img src="<?= $avatar ?>" class="avatar-result" alt="Avatar">
<h1>Hasil Analisis Mood</h1>

<p>Nama: <strong><?= $nama ?></strong> (<?= $nim ?>)</p>

<div class="score-display"><?= $score ?>%</div>
<div class="category">Kategori: <strong><?= $kategori ?></strong></div>

<div class="feedback">
    <?= $selected_feedback ?>
</div>

<?php if (!empty($top_areas)): ?>
<div class="attention-area">
    <h3>🚨 Area Perhatian Anda Hari Ini</h3>
    <p style="text-align: center; font-size: 0.9em; margin-bottom: 10px;">Anda memberikan skor terendah pada isu berikut:</p>
    <ul>
        <?php foreach ($top_areas as $item): ?>
            <li>**<?= $item['q_text'] ?>** (Skor: <?= $item['score'] ?>)</li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<?php if($score < 75): ?>
<a href="saran.php"><button>Lihat Saran Pemulihan 🧠</button></a>
<?php endif; ?>

<a href="trend.php"><button>Lihat Grafik Tren Mood 📈</button></a>
<a href="index.php"><button style="margin-top:20px;">Mulai Tes Baru</button></a>

</div>

</body>
</html>