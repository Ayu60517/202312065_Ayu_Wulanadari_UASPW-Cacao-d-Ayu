<?php
$host = "localhost"; // atau 127.0.0.1
$user = "root";
$pass = "";
$db   = "cafe_app";


$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
