<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Main</div>
                <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                    <div class="sb-nav-link-icon"><i class="fa fa-house"></i></div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading">Inventory</div>
                <a class="nav-link <?= ($current_page == 'stock_barang.php') ? 'active' : ''; ?>" href="stock_barang.php">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
                    Stock Barang
                </a>
                <a class="nav-link <?= ($current_page == 'pembelian.php') ? 'active' : ''; ?>" href="pembelian.php">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                    Pembelian Barang 
                </a>
                <a class="nav-link <?= ($current_page == 'penjualan.php') ? 'active' : ''; ?>" href="penjualan.php">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-cash-register"></i></div>
                    Penjualan Barang
                </a>
                <div class="sb-sidenav-menu-heading">Customer</div>
                <a class="nav-link <?= ($current_page == 'customer.php') ? 'active' : ''; ?>" href="customer.php">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-user"></i></div>
                    Data Customer
                </a>
                <div class="sb-sidenav-menu-heading">Supplier</div>
                <a class="nav-link <?= ($current_page == 'data_suplier.php') ? 'active' : ''; ?>" href="data_suplier.php">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-handshake"></i></div>
                    Data Supplier
                </a>
                <div class="sb-sidenav-menu-heading">Setting</div>
                <a class="nav-link <?= ($current_page == 'settingGeneral.php') ? 'active' : ''; ?>" href="settingGeneral.php">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                    Pengaturan
                </a>
                <a class="nav-link <?= ($current_page == 'identitas_usaha.php') ? 'active' : ''; ?>" href="identitas_usaha.php">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-address-card"></i></div>
                    Identitas
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <h6><?= $_SESSION['username']; ?></h6>
        </div>
    </nav>
</div>
