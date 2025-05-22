<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
$user = $_SESSION['user'];
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">User Dashboard</a>
    <div class="d-flex">
      <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h3 class="mb-4">Selamat Datang, <?= htmlspecialchars($user['username']) ?>!</h3>
                    <p class="mb-3">Email: <?= htmlspecialchars($user['email']) ?></p>
                    <?php if ($user['photo']): ?>
                        <img src="uploads/<?= htmlspecialchars($user['photo']) ?>" class="img-thumbnail rounded-circle" width="150">
                    <?php else: ?>
                        <p class="text-muted">Tidak ada foto profil</p>
                    <?php endif; ?>
                    <hr>
                    <a href="crud/list.php" class="btn btn-primary">Kelola User</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
