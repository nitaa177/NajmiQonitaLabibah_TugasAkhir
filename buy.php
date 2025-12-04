<?php
require_once 'TopupProcessor.class.php'; // MODUL 5/6: Memuat class OOP

// DATA CONFIGURATION (MODUL 1: ARRAY & VARIABEL)
$game_config = [
    
    'ml' => ['name' => 'Mobile Legends', 'currency' => 'Diamond', 'img' => 'ml_logo.png', 'class' => 'VIPProcessor'],
    'ff' => ['name' => 'Free Fire', 'currency' => 'Diamond', 'img' => 'ff_logo.png', 'class' => 'TopupProcessor'],
    'pubg' => ['name' => 'PUBG Mobile', 'currency' => 'UC', 'img' => 'pubg_logo.png', 'class' => 'TopupProcessor'], 
    
    
];
$game_code = $_GET['game'] ?? 'ml';
$current_game = $game_config[$game_code] ?? $game_config['ml'];

// MODUL 1: ARRAY Harga yang berbeda untuk setiap game
$all_packages = [
    // Harga untuk Mobile Legends (ML)
    'ml' => [
        ['id' => 1, 'qty' => 86, 'price' => 20000],
        ['id' => 2, 'qty' => 172, 'price' => 40000],
        ['id' => 3, 'qty' => 706, 'price' => 150000],
    ],
    // Harga untuk Free Fire (FF) - Dibuat sedikit lebih murah
    'ff' => [
        ['id' => 1, 'qty' => 100, 'price' => 15000],
        ['id' => 2, 'qty' => 210, 'price' => 30000],
        ['id' => 3, 'qty' => 860, 'price' => 120000],
    ],
    // Harga untuk PUBG Mobile (PUBG) - Dibuat menggunakan mata uang UC dan harga berbeda
    'pubg' => [
        ['id' => 1, 'qty' => 60, 'price' => 16000],
        ['id' => 2, 'qty' => 300, 'price' => 75000],
        ['id' => 3, 'qty' => 600, 'price' => 150000],
    ],
];

// MODUL 1: Variabel -> Ambil paket yang relevan dengan game yang sedang dibuka
$packages = $all_packages[$game_code] ?? $all_packages['ml']; 

// Pilihan Pembayaran 
$payments = [
    ['code' => 'DANA', 'name' => 'DANA', 'fee' => 0],
    ['code' => 'MANDIRI', 'name' => 'Transfer MANDIRI', 'fee' => 2000],
    ['code' => 'BRI', 'name' => 'Transfer BRI', 'fee' => 3000],
];

// --- INITIALIZATION ---
$status = 'form';
$uid = '';
$selected_pack = null;
$selected_pay = null;
$total_bayar = 0;
$objGame = null; // Inisialisasi awal objek

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uid = $_POST['uid'] ?? '';
    $pack_id = $_POST['package'] ?? 0;
    $pay_method = $_POST['payment'] ?? '';
    
    // MODUL 4: Loop mencari paket dan pembayaran
    foreach($packages as $p) { if($p['id'] == $pack_id) $selected_pack = $p; }
    foreach($payments as $pay) { if($pay['code'] == $pay_method) $selected_pay = $pay; }

    // MODUL 4: Loop Bersarang (Nested Loop) - Simulasi Cek Blacklist
    $blacklist = ['123', '999'];
    $is_banned = false;
    foreach($blacklist as $b) {
        if ($uid == $b) {
            $is_banned = true;
          
            break; 
        }
    }
    
    // MODUL 2: PENGKONDISIAN UTAMA
    if ($is_banned) {
        $status = 'error';
        // Buat objek sementara untuk log error
        $objGame = new TopupProcessor("N/A", 0);
        $objGame->addLog("ID $uid Blacklisted.");
    } elseif ($selected_pack && $selected_pay) {
        
        // MODUL 5/6: Inisiasi Objek dengan HARGA PAKET PENUH
        // Kita gunakan harga paket penuh ($selected_pack['price']) sebagai harga dasar.
        $objGame = new $current_game['class']($current_game['name'], $selected_pack['price']);
        
        // MODUL 5 & 6: Menghitung harga menggunakan OOP (Polymorphism)
        // Kita panggil hitungTotal(1) karena harga dasar yang di-pass sudah harga paket total.
        $total_item_price = $objGame->hitungTotal(1); 
        $total_bayar = $total_item_price + $selected_pay['fee'];
        
        // MODUL 7: Queue Process
        $objGame->prosesAntrian([ 'Kirim Transaksi']);
        $status = 'success';
    } else {
        $status = 'error';
        $objGame = new TopupProcessor("N/A", 0);
        $objGame->addLog("Data paket/pembayaran tidak valid.");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Top Up <?php echo $current_game['name']; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        
        <?php 
        // MODUL 8: GUI & MODUL 2: PENGKONDISIAN -> Menampilkan Form
        if($status == 'form'): ?>
            <h1>Top Up <span><?php echo $current_game['name']; ?></span></h1>
            
            <form method="POST">
                <h3 style="color:#4ecca3;">Masukkan User ID</h3>
                <input type="text" name="uid" placeholder="Contoh: 12345678 (Server ID)" required>

                <h3 style="color:#4ecca3;">Pilih Nominal <?php echo $current_game['currency']; ?></h3>
                <div class="grid-container">
                    <?php 
                    // MODUL 3: PERULANGAN -> Mencetak Pilihan Paket
                    // Perulangan ini sekarang hanya menggunakan array $packages yang sudah difilter di atas.
                    foreach($packages as $p): ?>
                    <label class="card-option">
                        <input type="radio" name="package" value="<?php echo $p['id']; ?>" required>
                        <div><?php echo $p['qty']; ?> <?php echo $current_game['currency']; ?></div>
                        <span class="price-tag">Rp <?php echo number_format($p['price']); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>

                <h3 style="color:#e94560;">Pilih Pembayaran</h3>
                <div class="grid-container">
                    <?php 
                    // MODUL 3: PERULANGAN -> Mencetak Pilihan Pembayaran
                    foreach($payments as $pay): ?>
                    <label class="card-option">
                        <input type="radio" name="payment" value="<?php echo $pay['code']; ?>" required>
                        <div><?php echo $pay['name']; ?></div>
                        <span style="font-size: 0.8em; color: #e7c9c9ff;">Fee: Rp <?php echo number_format($pay['fee']); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>

                <button type="submit" style="margin-top: 20px;">BAYAR SEKARANG</button>
            </form>

        <?php 
        // MODUL 2: PENGKONDISIAN & MODUL 8: GUI -> Menampilkan Struk Sukses
        elseif($status == 'success'): ?>
            <div class="result-box success">
                <h2 style="color: #4ecca3;">Transaksi Berhasil! 🎉</h2>
            </div>
            
            <div class="invoice-box">
                <h3 style="color:#333; border-bottom: 2px solid #333;">STRUK PEMBELIAN</h3>
                <table class="invoice-table">
                    <tr><td>Game</td><td><?php echo $current_game['name']; ?></td></tr>
                    <tr><td>User ID</td><td><?php echo htmlspecialchars($uid); ?></td></tr>
                    <tr><td>Item</td><td><?php echo $selected_pack['qty']; ?> <?php echo $current_game['currency']; ?></td></tr>
                    <tr><td>Pembayaran</td><td><?php echo $selected_pay['name']; ?></td></tr>
                    <tr class="total-row"><td>TOTAL BAYAR</td><td style="color: #c0392b;">Rp <?php echo number_format($total_bayar); ?></td></tr>
                </table>
                
                <h4 style="margin-top: 20px; color: #333;"></h4>
                <ul class="log-list">
                    <?php 
                    // MODUL 7: PERULANGAN -> Mencetak Log (dibalik agar yang terbaru di atas)
                    foreach(array_reverse($objGame->getLogs()) as $log): ?>
                        <li>&bull; <?php echo $log; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <br>
            <a href="PilihGame.php" class="btn">Beli Lagi</a>

        <?php 
        // MODUL 2: PENGKONDISIAN & MODUL 8: GUI -> Menampilkan Error
        elseif($status == 'error'): ?>
            <div class="result-box error">
                <h2>🚫 Transaksi Ditolak</h2>
                <p>ID Akun Anda terdeteksi dalam daftar Blacklist atau data tidak lengkap.</p>
                <h4 style="color: #e94560;">Log Sistem:</h4>
                <ul class="log-list">
                    <?php 
                    // MODUL 7: PERULANGAN -> Mencetak Log Error
                    foreach(array_reverse($objGame->getLogs()) as $log): ?>
                        <li>&bull; <?php echo $log; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <a href="buy.php?game=<?php echo $game_code; ?>" class="btn">Coba Lagi</a>

        <?php endif; ?>

    </div>
</body>
</html>
