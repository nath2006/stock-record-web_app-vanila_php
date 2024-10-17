<?php
include 'data/koneksi.php';
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Query untuk mendapatkan jumlah data 'pembelian'
$resultPembelian = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pembelian");
$countPembelian = mysqli_fetch_assoc($resultPembelian)['total'];

// Query untuk mendapatkan jumlah data 'stock barang'
$resultDataBarang = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM stok_barang");
$countDataBarang = mysqli_fetch_assoc($resultDataBarang)['total'];

// Query untuk mendapatkan jumlah data 'penjualan'
$resultPenjualanBarang = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM penjualan");
$countPenjualanBarang = mysqli_fetch_assoc($resultPenjualanBarang)['total'];

// Query untuk mendapatkan jumlah data 'customer'
$resultCustomer = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM customer");
$countCustomer = mysqli_fetch_assoc($resultCustomer)['total'];

// Ambil data sale perbulan di tahun saat ini
$dataPenjualanPerBulan = [];
for ($i = 1; $i <= 12; $i++) {
    $query = mysqli_query($koneksi, "SELECT SUM(jumlah) as total FROM penjualan WHERE MONTH(tanggal) = $i AND YEAR(tanggal) = YEAR(CURDATE())");
    $row = mysqli_fetch_assoc($query);
    $dataPenjualanPerBulan[] = (int)$row['total'];
}
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
                            <div class="container-xl px-4 mt-5">
                                <div class="d-flex justify-content-between align-items-sm-center flex-column flex-sm-row mb-4">
                                    <div class="me-4 mb-3 mb-sm-0">
                                        <h3 class="mb-0">Dashboard</h3>
                                        <div class="small">
                                            <span class="fw-500 text-primary"><?= date("l")  ?></span>
                                            &middot; <?= date(" j M Y")  ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-waves mb-4 mt-5">
                                    <div class="card-body p-5">
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col">
                                                <h1 class="text-primary">Selamat Datang, <?= $_SESSION['username'] ?>!</h1>
                                                <p class="text-gray-700">Lihat, Simpan, Edit data barang, pembelian barang, penjulan barang</p>
                                            </div>
                                            <div class="col d-none d-lg-block mt-xxl-n4"><img class="img-fluid px-xl-4 mt-xxl-n5" src="assets/img/statistics.svg" /></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-3 col-md-6 pt-2">
                                        <div class="card bg-primary text-white mb-4 h-80">
                                            <div class="card-body d-flex flex-column">
                                                <h2><?= $countPembelian; ?></h2>
                                                <h6 class="text-card">Data Pembelian Barang</h6>
                                            </div>
                                            <div class="card-footer d-flex align-items-center justify-content-between mt-auto">
                                                <a class="small text-white stretched-link" href="pembelian.php" style="text-decoration: none;">View Details</a>
                                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 pt-2">
                                        <div class="card bg-warning text-white mb-4 h-80">
                                            <div class="card-body d-flex flex-column">
                                                <h2><?= $countDataBarang; ?></h2>
                                                <h6 class="text-card">Data Barang</h6>
                                            </div>
                                            <div class="card-footer d-flex align-items-center justify-content-between mt-auto">
                                                <a class="small text-white stretched-link" href="stock_barang.php" style="text-decoration: none;">View Details</a>
                                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 pt-2">
                                        <div class="card bg-success text-white mb-4 h-80">
                                            <div class="card-body d-flex flex-column">
                                                <h2><?= $countPenjualanBarang; ?></h2>
                                                <h6 class="text-card">Data Penjualan Barang</h6>
                                            </div>
                                            <div class="card-footer d-flex align-items-center justify-content-between mt-auto">
                                                <a class="small text-white stretched-link" href="penjualan.php" style="text-decoration: none;">View Details</a>
                                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 pt-2">
                                        <div class="card bg-info text-white mb-4 h-80">
                                            <div class="card-body d-flex flex-column">
                                                <h2><?= $countCustomer; ?></h2>
                                                <h6 class="text-card">Data Customer Barang</h6>
                                            </div>
                                            <div class="card-footer d-flex align-items-center justify-content-between mt-auto">
                                                <a class="small text-white stretched-link" href="customer.php" style="text-decoration: none;">View Details</a>
                                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 pt-2">
                                        <div class="card bg-white text-white mb-4 h-80">
                                            <div class="card-header text-primary">
                                                <i class="fas text-primary fa-chart-line me-1"></i>
                                                Penjualan Tahunan
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <canvas id="penjualanChart" width="400" height="200"></canvas>
                                            </div>
                                            <!-- <div class="card-footer d-flex align-items-center justify-content-between mt-auto">
                                                <a class="small text-primary stretched-link" href="penjualan.php" style="text-decoration: none;">View Details</a>
                                                <div class="small text-primary"><i class="fas fa-angle-right"></i></div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </main>
                        <?php require_once __DIR__ ."/layouts/footer.php"?>
                    </div>
        </div>
        <?php require_once __DIR__ ."/layouts/script.php"; ?>

        <script>
document.addEventListener("DOMContentLoaded", function() {
    let ctx = document.getElementById('penjualanChart').getContext('2d');
    const data = <?= json_encode($dataPenjualanPerBulan); ?>;
    const currentYear = new Date().getFullYear();
    let delayed;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            datasets: [{
                label: `Penjualan per Bulan (Tahun ${currentYear})`,
                data: data,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                borderWidth: 2,
                pointHoverRadius: 5
            }]
        },
        options: {
            animation: {
                onComplete: () => {
                    delayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default' && !delayed) {
                        delay = context.dataIndex * 200 + context.datasetIndex * 100;
                    }
                    return delay;
                }
            },
            tooltips: {
                enabled: true,
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var currentValue = dataset.data[tooltipItem.index];
                        return 'Penjualan: ' + currentValue;
                    }
                }
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>

        </script>

    </body>
</html>
