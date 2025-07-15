<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['user'])) {
  header("Location: home.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = $_POST['password'];

  $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
  if ($user = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $user['password'])) {
      $_SESSION['user'] = $user;
      header("Location: " . ($user['role_id'] == 1 ? 'dashboard.php' : 'home.php'));
      exit;
    } else {
      $error = "Password salah!";
    }
  } else {
    $error = "Username tidak ditemukan!";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cacao d'Ayu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #5e3c29, #d9c3af);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
    }
    .login-box {
      background: #fff;
      padding: 40px 20px;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.15);
      width: 100%;
      max-width: 350px;
    }
    input {
      font-size: 16px;
    }
  </style>
</head>

    <body>
      <div class="login-box">
        <h3 class="text-center mb-4">☕ Cacao d'Ayu </h3>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>". htmlspecialchars($error)."</div>"; ?>
        <form method="post">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Login</button>
          <p class="mt-3 text-center">Belum punya akun? <a href="register.php">Daftar</a></p>
        </form>
      </div>
    </body>
    </html>
