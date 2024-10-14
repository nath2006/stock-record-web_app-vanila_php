<?php
include 'koneksi.php';

if (isset($_POST['save'])) {
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];

    mysqli_query($koneksi, "INSERT INTO