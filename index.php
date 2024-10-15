<?php
include 'koneksi.php';
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
// Query untuk mendapatkan jumlah data 'identitas usaha'
$resultUsaha = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM identitas_usaha");
$countUsaha = mysqli_fetch_assoc($resultUsaha)['total'];

//Query untuk mendapatkan jumlah data 'stock barang'
$resultDataBarang = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM stok_barang");
$countDataBarang = mysqli_fetch_assoc($resultDataBarang)['total'];

?>


<!DOCTYPE html>
<html lang="en">
    <?php 
        $pageTitle = "Dashboard Toko Elektronik";
        require_once __DIR__ . "/layouts/head.php"; 
    ?>
    <body class="sb-nav-fixed">
        <?php require_once __DIR__ . "/layouts/navbar.php"?>
            <div id="layoutSidenav">
                <?php require_once __DIR__ . "/layouts/sidebar.php"?>
                    <div id="layoutSidenav_content">
                        <main>
                            <!-- <div class="container-fluid px-4">
                                <h1 class="mt-4">Dashboard Toko Elektronik</h1>
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Hallo admin</h4>
                                                <p class="card-text">Selamat datang di dashboard perusahaan ABC</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-none d-md-block">
                                        <img src="assets/img/statistics.svg" class="img-fluid" alt="Illustration">
                                    </div>
                                </div>
                            </div> -->
                            <!-- Main page content-->
                            <div class="container-xl px-4 mt-5">
                                <!-- Custom page header alternative example-->
                                <div class="d-flex justify-content-between align-items-sm-center flex-column flex-sm-row mb-4">
                                    <div class="me-4 mb-3 mb-sm-0">
                                        <h3 class="mb-0">Dashboard</h3>
                                        <div class="small">
                                            <span class="fw-500 text-primary"><?= date("l")  ?></span>
                                            &middot; <?= date(" j M Y")  ?>
                                        </div>
                                    </div>
                                    <!-- Date range picker example-->
                                </div>
                                <!-- Illustration dashboard card example-->
                                <div class="card card-waves mb-4 mt-5">
                                    <div class="card-body p-5">
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col">
                                                <h2 class="text-primary">Selamat Datang, <?= $_SESSION['username'] ?>!</h2>
                                                <p class="text-gray-700">Lihat, Simpan, Edit data barang, pembelian barang, penjulan barang</p>
                                            </div>
                                            <div class="col d-none d-lg-block mt-xxl-n4"><img class="img-fluid px-xl-4 mt-xxl-n5" src="assets/img/statistics.svg" /></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-3 col-md-6 w-50">
                                        <div class="card bg-primary text-white mb-4 h-100">
                                            <div class="card-body d-flex flex-column">
                                                <h5>Total Data Identitas Usaha: <?= $countUsaha; ?></h5>
                                            </div>
                                            <div class="card-footer d-flex align-items-center justify-content-between mt-auto">
                                                <a class="small text-white stretched-link" href="identitas_usaha.php" style="text-decoration: none;">View Details</a>
                                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 w-50">
                                        <div class="card bg-warning text-white mb-4 h-100">
                                            <div class="card-body d-flex flex-column">
                                                <h5>Total Data Barang: <?= $countDataBarang; ?></h5>
                                            </div>
                                            <div class="card-footer d-flex align-items-center justify-content-between mt-auto">
                                                <a class="small text-white stretched-link" href="stock_barang.php" style="text-decoration: none;">View Details</a>
                                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </main>
                        <?php require_once __DIR__ ."/layouts/footer.php"?>
                    </div>
        </div>
        <?php require_once __DIR__ ."/layouts/script.php"; ?>
    </body>
</html>