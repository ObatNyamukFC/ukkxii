<?php
// Pastikan file koneksi dan fungsi di-include di proses_login.php
require_once 'fungsi.php';
include 'proses_login.php';
// Variabel $error akan didefinisikan di proses_login.php
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Inventaris</title>
    <style>
        /* Gaya CSS untuk tampilan login yang terpusat dan modern */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9ebee; /* Latar belakang abu-abu muda */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-container {
            width: 100%;
            max-width: 360px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Bayangan lembut */
        }

        h2 {
            text-align: center;
            color: #3f51b5; /* Warna utama */
            margin-bottom: 25px;
            font-weight: 600;
        }

        form label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            color: #555;
            font-size: 0.95em;
        }

        form input[type="text"],
        form input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        form input[type="text"]:focus,
        form input[type="password"]:focus {
            border-color: #3f51b5;
            outline: none;
        }

        form button {
            width: 100%;
            padding: 12px;
            margin-top: 25px;
            background-color: #3f51b5;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #303f9f;
        }

        .error-message {
            color: #d32f2f;
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #ffebee;
            border: 1px solid #ffcdd2;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>LOGIN INVENTARIS</h2>

        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>

        <form method="post" action="">
            <label for="username">👤 Username</label>
            <input type="text" id="username" name="username" required autocomplete="username">
            
            <label for="password">🔒 Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
            
            <button type="submit">Masuk</button>
        </form>
    </div>
</body>

</html>