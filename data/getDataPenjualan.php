<?php
include 'data/koneksi.php';

$dataPenjualanPerBulan = [];
for ($i = 1; $i <= 12; $i++) {
    $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM penjualan WHERE YEAR(tanggal) = YEAR(CURDATE()) AND MONTH(tanggal) = $i");
    $row = mysqli_fetch_assoc($query);
    $dataPenjualanPerBulan[] = $row['total'];
}

header('Content-Type: application/json');
echo json_encode($dataPenjualanPerBulan);
?>