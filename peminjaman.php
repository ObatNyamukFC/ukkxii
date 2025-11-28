<?php
// --- SETUP AWAL ---
include 'koneksi.php'; 
include 'fungsi.php'; 

// Cek login 
requireLogin();

// Ambil ID User dari session
$id_user = $_SESSION['user_id'] ?? 0;
$error = '';
$success = '';

// Ambil parameter dari URL
$id_barang_url = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

// Tentukan jenis transaksi
$jenis_transaksi = ($action === 'pinjam') ? 'KELUAR' : 'MASUK';
$judul_form = ($action === 'pinjam') ? 'Pencatatan Peminjaman' : 'Pencatatan Pengembalian';


// --- 1. AMBIL DATA BARANG UNTUK FORM ---
$data_barang = null;
if ($id_barang_url) {
    $stmt = $koneksi->prepare("SELECT id_barang, kode_barang, nama_barang, satuan FROM tbl_barang WHERE id_barang = ?");
    $stmt->bind_param("i", $id_barang_url);
    $stmt->execute();
    $result = $stmt->get_result();
    $data_barang = $result->fetch_assoc();
    
    if (!$data_barang) {
        $error = "ID Barang tidak valid.";
        $id_barang_url = null; // Batalkan proses jika barang tidak ditemukan
    }
} else {
    $error = "Pilih barang yang akan diproses.";
}


// --- 2. PROSES SIMPAN TRANSAKSI (POST REQUEST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $data_barang) {
    
    // Ambil data POST
    $id_barang = $data_barang['id_barang']; // Ambil ID dari data barang yang sudah divalidasi
    $jumlah = $_POST['jumlah'] ?? 0;
    $keterangan = $_POST['keterangan'] ?? '';
    $tgl_transaksi = date('Y-m-d H:i:s'); // Ambil waktu server saat ini
    
    // Validasi sederhana
    if ($jumlah <= 0) {
        $error = "Jumlah harus lebih dari 0.";
    } elseif ($id_user == 0) {
        $error = "Sesi pengguna hilang. Mohon login ulang.";
    } else {
        // Query INSERT ke tbl_transaksi
        $stmt = $koneksi->prepare("
            INSERT INTO tbl_transaksi (id_barang, id_user, jenis_transaksi, jumlah, tgl_transaksi, keterangan) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        // Tipe data: (INT, INT, ENUM(STRING), INT, DATETIME(STRING), TEXT(STRING))
        $stmt->bind_param("iisiss", $id_barang, $id_user, $jenis_transaksi, $jumlah, $tgl_transaksi, $keterangan);
        
        if ($stmt->execute()) {
            $success = "Transaksi " . ($jenis_transaksi === 'KELUAR' ? 'Peminjaman' : 'Pengembalian') . " berhasil dicatat!";
            
            // Redirect setelah sukses
            header("Location: index.php?status=" . strtolower($jenis_transaksi));
            exit();
        } else {
            $error = "Gagal mencatat transaksi: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($judul_form) ?> Barang</title>
    <style>
        /* CSS Disederhanakan untuk tampilan form */
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 20px; }
        .form-container { max-width: 500px; margin: 0 auto; padding: 20px; background-color: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { border-bottom: 2px solid #ccc; padding-bottom: 10px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input[type="number"], textarea { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 15px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .info-box { background-color: #e6f7ff; border: 1px solid #91d5ff; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        button { padding: 10px 15px; background-color: #3f51b5; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #303f9f; }
        a.batal { text-decoration: none; color: #555; padding: 10px 15px; border: 1px solid #ccc; border-radius: 4px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2><?= htmlspecialchars($judul_form) ?></h2>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <?php if ($data_barang): ?>
            <div class="info-box">
                <strong>Barang:</strong> <?= htmlspecialchars($data_barang['nama_barang']) ?> (<?= htmlspecialchars($data_barang['kode_barang']) ?>)<br>
                <strong>Satuan:</strong> <?= htmlspecialchars($data_barang['satuan']) ?>
            </div>

            <form method="post" action="">
                
                <label for="jumlah">Jumlah Barang (<?= $jenis_transaksi === 'KELUAR' ? 'Dipinjam' : 'Dikembalikan' ?>)</label>
                <input type="number" id="jumlah" name="jumlah" min="1" required>

                <label for="keterangan">Keterangan / Tujuan (Wajib)</label>
                <textarea id="keterangan" name="keterangan" rows="3" required placeholder="<?= $jenis_transaksi === 'KELUAR' ? 'Untuk keperluan apa? Siapa yang meminjam?' : 'Barang dikembalikan oleh siapa?' ?>"></textarea>
                
                <p style="font-size: 0.8em; color: #555;">*Transaksi akan dicatat sebagai **<?= $jenis_transaksi ?>** atas nama Anda (ID User: <?= $id_user ?>).</p>

                <div style="display: flex; gap: 10px; align-items: center; margin-top: 20px;">
                    <button type="submit">Catat Transaksi <?= $jenis_transaksi ?></button>
                    <a href="index.php" class="batal">Batal</a>
                </div>
            </form>
        <?php else: ?>
            <p>Silakan kembali ke <a href="index.php">halaman utama</a> dan pilih barang yang akan diproses.</p>
        <?php endif; ?>
    </div>
</body>
</html>