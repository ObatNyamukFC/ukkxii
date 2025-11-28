<?php

$password = "password123";
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// TAMBAHKAN INI UNTUK MENAMPILKAN HASILNYA
echo $hashedPassword;

?>