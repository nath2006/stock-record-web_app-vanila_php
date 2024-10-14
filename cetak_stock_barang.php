<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sertakan koneksi database
include 'koneksi.php';

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
    // Path gambar
    $fotoPath = 'images/' . htmlspecialchars($row['foto']);
    // Cek apakah gambar ada
    $imgTag = file_exists($fotoPath) ? '<img src="' . $fotoPath . '" width="75" height="auto" />' : 'No Image';
    $html .= '<tr>
                <td>' . htmlspecialchars($row['nama_barang']) . '</td>
                <td>' . htmlspecialchars($row['kategori']) . '</td>
                <td>' . htmlspecialchars($row['stok']) . '</td>
                <td>' . htmlspecialchars($row['harga']) . '</td>
                <td>' . $imgTag . '</td>
              </tr>';
}

$html .= '</tbody></table>';

// Cetak teks menggunakan writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// Tutup dan keluarkan dokumen PDF
$pdf->Output('Stock_Barang.pdf', 'I'); // 'I' untuk tamp