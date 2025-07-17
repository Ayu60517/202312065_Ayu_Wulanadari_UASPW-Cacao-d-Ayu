<?php
session_start();

// Cek apakah sudah login
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

// Arahkan berdasarkan role
if ($_SESSION['role'] === 'admin') {
    header("Location: dashboard.php");
    exit;
} else {
    header("Location: home.php");
    exit;
}
