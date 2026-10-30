<?php

session_start();

# ambil nombor telefon pengguna yang sedang login
$sesi_notel = $_SESSION['notel'] ?? '';

include('connection.php');
include('kawalan.php');
include('header.php');

# 1. proses padam janji temu
if (isset($_GET['padam_notel']) && isset($_GET['padam_tarikh'])) {
    $padam_notel = mysqli_real_escape_string($condb, $_GET['padam_notel']);
    $padam_tarikh = mysqli_real_escape_string($condb, $_GET['padam_tarikh']);

    # arahan sql padam berdasarkan primary key (notel & tarikh)
    # tambahan syarat WHERE notel = sesi supaya pengguna hanya boleh padam janji temu SENDIRI
    $arahan_padam = "DELETE FROM janjitemu WHERE notel='$padam_notel' AND tarikh='$padam_tarikh' AND notel='$sesi_notel'";

    if (mysqli_query($condb, $arahan_padam)) {
        echo "<script>alert('Janji temu anda berjaya dipadam.'); window.location.href='janjitemu.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memadam janji temu.');</script>";
    }

}

# 2. PROSES BORANG PENDAFTARAN JANJI TEMU
if (isset($_POST["submit_janjitemu"])) {
    $tarikh = mysqli_real_escape_string($condb, $_POST['tarikh']);
    $masa = mysqli_real_escape_string($condb, $_POST['masa']);
    $kod_perkhidmatan = mysqli_real_escape_string($condb, $_POST['kod_perkhidmatan']);

    $semakan_rekod = "SELECT * FROM janjitemu WHERE notel='$sesi_notel' AND tarikh='$tarikh'";
    $laksana_semakan = mysqli_query($condb, $semakan_rekod);

    if (mysqli_num_rows($laksana_semakan) >= 1) {
        echo "<script>alert('Gagal: Sila pilih tarikh lain.');</script>";

    } else {
        $arahan_tambah = "INSERT INTO janjitemu(notel, tarikh, kod_perkhidmatan, masa) VALUES ('$sesi_notel', '$tarikh','$kod_perkhidmatan','$masa')";
        if (mysqli_query($condb, $arahan_tambah)) {
            echo "<script>alert('Tahniah! Janji temu anda telah berjaya didaftarkan.'); window.location.href='janjitemu.php';</script>";

        } else {
            echo "<script>alert('Ralat teknikal. Pendaftaran gagal.');</script>";
        }
    }
} ?>
<br> <h3 align="center">Pendaftaran Janji Temu Baharu</h3>

<!-- 3. BORANG DAFTAR JANJI TEMU (ATAS) -->
<table align="center" width="50%" border="1" cellpadding="10" style="border-collapse: collapse; background-color: #f9f9f9;">
    <form action="" method="POST">
        <tr>
            <td width="35%" align="right"><b>Nombor Telefon:</b></td>
            <td> <?php echo $sesi_notel; ?></td>
        </tr>
        <tr>
            <td align="right"><b>Jenis Perkhidmatan:</b></td>
            <td>
                <select name="kod_perkhidmatan" required style="padding: 3px; width: 100%;">
                    <option value="">-- Sila Pilih Perkhidmatan --</option>
                    <?php $sql_servis = "SELECT * FROM perkhidmatan ORDER BY nama_perkhidmatan ASC"; $laksana_servis = mysqli_query($condb, $sql_servis);
                    while ($s = mysqli_fetch_array($laksana_servis)) {
                        echo "<option value='".htmlspecialchars($s['kod_perkhidmatan'])."'>".htmlspecialchars($s['nama_perkhidmatan'])."</option>";
                    } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right"><b>Masa:</b></td>
            <td>
                <select name="masa" required style="padding: 3px;">
                    <option value="">-- Pilih Masa --</option>
                    <option value="9.00 pagi">9.00 pagi</option>
                    <option value="10.00 pagi">10.00 pagi</option>
                    <option value="11.00 pagi">11.00 pagi</option>
                    <option value="12.00 tghari">12.00 tghari</option>
                    <option value="2.00 petang">2.00 petang</option>
                    <option value="3.00 petang">3.00 petang</option>
                    <option value="4.00 petang">4.00 petang</option>
                </select>
            </td>
        </tr>

        <tr>
            <td colspan="2" align="center" bgcolor="lightgray">
                <input type="submit" name="submit_janjitemu" value="Daftar Janji Temu Sekarang" style="padding: 5px 15px; font-weight: bold;">
                <input type="reset" value="Set Semula" style="padding: 5px 10px;">


            </td>
        </tr>
    </form>
</table>
<br>
<!-- 4. SENARAI JANJI TEMU PENGGUNA (BAWAH) -->
 <h3 align="center">Senarai Janji Temu Anda</h3>
 <table align="center" width="70%" border="1" cellpadding="8" style="border-collapse: collapse;">
    <tr bgcolor="cyan">
        <td width="20%" align="center"><b>Tarikh</b></td>
        <td width="15%" align="center"><b>Masa</b></td>
        <td width="45%"><b>Perkhidmatan</b></td>
        <td width="20%" align="center"><b>Tindakan</b></td>
    </tr>

    <?php
    # arahan sql (prepared statement - hanya papar janji temu pengguna yang sedang login)
    $arahan_papar = "SELECT t.tarikh, t.masa, t.notel, p.nama_perkhidmatan FROM janjitemu t JOIN perkhidmatan p ON t.kod_perkhidmatan = p.kod_perkhidmatan WHERE t.notel = ? ORDER BY t.tarikh DESC";
    $laksana_papar = mysqli_prepare($condb, $arahan_papar);
    mysqli_stmt_bind_param($laksana_papar, 's', $sesi_notel);
    mysqli_stmt_execute($laksana_papar);
    $senarai_papar = mysqli_stmt_get_result($laksana_papar);

    if (mysqli_num_rows($senarai_papar) > 0) {
        while ($rekod = mysqli_fetch_array($senarai_papar)) {
            echo "<tr>
                <td align='center'>".$rekod['tarikh']."</td>
                <td align='center'>".$rekod['masa']."</td>
                <td>".htmlspecialchars($rekod['nama_perkhidmatan'])."</td>
                <td align='center'>
                <a href='?padam_notel=".$rekod['notel']."&padam_tarikh=".$rekod['tarikh']."' onClick=\"return confirm('Anda pasti ingin membatalkan janji temu ini?')\">
                [ Batalkan ]
                </a>

                </td></tr>";

        }
    } else {
        # jika pengguna belum ada sebarang janji temu
        echo "<tr><td colspan='4' align='center'>
            <i>Anda belum membuat sebarang janji temu.</i>
            </td></tr>";
    }

    ?>

 </table>
</br>

<?php include("footer.php"); ?>
