<?php
// Pastikan file koneksi dan fungsi sudah dimasukkan (include_once lebih aman)
include 'koneksi.php'; 
include 'fungsi.php'; 

// 1. Cek login
requireLogin();

// 2. Query SQL untuk mengambil data transaksi
// Menggunakan t.* (semua kolom dari transaksi), b.nama_barang, dan u.nama_lengkap (alias nama_petugas)
$query_sql = "
    SELECT 
        t.*, 
        b.nama_barang, 
        u.nama_lengkap AS nama_petugas
    FROM 
        tbl_transaksi t 
    JOIN 
        tbl_barang b ON t.id_barang = b.id_barang
    JOIN 
        tbl_user u ON t.id_user = u.id_user
    ORDER BY 
        t.tgl_transaksi DESC
";

// Eksekusi query
$result = $koneksi->query($query_sql);
if ($result) {
    $transaksi = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // Jika ada error pada query (opsional: tampilkan error untuk debugging)
    $transaksi = [];
    // echo "Error SQL: " . $koneksi->error; 
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Transaksi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Gaya dasar agar tampilan tabel terlihat rapi tanpa file style.css */
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        nav a { text-decoration: none; padding: 5px 10px; border: 1px solid #ccc; border-radius: 4px; }
        nav { margin-bottom: 20px; }
    </style>
</head>

<body>
    <div>
        <nav style="display: flex; gap: 15px;">
            <a href="index.php">📦Barang</a>
            <a href="transaksi.php">📝Transaksi</a>
            <?php if (function_exists('isSuperAdmin') && isSuperAdmin()): ?>
                <a href="users.php">👥Users</a>
            <?php endif; ?>
            <a href="logout.php" style="color: red;">🚪Logout</a>
        </nav>

        <h2 style="margin: 20px 0;">Riwayat Transaksi</h2>

        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Petugas</th>
                    <th>Jumlah</th>
                    <th>Jenis Transaksi</th>
                    <th>Keterangan</th>
                    <th>Tanggal Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($transaksi)): ?>
                    <?php foreach ($transaksi as $t): ?>
                        <tr>
                            <td><?= $t['nama_barang'] ?></td>
                            
                            <td><?= $t['nama_petugas'] ?></td>
                            
                            <td><?= $t['jumlah'] ?></td>
                            
                            <?php 
                                $jenis = strtoupper($t['jenis_transaksi'] ?? ''); // Pastikan tidak null
                                $warna = ($jenis == 'MASUK') ? 'green' : 'red';
                            ?>
                            <td style="color: <?= $warna ?>">
                                <?= ucfirst(strtolower($jenis)) ?>
                            </td>
                            
                            <td><?= $t['keterangan'] ?></td>
                            
                            <td><?= $t['tgl_transaksi'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Tidak ada riwayat transaksi ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>