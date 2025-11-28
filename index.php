<?php
// --- SETUP AWAL ---
include 'koneksi.php'; 
include 'fungsi.php'; 

// Cek login
requireLogin();

// Ambil Nama User yang Login (Asumsi disimpan di session)
$nama_user = $_SESSION['nama_lengkap'] ?? "Petugas"; 
$user_id = $_SESSION['id_user'] ?? 0;


// --- 1. LOGIKA DATABASE (Dashboard Info Card) ---

// 1.1. Query untuk Menghitung Total Jenis Barang
$query_barang_count = "SELECT COUNT(id_barang) AS total_barang FROM tbl_barang";
$result_barang_count = $koneksi->query($query_barang_count);
$total_barang = $result_barang_count ? $result_barang_count->fetch_assoc()['total_barang'] : 0;

// 1.2. Query untuk Menghitung Total User
$query_user_count = "SELECT COUNT(id_user) AS total_user FROM tbl_user";
$result_user_count = $koneksi->query($query_user_count);
$total_user = $result_user_count ? $result_user_count->fetch_assoc()['total_user'] : 0;


// --- 2. LOGIKA DATABASE (Data Tabel Barang) ---

// 2.1. Proses DELETE BARANG (Jika ada request delete)
$error = '';
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    if (function_exists('isSuperAdmin') && isSuperAdmin()) {
        $stmt = $koneksi->prepare("DELETE FROM tbl_barang WHERE id_barang = ?");
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            header("Location: index.php?status=deleted");
            exit();
        } else {
            $error = "Gagal menghapus barang: " . $koneksi->error;
        }
    } else {
        $error = "Akses ditolak. Hanya SuperAdmin yang boleh menghapus.";
    }
}

// 2.2. Ambil Data Barang
// Query ini HANYA mengambil kolom yang ada di tbl_barang Anda
$query_data_barang = "
    SELECT 
        id_barang, 
        kode_barang, 
        nama_barang, 
        satuan
    FROM 
        tbl_barang
    ORDER BY 
        nama_barang ASC
";

$result_data_barang = $koneksi->query($query_data_barang);
$items = $result_data_barang ? $result_data_barang->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard & Data Barang</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
        /* CSS Gabungan dari Semua Bagian */
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
            background-color: #f4f7f6;
        }
        .container { 
            width: 90%; 
            max-width: 1100px; 
            margin: 20px auto; 
            padding: 20px;
        }
        .navbar {
            background-color: #3f51b5; 
            color: white;
            padding: 10px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .navbar a:hover { background-color: #303f9f; }
        .navbar .menu-right { display: flex; gap: 10px; }
        
        /* Info Card Styling */
        .card-container {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .card {
            padding: 15px; 
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex: 1; 
            min-width: 200px; 
            transition: transform 0.2s;
        }
        .card:hover { transform: translateY(-3px); }
        .card h3 { margin: 0 0 5px 0; font-size: 1.1em; color: #595959; }
        .card .count { font-size: 2em; font-weight: bold; }
        .welcome-card { background-color: #e6f7ff; border-left: 5px solid #1890ff; }
        .info-card { background-color: #f6ffed; border-left: 5px solid #52c41a; }
        .info-card .count { color: #52c41a; }
        .user-card { background-color: #fff1f0; border-left: 5px solid #f5222d; }
        .user-card .count { color: #f5222d; }

        /* Tabel Styling */
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #e0e0e0; }
        .menu-tabel a { margin-right: 10px; text-decoration: none; }
        footer { background-color: #333; color: white; text-align: center; padding: 15px 0; margin-top: 40px; font-size: 0.9em; }
    </style>
</head>
<body>
    
    <header class="navbar">
        <div class="logo">
            <a href="index.php">INVENTARIS UKK</a>
        </div>
        <nav class="menu-right">
            <a href="index.php">📦 Barang</a>
            <a href="transaksi.php">📝 Transaksi</a>
            <?php if (function_exists('isSuperAdmin') && isSuperAdmin()): ?>
                <a href="users.php">👥 Users</a>
            <?php endif; ?>
            <a href="logout.php" style="color: #ffcccc;" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a>
        </nav>
    </header>
    
    <div class="container">

        <h2 style="margin-top: 10px;">Dashboard Ringkasan</h2>

        <div class="card-container">
            
            <div class="card welcome-card">
                <h3>Selamat Datang,</h3>
                <div style="font-size: 1.5em; font-weight: bold; margin-top: 5px;">
                    <?= htmlspecialchars($nama_user) ?> 👋
                </div>
                <p style="margin-top: 10px;">Ringkasan Inventaris.</p>
            </div>

            <div class="card info-card">
                <h3>Total Jenis Barang</h3>
                <div class="count"><?= htmlspecialchars($total_barang) ?></div>
                <p>Jenis barang terdaftar.</p>
            </div>

            <div class="card user-card">
                <h3>Total User</h3>
                <div class="count"><?= htmlspecialchars($total_user) ?></div>
                <p>Petugas dan Admin.</p>
            </div>
            
        </div>
        
        <h2 style="margin-top: 40px;">Data Barang Inventaris</h2>
        
        <?php if (!empty($error)) echo "<p style='color:red;'>{$error}</p>"; ?>

        <div class="menu-tabel" style="margin-bottom: 15px;">
            <a href="form_barang.php">Tambah Barang</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Satuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['kode_barang']) ?></td>
                                <td><?= htmlspecialchars($item['nama_barang']) ?></td>
                                <td><?= htmlspecialchars($item['satuan']) ?></td> 
                                <td>
                                    <a href="edit_barang.php?id=<?= $item['id_barang'] ?>">Edit</a> |
                                    <a href="peminjaman.php?action=pinjam&id=<?= $item['id_barang'] ?>">Pinjam</a> |
                                    
                                    <a href="peminjaman.php?action=kembali&id=<?= $item['id_barang'] ?>">Kembalikan</a> |
                                    
                                    <a href="index.php?delete_id=<?= $item['id_barang'] ?>" 
                                       onclick="return confirm('Yakin ingin menghapus <?= htmlspecialchars($item['nama_barang']) ?>?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">Tidak ada data barang ditemukan.</td>
                        </tr>
                    <?php endif; ?>
            </tbody>
        </table>

        </div> 

    <footer style="background-color: #333; color: white; text-align: center; padding: 15px 0; font-size: 0.9em;">
        &copy; <?= date('Y') ?> UKK Inventaris. Dikelola oleh <?= htmlspecialchars($nama_user) ?>.
    </footer>

</body>
</html>