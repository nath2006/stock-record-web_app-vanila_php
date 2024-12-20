CREATE DATABASE toko_elektronik;

USE toko_elektronik;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE identitas_usaha (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_usaha VARCHAR(255),
    alamat TEXT,
    kontak VARCHAR(50)
);

CREATE TABLE stok_barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_barang VARCHAR(255),
    kategori VARCHAR(255),
    stok INT,
    harga DECIMAL(10,2),
    foto VARCHAR(255)
);

CREATE TABLE pembelian (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_barang INT,
    jumlah INT,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_barang) REFERENCES stok_barang(id)
);

CREATE TABLE penjualan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_barang INT,
    jumlah INT,
    id_customer INT,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_barang) REFERENCES stok_barang(id),
    FOREIGN KEY (id_customer) REFERENCES customer(id)
);

CREATE TABLE customer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    nomor_telepon VARCHAR(50) NOT NULL,
    alamat TEXT NOT NULL
);

CREATE TABLE user_settings (
    user_id INT PRIMARY KEY,
    locale VARCHAR(10) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
)