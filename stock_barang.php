<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['save_data_barang'])) {
    $id = $_POST['id'] ?? null; 
    $nama = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $foto = $_FILES['foto']['name'];

    // Upload Foto
    if ($foto) {
        move_uploaded_file($_FILES['foto']['tmp_name'], "images/$foto");
        $query = $id ? 
            "UPDATE stok_barang SET nama_barang = '$nama', kategori = '$kategori', stok = '$stok', harga = '$harga', foto = '$foto' WHERE id = $id" : 
            "INSERT INTO stok_barang (nama_barang, kategori, stok, harga, foto) VALUES ('$nama', '$kategori', '$stok', '$harga', '$foto')";
    } else {
        if ($id) {
            $query = "UPDATE stok_barang SET nama_barang = '$nama', kategori = '$kategori', stok = '$stok', harga = '$harga' WHERE id = $id";
        } else {
            $query = "INSERT INTO stok_barang (nama_barang, kategori, stok, harga) VALUES ('$nama', '$kategori', '$stok', '$harga')";
        }
    }
    mysqli_query($koneksi, $query);
}

// Apus Data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Hapus entri di tabel pembelian yang terkait
    mysqli_query($koneksi, "DELETE FROM pembelian WHERE id_barang='$id'");

    // Hapus dari tabel stok_barang
    mysqli_query($koneksi, "DELETE FROM stok_barang WHERE id='$id'");
}


$result = mysqli_query($koneksi, "SELECT * FROM stok_barang");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Edit Stock Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="sweetalert2.min.css">
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.min.js"></script>

</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-secondary">
        <a class="navbar-brand ps-3" href="index.php">Edit Stock Barang</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Main</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link active" href="stock_barang.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Stock Barang
                        </a>
                        <a class="nav-link" href="pembelian.php">
                            <div class="sb-nav-link-icon"><i class="fa-sharp fa-solid fa-inbox"></i></div>
                            Pembelian Barang
                        </a>
                        <a class="nav-link" href="identitas_usaha.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-handshake"></i></div>
                            Identitas Usaha
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <h6><?= $_SESSION['login']; ?></h6>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Stock Barang</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="fa-solid fa-circle-plus"></i> Tambah Data
                            </button>
                            <button type="button" class="btn btn-warning" >
                                <a href="cetak_stock_barang.php" class="text-white" target="_blank" style="text-decoration: none;">Cetak PDF</a>
                            </button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Stock</th>
                                        <th>Harga</th>
                                        <th>Foto</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                                            <td><?= htmlspecialchars($row['kategori']); ?></td>
                                            <td><?= htmlspecialchars($row['stok']); ?></td>
                                            <td><?= htmlspecialchars($row['harga']); ?></td>
                                            <td><img src="images/<?= htmlspecialchars($row['foto']); ?>" width="100"></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm" onclick="editData(<?= $row['id']; ?>, '<?= htmlspecialchars($row['nama_barang']); ?>', '<?= htmlspecialchars($row['kategori']); ?>', <?= $row['stok']; ?>, <?= $row['harga']; ?>, '<?= htmlspecialchars($row['foto']); ?>')">Edit</button>
                                                <a href="#" class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $row['id']; ?>)">Delete</a>
                                                <button type="button" class="btn btn-primary btn-sm" onclick="lihatData('<?= htmlspecialchars($row['nama_barang']); ?>', '<?= htmlspecialchars($row['kategori']); ?>', <?= $row['stok']; ?>, <?= $row['harga']; ?>, '<?= htmlspecialchars($row['foto']); ?>')">
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
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2024</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
</body>

<!-- Modal View-->
<div class="modal fade " id="modalLihatData" tabindex="-1" aria-labelledby="modalLihatDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="modalLihatDataLabel">Detail Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="lihatNamaBarang" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" id="lihatNamaBarang" readonly>
                </div>
                <div class="mb-3">
                    <label for="lihatKategori" class="form-label">Kategori</label>
                    <input type="text" class="form-control" id="lihatKategori" readonly>
                </div>
                <div class="mb-3">
                    <label for="lihatStok" class="form-label">Stok</label>
                    <input type="text" class="form-control" id="lihatStok" readonly>
                </div>
                <div class="mb-3">
                    <label for="lihatHarga" class="form-label">Harga</label>
                    <input type="text" class="form-control" id="lihatHarga" readonly>
                </div>
                <div class="mb-3">
                    <label for="lihatFotoBarang" class="form-label">Foto Barang</label>
                    <img id="lihatFotoBarang" class="img-fluid" style="display: none;" alt="Foto Barang">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal View End-->

 <!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Barang</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id"> 
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" name="nama_barang" id="nama_barang" placeholder="masukkan nama barang" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" name="kategori" id="kategori" placeholder="masukkan deskripsi barang" required>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stock</label>
                        <input type="number" class="form-control" name="stok" id="stok" placeholder="masukkan stock barang" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" name="harga" id="harga" step="0.01" placeholder="masukkan harga barang" required>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Barang</label>
                        <input type="file" class="form-control" name="foto" id="foto">
                        <small>Leave blank to keep current photo.</small>
                        <div class="mt-2">
                            <img id="currentFoto" src="" width="100" style="display:none;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Keluar</button>
                    <button type="submit" class="btn btn-primary" name="save_data_barang">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function editData(id, nama_barang, kategori, stok, harga, foto) {
    document.getElementById('id').value = id;
    document.getElementById('nama_barang').value = nama_barang;
    document.getElementById('kategori').value = kategori;
    document.getElementById('stok').value = stok;
    document.getElementById('harga').value = harga;

    // Nampilin Foto Saat ini (sebelum di edit datanya)
    if (foto) {
        document.getElementById('currentFoto').src = 'images/' + foto;
        document.getElementById('currentFoto').style.display = 'block';
    } else {
        document.getElementById('currentFoto').style.display = 'none'; 
    }
    document.getElementById('foto').value = ''; 
    document.getElementById('staticBackdropLabel').innerText = 'Edit Barang';

    // Buka Modal
    var modal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
    modal.show();
}
function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "?delete=" + id;
        }
    });
}

function lihatData(nama_barang, kategori, stok, harga, foto) {
    document.getElementById('lihatNamaBarang').value = nama_barang;
    document.getElementById('lihatKategori').value = kategori;
    document.getElementById('lihatStok').value = stok;
    document.getElementById('lihatHarga').value = harga;

    const fotoElem = document.getElementById('lihatFotoBarang');
    if (foto) {
        fotoElem.src = 'images/' + foto;
        fotoElem.style.display = 'block';
    } else {
        fotoElem.style.display = 'none';
    }

    var modalLihatData = new bootstrap.Modal(document.getElementById('modalLihatData'));
    modalLihatData.show();
}

</script>
</html>
