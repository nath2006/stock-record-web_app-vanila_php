<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sertakan koneksi database
include 'data/koneksi.php';

// Sertakan TCPDF
require_once('modules/tcpdf/tcpdf.php');

// Buat dokumen PDF baru
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set informasi dokumen
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Daftar Penjualan Barang');
$pdf->SetSubject('Daftar Penjualan Barang');
$pdf->SetKeywords('TCPDF, PDF, penjualan, barang');

// Tambahkan halaman
$pdf->AddPage();

// Ambil data penjualan dari database
$penjualan_result = mysqli_query($koneksi, "SELECT p.*, sb.nama_barang, c.nama AS customer_nama FROM penjualan p JOIN stok_barang sb ON p.id_barang = sb.id JOIN customer c ON p.id_customer = c.id");

// Siapkan konten untuk dicetak
$html = <<<EOD
<h1>Daftar Penjualan Barang</h1>
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Barang</th>
            <th>Jumlah</th>
            <th>Customer</th>
        </tr>
    </thead>
    <tbody>
EOD;

while ($row = mysqli_fetch_assoc($penjualan_result)) {
    $html .= <<<EOD
        <tr>
            <td>{$row['tanggal']}</td>
            <td>{$row['nama_barang']}</td>
            <td>{$row['jumlah']}</td>
            <td>{$row['customer_nama']}</td>
        </tr>
EOD;
}

$html .= <<<EOD
    </tbody>
</table>
EOD;

// Cetak konten HTML
$pdf->writeHTML($html, true, false, true, false, '');

// Close dan output PDF
$pdf->Output('Penjualan_Barang.pdf', 'I'); // 'I' untuk tampilan inline, 'D' untuk download
?>
