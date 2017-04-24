<?php 
/**
 *模拟账户激活:讲数据表的tg_active字段清空，若改字段为空表示该用户已被激活
 */ 
define("IN_TG", true);
define("SCRIPT","active");//定义常量表示本页内容
require dirname(__FILE__).'/includes/common.inc.php';
$link = connect();
if(!isset($_GET['active'])) {
	_alert_back('非法操作!');
}
//激活处理
if(isset($_GET['action']) && isset($_GET['active']) && $_GET['action'] == 'ok') {
	$active = escape($link, $_GET['active']);
	$query = "SELECT * FROM `tg_user` WHERE tg_active='$active' LIMIT 1";
	$result = execute($link, $query);
	if(mysqli_num_rows($result)) {
		$query = "UPDATE `tg_user` SET tg_active=NULL WHERE tg_active='$active' LIMIT 1";
		$result = execute($link,$query);
		if(mysqli_affected_rows($link) == 1){
			_location('账户激活成功','login.php');
		}else{
			_location('账户激活失败','register.php');
		}
	}else{
		_alert_back('非法操作！');
	}
	close($link);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>激活</title>
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/register.js"></script>
</head>
<body>
	<?php require ROOT_PATH.'includes/header.inc.php' ?>
	<div class="active">
		<h2>账户激活</h2>
		<p>模拟邮件功能，点击以下链接激活账户</p>
		<p><a href="active.php?action=ok&amp;active=<?php echo $_GET['active']?>"><?php echo 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]?>active.php?action=ok&amp;active=<?php echo $_GET['active']?></a></p>
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>

