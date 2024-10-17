<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sertakan koneksi database
include 'data/koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT locale FROM user_settings WHERE user_id = '$user_id'";
$result = mysqli_query($koneksi, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    $locale = $user_data['locale'];
} else {
    $locale = 'idn';
}

// Sertakan TCPDF
require_once('modules/tcpdf/tcpdf.php');

// Buat dokumen PDF baru
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set informasi dokumen
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nath2006');
$pdf->SetTitle('Daftar Stock Barang');
$pdf->SetSubject('Daftar Stock Barang');
$pdf->SetKeywords('TCPDF, PDF, stock, barang');

// Tambahkan halaman
$pdf->AddPage();

// Ambil tanggal saat ini
$date = date('d-m-Y');

// Set konten untuk dicetak
$html = <<<EOD
<table width="100%" border="0">
    <tr>
        <td><h1>Daftar Stock Barang</h1></td>
        <td style="text-align: right;"><p><b>Date: $date</b></p></td>
    </tr>
</table>
<br>
<br>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th><h4>Nama Barang</h4></th>
            <th><h4>Kategori</h4></th>
            <th><h4>Stock</h4></th>
            <th><h4>Harga</h4></th>
            <th><h4>Foto</h4></th>
        </tr>
    </thead>
    <tbody>
EOD;

// Ambil data dari database
$result = mysqli_query($koneksi, "SELECT * FROM stok_barang");
while ($row = mysqli_fetch_assoc($result)) {
    // Tentukan harga sesuai locale
    if ($locale === 'idn') {
        $harga = 'Rp ' . number_format($row['harga'], 0, ',', '.');
    } else {
        $harga = '$' . number_format($row['harga'], 2, '.', ',');
    }
    
    // Path gambar
    $fotoPath = 'images/' . htmlspecialchars($row['foto']);
    // Cek apakah gambar ada
    $imgTag = file_exists($fotoPath) ? '<img src="' . $fotoPath . '" width="75" height="auto" />' : 'No Image';

    // Tambahkan baris ke dalam HTML
    $html .= '<tr>
                <td>' . htmlspecialchars($row['nama_barang']) . '</td>
                <td>' . htmlspecialchars($row['kategori']) . '</td>
                <td>' . htmlspecialchars($row['stok']) . '</td>
                <td>' . $harga . '</td>
                <td>' . $imgTag . '</td>
              </tr>';
}

$html .= '</tbody></table>';

// Cetak teks menggunakan writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// Tutup dan keluarkan dokumen PDF
$pdf->Output('Stock_Barang.pdf', 'I'); // 'I' untuk tampilan browser
?>
