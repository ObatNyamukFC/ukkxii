<?php
// Include file logika terlebih dahulu
include 'edit_barang.php'; 
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $judul_form ?> Barang</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div>
        <h2><?= $judul_form ?> Barang</h2>
        
        <!-- Action dikosongkan agar POST kembali ke file ini dan diproses oleh logic.php -->
        <form method="post" action="">
            
            <label>Kode Barang</label>
            <!-- Sesuaikan nama input (name="kode_barang") dan value ($data['kode_barang']) -->
            <input type="text" name="kode_barang" value="<?= htmlspecialchars($data['kode_barang'] ?? '') ?>" required>

            <label>Nama Barang</label>
            <!-- Sesuaikan nama input (name="nama_barang") dan value ($data['nama_barang']) -->
            <input type="text" name="nama_barang" value="<?= htmlspecialchars($data['nama_barang'] ?? '') ?>" required>

            <label>Satuan (Misal: Pcs, Unit)</label>
             <!-- Sesuaikan nama input (name="satuan") dan value ($data['satuan']) -->
            <input type="text" name="satuan" value="<?= htmlspecialchars($data['satuan'] ?? '') ?>" required>


            <div style="display: flex; gap: 10px; align-items: center;">
                <button type="submit">Simpan</button>
                <a href="index.php">Batal</a>
            </div>
        </form>
    </div>
</body>

</html>
