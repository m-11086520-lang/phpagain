<?php
session_start();

$admin_sahaja = true; # halaman ini untuk admin sahaja

include('connection.php');
include('kawalan.php');
include('header.php');

# 1 arahan padam janji temu
if (isset($_GET['padam_notel']) && isset($_GET['padam_tarikh'])) {
    $notel = mysqli_real_escape_string($condb, $_GET['padam_notel']);
    $tarikh = mysqli_real_escape_string($condb, $_GET['padam_tarikh']);

    # laksana arahan padam
    $arahan_padam = "DELETE FROM janjitemu WHERE notel='$notel' AND tarikh='$tarikh'";
    $padam = mysqli_query($condb, $arahan_padam);

    if ($padam) {
        echo "<script>alert('Temujanji berjaya dibatalkan.'); window.location.href='janjitemu-senarai.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memadam temujanji.');</script>";
    }
}
?>

<h2 align='center'>SENARAI JANJI TEMU</h2>
<table align='center' width='80%' border='1' id='saiz' style='border-collapse: collapse;'>
    <tr bgcolor='lightblue'>
        <td colspan='2' style='padding: 10px;'>
            <!-- borang carian -->
            <form action='' method='POST' style='margin:0;'>
                <input type='text' name='carian_nama' placeholder='Carian nama'>
                <input type='submit' value='Cari'>
            </form>
        </td>
        <td colspan='4' align='right' style='padding: 10px;'>
            <button onclick="window.location.href='perkhidmatan-senarai.php'">
                [ + ] Tambah Perkhidmatan Baru
            </button>
            <?php include('butang-saiz.php'); ?>
        </td>
    </tr>
    <tr bgcolor='lightgray'>
        <th width='20%' style='padding: 8px;'>Nama Pengguna</th>
        <th width='15%'>No. Telefon</th>
        <th width='25%'>Jenis Perkhidmatan</th>
        <th width='15%'>Tarikh</th>
        <th width='10%'>Masa</th>
        <th width='15%'>Tindakan</th>
    </tr>
<?php
# 2. Syarat carian nama Pengguna (diikat dengan parameter - elak SQL injection)
$tambahan = !empty($_POST['carian_nama']) ? "WHERE p.nama_pengguna LIKE CONCAT('%', ?, '%')" : "";

# 3. arahan sql join
$sql = "SELECT j.notel, j.tarikh, j.masa, p.nama_pengguna, s.nama_perkhidmatan
        FROM janjitemu j
        INNER JOIN pengguna p ON j.notel = p.notel
        INNER JOIN perkhidmatan s ON j.kod_perkhidmatan = s.kod_perkhidmatan
        $tambahan ORDER BY j.tarikh ASC";

if (!empty($_POST['carian_nama'])) {
    $laksana = mysqli_prepare($condb, $sql);
    $carian = $_POST['carian_nama'];
    mysqli_stmt_bind_param($laksana, 's', $carian);
    mysqli_stmt_execute($laksana);
    $hasil = mysqli_stmt_get_result($laksana);
} else {
    $hasil = mysqli_query($condb, $sql);
}

# 4. paparkan rekod

while ($m = mysqli_fetch_array($hasil)) {
    echo "<tr>
        <td style='padding: 8px;'>".htmlspecialchars($m['nama_pengguna'])."</td>
        <td align='center'>{$m['notel']}</td>
        <td align='center'>".htmlspecialchars($m['nama_perkhidmatan'])."</td>
        <td align='center'>{$m['tarikh']}</td>
        <td align='center'>{$m['masa']}</td>
        <td align='center'>
        <a href='?padam_notel={$m['notel']}&padam_tarikh={$m['tarikh']}' onClick=\"return confirm('Anda pasti ingin memadam temujanji ini?')\">Hapus</a>
        </td>
    </tr>";
}
?>
</table>
<br>

<?php include('footer.php'); ?>
