<?php
include 'data/koneksi.php';
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['save_data_supplier'])) {
    $id = $_POST['id'] ?? null; 
    $nama = $_POST['nama_usaha'];
    $alamat = $_POST['alamat'];
    $kontak = $_POST['kontak'];

    if ($id) {
        // Edit data jika ID ada
        $query = "UPDATE data_supplier SET nama_usaha = '$nama', alamat = '$alamat', kontak = '$kontak' WHERE id = $id";
    } else {
        // Simpan data jika ID tidak ada
        $query = "INSERT INTO data_supplier (nama_usaha, alamat, kontak) VALUES ('$nama', '$alamat', '$kontak')";
    }
    mysqli_query($koneksi, $query);
}
$result = mysqli_query($koneksi, "SELECT * FROM data_supplier");
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
                            <h1 class="mt-4">Data Supplier</h1>
                            <br>
                            <div class="card mb-4">
                                <div class="card-header">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                        <i class="fa-solid fa-circle-plus"></i> Tambah Data
                                    </button>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nama Usaha</th>
                                                <th>Alamat</th>
                                                <th>Kontak</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['id']); ?></td>
                                                    <td><?= htmlspecialchars($row['nama_usaha']); ?></td>
                                                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                                                    <td><?= htmlspecialchars($row['kontak']); ?></td>
                                                    <td>
                                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="editData('<?= $row['id'] ?>', '<?= htmlspecialchars($row['nama_usaha']) ?>', '<?= htmlspecialchars($row['alamat']) ?>', '<?= htmlspecialchars($row['kontak']) ?>')">Edit</button>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </main>
                    <?php require_once __DIR__ ."/layouts/footer.php"; ?>  
                </div>
        </div>
    <?php require_once __DIR__ ."/layouts/script.php"; ?>
</body>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Identitas Usaha</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" /> 
                    <div class="mb-3">
                        <label for="nama_usaha" class="form-label">Nama Usaha</label>
                        <input type="text" class="form-control" name="nama_usaha" id="nama_usaha" placeholder="Masukkan nama usaha" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" id="alamat" placeholder="Masukkan alamat usaha" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="kontak" class="form-label">Kontak</label>
                        <input type="text" class="form-control" name="kontak" id="kontak" placeholder="Masukkan kontak usaha" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Keluar</button>
                    <button type="submit" class="btn btn-primary" name="save_data_supplier">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function editData(id, nama_usaha, alamat, kontak) {
        document.getElementById('id').value = id;
        document.getElementById('nama_usaha').value = nama_usaha;
        document.getElementById('alamat').value = alamat;
        document.getElementById('kontak').value = kontak;
    }
</script>
</html>
