<?php

session_start();

$admin_sahaja = true; # halaman ini untuk admin sahaja

include('connection.php');
include('kawalan.php');
include('header.php');
?>

<h2 align='center'>SENARAI PENGGUNA</h2>
<table align='center' width='80%' border='1' id='saiz'>
	<tr bgcolor='lightblue'>
		<td colspan='2' style='padding: 10px;' >
			<!-- Borang carian pengguna -->
			<form action='' method='POST' style='margin:0;'>
				<input type='text' name='nama_pengguna' placeholder='Carian'>
				<input type='submit' value='Cari'>
			</form>
		</td>

		<td colspan='4' align='right'>
			<button onclick="window.location.href='pengguna-upload.php'">
			[ + ] Muat Naik Data PENGGUNA
			</button>
			<?php include('butang-saiz.php')?>
		</td>
	</tr>

	<tr bgcolor='lightgray'>
		<th width='35%' style='padding: 8px;'>Nama Pengguna</th>
		<th width='15%'>Nombor Telefon</th>
		<th width='10%'>Katalaluan</th>
		<th width='10%'>Jenis Pengguna</th>
		<th width='20%'>Tindakan</th>
	</tr>
<?php
# Semak rekod terlebih dahulu, papar mesej jika tiada data
$tambahan = "";
$data_carian = "";

# tambah syarat dalam carian pengguna melalui nama_pengguna (prepared statement)
if (!empty($_POST['nama_pengguna'])) {
	$tambahan = " WHERE pengguna.nama_pengguna LIKE CONCAT('%', ?, '%')";
	$arahan_papar = "SELECT * FROM pengguna $tambahan ORDER BY jenis_pengguna";

	$laksana = mysqli_prepare($condb, $arahan_papar);
	mysqli_stmt_bind_param($laksana, 's', $_POST['nama_pengguna']);
	mysqli_stmt_execute($laksana);
	$hasil = mysqli_stmt_get_result($laksana);
} else {
	# senarai penuh
	$arahan_papar = "SELECT * FROM pengguna ORDER BY jenis_pengguna";
	$hasil = mysqli_query($condb, $arahan_papar);
}

# paparkan setiap rekod pengguna
while ($m = mysqli_fetch_array($hasil)) {
	echo "<tr>
		<td>".htmlspecialchars($m['nama_pengguna'])."</td>
		<td>".$m['notel']."</td>
		<td>".$m['katalaluan']."</td>
		<td>".$m['jenis_pengguna']."</td>
		<td>
| <a href='pengguna-kemaskini-borang.php?notel=".$m['notel']."'> KemasKini </a>
| <a href='pengguna-padam.php?notel=".$m['notel']."' onClick=\"return confirm('Anda ingin memadam data ini.')\">Hapus</a> | </td>
</tr>";
} ?>

</table>
<?php include('footer.php'); ?>
