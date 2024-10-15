<?php
include 'koneksi.php';
session_start();
include 'koneksi.php';


if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); 
} else {
    $error_message = ''; 
}

// Simpan penjualan
if (isset($_POST['save'])) {
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];
    $nama_customer = $_POST['nama_customer'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $alamat = $_POST['alamat'];

    // Ambil data stock sekarang
    $stock_result = mysqli_query($koneksi, "SELECT stok FROM stok_barang WHERE id='$id_barang'");
    $stock_row = mysqli_fetch_assoc($stock_result);
    $current_stock = $stock_row['stok'];

    if ($jumlah > $current_stock) {
        $_SESSION['error_message'] = "Stock Tidak Mencukupi Saat ini"; 
    } else {
        mysqli_query($koneksi, "INSERT INTO customer (nama, nomor_telepon, alamat) VALUES ('$nama_customer', '$nomor_telepon', '$alamat')");
        $id_customer = mysqli_insert_id($koneksi);
        mysqli_query($koneksi, "INSERT INTO penjualan (id_barang, jumlah, id_customer, tanggal) VALUES ('$id_barang', '$jumlah', '$id_customer', NOW())");
        mysqli_query($koneksi, "UPDATE stok_barang SET stok = stok - $jumlah WHERE id='$id_barang'");
    }
}

// Hapus penjualan
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($koneksi, "DELETE FROM penjualan WHERE id='$id'");
}

// Edit penjualan
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];
    $nama_customer = $_POST['nama_customer'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $alamat = $_POST['alamat'];

    // Update customer information
    mysqli_query($koneksi, "UPDATE customer SET nama='$nama_customer', nomor_telepon='$nomor_telepon', alamat='$alamat' WHERE id=(SELECT id_customer FROM penjualan WHERE id='$id')");

    // Update sale information
    mysqli_query($koneksi, "UPDATE penjualan SET id_barang='$id_barang', jumlah='$jumlah', tanggal=NOW() WHERE id='$id'");
}

// Ambil data penjualan
$penjualan_result = mysqli_query($koneksi, "SELECT p.*, sb.nama_barang, c.nama AS customer_nama, c.nomor_telepon, c.alamat FROM penjualan p JOIN stok_barang sb ON p.id_barang = sb.id JOIN customer c ON p.id_customer = c.id");

// Ambil data barang untuk dropdown
$barang_result = mysqli_query($koneksi, "SELECT * FROM stok_barang");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php $pageTitle = "Penjualan Barang"; require_once __DIR__ . "/layouts/head.php"; ?>
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
                    <h1 class="mt-4">Penjualan Barang</h1>
                    <br>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Masukan Data Penjualan Barang</h5>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="mb-3">
                                    <label for="id_barang" class="form-label">Barang:</label>
                                    <select name="id_barang" class="form-select" required>
                                        <?php while ($row = mysqli_fetch_assoc($barang_result)): ?>
                                            <option value="<?= $row['id']; ?>"><?= $row['nama_barang']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah:</label>
                                    <input type="number" class="form-control" name="jumlah" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_customer" class="form-label">Nama Customer:</label>
                                    <input type="text" class="form-control" name="nama_customer" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nomor_telepon" class="form-label">Nomor Telepon:</label>
                                    <input type="text" class="form-control" name="nomor_telepon" required>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat:</label>
                                    <input type="text" class="form-control" name="alamat" required>
                                </div>
                                <button type="submit" class="btn btn-primary" name="save">Simpan</button>
                            </form>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Data Penjualan</h5>
                            <a href="cetak_penjualan.php" target="_blank" class="btn btn-primary">Cetak PDF</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Barang</th>
                                        <th>Jumlah</th>
                                        <th>Customer</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($penjualan_result)): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['tanggal']); ?></td>
                                            <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                                            <td><?= htmlspecialchars($row['jumlah']); ?></td>
                                            <td><?= htmlspecialchars($row['customer_nama']); ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm" onclick="editData(<?= $row['id']; ?>, <?= $row['id_barang']; ?>, <?= $row['jumlah']; ?>, '<?= htmlspecialchars($row['customer_nama']) ?>', '<?= htmlspecialchars($row['nomor_telepon']) ?>', '<?= htmlspecialchars($row['alamat']) ?>')">
                                                    Edit Data
                                                </button>
                                                <a href="?delete=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">
                                                    Hapus Data
                                                </a>
                                                <button type="button" class="btn btn-primary btn-sm" onclick="lihatData('<?= htmlspecialchars($row['tanggal']) ?>', '<?= htmlspecialchars($row['jumlah']) ?>', '<?= htmlspecialchars($row['customer_nama']) ?>', '<?= htmlspecialchars($row['nomor_telepon']) ?>', '<?= htmlspecialchars($row['alamat']) ?>')">
                                                    Lihat Data
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

    <!-- Modal View-->
    <div class="modal fade" id="modalLihatData" tabindex="-1" aria-labelledby="modalLihatDataLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLihatDataLabel">Detail Penjualan Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="lihatTanggalPenjualan" class="form-label">Tanggal Penjualan</label>
                        <input type="text" class="form-control" id="lihatTanggalPenjualan" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="lihatJumlah" class="form-label">Jumlah Barang Terjual</label>
                        <input type="text" class="form-control" id="lihatJumlah" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="lihatNamaCustomer" class="form-label">Nama Customer</label>
                        <input type="text" class="form-control" id="lihatNamaCustomer" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="lihatNomorTelepon" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" id="lihatNomorTelepon" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="lihatAlamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="lihatAlamat" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Penjualan Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="formEdit">
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-3">
                            <label for="editId_barang" class="form-label">Barang:</label>
                            <select name="id_barang" id="editId_barang" class="form-select" required>
                                <?php mysqli_data_seek($barang_result, 0); // Reset pointer ?>
                                <?php while ($row = mysqli_fetch_assoc($barang_result)): ?>
                                    <option value="<?= $row['id']; ?>"><?= $row['nama_barang']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editJumlah" class="form-label">Jumlah:</label>
                            <input type="number" class="form-control" name="jumlah" id="editJumlah" required>
                        </div>
                        <div class="mb-3">
                            <label for="editNama_customer" class="form-label">Nama Customer:</label>
                            <input type="text" class="form-control" name="nama_customer" id="editNama_customer" required>
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
        function lihatData(tanggal, jumlah, customer, telepon, alamat) {
            document.getElementById('lihatTanggalPenjualan').value = tanggal;
            document.getElementById('lihatJumlah').value = jumlah;
            document.getElementById('lihatNamaCustomer').value = customer;
            document.getElementById('lihatNomorTelepon').value = telepon;
            document.getElementById('lihatAlamat').value = alamat;
            const modal = new bootstrap.Modal(document.getElementById('modalLihatData'));
            modal.show();
        }

        function editData(id, id_barang, jumlah, customer, telepon, alamat) {
            document.getElementById('editId').value = id;
            document.getElementById('editId_barang').value = id_barang;
            document.getElementById('editJumlah').value = jumlah;
            document.getElementById('editNama_customer').value = customer;
            document.getElementById('editNomor_telepon').value = telepon;
            document.getElementById('editAlamat').value = alamat;
            const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
            modal.show();
        }
    </script>
</body>
</html>
