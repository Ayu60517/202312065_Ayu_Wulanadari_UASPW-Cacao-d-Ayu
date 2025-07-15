<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
  header("Location: login.php");
  exit;
}

$id = $_GET['id'];
$produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id=$id"));
$kategori = mysqli_query($conn, "SELECT * FROM categories");

if (isset($_POST['update'])) {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $category_id = $_POST['category_id'];

  // Cek apakah gambar baru diunggah
  if ($_FILES['image']['name']) {
    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp, "uploads/$image");
  } else {
    $image = $produk['image']; // pakai gambar lama
  }

  mysqli_query($conn, "UPDATE products SET name='$name', price=$price, category_id=$category_id, image='$image' WHERE id=$id");
  if(!$produk){
  header("Location: produk.php");
  exit;
}
}
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .preview {
      max-height: 150px;
      object-fit: cover;
      margin-bottom: 10px;
    }
    </style>
    </head>
    <body class="bg-light">
      <div class="container py-5">
        <div class="col-md-6 offset-md-3 bg-white p-4 rounded shadow-sm">
          <h4 class="mb-4">Edit Produk</h4>
        
          <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
              <label>Nama Produk</label>
              <input type="text" name="name" class="form-control" required value="<?= $produk['name'] ?>">
            </div>
            
            <div class="mb-3">
              <label>Harga</label>
              <input type="number" name="price" class="form-control" required value="<?= $produk['price'] ?>">
            </div>
            
            <div class="mb-3">
              <label>Kategori</label>
              <select name="category_id" class="form-select" required>
                <?php while($k = mysqli_fetch_assoc($kategori)) { ?>
                  <option value="<?= $k['id'] ?>" <?= $k['id'] == $produk['category_id'] ? 'selected' : '' ?>>
                    <?= $k['name'] ?>
                  </option>
                  <?php } ?>
                </select>
              </div>
              
              <div class="mb-3">
                <label>Gambar Produk</label><br>
                <?php if ($produk['image']) { ?>
                  <img src="uploads/<?= $produk['image'] ?>" class="preview" id="preview-img">
                  <?php } ?>
                  <input type="file" name="image" class="form-control" onchange="previewFile(this)">
                </div>
                
                <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                <a href="produk.php" class="btn btn-secondary">Kembali</a>
              </form>
            </div>
          </div>
          
          <script>
          function previewFile(input) {
            const file = input.files[0];
            if (file) {
              const reader = new FileReader();
              reader.onload = function(e) {
                let img = document.getElementById('preview-img');
                if (!img) {
                  img = document.createElement('img');
                  img.className = 'preview';
                  img.id = 'preview-img';
                  input.parentNode.insertBefore(img, input);
                }
                img.src = e.target.result;
              };
              reader.readAsDataURL(file);
            }
            }
            </script>
            </body>
            </html>
