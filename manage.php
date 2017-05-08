<?php
session_start(); 
define("IN_TG", true);
define("SCRIPT","manage");//定义常量表示本页内容
require dirname(__FILE__).'/includes/common.inc.php';
$link = connect();
//身份访问验证
if(!(isset($_COOKIE['username']) && isset($_SESSION['admin']))) {
	_alert_back('非法登录！');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/register.js"></script>
</head>
<body>
	<?php require ROOT_PATH.'includes/header.inc.php' ?>
	<div class="member">
		<?php require ROOT_PATH.'includes/manage.inc.php' ?>
		<div class="member_main">
			<h2>后台管理中心</h2>
			<dl>
				<dd>服务器主机名：<?php echo $_SERVER['SERVER_NAME']?></dd>
				<dd>通信协议名称/版本：<?php echo $_SERVER['SERVER_PROTOCOL']?></dd>
				<dd>服务器IP：<?php echo $_SERVER['SERVER_ADDR']?></dd>
				<dd>客户端IP：<?php echo $_SERVER['REMOTE_ADDR']?></dd>
				<dd>服务器端口：<?php echo $_SERVER['SERVER_PORT']?></dd>
				<dd>客户端端口：<?php echo $_SERVER['REMOTE_PORT']?></dd>
				<dd>管理员邮箱：<?php echo $_SERVER['SERVER_ADMIN']?></dd>
				<dd>服务器主目录：<?php echo $_SERVER['DOCUMENT_ROOT']?></dd>
				<dd>服务器系统盘：<?php echo $_SERVER['SystemRoot']?></dd>
				<dd>脚本执行的绝对路径：<?php echo $_SERVER['SCRIPT_FILENAME']?></dd>
				<dd>Apache及PHP版本: <?php echo $_SERVER['SERVER_SOFTWARE']?></dd>
			</dl>
		</div>
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>