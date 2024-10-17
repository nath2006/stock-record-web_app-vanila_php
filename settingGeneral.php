<?php
include 'data/koneksi.php';
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Proses form
if (isset($_POST['save'])) {
    $userId = $_SESSION['user_id']; 
    $locale = $_POST['id_barang'];

    $stmt = $koneksi->prepare("SELECT * FROM user_settings WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $updateStmt = $koneksi->prepare("UPDATE user_settings SET locale = ? WHERE user_id = ?");
        $updateStmt->bind_param("si", $locale, $userId);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        $insertStmt = $koneksi->prepare("INSERT INTO user_settings (user_id, locale) VALUES (?, ?)");
        $insertStmt->bind_param("is", $userId, $locale);
        $insertStmt->execute();
        $insertStmt->close();
    }

    $stmt->close();
    echo "<script>alert('Pengaturan berhasil disimpan.');</script>";
}
?>

?>
<!DOCTYPE html>
<html lang="en">
<?php 
    $pageTitle = "Pengaturan Umum";
    require_once __DIR__ . "/layouts/head.php"; 
?>
<body class="sb-nav-fixed">
    <?php require_once __DIR__ . "/layouts/navbar.php"?>
    <div id="layoutSidenav">
        <?php require_once __DIR__ . "/layouts/sidebar.php"?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Pengaturan Umum</h1>
                    <br>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Pengaturan Format Uang</h5>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="mb-3">
                                    <label for="id_barang" class="form-label">Pilih Format:</label>
                                    <select name="id_barang" class="form-select" required>
                                        <option value="idn">Rupiah</option>
                                        <option value="en">US Dollar</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary" name="save">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <?php require_once __DIR__ ."/layouts/footer.php"; ?>  
        </div>
    </div>
    <?php require_once __DIR__ ."/layouts/script.php"; ?>
</body>
</html>
