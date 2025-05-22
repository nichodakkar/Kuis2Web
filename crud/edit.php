<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User tidak ditemukan!";
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];

    // Update password jika diisi
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $updatePassword = true;
    } else {
        $updatePassword = false;
    }

    // Upload foto baru jika ada
    if ($_FILES['photo']['name']) {
        $photo = time() . '_' . basename($_FILES['photo']['name']);
        $target = "../uploads/" . $photo;
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $error = "Gagal mengupload foto.";
        }
    } else {
        $photo = $user['photo'];
    }

    if (!$error) {
        if ($updatePassword) {
            $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=?, photo=? WHERE id=?");
            $stmt->bind_param("ssssi", $username, $email, $password, $photo, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username=?, email=?, photo=? WHERE id=?");
            $stmt->bind_param("sssi", $username, $email, $photo, $id);
        }

        if ($stmt->execute()) {
            header("Location: list.php");
            exit;
        } else {
            $error = "Gagal update data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Edit User</title>
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
    <h2 class="mb-4">Edit User</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($user['username']) ?>" />
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($user['email']) ?>" />
        </div>
        <div class="mb-3">
            <label class="form-label">Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password baru" />
        </div>
        <div class="mb-3">
            <label class="form-label">Foto Profil Saat Ini</label><br>
            <?php if ($user['photo'] && file_exists("../uploads/" . $user['photo'])): ?>
                <img src="../uploads/<?= htmlspecialchars($user['photo']) ?>" alt="Foto Profil" class="rounded-circle border" width="100" height="100" />
            <?php else: ?>
                <img src="https://via.placeholder.com/100" alt="Foto Default" class="rounded-circle border" />
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Ganti Foto Profil (opsional)</label>
            <input type="file" name="photo" class="form-control" accept="image/*" />
        </div>
        <button type="submit" class="btn btn-warning">Update</button>
    </form>
</div>

</body>
</html>
