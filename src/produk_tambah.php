<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
  header("Location: login.php");
  exit;
}

$kategori = mysqli_query($conn, "SELECT * FROM categories");

$success = $error = $inserted_id = null;

if (isset($_POST['submit'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $price = (int) $_POST['price'];
  $category_id = (int) $_POST['category_id'];

  $filename = $_FILES['image']['name'];
  $tmp_name = $_FILES['image']['tmp_name'];
  $target = 'uploads/' . $filename;

  if (move_uploaded_file($tmp_name, $target)) {
    mysqli_query($conn, "INSERT INTO products (name, price, category_id, image) VALUES ('$name', $price, $category_id, '$filename')");
    $inserted_id = mysqli_insert_id($conn);
    $success = "✅ Produk berhasil ditambahkan!";
  } else {
    $error = "❌ Gagal mengupload gambar.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body class="bg-light">
    
  <div class="container py-5">
    
  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
    <a href="produk_edit.php?id=<?= $inserted_id ?>" class="btn btn-warning mb-3">✏️ Edit Produk Ini</a>
    <?php elseif ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      
      <div class="col-md-6 offset-md-3 bg-white p-4 rounded shadow-sm">
        <h4 class="mb-4">Tambah Produk</h4>
        
        <form method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="price" class="form-control" required>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select" required>
              <option value="">- Pilih Kategori -</option>
              <?php while($k = mysqli_fetch_assoc($kategori)) { ?>
                <option value="<?= $k['id'] ?>"><?= $k['name'] ?></option>
                <?php } ?>
              </select>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Gambar Produk</label>
              <input type="file" name="image" accept="image/*" class="form-control" required>
            </div>
            
            <button class="btn btn-primary" name="submit">Simpan</button>
            <a href="produk.php" class="btn btn-secondary">Kembali</a>
          </form>
        </div>
      </div>
    </body>
    </html>
