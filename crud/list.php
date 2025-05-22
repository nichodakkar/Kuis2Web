<?php
session_start();
include '../config/db.php';

// Pastikan hanya user login yang bisa akses
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Daftar User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="../dashboard.php">Dashboard</a>
        <div>
            <a href="../logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4">Daftar User</h2>

    <a href="add.php" class="btn btn-primary mb-3">+ Tambah User</a>

    <table class="table table-bordered table-striped align-middle bg-white">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Foto</th>
                <th>Username</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td>
                    <?php if ($row['photo'] && file_exists("../uploads/" . $row['photo'])): ?>
                        <img src="../uploads/<?= htmlspecialchars($row['photo']) ?>" width="50" class="rounded-circle" alt="Foto Profil" />
                    <?php else: ?>
                        <img src="https://via.placeholder.com/50" class="rounded-circle" alt="Default" />
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
