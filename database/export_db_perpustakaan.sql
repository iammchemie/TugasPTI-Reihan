-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_perpustakaan
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `db_perpustakaan`
--

/*!40000 DROP DATABASE IF EXISTS `db_perpustakaan`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `db_perpustakaan` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `db_perpustakaan`;

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `author` varchar(100) NOT NULL,
  `publisher` varchar(100) NOT NULL,
  `year` smallint(6) NOT NULL,
  `isbn` varchar(17) NOT NULL,
  `category` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `isbn` (`isbn`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
INSERT INTO `books` VALUES (1,'Bumi Manusia','Pramoedya Ananta Toer','Hasta Mitra',1980,'978-979-97312-3-3','fiction',5,'2026-10-13 09:57:23','2026-10-13 09:57:23'),(2,'Laskar Pelangi','Andrea Hirata','Bentang Pustaka',2005,'978-979-3062-79-2','fiction',8,'2026-10-13 09:57:23','2026-10-13 09:57:23'),(3,'Sapiens','Yuval Noah Harari','Kepustakaan Populer',2017,'978-602-424-621-8','history',3,'2026-10-13 09:57:23','2026-10-13 09:57:23'),(4,'A Brief History of Time','Stephen Hawking','Bantam Books',1988,'978-0553380163','science',4,'2026-10-13 09:57:23','2026-10-13 09:57:23'),(5,'Clean Code','Robert C. Martin','Prentice Hall',2008,'978-0132350884','technology',6,'2026-10-13 09:57:23','2026-10-13 09:57:23'),(6,'The Pragmatic Programmer','Andrew Hunt','Addison-Wesley',1999,'978-0201616224','technology',2,'2026-10-13 09:57:23','2026-10-13 09:57:23'),(7,'Filsafat Ilmu','Jujun S. Suriasumantri','Pustaka Sinar Harapan',2009,'978-979-416-041-5','non_fiction',3,'2026-10-13 09:57:23','2026-10-13 09:57:23'),(8,'Negeri 5 Menara','Ahmad Fuadi','Gramedia',2009,'978-979-22-4862-3','fiction',7,'2026-10-13 09:57:23','2026-10-13 09:57:23'),(9,'Cosmos','Carl Sagan','Random House',1980,'978-0394502947','science',2,'2026-10-13 09:57:23','2026-10-13 09:57:23'),(10,'Sejarah Indonesia Modern','M.C. Ricklefs','Serambi',2008,'979-1112-15-1','history',4,'2026-10-13 09:57:23','2026-10-13 09:57:23'),(11,'Algoritma dan Pemrograman','Rinaldi Munir','Informatika',2016,'978-602-1514-01-9','technology',9,'2026-10-13 09:57:23','2026-10-13 09:57:23'),(12,'Atomic Habits','James Clear','Avery',2018,'978-0735211292','non_fiction',5,'2026-10-13 09:57:23','2026-10-13 09:57:23');
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_anggota`
--

DROP TABLE IF EXISTS `tb_anggota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_anggota` (
  `id_anggota` int(11) NOT NULL AUTO_INCREMENT,
  `no_induk` varchar(20) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(200) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tanggal_daftar` date DEFAULT NULL,
  PRIMARY KEY (`id_anggota`),
  UNIQUE KEY `no_induk` (`no_induk`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_anggota`
--

LOCK TABLES `tb_anggota` WRITE;
/*!40000 ALTER TABLE `tb_anggota` DISABLE KEYS */;
INSERT INTO `tb_anggota` VALUES (1,'AG001','Siti Aminah','Jl. Merdeka No. 10','081234567890','siti@example.com','2026-01-10'),(2,'AG002','Agus Prasetyo','Jl. Melati No. 5','081298765432','agus@example.com','2026-02-15');
/*!40000 ALTER TABLE `tb_anggota` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_buku`
--

DROP TABLE IF EXISTS `tb_buku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_buku` (
  `id_buku` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` varchar(20) DEFAULT NULL,
  `judul` varchar(150) NOT NULL,
  `penulis` varchar(100) DEFAULT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_buku`),
  UNIQUE KEY `isbn` (`isbn`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_buku`
--

LOCK TABLES `tb_buku` WRITE;
/*!40000 ALTER TABLE `tb_buku` DISABLE KEYS */;
INSERT INTO `tb_buku` VALUES (1,'9786021912953','Mengenal Java','Budi Santoso','Gramedia',2019,'Teknologi',5,'2026-08-08 13:37:46'),(2,'9786023854521','Algoritma Pemrograman','Rina Melati','Informatika',2020,'Pendidikan',3,'2026-08-08 13:37:46'),(3,'9789790732603','Matematika Dasar','Andi Wijaya','Erlangga',2018,'Pendidikan',7,'2026-08-08 13:37:46');
/*!40000 ALTER TABLE `tb_buku` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_peminjaman`
--

DROP TABLE IF EXISTS `tb_peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_peminjaman` (
  `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT,
  `id_buku` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_rencana_kembali` date DEFAULT NULL,
  `tanggal_kembali_aktual` date DEFAULT NULL,
  `status` enum('Dipinjam','Dikembalikan','Terlambat') DEFAULT 'Dipinjam',
  PRIMARY KEY (`id_peminjaman`),
  KEY `fk_peminjaman_buku` (`id_buku`),
  KEY `fk_peminjaman_anggota` (`id_anggota`),
  CONSTRAINT `fk_peminjaman_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `tb_anggota` (`id_anggota`) ON UPDATE CASCADE,
  CONSTRAINT `fk_peminjaman_buku` FOREIGN KEY (`id_buku`) REFERENCES `tb_buku` (`id_buku`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_peminjaman`
--

LOCK TABLES `tb_peminjaman` WRITE;
/*!40000 ALTER TABLE `tb_peminjaman` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$12$i3I5HqmaLIvGtPkg9AfYguosiNqdb6lRTtwluOP5sV4zuDzKQSsr6','2026-10-13 09:57:23');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'db_perpustakaan'
--

--
-- Dumping routines for database 'db_perpustakaan'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-13 17:03:31
