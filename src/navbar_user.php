<style>
  body {
    background: url('uploads/logo.png') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
  }

  nav.navbar {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
  }

  /* Tambahkan padding-top untuk mendorong konten ke bawah */
  .content-wrapper {
    padding-top: 80px;
  }
</style>

<nav class="navbar navbar-expand-lg navbar-dark px-4 py-3" style="background-color: #5e3c29;">
  <div class="container-fluid">
    <img src="uploads/bg.home().png" alt="Cacao d’Ayu" style="height: 40px; width: 40px; margin-right: 8px; object-fit: cover; border-radius: 50%;">

    <span style="font-weight: bold; color: #f5deb3">Cacao d’Ayu</span>

    <div class="collapse navbar-collapse" id="navbarUser">
      <ul class="navbar-nav me-auto">
        <!-- Navigasi lainnya -->
      </ul>
    </div>

    <!-- ✅ User Info -->
    <div class="d-flex align-items-center gap-2 text-white">
      <span>Halo, <?= $_SESSION['user']['username'] ?></span>
      <a href="cart.php" class="btn btn-outline-light btn-sm">🛒</a>
      <a href="history.php" class="btn btn-outline-light btn-sm">🧾</a>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="content-wrapper">
</div>
