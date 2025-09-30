<?php
session_start();

session_unset();
session_destroy();

// Kill before 1day
setcookie("logged_in", "", time() - 86400, "/");

// Back to home
header("Location: ../index.php");
exit;
?>
