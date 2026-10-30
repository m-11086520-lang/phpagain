<?php
session_start();

include('connection.php');
include('kawalan.php');
include('header.php');
?>

<!-- CSS Khas untuk mencetak laporan -->
 <style>
    @media print {
        .no-print { display: none !important;}
        body { background-color: white;}
    }
    .btn-cetak {
        padding: 8px 15px;
        background: #333;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        cursor: pointer;
        border: none;
        font-size: 14px;
    }
    .btn-cari {
        padding: 8px 15px;
        background: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }
 </style>

 <div style="width: 80%; margin: 0 auto;">
    <h2>LAPORAN JANJI TEMU HARIAN</h2>
    <!-- 1. KOTAK PILIHAN TARIKH -->
     <div class="no-print" style="background: #e9ecef; padding: 20px; border-radius: 8px; border: 1px solid #ccc; text-align: center; margin-bottom: 20px;">
        <form action="" method="POST" style="margin: 0;">
            <label style="font-size: 16px; font-weight: bold">Pilih Tarikh Janji Temu</label>
            <?php
            # kekalkan tarikh yang dipilih di dalam kotak form
            $tarikh_pilihan = $_POST['tarikh_carian'] ?? date('Y-m-d');
            ?>

            <input type="date" name="tarikh_carian" value="<?php echo $tarikh_pilihan; ?>" required style="padding: 6px; font-size: 16px">
            <input type="submit" name="papar_laporan" value="Cari Rekod" class="btn-cari"> &nbsp;&nbsp;
            <button type="button" class="btn-cetak" onclick="window.print()">Cetak</button>

        </form>
    </div>
    <!-- 2. HASIL LAPORAN SELEPAS TARIKHPILIH -->
    <?php
        if (isset($_POST['papar_laporan'])) {
            $tarikh = mysqli_real_escape_string($condb, $_POST['tarikh_carian']);

            # tukar format tarikh
            $tarikh_cantik = date('d/m/Y', strtotime($tarikh));

            # header
            echo "<center>
                <p>Jadual Bertugas Bagi Tarikh: <b style='color:blue; font-size:18px;'> $tarikh_cantik </b></p></center>";

                $sql_detail = "SELECT j.masa, p.nama_pengguna, p.notel, s.nama_perkhidmatan
                               FROM janjitemu j
                               INNER JOIN pengguna p ON j.notel = p.notel
                               INNER JOIN perkhidmatan s ON j.kod_perkhidmatan = s.kod_perkhidmatan
                               WHERE j.tarikh = '$tarikh'
                               ORDER BY j.masa ASC";
                $query_detail = mysqli_query($condb, $sql_detail);
                $jumlah_rekod = mysqli_num_rows($query_detail);

                # paparan insight
                echo "<table width='100%' border='0' cellpadding='10' style='background-color:#fff3cd; color:#856404; border-radius:5px; margin-bottom:15px; border:1px solid #ffeeba;'>
                      <tr>
                      <td align='center'>
                      <b>RINGKASAN HARI INI:</b> Terdapat <b>$jumlah_rekod</b> pelanggan yang telah menempah janji temu pada tarikh ini.
                      </td>
                      </tr>
                      </table>";
                # jadual senarai
                echo "<table width='100%' border='1' cellpadding='8' style='border-collapse:collapse;'>
                      <tr bgcolor='#1F4E78' style='color: white;'>
                      <th width='5%'>Bil</th>
                      <th width='15%'>Waktu / Masa</th>
                      <th width='35%'>Nama Pelanggan</th>
                      <th width='20%'>No. Telefon</th>
                      <th width='25%'>Urusan / Servis</th>
                      </tr>";

                $bil = 1;
                if ($jumlah_rekod > 0) {
                    while ($m = mysqli_fetch_array($query_detail)) {
                        echo "<tr>
                        <td align='center'>".$bil++."</td>
                        <td align='center'><b>".$m['masa']."</b></td>
                        <td>".htmlspecialchars($m['nama_pengguna'])."</td>
                        <td align='center'>".$m['notel']."</td>
                        <td align='center'>".htmlspecialchars($m['nama_perkhidmatan'])."</td>
                        </tr>";
                    }
                } else {
                    # jika hari tersebut tiada orang tempah
                    echo "<tr><td colspan='5' align='center' style='padding:20px; color:gray;'><i>Tiada sebarang janji temu ditempah pada tarikh ini.</i></td></tr>";
                }

                # Tandatangan bawah laporan (dipaparkan untuk semua keadaan laporan)
                echo "</table><br><br>
                <div style='float: right; text-align: center; width: 250px;'>
                <p>Disahkan Oleh:</p>
                <br><br><br>
                <hr>
                <b>Penyelia Bertugas</b>
                </div>
                <div style='clear: both;'></div>";

        } else {
            echo "<br><br><h3 align='center' style='color:gray;'>Sila pilih tarikh pada kalendar di atas dan klik 'Cari Rekod'</h3></br></br> ";
        }

    ?>
 </div>
 <div class="no-print"> <?php include('footer.php'); ?></div>
