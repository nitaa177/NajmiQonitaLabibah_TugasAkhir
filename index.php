<?php
// index.php
// PASTIKAN BARIS INI ADALAH BARIS PERTAMA DI FILE, TANPA SPASI ATAU BARIS KOSONG DI ATASNYA

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Di sini seharusnya ada validasi data dan pengecekan database.
    
    // Karena ini simulasi, kita langsung anggap login sukses dan LAKUKAN REDIRECTION
    header("Location: PilihGame.php");
    exit(); // Penting untuk menghentikan eksekusi kode setelah header
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up Diamond - Login</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

    <h1>Selamat Datang di Layanan Top Up Diamond</h1>

    <div class="login-container">
        <h2>1. Masuk Akun Anda</h2>
        
        <form method="POST" action="index.php">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Anda" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Masukkan Email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Masukkan Password" required>

            <button type="submit">Login dan Lanjutkan</button>
        </form>
    </div>
    
</body>
</html>
