<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Optional: kamu bisa tambahkan cek supaya user tidak bisa hapus dirinya sendiri
    if ($id == $_SESSION['user']['id']) {
        $_SESSION['error'] = "Anda tidak dapat menghapus user yang sedang login.";
        header("Location: list.php");
        exit;
    }

    // Hapus foto profil dulu jika ada
    $stmt = $conn->prepare("SELECT photo FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user['photo'] && file_exists("../uploads/" . $user['photo'])) {
        unlink("../uploads/" . $user['photo']);
    }

    // Hapus user dari database
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: list.php");
exit;
