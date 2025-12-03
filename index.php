<?php
// index.php

// MODUL 2: PENGKONDISIAN
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi simulasi
    if (!empty($_POST['user']) && !empty($_POST['pass'])) {
        header("Location: PilihGame.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login NitaGame</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 400px;">
        <h1>Nita<span>Game</span></h1>
        <p style="text-align: center; color: #aaa;">Top Up Game Terpercaya</p>
        
        <form method="POST" action="index.php">
            <label>Username</label>
            <input type="text" name="user" placeholder="Masukkan Username" required>
            
            <label>Password</label>
            <input type="password" name="pass" placeholder="Masukkan Password" required>
            
            <button type="submit">Masuk Sekarang</button>

    <p style="text-align: center; margin-top: 15px; font-size: 0.9em;">Belum punya akun? <a href="register.php" style="color: #4ecca3;">Register</a></p>
</body>
</html>
