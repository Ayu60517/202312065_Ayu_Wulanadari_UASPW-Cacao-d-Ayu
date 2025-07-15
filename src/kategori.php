<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
include 'koneksi.php';

// Tambah kategori
if (isset($_POST['add'])) {
  $name = $_POST['name'];
  mysqli_query($conn, "INSERT INTO categories (name) VALUES ('$name')");
}

// Hapus kategori
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM categories WHERE id=$id");
}

$data = mysqli_query($conn, "SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manajemen Kategori</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
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
      margin-bottom: 10px;
      text-decoration: none;
      border-radius: 5px;
      padding: 8px;
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

    @media (max-width: 768px) {
      .sidebar {
        display: none;
        width: 200px;
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

<!-- Tombol Toggle Sidebar -->
<button class="btn btn-outline-secondary d-md-none m-3" id="toggleSidebar">☰ Menu</button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <h4>Cacao d’Ayu</h4>
  <hr class="text-white">
  <a href="dashboard.php">🏠 Dashboard</a>
  <a href="users.php">👥 Pengguna</a>
  <a href="produk.php">🥤 Produk</a>
  <a href="kategori.php" class="bg-light text-dark">📂 Kategori</a>
  <a href="laporan.php">📊 Laporan</a>
  <a href="logout.php" class="text-danger">🚪 Logout</a>
</div>

<!-- Konten -->
<div class="main">
  <h3 class="mb-4">📂 Manajemen Kategori Produk</h3>

  <form method="post" class="row g-3 mb-4">
    <div class="col-12 col-md-6">
      <input type="text" name="name" class="form-control" placeholder="Nama Kategori" required>
    </div>
    <div class="col-12 col-md-2">
      <button type="submit" name="add" class="btn btn-primary w-100">Tambah</button>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-bordered table-hover bg-white shadow-sm">
      <thead class="table-light">
        <tr><th>ID</th><th>Nama Kategori</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php while ($k = mysqli_fetch_assoc($data)) { ?>
          <tr>
            <td><?= $k['id'] ?></td>
            <td><?= $k['name'] ?></td>
            <td>
              <a href="?delete=<?= $k['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  // Sidebar toggle button
  document.getElementById("toggleSidebar").addEventListener("click", function () {
    const sidebar = document.getElementById("sidebar");
    sidebar.style.display = sidebar.style.display === "block" ? "none" : "block";
  });
</script>
</body>
</html>
