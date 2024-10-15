<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Main</div>
                <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <a class="nav-link <?= ($current_page == 'stock_barang.php') ? 'active' : ''; ?>" href="stock_barang.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Stock Barang
                </a>
                <a class="nav-link <?= ($current_page == 'pembelian.php') ? 'active' : ''; ?>" href="pembelian.php">
                    <div class="sb-nav-link-icon"><i class="fa-sharp fa-solid fa-inbox"></i></div>
                    Pembelian Barang 
                </a>
                <a class="nav-link <?= ($current_page == 'identitas_usaha.php') ? 'active' : ''; ?>" href="identitas_usaha.php">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-handshake"></i></div>
                    Identitas Usaha
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?= ($current_page == 'profil.php') ? 'active' : ''; ?>" href="profil.php">My Profile</a>
                        <a class="nav-link <?= ($current_page == 'logout.php') ? 'active' : ''; ?>" href="logout.php">Logout</a>
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
