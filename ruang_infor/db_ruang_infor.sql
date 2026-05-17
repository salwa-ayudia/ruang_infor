-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Bulan Mei 2026 pada 18.24
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ruang_infor`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bookmark`
--

CREATE TABLE `bookmark` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_materi` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `icon` varchar(10) DEFAULT NULL,
  `warna` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `deskripsi`, `icon`, `warna`, `created_at`) VALUES
(2, 'Web Development', 'HTML, CSS, JavaScript, React, Node.js, dan framework web modern', '📲', '#ec4899', '2026-05-04 09:18:10'),
(3, 'Algoritma & Pemrograman I', 'C++, variabel, tipe data, operator, percabangan, perulangan, fungsi dasar, array, dan input/output.', '💻', '#3b82f6', '2026-05-04 09:38:42'),
(4, 'Human Computer Interaction', 'User Interface (UI), User Experience (UX), usability, user research, wireframing, prototyping, dan evaluasi desain.', '🎯', '#eab308', '2026-05-05 10:02:07'),
(5, 'Basis Data', 'Basis data, DBMS, tabel, field, record, primary key, foreign key, ERD, relasi tabel, normalisasi, SQL dasar (SELECT, INSERT, UPDATE, DELETE), join table, query, dan pengelolaan database.', '📚', '#9333ea', '2026-05-17 19:36:50'),
(6, 'Statistika', 'Statistika, pengumpulan data, penyajian data, mean, median, modus, distribusi data, peluang, varians, standar deviasi, korelasi, regresi, dan pengujian hipotesis.', '📊', '#06b6d4', '2026-05-17 19:37:53'),
(7, 'Jaringan Komputer', 'Jaringan komputer, topologi jaringan, IP address, subnetting, routing, switching, model OSI, TCP/IP, perangkat jaringan, konfigurasi jaringan, protokol jaringan, dan keamanan jaringan.\r\n', '🛠', '#f97316', '2026-05-17 19:38:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `materi`
--

CREATE TABLE `materi` (
  `id_materi` int(11) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `penulis` varchar(100) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `unduhan` int(11) DEFAULT 0,
  `tanggal_publish` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `materi`
--

INSERT INTO `materi` (`id_materi`, `id_kategori`, `id_user`, `judul`, `penulis`, `isi`, `file`, `views`, `unduhan`, `tanggal_publish`) VALUES
(4, 4, NULL, 'Pengenalan Human Computer Interaction', 'Nurul Bahiyah, M.Kom', '<p><strong>Human-Computer Interaction</strong> atau HCI, yaitu disiplin ilmu yang mempelajari bagaimana manusia berinteraksi dengan komputer melalui desain, evaluasi, dan implementasi sistem interaktif. HCI tidak hanya berfokus pada tampilan antarmuka pengguna (UI), tetapi juga mencakup pengalaman pengguna (UX), mulai dari desain perangkat keras, perangkat lunak, hingga konteks penggunaan sistem. Dalam HCI terdapat tiga aspek utama, yaitu manusia sebagai pengguna yang memiliki perilaku dan kemampuan kognitif, komputer sebagai teknologi yang digunakan, serta interaksi sebagai cara komunikasi antara pengguna dan sistem. Tujuan utama HCI adalah menciptakan sistem yang mudah digunakan, intuitif, efisien, nyaman, serta mampu memberikan pengalaman pengguna yang baik.</p>\r\n\r\n<p>Materi juga menjelaskan pentingnya HCI dalam pengembangan aplikasi modern. Pengalaman pengguna yang buruk dapat menyebabkan pengguna tidak kembali menggunakan suatu aplikasi atau website, sedangkan desain yang baik dapat meningkatkan kredibilitas dan produktivitas pengguna. Oleh karena itu, HCI berperan dalam meningkatkan usability, mengurangi kesalahan penggunaan, memberikan aksesibilitas bagi semua pengguna, dan meningkatkan kepuasan pengguna. HCI bersifat interdisipliner karena menggabungkan berbagai bidang ilmu seperti psikologi, desain, ilmu komputer, ergonomi, sosiologi, dan cognitive science untuk memahami kebutuhan pengguna secara menyeluruh.</p>\r\n\r\n<p>Selain itu, dijelaskan pula perkembangan antarmuka pengguna dari masa ke masa, mulai dari CLI (Command Line Interface), GUI (Graphical User Interface), touch interface, voice interface, gesture interface, hingga perkembangan menuju Brain-Computer Interface. Perkembangan ini menunjukkan bahwa teknologi semakin mengarah pada pengalaman yang lebih natural dan imersif bagi pengguna. Dalam proses desain sistem, terdapat dua pendekatan utama yaitu developer-centered yang berfokus pada teknologi dan kebutuhan pengembang, serta user-centered design yang berfokus pada kebutuhan pengguna melalui riset, pengujian, empati, dan proses desain yang iteratif.</p>\r\n\r\n<p>Materi ini juga membahas berbagai metode dan tools dalam HCI, seperti user interview, survey, persona, wireframing, prototyping, usability testing, hingga penggunaan tools desain seperti Figma dan Adobe XD. Kemudian dijelaskan karakteristik good UI dan bad UI. Good UI memiliki tampilan yang jelas, konsisten, responsif, mudah dipahami, serta memberikan feedback kepada pengguna. Sebaliknya, bad UI biasanya membingungkan, tidak konsisten, lambat, sulit dinavigasi, dan menyebabkan overload informasi. HCI diterapkan pada berbagai domain seperti aplikasi web, mobile apps, desktop software, game, business applications, hingga teknologi baru seperti VR/AR dan wearable devices, yang masing-masing memiliki tantangan desain tersendiri.</p>\r\n', '1778296461_69fea68da296e_Pengenalan HCI.pdf', 20, 1, '2026-05-09 10:14:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `progress_baca`
--

CREATE TABLE `progress_baca` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_materi` int(11) DEFAULT NULL,
  `progress` int(11) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `username`, `password`, `role`, `created_at`, `status`) VALUES
(1, 'Salwa Ayudia', 'admin', '$2y$10$Xj0BG/BUDD3/eRBXt/iAlO9N743PWOnHu.hVc6d1qjdfx0T2WLRy6', 'admin', '2026-05-03 17:10:36', 'aktif'),
(2, 'Fattah', 'fattah979', '$2y$10$d721AsisufeY583mWsoK0OcNXng7by3Rxqero0cEj.bAaVBrh7yJq', 'user', '2026-05-13 07:36:15', 'aktif'),
(3, 'Najwa Ramadhan', 'Najwa', '$2y$10$pYulYQQIeAMdDYEKPm0whOD4lTCisIyfsv03V821Ajv9kttVGiaby', 'user', '2026-05-17 13:15:33', 'aktif'),
(4, 'fattah979', 'fattah', '$2y$10$WlYOS/Fka.k/mnL5IUS3W.s.FZ2aDexoB24EcrX5MvZyH43AGXcme', 'user', '2026-05-17 13:21:50', 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bookmark`
--
ALTER TABLE `bookmark`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_bookmark` (`id_user`,`id_materi`),
  ADD KEY `id_materi` (`id_materi`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id_materi`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `progress_baca`
--
ALTER TABLE `progress_baca`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_progress` (`id_user`,`id_materi`),
  ADD KEY `id_materi` (`id_materi`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bookmark`
--
ALTER TABLE `bookmark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `materi`
--
ALTER TABLE `materi`
  MODIFY `id_materi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `progress_baca`
--
ALTER TABLE `progress_baca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bookmark`
--
ALTER TABLE `bookmark`
  ADD CONSTRAINT `bookmark_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookmark_ibfk_2` FOREIGN KEY (`id_materi`) REFERENCES `materi` (`id_materi`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `materi`
--
ALTER TABLE `materi`
  ADD CONSTRAINT `materi_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE SET NULL,
  ADD CONSTRAINT `materi_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `progress_baca`
--
ALTER TABLE `progress_baca`
  ADD CONSTRAINT `progress_baca_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `progress_baca_ibfk_2` FOREIGN KEY (`id_materi`) REFERENCES `materi` (`id_materi`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
