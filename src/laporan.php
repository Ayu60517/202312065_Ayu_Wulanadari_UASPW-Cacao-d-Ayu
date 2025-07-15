<?php
session_start();
if (($_SESSION['user']['role_id'] != 1)) {
  header("Location: login.php");
  exit;
}
include 'koneksi.php';

// Hapus transaksi
if (isset($_POST['delete'])) {
  $id = (int) $_POST['delete'];
  mysqli_query($conn, "DELETE FROM transactions WHERE id = $id");
  header("Location: laporan.php");
  exit;
}

// Ambil data transaksi
$data = mysqli_query($conn, "SELECT t.id, u.username, t.order_type, t.note, p.method, t.total, t.created_at FROM transactions t JOIN users u ON t.user_id = u.id JOIN payments p ON t.payment_id = p.id ORDER BY t.created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Transaksi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }
    .main {
      margin-left: 220px;
      padding: 30px;
      transition: margin-left 0.3s ease;
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
      padding: 8px;
      border-radius: 6px;
    }
    .sidebar a:hover {
      background-color: #d9c3af;
      color: #5e3c29;
    }
    @media (max-width: 768px) {
      .sidebar {
        display: none;
        width: 200px;
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
  <hr>
  <a href="dashboard.php">🏠 Dashboard</a>
  <a href="users.php">👥 Pengguna</a>
  <a href="produk.php">🥤 Produk</a>
  <a href="kategori.php">📂 Kategori</a>
  <a href="laporan.php" class="bg-light text-dark">📊 Laporan</a>
  <a href="logout.php" class="text-danger">🚪 Logout</a>
</div>

<!-- Konten -->
<div class="main">
  <h3 class="mb-4">📊 Laporan Transaksi</h3>

  <div class="table-responsive">
    <table class="table table-bordered table-hover bg-white shadow-sm">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Jenis</th>
          <th>Pembayaran</th>
          <th>Total</th>
          <th>Catatan</th>
          <th>Tanggal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($t = mysqli_fetch_assoc($data)) {
          $ikon = match($t['order_type']) {
            'dine-in' => '🍽️',
            'takeaway' => '🥡',
            'delivery' => '🚚',
            default => '❓'
          };
        ?>
        <tr>
          <td><?= $t['id'] ?></td>
          <td><?= $t['username'] ?></td>
          <td><?= $ikon . ' ' . ucfirst($t['order_type']) ?></td>
          <td><?= $t['method'] ?></td>
          <td>Rp<?= number_format($t['total']) ?></td>
          <td><?= $t['note'] ?></td>
          <td><?= date("d M Y H:i", strtotime($t['created_at'])) ?></td>
          <td class="text-center">
            <a href="struk.php?id=<?= $t['id'] ?>" target="_blank" class="btn btn-info btn-sm">🧾</a>
            <form method="post" style="display:inline;">
              <input type="hidden" name="delete" value="<?= $t['id'] ?>">
              <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus transaksi?')">🗑️</button>
            </form>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  // Toggle sidebar
  document.getElementById("toggleSidebar").addEventListener("click", function () {
    const sidebar = document.getElementById("sidebar");
    sidebar.style.display = sidebar.style.display === "block" ? "none" : "block";
  });
</script>

</body>
</html>
