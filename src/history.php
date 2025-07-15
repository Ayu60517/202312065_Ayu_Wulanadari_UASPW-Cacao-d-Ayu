<?php
session_start();
include 'koneksi.php';
$user_id = $_SESSION['user']['id'];

$data = mysqli_query($conn, "SELECT t.id, t.total, t.order_type, t.note, p.method, t.created_at FROM transactions t JOIN payments p ON t.payment_id = p.id WHERE t.user_id = $user_id ORDER BY t.created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Riwayat Transaksi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container-riwayat {
      max-width: 900px;
      margin: auto;
    }
    @media (max-width: 576px) {
      .table th, .table td {
        font-size: 13px;
        padding: 6px;
      }
    }
  </style>
</head>
<body class="bg-light">

<?php include 'navbar_user.php'; ?>

<div class="container-fluid py-4 px-3 mt-3 container-riwayat">
  <h3 class="mb-4 text-center">📜 Riwayat Pesanan</h3>

  <div class="table-responsive">
    <table class="table table-bordered table-hover bg-white shadow-sm">
      <thead class="table-light text-center">
        <tr>
          <th>ID</th>
          <th>Jenis</th>
          <th>Pembayaran</th>
          <th>Total</th>
          <th>Catatan</th>
          <th>Waktu</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($r = mysqli_fetch_assoc($data)) {
          $ikon = match($r['order_type']) {
            'dine-in' => '🍽️',
            'takeaway' => '🥡',
            'delivery' => '🚚',
            default => '❓'
          };
        ?>
        <tr>
          <td class="text-center"><?= $r['id'] ?></td>
          <td><?= $ikon . ' ' . ucfirst($r['order_type']) ?></td>
          <td><?= $r['method'] ?></td>
          <td>Rp<?= number_format($r['total']) ?></td>
          <td class="text-break"><?= $r['note'] ?></td>
          <td><?= date("d M Y H:i", strtotime($r['created_at'])) ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <div class="text-center mt-3">
    <a href="home.php" class="btn btn-secondary">← Kembali</a>
  </div>
</div>

</body>
</html>
