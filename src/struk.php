
<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id'])) {
  echo "Transaksi tidak ditemukan."; exit;
}

$id = (int) $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT t.*, u.username, p.method FROM transactions t JOIN users u ON t.user_id = u.id JOIN payments p ON t.payment_id = p.id WHERE t.id = $id"));

if (!$data) {
  echo "Data tidak ditemukan."; exit;
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Struk #<?= $data['id'] ?></title>
    <style>
    .btn-back {
      display: block;
      margin: 15px auto 0;
      text-align: center;
      text-decoration: none;
      padding: 8px 20px;
      background: #6c757d;
      color: #fff;
      border-radius: 6px;
      width: fit-content;
    }
    
    @media print {
      .btn-back, .print-btn {
        display: none;
      }
    }
    
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f3f3f3;
      padding: 40px;
    }
    .struk-container {
      max-width: 400px;
      background: white;
      margin: auto;
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      padding: 30px;
    }
    h2 {
      text-align: center;
      color: #5e3c29;
      margin-bottom: 5px;
    }
    .sub-title {
      text-align: center;
      color: #888;
      margin-bottom: 20px;
      font-size: 14px;
    }
    .detail {
      font-size: 15px;
      margin: 10px 0;
    }
    .detail span {
      font-weight: bold;
      color: #333;
    }
    .total {
      font-size: 18px;
      margin-top: 20px;
      border-top: 1px dashed #ccc;
      padding-top: 15px;
      text-align: right;
      font-weight: bold;
    }
    .thanks {
      text-align: center;
      margin-top: 30px;
      color: #5e3c29;
      font-weight: 500;
    }
    .print-btn {
      display: block;
      margin: 25px auto 0;
      padding: 10px 25px;
      background: #198754;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    @media print {
      .print-btn {
        display: none;
      }
    }
    </style>
    </head>
    <body>
      
    <div class="struk-container">
      <h2>Cacao d’Ayu</h2>
      <div class="sub-title">Struk Transaksi</div>
      
      <div class="detail">📄 No. Transaksi: <span>#<?= $data['id'] ?></span></div>
      <div class="detail">👤 Pelanggan: <span><?= $data['username'] ?></span></div>
      <div class="detail">🕒 Tanggal: <span><?= date('d M Y H:i', strtotime($data['created_at'])) ?></span></div>
      <div class="detail">💳 Metode Bayar: <span><?= $data['method'] ?></span></div>
      <div class="detail">🍽️ Jenis Pesanan: <span><?= ucfirst($data['order_type']) ?></span></div>
      <div class="detail">📝 Catatan: <span><?= $data['note'] ?: '-' ?></span></div>
      
      <div class="total">Total: Rp<?= number_format($data['total']) ?></div>
      <div class="thanks">✅ Terima kasih! Pesanan Anda sedang diproses.</div>

      <button class="print-btn" onclick="window.print()">🖨️ Cetak Struk</button>
      <a href="laporan.php" class="btn-back">🔙 Kembali ke Laporan</a>
    </div>
  </body>
  </html>
