<?php
session_start();

$admin_sahaja = true; # halaman ini untuk admin sahaja

include('connection.php');
include('kawalan.php');

# semak jika notel wujud dalam url
if (isset($_GET['notel'])) {
	$notel = mysqli_real_escape_string($condb, $_GET['notel']);

	if ($notel == ($_SESSION['notel'] ?? '')) {
		# elak admin memadam akaun yang sedang aktif
		$mesej = "Anda tidak boleh memadam akaun sendiri.";
	} elseif (mysqli_query($condb, "DELETE FROM pengguna WHERE notel='$notel'")) {
		$mesej = "Padam data berjaya";
	} else {
		$mesej = "Padam data gagal";
	}
} else {
	$mesej = "Ralat! Akses secara terus.";
}

# paparkan alert dan redirect ( 1 kali sahaja di bawah )
echo "<script>alert('$mesej'); window.location.href='pengguna-senarai.php';</script>";
?>
