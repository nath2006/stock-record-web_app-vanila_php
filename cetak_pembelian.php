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
$pdf->SetTitle('Daftar Pembelian Barang');
$pdf->SetSubject('Daftar Pembelian Barang');
$pdf->SetKeywords('TCPDF, PDF, pembelian, barang');

// Tambahkan halaman
$pdf->AddPage();

// Set konten untuk dicetak
$html = <<<EOD
<h1>Daftar Pembelian Barang</h1>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Tanggal</th> 
            <th>Barang</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
EOD;

// Ambil data dari database
$result = mysqli_query($koneksi, "SELECT p.*, sb.nama_barang FROM pembelian p JOIN stok_barang sb ON p.id_barang = sb.id");
while ($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>
                <td>' . htmlspecialchars($row['tanggal']) . '</td> 
                <td>' . htmlspecialchars($row['nama_barang']) . '</td>
                <td>' . htmlspecialchars($row['jumlah']) . '</td>
              </tr>';
}

$html .= '</tbody></table>';

// Cetak teks menggunakan writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// Tutup dan keluarkan dokumen PDF
$pdf->Output('Pembelian_Barang.pdf', 'I'); // 'I' untuk tampilan inline di browser, 'D' untuk download
?>
