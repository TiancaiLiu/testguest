<?php 
define("IN_TG", true); 
require dirname(__FILE__).'/includes/common.inc.php';
session_destroy();
setcookie('username', '', time()-3600);
setcookie('uniqid', '', time()-3600);
_location(null,'index.php');
?>