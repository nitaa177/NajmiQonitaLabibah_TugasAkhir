<?php
// register.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $error_message = '';

    // MODUL 2: PENGKONDISIAN (Validasi Password)
    if ($password !== $confirm_password) {
        $error_message = "Password dan Konfirmasi Password tidak sama.";
    } elseif (strlen($password) < 6) {
        $error_message = "Password minimal 6 karakter.";
    }

    // MODUL 3: PERULANGAN (Simulasi Kekuatan Password)
    // Cek apakah password mengandung minimal satu angka
    $has_number = false;
    for ($i = 0; $i < strlen($password); $i++) {
        // Modul 2: Pengecekan karakter
        if (is_numeric($password[$i])) {
            $has_number = true;
            break; // Modul 3: Hentikan perulangan jika ditemukan
        }
    }
    if (!$has_number && $error_message == '') {
        $error_message = "Password harus mengandung minimal satu angka.";
    }

    // Jika tidak ada error, Lakukan Simulasi Pendaftaran
    if ($error_message == '') {
        // Di sini seharusnya ada kode untuk menyimpan data ke database.
        
        // Simulasi sukses: Redirect kembali ke halaman login
        header("Location: index.php?registered=true");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register NovaGame</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 400px;">
        <h1>Buat Akun <span>NovaGame</span></h1>
        
        <?php if (!empty($error_message)): ?>
            <div class="result-box error" style="margin-bottom: 20px;">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan Username" required>

            <label>Email</label>
            <input type="email" name="email" placeholder="Masukkan Email" required>
            
            <label>Password</label>
            <input type="password" name="password" placeholder="Minimal 6 karakter & 1 angka" required>
            
            <label>Konfirmasi Password</label>
            <input type="password" name="confirm_password" placeholder="Ulangi Password Anda" required>

            <button type="submit">Register Akun</button>
            <p style="text-align: center; margin-top: 15px; font-size: 0.9em;">
                Sudah punya akun? <a href="index.php" style="color: #4ecca3;">Login</a>
            </p>
        </form>
    </div>
</body>
</html>
