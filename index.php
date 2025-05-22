<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Login</h3>
                        <?php
                        session_start();
                        include 'config/db.php';

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $email = $_POST['email'];
                            $password = $_POST['password'];

                            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                            $stmt->bind_param("s", $email);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows === 1) {
                                $user = $result->fetch_assoc();
                                if (password_verify($password, $user['password'])) {
                                    $_SESSION['user'] = $user;
                                    header("Location: dashboard.php");
                                    exit;
                                } else {
                                    $error = "Password salah!";
                                }
                            } else {
                                $error = "Email tidak ditemukan!";
                            }
                        }
                        ?>
                        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
