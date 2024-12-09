<?php
include "data/koneksi.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['restore'])) {
    // Menyimpan file backup yang diupload
    $fileName = $_FILES['backupFile']['tmp_name'];
    $fileType = pathinfo($_FILES['backupFile']['name'], PATHINFO_EXTENSION);

    // Validasi ekstensi file
    if ($fileType !== 'sql') {
        echo "File yang diupload harus berformat .sql";
    } else {
        // Membaca isi file backup
        $fileContent = file_get_contents($fileName);

        // Pisahkan perintah SQL berdasarkan tanda titik koma
        $queries = explode(';', $fileContent);

        // Nonaktifkan pengecekan foreign key
        $koneksi->query("SET foreign_key_checks = 0");

        // Drop existing tables
        foreach ($queries as $query) {
            if (strpos(trim($query), 'CREATE TABLE') === 0) {
                preg_match('/CREATE TABLE `([^`]*)`/', $query, $matches);
                if (isset($matches[1])) {
                    $table = $matches[1];
                    $koneksi->query("DROP TABLE IF EXISTS `$table`");
                }
            }
        }

        // Eksekusi perintah SQL dari file backup
        if ($koneksi->multi_query($fileContent)) {
            do {
                if ($result = $koneksi->store_result()) {
                    $result->free();
                }
            } while ($koneksi->next_result());
            echo "Restore berhasil!";
            header("Location: index.php");
        } else {
            echo "Error: " . $koneksi->error;
        }

        // Aktifkan kembali pengecekan foreign key
        $koneksi->query("SET foreign_key_checks = 1");
        $koneksi->close();
    }
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
                <h1 class="m-3 py-2">Backup Data</h1>
                <div class="card bg-light text-black d-flex flex-column m-3">
                    <div class="p-3">
                        <form method="POST" enctype="multipart/form-data">
                            <label for="backupFile" class="form-label">Masukan File Database Backup Anda</label>
                            <input class="form-control" type="file" id="backupFile" name="backupFile" >
                            <button type="submit" name="restore" class="btn btn-primary mt-3">Restore Database</button>
                        </form>
                    </div>
                </div>
            </main>
            <?php require_once __DIR__ . "/layouts/footer.php"; ?>
        </div>
    </div>
<?php require_once __DIR__ . "/layouts/footer.php"; ?>
</body>
<?php require_once __DIR__ . "/layouts/script.php"; ?>
</html>