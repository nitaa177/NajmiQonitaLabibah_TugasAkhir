<?php
// Topup.php

// MODUL 5: OOP 1 (Class, Attribute, Method, Encapsulation)
class TopupProcessor {
    protected $namaGame;
    protected $hargaDasar;
    private $statusStack = []; // MODUL 7: Stack

    public function __construct($nama, $harga) {
        $this->namaGame = $nama;
        $this->hargaDasar = $harga;
        $this->addLog("Object Processor '{$nama}' Created.");
    }

    public function getNamaGame() { return $this->namaGame; }

    // MODUL 3: Perulangan sederhana
    public function hitungTotal($qty) {
        $total = 0;
        for($i=0; $i<$qty; $i++) { $total += $this->hargaDasar; }
        return $total;
    }

    // MODUL 7: Stack Operations
    public function addLog($pesan) { array_push($this->statusStack, $pesan); }
    public function getLogs() { return $this->statusStack; }

    // MODUL 7: Queue Logic
    public function prosesAntrian($antrian) {
        while(!empty($antrian)) {
            $task = array_shift($antrian); // FIFO (Dequeue)
            $this->addLog("System: $task... OK");
        }
    }
}

// MODUL 6: OOP 2 (Inheritance & Polymorphism)
class VIPProcessor extends TopupProcessor {
    private $diskonRate = 0.15; // 15% VIP Discount

    // Polymorphism: Method Overriding
    public function hitungTotal($qty) {
        $total = parent::hitungTotal($qty);
        $diskon = $total * $this->diskonRate;
        $total_final = $total - $diskon;
        $this->addLog("VIP Discount Applied: Rp " . number_format($diskon));
        return $total_final; 
    }
}
?>
