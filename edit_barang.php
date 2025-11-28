<?php
// --- SETUP AWAL ---
include 'koneksi.php'; 
include 'fungsi.php'; 

// Cek login 
requireLogin();

// Pastikan koneksi tersedia
if (!$koneksi) {
    die("Koneksi database tidak tersedia.");
}

// --- INISIALISASI VARIABEL ---
$id_barang = $_GET['id'] ?? null; // Ambil ID dari URL (disarankan pakai 'id' saja)
$data = null;
$judul_form = 'Tambah'; 
$error = '';


// --- 1. LOGIKA PENGAMBILAN DATA (UNTUK EDIT) ---
if ($id_barang) {
    // Ambil data barang untuk diedit
    $stmt = $koneksi->prepare("SELECT id_barang, kode_barang, nama_barang, satuan FROM tbl_barang WHERE id_barang = ?");
    $stmt->bind_param("i", $id_barang);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    if ($data) {
        $judul_form = 'Edit';
        // Ambil id_barang dari data yang diambil
        $id_barang = $data['id_barang'];
    } else {
        // Jika ID ada tapi data tidak ditemukan
        header("Location: index.php");
        exit();
    }
}


// --- 2. PROSES SIMPAN DATA (INSERT/UPDATE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ambil data POST
    $id_barang_post = $_POST['id_barang_hidden'] ?? null; // ID dikirim dari input hidden
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $satuan = $_POST['satuan'];

    if ($id_barang_post) {
        // UPDATE data barang (EDIT)
        // id_barang_post bertipe INT, sisanya STRING
        $stmt = $koneksi->prepare("UPDATE tbl_barang SET kode_barang=?, nama_barang=?, satuan=? WHERE id_barang=?");
        $stmt->bind_param("sssi", $kode_barang, $nama_barang, $satuan, $id_barang_post);
    } else {
        // INSERT data barang baru (TAMBAH)
        $stmt = $koneksi->prepare("INSERT INTO tbl_barang (kode_barang, nama_barang, satuan) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $kode_barang, $nama_barang, $satuan);
    }

    if ($stmt->execute()) {
        // Redirect setelah selesai
        header("Location: index.php?status=success");
        exit();
    } else {
        $error = "Gagal menyimpan data: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($judul_form) ?> Barang</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS dasar untuk form */
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 20px; }
        div { max-width: 500px; margin: 0 auto; padding: 20px; background-color: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input[type="text"] { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 15px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px 15px; background-color: #3f51b5; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #303f9f; }
        a { text-decoration: none; color: #555; padding: 10px 15px; border: 1px solid #ccc; border-radius: 4px; }
    </style>
</head>

<body>
    <div>
        <h2><?= htmlspecialchars($judul_form) ?> Barang</h2>
        
        <?php if ($error): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="">
            
            <?php if ($id_barang): ?>
                <input type="hidden" name="id_barang_hidden" value="<?= htmlspecialchars($id_barang) ?>">
            <?php endif; ?>
            
            <label>Kode Barang</label>
            <input type="text" name="kode_barang" value="<?= htmlspecialchars($data['kode_barang'] ?? '') ?>" required>

            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="<?= htmlspecialchars($data['nama_barang'] ?? '') ?>" required>

            <label>Satuan (Misal: Pcs, Unit)</label>
            <input type="text" name="satuan" value="<?= htmlspecialchars($data['satuan'] ?? '') ?>" required>

            <div style="display: flex; gap: 10px; align-items: center;">
                <button type="submit">Simpan</button>
                <a href="index.php">Batal</a>
            </div>
        </form>
    </div>
</body>

</html>