<?php
include 'data/koneksi.php';
session_start();

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); 
} else {
    $error_message = ''; 
}

if (isset($_POST['save'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $propinsi = $_POST['provinsi'];
    $kabupaten_kota = $_POST['kabupaten_kota'];
    $kecamatan = $_POST['kecamatan'];
    $kelurahan = $_POST['kelurahan'];
    $email = $_POST['email'];
    $no_tlp = $_POST['no_tlp'];

    // Perbarui data jika sudah ada di database
    $query = mysqli_query($koneksi, "SELECT * FROM identitas_usaha LIMIT 1");
    if (mysqli_num_rows($query) > 0) {
        mysqli_query($koneksi, "UPDATE identitas_usaha SET 
            nama='$nama', 
            alamat='$alamat', 
            propinsi='$propinsi', 
            kabupaten_kota='$kabupaten_kota', 
            kecamatan='$kecamatan', 
            kelurahan='$kelurahan', 
            email='$email', 
            no_tlp='$no_tlp' 
            WHERE id=1");
    } else {
        mysqli_query($koneksi, "INSERT INTO identitas_usaha (nama,alamat,propinsi,kabupaten_kota,kecamatan,kelurahan,email,no_tlp) VALUES ('$nama', '$alamat', '$propinsi','$kabupaten_kota', '$kecamatan', '$kelurahan', '$email', '$no_tlp')");
    }
}

// Ambil data untuk ditampilkan di formulir
$query = mysqli_query($koneksi, "SELECT * FROM identitas_usaha LIMIT 1");
$row = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php $pageTitle = "Identitas Usaha"; require_once __DIR__ . "/layouts/head.php"; ?>
</head>
<body class="sb-nav-fixed">
    <?php require_once __DIR__ . "/layouts/navbar.php"; ?>
    <div id="layoutSidenav">
        <?php require_once __DIR__ . "/layouts/sidebar.php"; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <?php if (!empty($error_message)): ?>
                        <div class="mt-2 alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($error_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <h1 class="mt-4">Identitas Usaha</h1>
                    <br>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Data Identitas Usaha untuk Invoice</h5>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="mb-3">
                                    <label for="nama_usaha" class="form-label">Nama</label>
                                    <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($row['nama'] ?? '') ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat_usaha" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" name="alamat" value="<?= htmlspecialchars($row['alamat'] ?? '') ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="provinsi" class="form-label">Provinsi</label>
                                    <input type="text" class="form-control" name="provinsi" value="<?= htmlspecialchars($row['propinsi'] ?? '') ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kabupaten_kota" class="form-label">Kota</label>
                                    <input type="text" class="form-control" name="kabupaten_kota" value="<?= htmlspecialchars($row['kabupaten_kota'] ?? '') ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kecamatan" class="form-label">Kecamatan</label>
                                    <input type="text" class="form-control" name="kecamatan" value="<?= htmlspecialchars($row['kecamatan'] ?? '') ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kelurahan" class="form-label">Kelurahan</label>
                                    <input type="text" class="form-control" name="kelurahan" value="<?= htmlspecialchars($row['kelurahan'] ?? '') ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" name="email" value="<?= htmlspecialchars($row['email'] ?? '') ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="no_tlp" class="form-label">No. Tlp</label>
                                    <input type="text" class="form-control" name="no_tlp" value="<?= htmlspecialchars($row['no_tlp'] ?? '') ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary" name="save">Simpan</button>
                            </form>
                        </div>
                    </div>

                </div>
            </main>
            <?php require_once __DIR__ . "/layouts/footer.php"; ?>
        </div>
    </div>
    <?php require_once __DIR__ . "/layouts/script.php"; ?>
</body>
</html>
