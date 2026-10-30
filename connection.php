<?php

# kod ini telah dibina semula dan diperbaiki, setiap ralat sintaks telah diselesaikan,
# tetapi anda mesti menukar nama sistem anda sendiri, ralat masa jalan telah diselesaikan,
# semua struktur kod tidak dioptimumkan dan perlu dioptimumkan dan mudah.

/**
 *
 *  All code have been reconstructed and fixed
 *  But all code structure are still not optimized
 *  Please use this wisely and rename it to your own system name
 *  Sintax Error has been resolved
 *  Runtime Error has been resolved
 *  DO NOT COPY AND PASTE, YOU MUST REWRITE EVERYTHING, COPY / PASTE IS AGAINST OF THE RULES!
 */

#set time zone
date_default_timezone_set('Asia/Kuala_Lumpur');

# sambung ke pangkalan data (nama DB, user dan password ikut persekitaran sendiri)
$condb = mysqli_connect('localhost', 'root', '', 'eklinik');

# semak sambungan - jika gagal, berhenti dengan mesej yang jelas
if (!$condb) {
	die('Sambungan pangkalan data gagal: ' . mysqli_connect_error());
}

# tetapkan set aksara supaya data Melayu (huruf, tanda baca) disimpan dengan betul
mysqli_set_charset($condb, 'utf8mb4');
?>
