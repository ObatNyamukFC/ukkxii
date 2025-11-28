<?php

// Membuat koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "ukklv5");

// Cek koneksi, jika gagal tampilkan pesan error
if (!$koneksi) {
    die("Database connection failed: " . mysqli_connect_error());
}