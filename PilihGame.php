<?php
// PilihGame.php

// MODUL 1: ARRAY Data Game
$games = [
    // Catatan: Ganti URL placeholder dengan path gambar lokal Anda (misal: 'ml.jpg')
    ['code' => 'ml', 'name' => 'Mobile Legends', 'img' => 'ml.jpg'],
    ['code' => 'ff', 'name' => 'Free Fire', 'img' => 'ff.jpg'], 
    ['code' => 'pubg', 'name' => 'PUBG Mobile', 'img' => 'pubg.jpg'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Game</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2 style="color: #4ecca3;">WELCOME TO NITA<span>GAME</span></h2>
        <h3 style="color: #aaa;">Pilih Game untuk Top Up</h3>
        
        <div class="grid-container" style="margin-top: 30px;">
            <?php foreach ($games as $game): ?>
                <a href="buy.php?game=<?php echo $game['code']; ?>" style="text-decoration: none; color: inherit;">
                    <div class="card-option">
                        <img src="<?php echo $game['img']; ?>" alt="<?php echo $game['name']; ?>">
                        <p><b><?php echo $game['name']; ?></b></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        
        <p style="text-align: center; margin-top: 30px;"><a href="index.php" style="color: #e94560;">Logout</a></p>
    </div>
</body>
</html>
