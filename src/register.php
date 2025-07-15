<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  // Default role_id = 2 (user biasa)
  mysqli_query($conn, "INSERT INTO users (username, password, role_id) VALUES ('$username', '$password', 2)");
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <title>Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
      background: linear-gradient(135deg, #5e3c29, #d9c3af);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .register-box {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.15);
      width: 100%;
      max-width: 400px;
    }
    </style>
    </head>
    <body>
      <div class="register-box">
        <h3 class="text-center mb-4">Daftar Akun Baru</h3>
        <form method="post">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button class="btn btn-success w-100">Daftar</button>
          <p class="text-center mt-3"><a href="login.php">Sudah punya akun? Login</a></p>
        </form>
      </div>
    </body>
    </html>