<?php
session_start();

// --- PENGATURAN SESSION TIMEOUT (15 MENIT) ---
$inactive = 900; 
if (isset($_SESSION['timeout'])) {
    $session_life = time() - $_SESSION['timeout'];
    if ($session_life > $inactive) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }
}
$_SESSION['timeout'] = time();


// --- Definisi Kelas & Struktur Data ---

class Queue {
    private $data = array(); 
    public function enqueue($v){ 
        $this->data[] = $v; 
    }
    public function get($i){ 
        return $this->data[$i]; 
    }
    public function count(){ 
        return count($this->data); 
    }
}

$queue = new Queue();

// Daftar Pertanyaan (Sama seperti sebelumnya)
$pertanyaan_data = array(
    array(
        "q" => "Bagaimana perasaanmu saat bangun tidur?",
        "opts" => array("Sangat Bersemangat", "Cukup Segar", "Agak Lelah", "Sangat Berat"), 
    ),
    array(
        "q" => "Bagaimana tingkat semangatmu hari ini?",
        "opts" => array("Penuh Energi", "Cukup Bersemangat", "Biasa Saja", "Tidak Ada Semangat"), 
    ),
    array(
        "q" => "Apakah kamu merasa mampu menyelesaikan tugas hari ini?",
        "opts" => array("Sangat Yakin", "Cukup Yakin", "Ragu-ragu", "Tidak Mampu Sama Sekali"), 
    ),
    array(
        "q" => "Seberapa baik kamu fokus selama 1 jam terakhir?",
        "opts" => array("Fokus Penuh", "Cukup Fokus", "Sering Terganggu", "Sangat Sulit Fokus"), 
    ),
    array(
        "q" => "Seberapa sering kamu merasa pikiranmu kacau hari ini?",
        "opts" => array("Tidak Pernah", "Jarang", "Beberapa Kali", "Sepanjang Hari"), 
    ),
    array(
        "q" => "Bagaimana suasana hatimu secara umum hari ini?",
        "opts" => array("Sangat Bahagia", "Netral/Tenang", "Agak Sedih/Kesal", "Sangat Buruk"), 
    ),
    array(
        "q" => "Seberapa berat hari yang kamu jalani?",
        "opts" => array("Sangat Ringan", "Cukup Menantang", "Berat", "Sangat Berat"), 
    ),
    array(
        "q" => "Seberapa puas kamu dengan produktivitasmu hari ini?",
        "opts" => array("Sangat Puas", "Cukup Puas", "Kurang Puas", "Sama Sekali Tidak Puas"), 
    ),
    array(
        "q" => "Bagaimana tingkat energimu saat ini (skala 1-4)?",
        "opts" => array("Level 4 (Penuh)", "Level 3 (Baik)", "Level 2 (Lelah)", "Level 1 (Habis)"), 
    ),
    array(
        "q" => "Bagaimana kondisi emosimu saat ini?",
        "opts" => array("Sangat Terkendali", "Terkendali", "Agak Sensitif", "Mudah Meledak"), 
    ),
    array(
        "q" => "Seberapa baik interaksimu dengan orang lain hari ini?",
        "opts" => array("Sangat Positif", "Baik", "Ada Konflik Kecil", "Menghindari Kontak"), 
    ),
    array(
        "q" => "Apakah kamu merasa stres hari ini?",
        "opts" => array("Tidak Sama Sekali", "Stres Ringan", "Stres Sedang", "Stres Berat"), 
    ),
    array(
        "q" => "Apakah kamu merasa cemas hari ini?",
        "opts" => array("Tidak Sama Sekali", "Cemas Ringan", "Cemas Sedang", "Cemas Berat"), 
    ),
    array(
        "q" => "Seberapa nyaman kamu dengan diri sendiri hari ini?",
        "opts" => array("Sangat Nyaman", "Cukup Nyaman", "Agak Tidak Nyaman", "Sangat Tidak Nyaman"), 
    ),
    array(
        "q" => "Bagaimana kondisi mentalmu saat ini?",
        "opts" => array("Sangat Jernih", "Cukup Jernih", "Agak Kabur", "Sangat Tegang"), 
    ),
    array(
        "q" => "Apakah kamu merasa semuanya berjalan sesuai rencana?",
        "opts" => array("Sangat Sesuai", "Cukup Sesuai", "Banyak Melenceng", "Sama Sekali Tidak"), 
    ),
    array(
        "q" => "Seberapa sering kamu merasa tenang hari ini?",
        "opts" => array("Selalu Tenang", "Sering Tenang", "Jarang Tenang", "Tidak Pernah Tenang"), 
    ),
    array(
        "q" => "Seberapa rileks tubuhmu sekarang?",
        "opts" => array("Sangat Rileks", "Cukup Rileks", "Agak Tegang", "Sangat Tegang/Kaku"), 
    ),
    array(
        "q" => "Bagaimana motivasimu saat ini?",
        "opts" => array("Sangat Tinggi", "Cukup Tinggi", "Rendah", "Tidak Ada"), 
    ),
    array(
        "q" => "Apakah kamu merasa hari ini lebih baik dari kemarin?",
        "opts" => array("Jauh Lebih Baik", "Sedikit Lebih Baik", "Sama Saja", "Jauh Lebih Buruk"), 
    )
);

foreach($pertanyaan_data as $p) {
    $queue->enqueue($p); 
}

// --- Logika Penanganan Sesi & Index ---

if (!isset($_SESSION['nama']) || !isset($_SESSION['nim'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['answers'])){
    $_SESSION['answers'] = array();
    $_SESSION['index'] = 0;
}

// Ambil avatar dari POST (hanya sekali)
if (!isset($_SESSION['avatar']) && isset($_POST['avatar'])) {
    $_SESSION['avatar'] = $_POST['avatar'];
} elseif (!isset($_SESSION['avatar'])) {
    header("Location: avatar.php");
    exit;
}

// Logika Tombol "Kembali"
if(isset($_POST['kembali']) && $_SESSION['index'] > 0){
    $_SESSION['index']--;
    unset($_SESSION['answers'][$_SESSION['index']]); 
    header("Location: pertanyaan.php"); 
    exit;
}

// Logika Pemrosesan Jawaban
if(isset($_POST['jawaban'])){
    $_SESSION['answers'][$_SESSION['index']] = $_POST['jawaban'];
    $_SESSION['index']++;
}

// Pengecekan Akhir Kuesioner
if($_SESSION['index'] >= $queue->count()){
    header("Location: proses.php");
    exit;
}

// --- Persiapan Tampilan Pertanyaan ---

$current_question = $queue->get($_SESSION['index']);
$q_text = $current_question['q'];
$opts_text = $current_question['opts'];


// =========================================================
// ✅ LOGIKA BARU UNTUK MOOD BAR DAN KEMAJUAN TES
// =========================================================
$answers_count = count($_SESSION['answers']);
$total_answered_score = 0;

if ($answers_count > 0) {
    foreach ($_SESSION['answers'] as $score) {
        $total_answered_score += $score;
    }
}

$max_possible_answered = $answers_count * 4; 
$overall_questions_count = $queue->count();

// Hitung persentase mood sementara
$current_mood_percent = 0;
if ($answers_count > 0) {
    $current_mood_percent = round(($total_answered_score / $max_possible_answered) * 100);
}

// Tentukan warna bar mood
$mood_bar_color = '#00ffff'; // Biru terang
if ($current_mood_percent >= 75) {
    $mood_bar_color = '#00ff00'; // Hijau
} elseif ($current_mood_percent >= 50) {
    $mood_bar_color = '#ffff00'; // Kuning
} elseif ($current_mood_percent > 0) {
    $mood_bar_color = '#ff0000'; // Merah
}

// Hitung persentase kemajuan kuesioner
$progress_percent = round(($_SESSION['index'] / $overall_questions_count) * 100);

?>

<!DOCTYPE html>
<html>
<head>
<title>Pertanyaan</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">

<style>
body {margin:0; font-family:'Orbitron',sans-serif; background:linear-gradient(-45deg,#ff0099,#493240,#00ffff,#ff9900); background-size:400% 400%; animation:gradientBG 15s ease infinite; color:white; display:flex; justify-content:center; align-items:center; height:100vh;}
@keyframes gradientBG{0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}

.container { 
    background: rgba(0,0,0,0.7); 
    padding:30px; 
    border-radius:20px; 
    box-shadow:0 0 30px #00ffff; 
    width:500px; 
    text-align:center; 
    transition:.4s; 
    position: relative; /* Penting untuk progress bar */
}
.slide-out {transform:translateX(100%); opacity:0;}

.card { background:#222; padding:15px; border-radius:12px; cursor:pointer; transition:0.2s; margin-bottom: 10px;}
.card:hover { background:#5500ff; transform:scale(1.06); text-shadow:0 0 10px #00ffff,0 0 20px #ff00ff;}

.avatar-display {position:absolute; top:20px; right:20px; width:80px; border-radius:15px; object-fit:cover; box-shadow:0 0 10px #00ffff;}

/* Gaya Tombol Kembali */
#back-form button {
    padding:8px 15px;
    border:none;
    border-radius:10px;
    background:#444;
    color:white;
    font-size:14px;
    cursor:pointer;
    transition:0.3s;
    margin-top:15px;
}
#back-form button:hover {
    background:#ff00ff;
}

/* GAYA PROGRESS BAR */
.test-progress-bar {
    position: absolute;
    top: 0;
    left: 0;
    height: 5px;
    background: #ff00ff; /* Kemajuan Tes */
    width: 0;
    transition: width 0.3s ease;
    border-top-left-radius: 20px;
}
.mood-progress-container {
    height: 10px;
    width: 100%;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 5px;
    margin-bottom: 20px;
    overflow: hidden;
}
.mood-progress-bar {
    height: 100%;
    transition: width 0.5s ease;
    background-color: transparent; 
}

</style>

<script>
function pilih(v){
    let cont = document.querySelector('.container');
    cont.classList.add("slide-out");

    setTimeout(()=>{
        document.getElementById("jawaban").value=v;
        document.getElementById("f").submit();
    },400);
}
</script>

</head>
<body>

<div class="container">
<div class="test-progress-bar" style="width: <?= $progress_percent ?>%;"></div>

<img src="<?= $_SESSION['avatar'] ?>" class="avatar-display" alt="Avatar">

<h2>Pertanyaan <?= $_SESSION['index']+1 ?>/<?= $queue->count() ?></h2>

<?php if ($answers_count > 0): ?>
    <small style="color: <?= $mood_bar_color ?>;">Mood Sementara: **<?= $current_mood_percent ?>%**</small>
    <div class="mood-progress-container">
        <div 
            class="mood-progress-bar" 
            style="width: <?= $current_mood_percent ?>%; background-color: <?= $mood_bar_color ?>;">
        </div>
    </div>
<?php endif; ?>

<p><?= $q_text ?></p>

<form method="POST" id="f">
<input type="hidden" name="jawaban" id="jawaban">

<div class="cards">
<?php foreach($opts_text as $i=>$o): ?>
    <div class="card" onclick="pilih(<?= 4-$i ?>)"><?= $o ?></div>
<?php endforeach; ?>
</div>
</form>

<?php if ($_SESSION['index'] > 0): ?>
<form method="POST" id="back-form">
    <input type="hidden" name="kembali" value="1">
    <button type="submit">← Kembali</button>
</form>
<?php endif; ?>
</div>

</body>
</html>