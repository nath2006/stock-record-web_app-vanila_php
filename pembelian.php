<?php
include 'data/koneksi.php';
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Simpan pembelian
if (isset($_POST['save'])) {
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];
    
    // Insert new purchase with the current date
    mysqli_query($koneksi, "INSERT INTO pembelian (id_barang, jumlah, tanggal) VALUES ('$id_barang', '$jumlah', NOW())");
    mysqli_query($koneksi, "UPDATE stok_barang SET stok = stok + $jumlah WHERE id='$id_barang'");
}

// Edit pembelian
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];

    mysqli_query($koneksi, "UPDATE pembelian SET id_barang='$id_barang', jumlah='$jumlah', tanggal=NOW() WHERE id='$id'");
}

// Hapus pembelian
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($koneksi, "DELETE FROM pembelian WHERE id='$id'");
}

$pembelian_result = mysqli_query($koneksi, "SELECT p.*, sb.nama_barang, sb.kategori, sb.stok FROM pembelian p JOIN stok_barang sb ON p.id_barang = sb.id");

// Ambil data barang untuk dropdown
$barang_result = mysqli_query($koneksi, "SELECT * FROM stok_barang");

?>

<!DOCTYPE html>
<html lang="en">
    <?php 
                $pageTitle = "Pembelian Barang";
                require_once __DIR__ . "/layouts/head.php"; 
    ?>
<body class="sb-nav-fixed">
    <?php require_once __DIR__ . "/layouts/navbar.php"?>
        <div id="layoutSidenav">
            <?php require_once __DIR__ . "/layouts/sidebar.php"?>
                <div id="layoutSidenav_content">
                    <main>
                        <div class="container-fluid px-4">
                            <h1 class="mt-4">Pembelian Barang</h1>
                            <br>
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5>Masukan Data Pembelian Barang</h5>
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
                                        <button type="submit" class="btn btn-primary" name="save">Simpan</button>
                                    </form>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5>Data Pembelian</h5>
                                    <a href="cetak_pembelian.php" target="_blank" class="btn btn-primary">Cetak PDF</a>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th> <!-- Update column header -->
                                                <th>Barang</th>
                                                <th>Jumlah</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($pembelian_result)): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['tanggal']); ?></td>
                                                    <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                                                    <td><?= htmlspecialchars($row['jumlah']); ?></td>
                                                    <td>
                                                        <button class="btn btn-warning btn-sm" onclick="editData(<?= $row['id']; ?>, <?= $row['id_barang']; ?>, <?= $row['jumlah']; ?>)">
                                                            Edit Data
                                                        </button>
                                                        <a href="?delete=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">
                                                            Delete Data
                                                        </a>
                                                        <button type="button" class="btn btn-primary btn-sm" onclick="lihatData('<?= htmlspecialchars($row['tanggal']) ?? ''; ?>', '<?= htmlspecialchars($row['kategori']) ?? ''; ?>', '<?= htmlspecialchars($row['jumlah']) ?? ''; ?>')">
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
                    <?php require_once __DIR__ ."/layouts/footer.php"; ?>                               
                </div>
        </div>
    <?php require_once __DIR__ ."/layouts/script.php"; ?>

<!-- Modal View-->
<div class="modal fade " id="modalLihatData" tabindex="-1" aria-labelledby="modalLihatDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="modalLihatDataLabel">Detail Pemebelian Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="lihatNamaBarang" class="form-label">Tanggal Pembelian</label>
                    <input type="text" class="form-control" id="tanggalPembelian" readonly>
                </div>
                <div class="mb-3">
                    <label for="lihatKategori" class="form-label">Kategori Barang</label>
                    <input type="text" class="form-control" id="lihatKategori" readonly>
                </div>
                <div class="mb-3">
                    <label for="lihatStok" class="form-label">Jumlah Pembelian Barang</label>
                    <input type="text" class="form-control" id="lihatPembelianBarang" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal View End-->

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Pembelian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-3">
                            <label for="edit_id_barang" class="form-label">Barang:</label>
                            <select name="id_barang" id="edit_id_barang" class="form-select" required>
                                <?php
                                mysqli_data_seek($barang_result, 0); // Reset pointer untuk fetch ulang
                                while ($row = mysqli_fetch_assoc($barang_result)): ?>
                                    <option value="<?= $row['id']; ?>"><?= $row['nama_barang']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_jumlah" class="form-label">Jumlah:</label>
                            <input type="number" class="form-control" name="jumlah" id="edit_jumlah" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editData(id, id_barang, jumlah) {
            document.getElementById('editId').value = id;
            document.getElementById('edit_id_barang').value = id_barang;
            document.getElementById('edit_jumlah').value = jumlah;
            var myModal = new bootstrap.Modal(document.getElementById('editModal'), {
                keyboard: false
            });
            myModal.show();
        }
        function lihatData(tanggalPembelian, kategoriBarang, jumlahPembelian) {
            document.getElementById('tanggalPembelian').value = tanggalPembelian;
            document.getElementById('lihatKategori').value = kategoriBarang;
            document.getElementById('lihatPembelianBarang').value = jumlahPembelian;

            var modalLihatData = new bootstrap.Modal(document.getElementById('modalLihatData'));
            modalLihatData.show();
        }

    </script>
</body>
</html>
