<?php
session_start();
include '../config/db.php';

$target = "../uploads/" . $photo;
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Upload foto profil
    $photo = null;
    if ($_FILES['photo']['name']) {
        $photo = time() . '_' . basename($_FILES['photo']['name']);
        $target = "../uploads/" . $photo;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $error = "Gagal mengupload foto.";
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, photo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $photo);
        if ($stmt->execute()) {
            header("Location: list.php");
            exit;
        } else {
            $error = "Gagal menyimpan data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Tambah User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="../dashboard.php">Dashboard</a>
        <div>
            <a href="list.php" class="btn btn-outline-light">Kembali ke Daftar</a>
        </div>
    </div>
</nav>

<div class="container" style="max-width: 600px;">
    <h2 class="mb-4">Tambah User Baru</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required placeholder="Masukkan username" />
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required placeholder="Masukkan email" />
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required placeholder="Masukkan password" />
        </div>
        <div class="mb-3">
            <label class="form-label">Foto Profil (opsional)</label>
            <input type="file" name="photo" class="form-control" accept="image/*" />
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

</body>
</html>
