<?php
session_start();

# tamatkan sesi login sepenuhnya sebelum keluar
session_unset();
session_destroy();
header("Location: index.php");
exit();
?>
