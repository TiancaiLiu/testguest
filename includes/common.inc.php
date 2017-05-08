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
$link = connect();
//网站系统设置初始化
$query = "SELECT * FROM `tg_system` WHERE id=1";
$result = execute($link,$query);
if(mysqli_num_rows($result) == 1){
	$data = mysqli_fetch_assoc($result);
	$system = array();
	$system['webname'] = $data['tg_webname'];
	$system['article'] = $data['tg_article'];
	$system['blog'] = $data['tg_blog'];
	$system['photo'] = $data['tg_photo'];
	$system['skin'] = $data['tg_skin'];
	$system['code'] = $data['tg_code'];
	$system['register'] = $data['tg_register'];
	$system['string'] = $data['tg_string'];
	$system = _html($system);
}else{
	exit('系统表异常，请管理员检查');
}
?>