<?php
session_start();
include 'koneksi.php';
$user_id = $_SESSION['user']['id'];

if (isset($_GET['add'])) {
  $pid = $_GET['add'];
  mysqli_query($conn, "INSERT INTO carts (user_id, product_id) VALUES ($user_id, $pid)");
}

if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM carts WHERE id=$id AND user_id=$user_id");
}

$data = mysqli_query($conn, "
  SELECT c.id, p.name, p.price
  FROM carts c
  JOIN products p ON c.product_id = p.id
  WHERE c.user_id = $user_id
");

$total = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Keranjang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container-keranjang {
      max-width: 700px;
      margin: auto;
    }
    @media (max-width: 576px) {
      .table th, .table td {
        font-size: 14px;
        padding: 0.5rem;
      }
    }
  </style>
</head>
<body>

<?php include 'navbar_user.php'; ?>

<div class="container-fluid px-3 py-4 mt-4 container-keranjang">
  <h3 class="mb-4 text-center">🛒 Keranjang Belanja</h3>

  <div class="table-responsive">
    <table class="table table-bordered align-middle bg-white shadow-sm">
      <thead class="table-light">
        <tr>
          <th>Produk</th>
          <th>Harga</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($item = mysqli_fetch_assoc($data)) {
          $total += $item['price'];
        ?>
        <tr>
          <td><?= $item['name'] ?></td>
          <td>Rp<?= number_format($item['price']) ?></td>
          <td><a href="?delete=<?= $item['id'] ?>" class="btn btn-sm btn-danger">Hapus</a></td>
        </tr>
        <?php } ?>
        <tr class="fw-bold bg-light">
          <td>Total</td>
          <td colspan="2">Rp<?= number_format($total) ?></td>
        </tr>
      </tbody>
    </table>
  </div>

  <form method="post" action="checkout.php" class="bg-white shadow-sm p-4 rounded mt-4">
    <input type="hidden" name="total" value="<?= $total ?>">

    <div class="mb-3">
      <label class="form-label">Jenis Pesanan</label>
      <select name="order_type" id="order_type" class="form-select" required>
        <option value="dine-in">Dine-In 🍽️</option>
        <option value="takeaway">Takeaway 🥡</option>
        <option value="delivery">Delivery 🚚</option>
      </select>
    </div>

    <div class="mb-3" id="alamatBox" style="display:none;">
      <label class="form-label">Alamat</label>
      <input type="text" name="alamat" class="form-control" placeholder="Masukkan alamat pengantaran">
    </div>

    <div class="mb-3">
      <label class="form-label">Metode Pembayaran</label>
      <select name="payment_id" id="payment_id" class="form-select" required>
        <option value="1">Cash 💵</option>
        <option value="2">QRIS 📱</option>
        <option value="3">Transfer Bank 🏦</option>
      </select>
    </div>

    <div class="mb-3" id="pembayaranBox" style="display:none;">
      <label class="form-label">QR/Transfer Tujuan:</label>
      <div id="qrisImg" style="display:none;">
        <img src="assets/qris.png" class="img-fluid rounded" style="max-width:200px;">
      </div>
      <div id="tfInfo" style="display:none;">
        <p class="mt-2 mb-0"><strong>Transfer ke:</strong></p>
        <small>BRI - 123456789 a.n. Cacao d’Ayu</small>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Catatan / Nomor Meja</label>
      <input type="text" name="note" class="form-control" placeholder="Contoh: Meja 5 atau 'tanpa gula'">
    </div>

    <button class="btn btn-success w-100">Checkout</button>
  </form>

  <div class="text-center mt-3">
    <a href="home.php" class="btn btn-secondary">← Kembali</a>
  </div>
</div>

<script>
const orderType = document.getElementById('order_type');
const alamatBox = document.getElementById('alamatBox');
const paymentMethod = document.getElementById('payment_id');
const pembayaranBox = document.getElementById('pembayaranBox');
const qrisImg = document.getElementById('qrisImg');
const tfInfo = document.getElementById('tfInfo');

orderType.addEventListener('change', () => {
  alamatBox.style.display = (orderType.value === 'takeaway' || orderType.value === 'delivery') ? 'block' : 'none';
});

paymentMethod.addEventListener('change', () => {
  const val = paymentMethod.value;
  pembayaranBox.style.display = (val == 2 || val == 3) ? 'block' : 'none';
  qrisImg.style.display = (val == 2) ? 'block' : 'none';
  tfInfo.style.display = (val == 3) ? 'block' : 'none';
});
</script>
</body>
</html>
