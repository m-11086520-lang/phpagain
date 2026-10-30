<?php
session_start();

$admin_sahaja = true; # halaman ini untuk admin sahaja

include('connection.php');
include('kawalan.php');

# semak kewujudan data POST
if (!empty($_POST)) {

	# mengambil dan menapis data POST
	$nama_pengguna = mysqli_real_escape_string($condb, $_POST['nama_pengguna']);
	$notel = mysqli_real_escape_string($condb, $_POST['notel']);
	$katalaluan = mysqli_real_escape_string($condb, $_POST['katalaluan']);
	$jenis_pengguna = mysqli_real_escape_string($condb, $_POST['jenis_pengguna']);

	# notel_lama dihantar melalui URL (GET) dengan borang
	$notel_lama = mysqli_real_escape_string($condb, $_GET['notel_lama'] ?? '');

	# had atas had bawah nombor telefon: mestilah antara 10 hingga 12 digit
	if (strlen($notel) < 10 || strlen($notel) > 12) {
		die("<script>alert('Ralat: Bil Digit notel antara 10-12 digit sahaja'); window.history.back();</script>");
	}

	if (empty($notel_lama)) {
		die("<script>alert('Ralat: Rekod asal tidak dijumpai.'); window.history.back();</script>");
	}

	# arahan SQL untuk kemaskini pengguna
	$arahan = "UPDATE pengguna SET
	nama_pengguna = '$nama_pengguna',
	notel = '$notel',
	katalaluan = '$katalaluan',
	jenis_pengguna = '$jenis_pengguna'
	WHERE notel = '$notel_lama'";

	if (mysqli_query($condb, $arahan)) {
		echo "<script>alert('Kemaskini Berjaya'); window.location.href='pengguna-senarai.php';</script>";
	} else {
		echo "<script>alert('Kemaskini Gagal'); window.history.back();</script>";
	}

} else {
	die("<script>alert('Akses secara terus.'); window.location.href='pengguna-senarai.php';</script>");
}
?>
