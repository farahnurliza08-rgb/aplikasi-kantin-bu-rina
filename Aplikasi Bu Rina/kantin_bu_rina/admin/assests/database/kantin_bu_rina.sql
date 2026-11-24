CREATE DATABASE IF NOT EXISTS kantin_bu_rina;
USE kantin_bu_rina;

CREATE TABLE admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE menu (
    id_menu INT AUTO_INCREMENT PRIMARY KEY,
    nama_menu VARCHAR(100) NOT NULL,
    kategori ENUM('Makanan','Minuman') NOT NULL,
    harga INT NOT NULL,
    stok INT NOT NULL DEFAULT 0
);

CREATE TABLE pesanan (
    id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
    nomor_antrean VARCHAR(30) UNIQUE,
    nama_pelanggan VARCHAR(100) NOT NULL,
    total_harga INT NOT NULL,
    metode_pembayaran ENUM('Cash','QRIS') NOT NULL,
    status ENUM('Menunggu','Diproses','Selesai')
        DEFAULT 'Menunggu',
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE detail_pesanan (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL,
    id_menu INT NOT NULL,
    jumlah INT NOT NULL,
    harga INT NOT NULL,
    FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan),
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu)
);

INSERT INTO menu (nama_menu,kategori,harga,stok) VALUES
('Bakso','Makanan',10000,30),
('Mie Ayam','Makanan',12000,25),
('Nasi Goreng','Makanan',15000,20),
('Ayam Geprek','Makanan',15000,20),
('Nasi Ayam','Makanan',13000,20),
('Sosis Bakar','Makanan',8000,25),
('Kentang Goreng','Makanan',10000,20),
('Es Teh Manis','Minuman',5000,50),
('Teh Botol','Minuman',6000,40),
('Es Jeruk','Minuman',7000,30),
('Air Mineral','Minuman',4000,50),
('Pop Ice','Minuman',5000,30);