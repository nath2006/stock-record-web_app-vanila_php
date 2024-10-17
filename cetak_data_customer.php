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
$pdf->SetTitle('Daftar Data Customer');
$pdf->SetSubject('Daftar Data Customer');
$pdf->SetKeywords('TCPDF, PDF, data, customer, barang');

// Tambahkan halaman
$pdf->AddPage();

// Ambil data penjualan dari database
$customer_result = mysqli_query($koneksi, "SELECT * FROM customer");

// Siapkan konten untuk dicetak
$html = <<<EOD
<h1>Daftar Data Customer</h1>
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Nama Customer</th>
            <th>Nomor Telepon</th>
            <th>Alamat</th>
        </tr>
    </thead>
    <tbody>
EOD;

while ($row = mysqli_fetch_assoc($customer_result)) {
    $html .= <<<EOD
        <tr>
            <td>{$row['nama']}</td>
            <td>{$row['nomor_telepon']}</td>
            <td>{$row['alamat']}</td>
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
$pdf->Output('Data_Customer.pdf', 'I'); // 'I' untuk tampilan inline, 'D' untuk download
?>
