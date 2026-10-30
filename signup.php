<?php
session_start();

include('connection.php');

if (isset($_POST['notel'])) {

	# mengambil data POST dari Borang (form)
	$nama_pengguna = trim($_POST['nama_pengguna']);
	$notel = trim($_POST['notel']);
	$katalaluan = $_POST['katalaluan'];

	# data validation : had atas dan bawah (5 hingga 8 aksara)
	if (strlen($katalaluan) < 5 || strlen($katalaluan) > 8) {
		die("<script>alert('Ralat: Kata laluan mestilah antara 5-8 aksara'); window.location.href='signup.php';</script>");
	}

	# semak notel dah wujud atau belum (prepared statement)
	$sql_semak = "SELECT notel FROM pengguna WHERE notel = ?";
	$laksana_semak = mysqli_prepare($condb, $sql_semak);
	mysqli_stmt_bind_param($laksana_semak, 's', $notel);
	mysqli_stmt_execute($laksana_semak);
	mysqli_stmt_store_result($laksana_semak);

	if (mysqli_stmt_num_rows($laksana_semak) >= 1) {
		die("<script>alert('No Telefon telah digunakan'); window.location.href='signup.php';</script>");
	}

	# simpan data pengguna baru
	$stmt = mysqli_prepare($condb, "INSERT INTO pengguna (notel, nama_pengguna, katalaluan, jenis_pengguna) VALUES (?, ?, ?, 'pengguna biasa')");
	mysqli_stmt_bind_param($stmt, 'sss', $notel, $nama_pengguna, $katalaluan);

	if (mysqli_stmt_execute($stmt)) {
		echo "<script>alert('Tahniah! Pendaftaran Berjaya.'); window.location.href='login.php';</script>";
		exit;
	} else {
		echo "<script>alert('Pendaftaran gagal.');</script>";
	}
}

include('header.php');
?>
<div style="text-align: center;">
	<h3>Daftar Pengguna Baru (SIGN UP)</h3>
	<p>Sila Lengkapkan Maklumat di bawah</p>

	<!-- borang ringkas tanpa table -->
	<form method='POST' action=''>
		<b>No. Telefon:</b><br>
		<input type='text' name='notel' placeholder='Contoh: 0123456789' required>
		<br><br>
		<b>Nama Pengguna:</b><br>
		<input type='text' name='nama_pengguna' placeholder='Nama penuh' required>
		<br><br>
		<b>Katalaluan:</b>(antara 5-8 aksara sahaja)<br>
		<input type='password' name='katalaluan' required>
		<br><br>
			<input type='submit' value="Daftar Sekarang">
		</form>
</div>
<?php include('footer.php'); ?>
