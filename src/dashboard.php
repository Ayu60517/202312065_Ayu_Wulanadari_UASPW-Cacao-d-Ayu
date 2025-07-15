<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
include 'koneksi.php';

$total_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$total_produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM products"))['total'];
$total_kategori = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM categories"))['total'];
$total_transaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM transactions WHERE DATE(created_at) = CURDATE()"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Cacao d’Ayu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f5f5f5;
      margin: 0;
    }

    .sidebar {
      height: 100vh;
      background-color: #5e3c29;
      color: #fff;
      padding: 20px;
      position: fixed;
      width: 220px;
      top: 0;
      left: 0;
      z-index: 1000;
    }

    .sidebar h4 {
      font-weight: bold;
    }

    .sidebar a {
      color: #fff;
      display: block;
      padding: 10px;
      text-decoration: none;
      border-radius: 8px;
    }

    .sidebar a:hover {
      background-color: #d9c3af;
      color: #5e3c29;
    }

    .main {
      margin-left: 220px;
      padding: 30px;
      transition: margin-left 0.3s ease;
    }

    .card:hover {
      transform: scale(1.02);
      transition: 0.2s;
    }

    /* Responsive Sidebar */
    @media (max-width: 768px) {
      .sidebar {
        display: none;
        width: 200px;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        background-color: #5e3c29;
        padding-top: 60px;
      }

      .main {
        margin-left: 0;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <!-- Tombol Toggle Sidebar (mobile only) -->
  <button class="btn btn-outline-secondary d-md-none m-3" id="toggleSidebar">☰ Menu</button>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h4>Cacao d’Ayu</h4>
    <hr class="text-white">

    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="users.php">👥 Pengguna</a>
    <a href="produk.php">🥤 Produk</a>
    <a href="kategori.php">📂 Kategori</a>
    <a href="laporan.php">📊 Laporan </a>
    <a href="logout.php" class="text-danger">🚪 Logout</a>
  </div>

  <!-- Konten Utama -->
  <div class="main">
    <h2 class="mb-4">Dashboard Admin</h2>

    <div class="row mb-4 g-4">
      <div class="col-md-3 col-sm-6">
        <div class="card text-bg-light shadow-sm">
          <div class="card-body">
            <h6 class="text-muted">Total User</h6>
            <h3><?= $total_user ?></h3>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card text-bg-light shadow-sm">
          <div class="card-body">
            <h6 class="text-muted">Total Produk</h6>
            <h3><?= $total_produk ?></h3>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card text-bg-light shadow-sm">
          <div class="card-body">
            <h6 class="text-muted">Total Kategori</h6>
            <h3><?= $total_kategori ?></h3>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card text-bg-light shadow-sm">
          <div class="card-body">
            <h6 class="text-muted">Transaksi Hari Ini</h6>
            <h3><?= $total_transaksi ?></h3>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-md-6 col-xl-3">
        <a href="users.php" class="text-decoration-none">
          <div class="card shadow-sm text-center">
            <div class="card-body">
              <i data-lucide="users" class="mb-2" style="width:32px;height:32px;"></i>
              <h5>Pengguna</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-6 col-xl-3">
        <a href="produk.php" class="text-decoration-none">
          <div class="card shadow-sm text-center">
            <div class="card-body">
              <i data-lucide="coffee" class="mb-2" style="width:32px;height:32px;"></i>
              <h5>Produk</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-6 col-xl-3">
        <a href="kategori.php" class="text-decoration-none">
          <div class="card shadow-sm text-center">
            <div class="card-body">
              <i data-lucide="folder-open" class="mb-2" style="width:32px;height:32px;"></i>
              <h5>Kategori</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-6 col-xl-3">
        <a href="laporan.php" class="text-decoration-none">
          <div class="card shadow-sm text-center">
            <div class="card-body">
              <i data-lucide="bar-chart-2" class="mb-2" style="width:32px;height:32px;"></i>
              <h5>Laporan</h5>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>

  <script>
    lucide.createIcons();
    document.getElementById("toggleSidebar").addEventListener("click", function () {
      const sidebar = document.getElementById("sidebar");
      if (sidebar.style.display === "block") {
        sidebar.style.display = "none";
      } else {
        sidebar.style.display = "block";
      }
    });
  </script>
</body>
</html>
