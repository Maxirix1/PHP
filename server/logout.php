<?php
session_start();
session_unset();
session_destroy();
header('Location: ../client/login.php');
exit();
?>
