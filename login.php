<?php 
session_start(); 
define("IN_TG", true);
define("SCRIPT","login");//定义常量表示本页内容
require dirname(__FILE__).'/includes/common.inc.php';
$link = connect();
_login_state();//登录状态
if(isset($_POST['submit'])) {
	include_once ROOT_PATH.'includes/check_login.inc.php'; //引入验证函数库
	_check_vcode($_POST['vcode'],$_SESSION['vcode']);//判断验证码
	$clean = array();
	$clean['username'] = _check_username($_POST['username'],2,20);
	$clean['password'] = _check_password($_POST['password'],6);
	$clean['time'] = _check_time($_POST['time']);
	//var_dump($clean);
	$query = "SELECT * FROM `tg_user` WHERE tg_username='{$clean['username']}' AND tg_password='{$clean['password']}' AND tg_active='' LIMIT 1";
	$result = execute($link, $query);
	if(mysqli_num_rows($result)==1) {
		$data = mysqli_fetch_assoc($result);
		//var_dump($data);
		_setcookie($data['tg_username'],$data['tg_uniqid'],$clean['time']);
		close($link);
		session_destroy();
		_location(null,'index.php');
	}else{
		close($link);
		session_destroy();
		_location('用户名密码不正确或改账户未激活','login.php');
	}
	
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>登录</title>
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/login.js"></script>
	<script type="text/javascript">
		function newgdcode(obj,url) {
			obj.src = url + '?nowtime=' + new Date().getTime();
		}
	</script>
</head>
<body>
	<?php require ROOT_PATH.'includes/header.inc.php' ?>
	<div class="login">
		<h2>登录</h2>
		<form action="" method="post" name="login">
			<ul>
				<li>
					<label>用户名:</label>
					<input type="text" name="username" />
				</li>
				<li>
					<label>密　码:</label>
					<input type="password" name="password" />
				</li>
				<li>
					<label>保　留:</label>
					<div class='time'>
				   		<input type="radio" name="time" value="0" checked="checked" />不保留<input type="radio" name="time" value="1" />一天<input type="radio" name="time" value="2" />一周<input type="radio" name="time" value="3" />一月
				 	</div>
				</li>
				<li>
					<label>验证码:</label>
					<img src="show_code.php" alt="看不清楚，换一张" align="absmiddle" onclick="javascript:newgdcode(this,this.src);" class="code" />
					<input type="text" name="vcode" style="width: 90px" />
				</li>
				<li>
					<input type="submit" name="submit" value="登录" />
					<input type="button" name="submit" value="注册" />
				</li>
			</ul>
		</form>
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>