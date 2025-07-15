<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Terima Kasih</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f9f5f0;
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .card {
      padding: 30px;
      max-width: 400px;
      width: 100%;
      border-radius: 16px;
      background: white;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      text-align: center;
    }
    .card h2 {
      color: #5e3c29;
      margin-bottom: 15px;
    }
    .card p {
      font-size: 1rem;
      margin-bottom: 25px;
    }
  </style>
</head>
<body>

<div class="card">
  <h2>🎉 Terima Kasih!</h2>
  <p>Pesanan Anda telah berhasil dikirim.<br>Kami akan segera menyiapkannya.</p>
  <a href="home.php" class="btn btn-outline-primary w-100">Kembali ke Menu</a>
</div>

</body>
</html>
