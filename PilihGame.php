<?php
// Pastikan pengguna telah melalui proses login (simulasi)
// Dalam aplikasi nyata, di sini akan ada pengecekan session.
// Untuk simulasi sederhana, kita asumsikan jika file ini diakses, berarti sudah lolos.
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Game</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

    <div class="game-selection login-container">
        <h2>Pilih Game Favorit Anda</h2>
        <p style="text-align: center;">Klik ikon game untuk melanjutkan ke halaman pembelian.</p>
        
        <div class="game-icons">
            
            <a href="buy.php?game=ff">
                <img src="ff.jpg" alt="Free Fire" class="game-icon">
                <p>Free Fire</p>
            </a>
            
            <a href="buy.php?game=ml">
                <img src="ml.jpg" alt="Mobile Legends" class="game-icon">
                <p>Mobile Legends</p>
            </a>
            
            <a href="buy.php?game=pubg">
                <img src="pubg.jpg" alt="PUBG Mobile" class="game-icon">
                <p>PUBG Mobile</p>
            </a>

        </div>
        <p style="text-align: center; margin-top: 20px;"><a href="index.php">Logout / Kembali ke Login</a></p>
    </div>

</body>
</html>
