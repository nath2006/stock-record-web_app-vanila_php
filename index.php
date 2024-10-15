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
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard Toko Elektronik</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-secondary">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">Dashboard Toko Elektronik</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                        <div class="sb-sidenav-menu-heading">Main</div>
                        <a class="nav-link active" href="index.php">
                                <div class="sb-nav-link-icon "><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="stock_barang.php">
                                <div class="sb-nav-link-icon "><i class="fas fa-tachometer-alt"></i></div>
                                Stock Barang
                            </a>
                            <a class="nav-link" href="pembelian.php">
                                <div class="sb-nav-link-icon"><i class="fa-sharp fa-solid fa-inbox"></i></div>
                                Pembelian Barang 
                            </a>
                            <a class="nav-link" href="identitas_usaha.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-handshake"></i></i></div>
                                Identitas Usaha
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="profil.php">My Profile</a>
                                    <a class="nav-link" href="logout.php">Logout</a>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <h6><?= $_SESSION['login']; ?></h6>
                    </div>
                </nav>
            </div>

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
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2024</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Barang</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form enctype="multipart/form-data" method="post">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nim" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" name="namabarang" id="namabarang" placeholder="masukkan nama barang" required>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Deskripsi</label>
                    <input type="text" class="form-control" name="deskripsi" id="deskripsi" placeholder="masukkan deskripsi barang" required>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Stock</label>
                    <input type="number" class="form-control" name="stock" id="stock" required>
                </div>
                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar</label>
                    <input type="file" class="form-control" name="gambar" id="gambar" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Keluar</button>
                <button type="submit" class="btn btn-primary" name="addnewbarang">Simpan</button>
            </div>
        </form>
        </div>
    </div>
    </div>
</html>