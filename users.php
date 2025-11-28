<?php
// --- SETUP AWAL ---
include 'koneksi.php'; 
include 'fungsi.php'; 

// Cek login
requireLogin();

// --- PENGAMANAN HANYA UNTUK ADMIN ---
// Asumsi 'admin' adalah level tertinggi (isSuperAdmin() dari fungsi.php)
if (!isSuperAdmin()) {
    // Arahkan ke dashboard jika bukan Admin
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';


// --- 1. LOGIKA HAPUS USER ---
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Mencegah Admin menghapus dirinya sendiri
    if ($delete_id == ($_SESSION['user_id'] ?? 0)) {
        $error = "Anda tidak bisa menghapus akun Anda sendiri.";
    } else {
        // Hapus user dengan prepared statement
        $stmt = $koneksi->prepare("DELETE FROM tbl_user WHERE id_user = ?");
        $stmt->bind_param("i", $delete_id);
        
        if ($stmt->execute()) {
            $success = "User berhasil dihapus.";
            header("Location: user.php?status=deleted");
            exit();
        } else {
            $error = "Gagal menghapus user: " . $koneksi->error;
        }
    }
}


// --- 2. AMBIL DATA USER ---
$query_user = "
    SELECT 
        id_user, 
        username, 
        nama_lengkap, 
        level
    FROM 
        tbl_user
    ORDER BY 
        level ASC, nama_lengkap ASC
";

$result_user = $koneksi->query($query_user);
$users = $result_user ? $result_user->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola User</title>
    <style>
        /* CSS Dasar */
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; }
        .container { width: 90%; max-width: 900px; margin: 20px auto; padding: 20px; background-color: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { border-bottom: 2px solid #ccc; padding-bottom: 10px; margin-bottom: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #e0e0e0; }
        .menu a { margin-right: 15px; text-decoration: none; padding: 8px 15px; border: 1px solid #3f51b5; background-color: #3f51b5; color: white; border-radius: 4px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="container">
        <h2>👥 Pengelolaan User</h2>
        
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success || (isset($_GET['status']) && $_GET['status'] == 'deleted')): ?>
            <p class="success">User berhasil dihapus.</p>
        <?php endif; ?>

        <div class="menu">
            <a href="index.php" style="background-color: #6c757d;">Kembali ke Dashboard</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Level</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id_user']) ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($user['level'])) ?></td>
                            <td>
                                <?php 
                                // Hanya izinkan hapus jika user bukan Admin dan bukan dirinya sendiri
                                if ($user['id_user'] != ($_SESSION['user_id'] ?? 0) && $user['level'] != 'admin'): 
                                ?>
                                    | <a href="user.php?delete_id=<?= $user['id_user'] ?>" 
                                       onclick="return confirm('Yakin ingin menghapus user <?= htmlspecialchars($user['nama_lengkap']) ?>?')">Hapus</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Tidak ada user terdaftar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>