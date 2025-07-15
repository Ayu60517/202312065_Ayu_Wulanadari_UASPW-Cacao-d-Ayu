<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user']['id'];
  $total = $_POST['total'];
  $order_type = $_POST['order_type'];
  $payment_id = $_POST['payment_id'];
  $note = mysqli_real_escape_string($conn, $_POST['note']);

  mysqli_query($conn, "INSERT INTO transactions (user_id, payment_id, order_type, total, note)VALUES ($user_id, $payment_id, '$order_type', $total, '$note')");

  mysqli_query($conn, "DELETE FROM carts WHERE user_id = $user_id");
  
  header("Location: thankyou.php");
  exit;
}
?>
