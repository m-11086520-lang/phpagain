<?php $jenis = $_SESSION['jenis_pengguna'] ?? ''; ?>

<!-- Font moden untuk keseluruhan sistem : Inter (google fonts) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
	/* Guna Inter untuk semua teks, borang, jadual dan butang.
	   Jika tiada internet, ia terus guna Arial sebagai ganti. */
	* {
		font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
	}
</style>

<!--Tajuk sistem-->
<h1 align='center'>Epsilon - Sistem e-janjitemu Klinik</h1>
<hr>

<div style="text-align: center;">
	<!-- Menu ini dipapar kepada Semua orang -->
		<a href='index.php'>Laman Utama</a>

	<!-- Menu khas ikut jenis pengguna yang berjaya login -->
	<?php if ($jenis == "admin") { ?>
	|	<a href='pengguna-senarai.php'>Senarai Pengguna</a>
	|	<a href='janjitemu-senarai.php'>Senarai Janji Temu</a>
	|	<a href='perkhidmatan-senarai.php'>Senarai Perkhidmatan</a>
	|	<a href='laporan.php'>Laporan Janji Temu</a>

	<?php } elseif ($jenis == "pengguna biasa") { ?>
	|	<a href='janjitemu.php'>Janji Temu</a>

	<?php } ?>

	<!-- Butang Login/Logout -->
	<?php if (!empty($jenis)) { ?>
	|	<a href='logout.php'>Logout</a>

	<?php } else { ?>
	|	<a href='login.php'>Log masuk</a>
	|	<a href='signup.php'>Daftar Sini</a> |

	<?php } ?>
<hr>
