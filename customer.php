<?php
include 'data/koneksi.php';
session_start();

if (isset($_POST['save'])) {
    $nama_customer = $_POST['nama'];
    $telepon_customer = $_POST['nomor_telepon'];
    $alamat_customer = $_POST['alamat'];
    mysqli_query($koneksi, "INSERT INTO customer (nama, nomor_telepon, alamat) VALUES ('$nama_customer', '$telepon_customer', '$alamat_customer')");
}

if (isset($_POST['edit'])) {
    $id = $_POST['id']; // Get the ID from the hidden input
    $nama_customer = $_POST['nama'];
    $telepon_customer = $_POST['nomor_telepon'];
    $alamat_customer = $_POST['alamat'];
    mysqli_query($koneksi, "UPDATE customer SET nama='$nama_customer', nomor_telepon='$telepon_customer', alamat='$alamat_customer' WHERE id='$id'");
}

$customer_result = mysqli_query($koneksi, "SELECT * FROM customer");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php $pageTitle = "Data Customer"; require_once __DIR__ . "/layouts/head.php"; ?>
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
                    <h1 class="mt-4">Data Customer</h1>
                    <br>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Data Customer</h5>
                            <a href="cetak_data_customer.php" target="_blank" class="btn btn-primary">Cetak PDF</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Nomor Telepon</th>
                                        <th>Alamat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($customer_result)): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['nama']); ?></td>
                                            <td><?= htmlspecialchars($row['nomor_telepon']); ?></td>
                                            <td><?= htmlspecialchars($row['alamat']); ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm" onclick="editData('<?= htmlspecialchars($row['id']); ?>', '<?= htmlspecialchars($row['nama']); ?>', '<?= htmlspecialchars($row['nomor_telepon']); ?>', '<?= htmlspecialchars($row['alamat']); ?>')">
                                                    Edit Data
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <?php require_once __DIR__ . "/layouts/footer.php"; ?>
        </div>
    </div>
    <?php require_once __DIR__ . "/layouts/script.php"; ?>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Data Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="formEdit">
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-3">
                            <label for="editNama_customer" class="form-label">Nama Customer:</label>
                            <input type="text" class="form-control" name="nama" id="editNama_customer" required>
                        </div>
                        <div class="mb-3">
                            <label for="editNomor_telepon" class="form-label">Nomor Telepon:</label>
                            <input type="text" class="form-control" name="nomor_telepon" id="editNomor_telepon" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAlamat" class="form-label">Alamat:</label>
                            <input type="text" class="form-control" name="alamat" id="editAlamat" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="edit">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editData(id, nama, telepon, alamat) {
            document.getElementById('editId').value = id;
            document.getElementById('editNama_customer').value = nama;
            document.getElementById('editNomor_telepon').value = telepon;
            document.getElementById('editAlamat').value = alamat;
            const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
            modal.show();
        }
    </script>
</body>
</html>
