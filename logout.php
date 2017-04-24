<?php 
define("IN_TG", true); 
require dirname(__FILE__).'/includes/common.inc.php';
setcookie('username', '', time()-3600);
setcookie('uniqid', '', time()-3600);
_location(null,'index.php');
?>