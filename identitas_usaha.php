<?php
include 'data/koneksi.php';
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<?php 
                $pageTitle = "Data Supplier";
                require_once __DIR__ . "/layouts/head.php"; 
    ?>
<body class="sb-nav-fixed">
    <?php require_once __DIR__ . "/layouts/navbar.php"?>
        <div id="layoutSidenav">
            <?php require_once __DIR__ . "/layouts/sidebar.php"?>
                <div id="layoutSidenav_content">
                    <main>
                        <div class="container-fluid px-4">
                            <h1 class="mt-4">Identitas Usaha</h1>
                            <br>
                        </div>
                    </main>
                    <?php require_once __DIR__ ."/layouts/footer.php"; ?>  
                </div>
        </div>
    <?php require_once __DIR__ ."/layouts/script.php"; ?>
</body>
</html>
