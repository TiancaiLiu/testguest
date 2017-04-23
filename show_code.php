<?php 
session_start();
include_once 'includes/vcode.inc.php';
$_SESSION['vcode'] = vcode(80,40,25,4);
?>