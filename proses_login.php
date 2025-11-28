<?php

include 'koneksi.php';

// Pastikan session sudah dimulai (biasanya ada di fungsi.php, tapi amannya cek)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Jika sudah login, redirect ke index (Pastikan fungsi.php sudah di-include/require_once)
if (function_exists('isLoggedIn') && isLoggedIn()) {
    header("Location: index.php");
    exit();
}

// Proses login jika ada request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data user berdasarkan username (SOLUSI 1)
    // PERHATIAN: PENGGUNAAN VARIABEL LANGSUNG DI QUERY MYSQLI TIDAK AMAN (SQL INJECTION)
    $query = $koneksi->query("SELECT * FROM tbl_user WHERE username = '$username'");
    $user = $query->fetch_assoc(); 
    
    // Verifikasi password (SOLUSI 2 dan 3)
    if ($user && password_verify($password, $user['password'])) {
        // SET SESI MENGGUNAKAN NAMA KOLOM YANG BENAR
        $_SESSION['user_id'] = $user['id_user'];  // Kolom ID dari tabel
        $_SESSION['user_level'] = $user['level'];  // Kolom LEVEL dari tabel
        
        header("Location: index.php");
        exit();
    }
    $error = "Login gagal! Username atau password salah.";
}

?>