<?php
session_start();

$admin_sahaja = true; # halaman ini untuk admin sahaja

include('connection.php');
include('kawalan.php');

# Semak data GET, jika tiada terus hantar ke fail pengguna-senarai
if (empty($_GET['notel'])) {
	die("<script>window.location.href='pengguna-senarai.php';</script>");
}

$notel = mysqli_real_escape_string($condb, $_GET['notel']);
$m = mysqli_fetch_array(mysqli_query($condb, "SELECT * FROM pengguna WHERE notel = '$notel'"));

# jika rekod tidak dijumpai, kembali ke senarai
if (!$m) {
	die("<script>alert('Rekod pengguna tidak dijumpai.'); window.location.href='pengguna-senarai.php';</script>");
}

include('header.php');
?>

<div>
<h3>Kemaskini maklumat pengguna</h3></div>

<form action='pengguna-kemaskini-proses.php?notel_lama=<?= urlencode($notel) ?>' method='POST'>

<label>Nama Pengguna:</label>
<input type='text' name='nama_pengguna' value='<?= htmlspecialchars($m['nama_pengguna']) ?>' required><br>

<label>No. Telefon:</label>
<input type='text' name='notel' value='<?= $m['notel'] ?>' required><br>

<label>Katalaluan:</label>
<input type='text' name='katalaluan' value='<?= htmlspecialchars($m['katalaluan']) ?>' required><br>

<label>Jenis Pengguna:</label>
<select name='jenis_pengguna' required>
<option value='<?= htmlspecialchars($m['jenis_pengguna']) ?>'><?= htmlspecialchars($m['jenis_pengguna']) ?> </option>

<?php

# dapatkan senarai jenis pengguna
$laksana_arahan = mysqli_query($condb, "SELECT jenis_pengguna FROM pengguna GROUP BY jenis_pengguna");

while ($n = mysqli_fetch_array($laksana_arahan)) {
	if ($n['jenis_pengguna'] != $m['jenis_pengguna']) {
		echo "<option value='".htmlspecialchars($n['jenis_pengguna'])."'>".htmlspecialchars($n['jenis_pengguna'])."</option>";
	}
}

?>
</select><br>

<input type='submit' value='Kemaskini Data'>
</form>
</div>

<?php include('footer.php'); ?>
