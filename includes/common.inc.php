<?php
define('ROOT_PATH', substr(dirname(__FILE__), 0,-8));//定义路径常量
require ROOT_PATH.'includes/global.func.php';//引入核心函数库
require ROOT_PATH.'includes/config.inc.php';
require ROOT_PATH.'includes/mysql.inc.php';

header('Content-Type:text/html;charset=utf8'); 
if(!defined("IN_TG")) {
	exit('Access Defined!');
}

define('GPC',"get_magic_quotes_gpc()");//定义自动转义常量

//拒绝低版本php
if(PHP_VERSION < '5.1.0') {
	exit('Version is too Low!');
}
?>