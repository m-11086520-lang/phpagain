<?php

# Menyemak jika pengguna telah login atau tidak
$jenis = $_SESSION['jenis_pengguna'] ?? '';

# admin dan pengguna biasa hendaklah sama dengan phpmyadmin
$akses = ["admin", "pengguna biasa"];

if (empty($jenis) || !in_array($jenis, $akses)) {
	$mesej = "Sila login untuk mengakses halaman ini.";
	$skrip = "<script>alert('$mesej'); window.location.href='logout.php';</script>";
	die($skrip);
}

# Halaman pentadbiran sahaja (tetapkan $admin_sahaja = true SEBELUM include kawalan.php)
if (!empty($admin_sahaja) && $jenis != 'admin') {
	$mesej = "Halaman ini untuk admin sahaja.";
	$skrip = "<script>alert('$mesej'); window.location.href='index.php';</script>";
	die($skrip);
}
?>
