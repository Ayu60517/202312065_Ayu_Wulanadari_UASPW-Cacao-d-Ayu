<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
include 'koneksi.php';

$filter = "";
if (isset($_GET['kategori']) && $_GET['kategori'] != '') {
  $id_kat = (int)$_GET['kategori'];
  $filter = "WHERE products.category_id = $id_kat";
}

$produk = mysqli_query($conn, "SELECT products.*, categories.name AS category FROM products JOIN categories ON products.category_id = categories.id $filter ORDER BY categories.name, products.name");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cacao d’Ayu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      background-color: #fefefe;
      font-family: 'Segoe UI', sans-serif;
    }

    .card:hover {
      transform: scale(1.02);
      transition: 0.2s;
    }

    .card-img-top {
      height: 140px;
      object-fit: cover;
      border-radius: 6px 6px 0 0;
    }

    .card-body {
      min-height: 160px;
    }

    .toast-message {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: #198754;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      z-index: 9999;
      font-weight: 600;
    }

    /* Ukuran dropdown agar kecil dan rapi */
    select.form-select-sm {
      font-size: 13px;
      padding: 4px 8px;
      line-height: 1.2;
      height: auto;
      max-width: 160px;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
    }

    @media (max-width: 576px) {
      .card-img-top {
        height: 110px;
      }

      select.form-select-sm {
        font-size: 12px;
        padding: 4px 6px;
        max-width: 130px;
      }

      select.form-select-sm option {
        font-size: 12px;
        padding: 2px;
      }
    }
  </style>
</head>
<body>

<?php include 'navbar_user.php'; ?>

<div class="container-md py-4 mt-4" style="max-width: 1200px;">

  <h3 class="text-center mb-4">Menu Hari Ini</h3>

  <!-- Filter -->
  <div class="mb-3">
    <form method="get" class="d-flex flex-wrap align-items-center gap-2">
      <label for="kategori" class="fw-semibold mb-0">Kategori:</label>
      <select name="kategori" onchange="this.form.submit()" id="kategori" class="form-select form-select-sm">
        <option value="">Semua</option>
        <?php
        $kategoriList = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
        while ($kat = mysqli_fetch_assoc($kategoriList)) {
          $selected = isset($_GET['kategori']) && $_GET['kategori'] == $kat['id'] ? 'selected' : '';
          echo "<option value='{$kat['id']}' $selected>{$kat['name']}</option>";
        }
        ?>
      </select>
    </form>
  </div>

  <!-- Produk -->
  <?php
  $current_kategori = '';
  while ($p = mysqli_fetch_assoc($produk)) {
    if ($current_kategori != $p['category']) {
      if ($current_kategori != '') echo '</div>';
      $current_kategori = $p['category'];
      echo "<h4 class='mt-5 mb-3 text-primary border-bottom pb-2'>🍽️ {$current_kategori}</h4><div class='row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4'>";
    }
    ?>
    <div class="col">
      <div class="card h-100 shadow-sm text-center">
        <img src="uploads/<?= $p['image'] ?>" class="card-img-top">
        <div class="card-body">
          <h5 class="card-title"><?= $p['name'] ?></h5>
          <p class="fw-bold">Rp<?= number_format($p['price']) ?></p>
          <button class="btn btn-success btn-sm pesan-btn w-100" data-id="<?= $p['id'] ?>">Pesan</button>
        </div>
      </div>
    </div>
  <?php } ?>
  </div>

</div>

<script>
document.querySelectorAll('.pesan-btn').forEach(btn => {
  btn.addEventListener('click', function () {
    const productId = this.dataset.id;
    fetch('add_to_cart.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'product_id=' + encodeURIComponent(productId)
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        showToast('✅ Produk ditambahkan ke keranjang!');
      }
    });
  });
});

function showToast(msg) {
  const toast = document.createElement('div');
  toast.className = 'toast-message';
  toast.innerText = msg;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 2000);
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
