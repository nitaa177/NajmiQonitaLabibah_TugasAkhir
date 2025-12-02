<?php
// buy.php

// 1. Ambil parameter 'game' dari URL untuk menentukan data game yang dipilih
$selected_game = isset($_GET['game']) ? $_GET['game'] : 'ml'; 

// Variabel status: 'select_package' (default) atau 'confirm_payment'
$page_status = 'select_package'; 

// Data Pembelian yang akan diisi jika formulir pertama disubmit
$game_id = '';
$selected_qty = 0;
$selected_price_str = '';

// --- FUNGSI DATA GAME ---
$title = "";
$placeholder_id = "";
$currency = "";
$image_path = "";
$diamond_options = [];

if ($selected_game == 'ff') {
    $title = "Top Up Diamond Free Fire";
    $placeholder_id = "ID Game (mis. 12345678)";
    $currency = "Diamonds 💎";
    $image_path = "gambar/ff_logo.png"; 
    $diamond_options = [
        ["qty" => 70,  "price" => 10000],
        ["qty" => 140, "price" => 20000],
        ["qty" => 355, "price" => 50000]
    ];
} elseif ($selected_game == 'ml') {
    $title = "Top Up Diamond Mobile Legends";
    $placeholder_id = "ID Game (mis. 98765432) dan Server ID (mis. 2005)";
    $currency = "Diamonds 💎";
    $image_path = "gambar/ml_logo.png";
    $diamond_options = [
        ["qty" => 86, "price" => 25000],
        ["qty" => 172, "price" => 50000],
        ["qty" => 344, "price" => 100000]
    ];
} elseif ($selected_game == 'pubg') {
    $title = "Top Up UC PUBG Mobile";
    $placeholder_id = "ID Karakter (mis. 5123456789)";
    $currency = "UC 💎";
    $image_path = "gambar/pubg_logo.png";
    $diamond_options = [
        ["qty" => 60, "price" => 15000],
        ["qty" => 300, "price" => 75000],
        ["qty" => 600, "price" => 150000]
    ];
}

// --- LOGIKA PENANGANAN FORMULIR (SIMULASI TRANSAKSI) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cek apakah data yang dibutuhkan ada (ID Game dan Paket)
    if (isset($_POST['game_id']) && isset($_POST['package'])) {
        
        $game_id = $_POST['game_id'];
        $selected_qty = $_POST['package'];

        // Cari harga berdasarkan quantity yang dipilih
        foreach ($diamond_options as $option) {
            if ($option['qty'] == $selected_qty) {
                // Konversi harga (integer) ke format Rupiah
                $selected_price_str = "Rp " . number_format($option['price'], 0, ',', '.');
                break;
            }
        }

        // Ubah status ke konfirmasi pembayaran
        $page_status = 'confirm_payment';

    } elseif (isset($_POST['final_pay'])) {
        // Jika formulir pembayaran akhir disubmit
        $page_status = 'payment_success';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Tambahkan CSS spesifik untuk simulasi pembayaran agar terlihat lebih rapi */
        .summary-box {
            background-color: #ecf0f1; 
            padding: 15px; 
            border-radius: 8px; 
            margin-bottom: 20px;
        }
        .summary-box p {
            font-size: 1.1em;
            margin: 5px 0;
        }
        .summary-box span {
            font-weight: bold;
            float: right;
            color: #e74c3c;
        }
        .success-message {
            background-color: #e6ffe6;
            border: 2px solid #27ae60;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="game-header" style="text-align: center;">
            <h1><?php echo $title; ?></h1>
        </div>
        
        <hr>

        <?php if ($page_status == 'select_package'): ?>
        
        <form method="POST" action="buy.php?game=<?php echo $selected_game; ?>">
            
            <h3>1. Masukkan Detail Akun</h3>
            <label for="game_id">ID Akun Game</label>
            <input type="text" id="game_id" name="game_id" placeholder="<?php echo $placeholder_id; ?>" required>

            <h3>2. Pilih Jumlah <?php echo $currency; ?></h3>
            <div class="diamond-options">
            <?php foreach ($diamond_options as $option): ?>
                <label class="package-card">
                    <input type="radio" name="package" value="<?php echo $option['qty']; ?>" required>
                    <span class="qty"><?php echo $option['qty'] . " " . $currency; ?></span>
                    <span class="price">Rp <?php echo number_format($option['price'], 0, ',', '.'); ?></span>
                </label>
            <?php endforeach; ?>
            </div>

            <button type="submit" style="margin-top: 20px;">Lanjutkan ke Pembayaran</button>
        </form>

        <?php elseif ($page_status == 'confirm_payment'): ?>

        <h2>3. Konfirmasi Transaksi</h2>
        
        <div class="summary-box">
            <p>Game ID: <span><?php echo htmlspecialchars($game_id); ?></span></p>
            <p>Paket: <span><?php echo $selected_qty . " " . $currency; ?></span></p>
            <hr>
            <p style="font-size: 1.3em;">Total Pembayaran: <span style="font-size: 1.3em;"><?php echo $selected_price_str; ?></span></p>
        </div>

        <form method="POST" action="buy.php?game=<?php echo $selected_game; ?>">
            <input type="hidden" name="final_pay" value="true"> 

            <h3>4. Pilih Metode Pembayaran (Simulasi)</h3>
            <div class="diamond-options">
                <label class="package-card">
                    <input type="radio" name="payment_method" value="gopay" required>
                    <span class="qty">Transfer Bank (BCA)</span>
                </label>
                <label class="package-card">
                    <input type="radio" name="payment_method" value="dana" required>
                    <span class="qty">E-Wallet (Dana / OVO)</span>
                </label>
            </div>
            
            <button type="submit" style="margin-top: 20px; background-color: #2ecc71;">Bayar Sekarang</button>
        </form>

        <?php elseif ($page_status == 'payment_success'): ?>

        <div class="success-message">
            <h2 style="color: #27ae60;">Transaksi Berhasil! 🎉</h2>
            <p>Terima kasih. Pesanan Anda sedang diproses.</p>
            <p>Diamond/UC akan segera masuk ke akun Anda.</p>
            <p style="margin-top: 25px;"><a href="PilihGame.php">Lakukan Top Up Lagi</a></p>
        </div>

        <?php endif; ?>

    </div>
</body>
</html>
