<?php
include_once 'config.php';
include_once 'auth.php';

logout();
header("Location: login.php");
exit;
?>