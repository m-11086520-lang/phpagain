<?php
session_start();

if (isset($_POST['notel'])) {
	# sambung database dahulu sebelum proses login
	include('connection.php');

	# ambil data yang dihantar melalui form
	$notel = trim($_POST['notel']);
	$katalaluan = $_POST['katalaluan'];

	# proses login menggunakan prepared statement (elak SQL injection)
	$query = "SELECT * FROM pengguna WHERE notel = ? LIMIT 1";
	$result = mysqli_prepare($condb, $query);
	mysqli_stmt_bind_param($result, 's', $notel);
	mysqli_stmt_execute($result);
	$senarai = mysqli_stmt_get_result($result);

	if (mysqli_num_rows($senarai) == 1) {
		$row = mysqli_fetch_array($senarai);

	# semak kata laluan untuk akaun lama (hash) dan baru (plaintext)
	$padan = (strcmp((string)$row['katalaluan'], $katalaluan) === 0)
		|| password_verify($katalaluan, (string)$row['katalaluan']);

		if ($padan) {
			$_SESSION['notel'] = $row['notel'];
			$_SESSION['jenis_pengguna'] = $row['jenis_pengguna'];
			echo "<script>window.location.href='index.php';</script>";
			exit;
		}
	}

	echo "<script>alert('Login Gagal. Semak No Telefon dan Katalaluan.'); window.history.back();</script>";
	exit;
}

include('header.php');
?>

<!-- form login -->
<div style="text-align: center;">
	<h3>Daftar Masuk Pengguna (LOGIN)</h3>
	<p>Sila lengkapkan maklumat di bawah</p>
	<form action="" method="POST">
		<b>No. Telefon:</b><br>
		<input type="text" name="notel" required><br><br>
		<b>Kata Laluan:</b><br>
		<input type="password" name="katalaluan" required><br><br>
		<input type="submit" value="Daftar Masuk">
	</form>
</div>
<?php include('footer.php'); ?>
