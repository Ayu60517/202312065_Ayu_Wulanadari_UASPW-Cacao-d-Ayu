<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
  header("Location: login.php");
  exit;
}

$is_edit = false;
if (isset($_GET['edit'])) {
  $is_edit = true;
  $edit_id = $_GET['edit'];
  $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id=$edit_id"));
}

if (isset($_POST['submit'])) {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $category_id = $_POST['category_id'];
  $id = $_POST['id'];

  $image = $_FILES['image']['name'];
  $tmp = $_FILES['image']['tmp_name'];

  if ($image != '') {
    move_uploaded_file($tmp, "uploads/$image");
  } else {
    $image = $_POST['old_image'];
  }

  if ($id != '') {
    mysqli_query($conn, "UPDATE products SET name='$name', price=$price, category_id=$category_id, image='$image' WHERE id=$id");
  } else {
    move_uploaded_file($tmp, "uploads/$image");
    mysqli_query($conn, "INSERT INTO products (name, price, category_id, image) VALUES ('$name', $price, $category_id, '$image')");
  }

  header("Location: produk.php");
  exit;
}

if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM products WHERE id=$id");
}

$kategori = mysqli_query($conn, "SELECT * FROM categories");
$produk = mysqli_query($conn, "SELECT products.*, categories.name AS category FROM products JOIN categories ON products.category_id = categories.id");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manajemen Produk</title>
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

    img.thumb {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 5px;
    }

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

<!-- Tombol Toggle Sidebar -->
<button class="btn btn-outline-secondary d-md-none m-3" id="toggleSidebar">☰ Menu</button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <h4>Cacao d’Ayu</h4>
  <hr class="text-white">
  <a href="dashboard.php">🏠 Dashboard</a>
  <a href="users.php">👥 Pengguna</a>
  <a href="produk.php" class="bg-light text-dark">🥤 Produk</a>
  <a href="kategori.php">📂 Kategori</a>
  <a href="laporan.php">📊 Laporan</a>
  <a href="logout.php" class="text-danger">🚪 Logout</a>
</div>

<!-- Konten -->
<div class="main">
  <h3 class="mb-4">🥤 Manajemen Produk</h3>

  <form method="post" enctype="multipart/form-data" class="row g-3 mb-4">
    <input type="hidden" name="id" value="<?= $is_edit ? $edit['id'] : '' ?>">
    <input type="hidden" name="old_image" value="<?= $is_edit ? $edit['image'] : '' ?>">

    <div class="col-12 col-md-2">
      <input type="text" name="name" class="form-control" placeholder="Nama Produk" value="<?= $is_edit ? $edit['name'] : '' ?>" required>
    </div>

    <div class="col-12 col-md-2">
      <input type="number" name="price" class="form-control" placeholder="Harga" value="<?= $is_edit ? $edit['price'] : '' ?>" required>
    </div>

    <div class="col-12 col-md-2">
      <select name="category_id" class="form-select" required>
        <option value="">Pilih Kategori</option>
        <?php mysqli_data_seek($kategori, 0); while ($k = mysqli_fetch_assoc($kategori)) { ?>
          <option value="<?= $k['id'] ?>" <?= $is_edit && $k['id'] == $edit['category_id'] ? 'selected' : '' ?>><?= $k['name'] ?></option>
        <?php } ?>
      </select>
    </div>

    <div class="col-12 col-md-3">
      <input type="file" name="image" class="form-control" accept="image/*">
      <?php if ($is_edit && $edit['image']) { ?>
        <img src="uploads/<?= $edit['image'] ?>" class="thumb mt-1">
      <?php } ?>
    </div>

    <div class="col-12 col-md-3">
      <button class="btn btn-<?= $is_edit ? 'success' : 'primary' ?> w-100" name="submit"><?= $is_edit ? 'Simpan Perubahan' : 'Tambah' ?></button>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-bordered table-hover bg-white shadow-sm">
      <thead class="table-light">
        <tr>
          <th>ID</th><th>Gambar</th><th>Nama Produk</th><th>Harga</th><th>Kategori</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($p = mysqli_fetch_assoc($produk)) { ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td>
              <?php if ($p['image']) { ?>
                <img src="uploads/<?= $p['image'] ?>" class="thumb">
              <?php } else { echo "-"; } ?>
            </td>
            <td><?= $p['name'] ?></td>
            <td>Rp<?= number_format($p['price']) ?></td>
            <td><?= $p['category'] ?></td>
            <td>
              <a href="?edit=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="?delete=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')">Hapus</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  document.getElementById("toggleSidebar").addEventListener("click", function () {
    const sidebar = document.getElementById("sidebar");
    sidebar.style.display = sidebar.style.display === "block" ? "none" : "block";
  });
</script>
</body>
</html>
