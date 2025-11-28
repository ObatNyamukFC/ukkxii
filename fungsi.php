<?php

include 'koneksi.php';

// Memulai sesi
session_start();

// Cek apakah user sudah login
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Paksa user untuk login jika belum login
function requireLogin()
{
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Cek apakah user adalah superadmin
// Fungsi pembantu untuk mendapatkan level user dengan aman
function getUserLevel()
{
    // Menggunakan key 'user_level' untuk menyimpan level ('admin'/'petugas')
    return $_SESSION['user_level'] ?? null;
}

// Cek apakah user adalah admin (level tertinggi di tbl_user)
function isSuperAdmin()
{
    // PERBAIKAN: Menggunakan fungsi getUserLevel() yang aman dan 
    // membandingkan dengan nilai 'admin' dari tabel Anda.
    return isLoggedIn() && getUserLevel() === 'admin';
}

// Fungsi logout
function logout()
{
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}