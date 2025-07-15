<?php
session_start();
header('Content-Type: application/json');
include 'koneksi.php';

if (!isset($_SESSION['user']) || !isset($_POST['product_id'])) {
  http_response_code(403);
  echo json_encode(['success' => false, 'message' => 'Unauthorized']);
  exit;
}

$user_id = $_SESSION['user']['id'];
$product_id = (int) $_POST['product_id'];

// Cek apakah sudah ada di keranjang
$cek = $conn->prepare("SELECT id FROM carts WHERE user_id = ? AND product_id = ?");
$cek->bind_param("ii", $user_id, $product_id);
$cek->execute();
$res = $cek->get_result();

if ($res->num_rows === 0) {
  $stmt = $conn->prepare("INSERT INTO carts (user_id, product_id) VALUES (?, ?)");
  $stmt->bind_param("ii", $user_id, $product_id);
  $stmt->execute();
}

echo json_encode(['success' => true]);
?>
