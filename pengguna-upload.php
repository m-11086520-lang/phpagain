<?php
session_start();

$admin_sahaja = true; # halaman ini untuk admin sahaja

include('connection.php');
include('kawalan.php');

# menyemak kewujudan data POST (proses dibuat di atas supaya mesej hanya muncul selepas submit)
if (isset($_POST['upload'])) {

	# semak fail betul-betul dimuat naik
	if (!empty($_FILES['data_pengguna']['tmp_name'])) {
		$fail_sementara = $_FILES["data_pengguna"]["tmp_name"];
		$jenis_fail = strtolower(pathinfo($_FILES["data_pengguna"]["name"], PATHINFO_EXTENSION));

		# semak saiz fail dan jenis fail
		if ($_FILES['data_pengguna']['size'] > 0 && $jenis_fail == "txt") {
			# baca keseluruhan fail TXT
			$senarai_data = file($fail_sementara, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			$bil = 0;

			# loop setiap baris
			foreach ($senarai_data as $baris) {
				$pecahan = array_map('trim', explode("|", $baris));

				# baris yang tidak sah dilangkau
				if (count($pecahan) < 3) {
					continue;
				}

				list($notel, $nama_pengguna, $katalaluan) = $pecahan;

				# semak notel dah wujud
				$semak = mysqli_prepare($condb, "SELECT notel FROM pengguna WHERE notel = ?");
				mysqli_stmt_bind_param($semak, 's', $notel);
				mysqli_stmt_execute($semak);
				mysqli_stmt_store_result($semak);

				if (mysqli_stmt_num_rows($semak) > 0) {
					continue; # rekod telah wujud, lompat tanpa spam alert
				} else {
					# simpan rekod baru (prepared statement)
					$stmt = mysqli_prepare($condb, "INSERT INTO pengguna (notel, nama_pengguna, katalaluan, jenis_pengguna) VALUES (?, ?, ?, 'pengguna biasa')");
					mysqli_stmt_bind_param($stmt, 'sss', $notel, $nama_pengguna, $katalaluan);

					if (mysqli_stmt_execute($stmt)) {
						$bil++;
					}
				}
			}

			echo "<script>alert('Import data selesai. Sebanyak $bil rekod berjaya disimpan.'); window.location.href='pengguna-senarai.php';</script>";
			exit;
		}

		echo "<script>alert('Hanya fail .txt yang boleh dimuat naik.'); window.history.back();</script>";
		exit;
	}

	echo "<script>alert('Hanya fail .txt yang boleh dimuat naik.'); window.history.back();</script>";
	exit;
}

include('header.php');
?>

<!-- Borang muat naik fail pengguna -->
<h3 align="center">Muat naik data pengguna (*.txt)</h3>
<form action='' method='POST' enctype='multipart/form-data' style='text-align:center'>
<p><b>Sila pilih fail TXT yang ingin dimuat naik</b></p>
<input type='file' name='data_pengguna' accept='.txt' required><br><br>
<button type='submit' name='upload'>Muat Naik</button>

</form>
<?php include('footer.php'); ?>
