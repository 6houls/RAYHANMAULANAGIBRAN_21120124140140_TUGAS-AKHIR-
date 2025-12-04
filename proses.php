<?php
session_start();

class Stack {
    private $s = array(); 
    public function push($v){ 
        $this->s[] = $v; 
    }
    public function top(){ 
        return end($this->s); 
    }
}


class Analisis {
    private $ans;
    function __construct($a){ 
        $this->ans = $a; 
    }
    function total(){
        $t=0;
        foreach($this->ans as $v) $t += $v;
        return $t;
    }
}

$stack = new Stack();

if (!isset($_SESSION['answers']) || empty($_SESSION['answers'])) {
    header("Location: index.php");
    exit;
}

$score = (new Analisis($_SESSION['answers']))->total();

// BARIS YANG SUDAH DIPERBAIKI (HAPUS KARAKTER /)
$max = count($_SESSION['answers']) * 4; 
$percent = ($score/$max)*100;

$stack->push($percent);

$_SESSION['scorePercent'] = $stack->top();
$_SESSION['saran_mode'] = ($percent >= 75 ? "stabil":"turun"); 

header("Location: hasil.php");
exit;