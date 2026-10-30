<?php
session_start();

$admin_sahaja = true; # halaman ini untuk admin sahaja

include('connection.php');
include('kawalan.php');
include('header.php');

# 1 proses daftar perkhidmatan baru
if (isset($_POST['sumbit_tambah'])) {
    # ambil data dari borang dan bersihkan dahulu
    $kod_baru = mysqli_real_escape_string($condb, $_POST['kod_perkhidmatan']);
    $nama_baru = mysqli_real_escape_string($condb, $_POST['nama_perkhidmatan']);

    # arahan sql untuk tambah data ke jadual perkhidmatan
    $arahan_tambah = "INSERT INTO perkhidmatan(kod_perkhidmatan, nama_perkhidmatan) VALUES('$kod_baru', '$nama_baru')";

    if (mysqli_query($condb, $arahan_tambah)) {
        echo "<script>alert('Tahniah! Jenis perkhidmatan baharu berjaya ditambah.'); window.location.href='perkhidmatan-senarai.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal: Kod perkhidmatan mungkin telah wujud.');</script>";
    }
}
# 2. PROSES PADAM perkhidmatan
if (isset($_GET['padam'])) {
    $kod_padam = mysqli_real_escape_string($condb, $_GET['padam']);
    $arahan_padam = "DELETE FROM perkhidmatan WHERE kod_perkhidmatan = '$kod_padam'";
    if (mysqli_query($condb, $arahan_padam)) {
        echo "<script>alert('Rekod perkhidmatan berjaya dipadam.'); window.location.href='perkhidmatan-senarai.php';</script>";
        exit;
    }
}

?>

<h2 align='center'>SENARAI PERKHIDMATAN</h2>
<table align='center' width='60%' border='1' style="margin-bottom: 20px;">
    <tr bgcolor='lightgreen'>
        <td align='center' style='padding: 15px;'>
            <form action='' method='POST' style='margin:0;'>
                <b>Tambah perkhidmatan:</b> &nbsp;
                <input type='text' name='kod_perkhidmatan' placeholder=' Kod (Cth: K05)' required size='15'>

                <input type='text' name='nama_perkhidmatan' placeholder='Nama perkhidmatan (Cth: Daftar Talian)' required size='40'>
                <input type='submit' name='sumbit_tambah' value='+ Tambah perkhidmatan'>
            </form>
        </td>
    </tr>
</table>

<table align='center' width='60%' border='1' id='saiz' style='border-collapse: collapse;'>
    <tr bgcolor='lightblue'>
        <td colspan='3' style='padding: 10px;' align='right'>
            <?php include('butang-saiz.php'); ?>
        </td>
    </tr>
    <tr bgcolor='lightgray'>
        <th width='20%' style='padding: 8px;'><b>Kod perkhidmatan</b></th>
        <th width='60%'><b>Nama perkhidmatan</b></th>
        <th width='20%'><b>Tindakan</b></th>
    </tr>
<?php

# arahan sql untuk memaparkan senarai perkhidmatan sedia ada

$arahan_papar = "SELECT * FROM perkhidmatan ORDER BY kod_perkhidmatan ASC";
$laksana = mysqli_query($condb, $arahan_papar);

while ($m = mysqli_fetch_array($laksana)) {
    echo "<tr>
        <td align='center'>".$m['kod_perkhidmatan']."</td>
        <td>".htmlspecialchars($m['nama_perkhidmatan'])."</td>
        <td align='center'>
        <a href='perkhidmatan-senarai.php?padam=".$m['kod_perkhidmatan']."'
        onClick=\"return confirm('Anda pasti ingin memadam rekod ini?')\">
        [ x ] Hapus</a>
        </td>
    </tr>";
} ?>

</table>
<?php include('footer.php'); ?>
