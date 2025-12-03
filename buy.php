<?php
require_once 'TopupProcessor.class.php';

// --- DATA CONFIGURATION (MODUL 1: ARRAY & VARIABEL) ---
$game_config = [
    'ml' => ['name' => 'Mobile Legends', 'currency' => 'Diamond', 'img' => 'ml_logo.png', 'class' => 'VIPProcessor'],
    'ff' => ['name' => 'Free Fire', 'currency' => 'Diamond', 'img' => 'ff_logo.png', 'class' => 'TopupProcessor'],
];
$game_code = $_GET['game'] ?? 'ml';
$current_game = $game_config[$game_code] ?? $game_config['ml'];

$packages = [
    ['id' => 1, 'qty' => 86, 'price' => 20000],
    ['id' => 2, 'qty' => 172, 'price' => 40000],
    ['id' => 3, 'qty' => 706, 'price' => 150000],
];

// Pilihan Pembayaran (sesuai request)
$payments = [
    ['code' => 'DANA', 'name' => 'DANA', 'fee' => 0],
    ['code' => 'MANDIRI', 'name' => 'Transfer MANDIRI', 'fee' => 2000],
    ['code' => 'BRI', 'name' => 'Transfer BRI', 'fee' => 2000],
    ['code' => 'ALFAMART', 'name' => 'Alfamart', 'fee' => 3500],
];

// --- INITIALIZATION ---
$base_price_sim = $packages[0]['price'] / $packages[0]['qty'];
$objGame = new $current_game['class']($current_game['name'], $base_price_sim); // Modul 5/6

$status = 'form';
$uid = '';
$selected_pack = null;
$selected_pay = null;
$total_bayar = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uid = $_POST['uid'] ?? '';
    $pack_id = $_POST['package'] ?? 0;
    $pay_method = $_POST['payment'] ?? '';
    
    // MODUL 3: Loop mencari paket
    foreach($packages as $p) { if($p['id'] == $pack_id) $selected_pack = $p; }
    // MODUL 3: Loop mencari pembayaran
    foreach($payments as $pay) { if($pay['code'] == $pay_method) $selected_pay = $pay; }

    // MODUL 4: Loop Bersarang (Nested Loop) - Simulasi Cek Blacklist
    $blacklist = ['123', '999'];
    $is_banned = false;
    foreach($blacklist as $b) {
        if ($uid == $b) {
            $is_banned = true;
            $objGame->addLog("ID $uid Blacklisted."); // Modul 7: Stack
            break; // Modul 4: Loop Control
        }
    }

    // MODUL 2: PENGKONDISIAN
    if($is_banned) {
        $status = 'error';
    } elseif ($selected_pack && $selected_pay) {
        // MODUL 5 & 6: Menghitung harga menggunakan OOP (Polymorphism)
        $total_item_price = $objGame->hitungTotal($selected_pack['qty']);
        $total_bayar = $total_item_price + $selected_pay['fee'];
        
        // MODUL 7: Queue Process
        $objGame->prosesAntrian(['Verifikasi UID', 'Cek Saldo', 'Kirim Transaksi']);
        $status = 'success';
    } else {
        $status = 'error';
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
        
        <?php if($status == 'form'): ?>
            <h1>Top Up <span><?php echo $current_game['name']; ?></span></h1>
            
            <form method="POST">
                <h3 style="color:#4ecca3;">Masukkan User ID</h3>
                <input type="text" name="uid" placeholder="Contoh: 12345678 (Server ID)" required>

                <h3 style="color:#4ecca3;">Pilih Nominal <?php echo $current_game['currency']; ?></h3>
                <div class="grid-container">
                    <?php foreach($packages as $p): ?>
                    <label class="card-option">
                        <input type="radio" name="package" value="<?php echo $p['id']; ?>" required>
                        <div><?php echo $p['qty']; ?> <?php echo $current_game['currency']; ?></div>
                        <span class="price-tag">Rp <?php echo number_format($p['price']); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>

                <h3 style="color:#e94560;">Pilih Pembayaran</h3>
                <div class="grid-container">
                    <?php foreach($payments as $pay): ?>
                    <label class="card-option">
                        <input type="radio" name="payment" value="<?php echo $pay['code']; ?>" required>
                        <div><?php echo $pay['name']; ?></div>
                        <span style="font-size: 0.8em; color: #e7c9c9ff;">Fee: Rp <?php echo number_format($pay['fee']); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>

                <button type="submit" style="margin-top: 20px;">BAYAR SEKARANG</button>
            </form>

        <?php elseif($status == 'success'): ?>
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
                  
                </ul>
            </div>
            <br>
            <a href="PilihGame.php" class="btn">Beli Lagi</a>

        <?php elseif($status == 'error'): ?>
            <div class="result-box error">
                <h2>🚫 Transaksi Ditolak</h2>
                <p>ID Akun Anda terdeteksi dalam daftar Blacklist atau data tidak lengkap.</p>
                <h4 style="color: #e94560;">Log Sistem:</h4>
                <ul class="log-list">
                    <?php foreach(array_reverse($objGame->getLogs()) as $log): ?>
                        <li>&bull; <?php echo $log; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <a href="buy.php?game=<?php echo $game_code; ?>" class="btn">Coba Lagi</a>

        <?php endif; ?>

    </div>
</body>
</html>
