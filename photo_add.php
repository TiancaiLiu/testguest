<?php
session_start();
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","photo_add"); //定义常量表示本页内容
$link = connect();
//身份访问验证
if(!(isset($_COOKIE['username']) && isset($_SESSION['admin']))) {
	_alert_back('非法登录！');
}
//添加数据
if(isset($_POST['submit'])) {
	$clean = array();
	$clean['name'] = $_POST['name'];
	$clean['type'] = $_POST['type'];
	if($clean['type'] == 1){
		$clean['password'] = sha1($_POST['password']);
	}
	$clean['content'] = $_POST['content'];
	$clean['dir'] = time();
	$clean = _mysql_string($clean);
	//判断文件夹目录是否存在
	if(!is_dir('photo')){
		mkdir('photo',0777);
	}
	//再在这个主目录里面创建定义的相册目录
	if(!is_dir('photo/'.$clean['dir'])){
		mkdir('photo/'.$clean['dir']);
	}
	//将当前的目录信息写入数据库
	if($clean['type'] == 0){
		$query = "INSERT INTO `tg_dir`(tg_name,tg_type,tg_content,tg_dir,tg_date) VALUES ('{$clean['name']}','{$clean['type']}','{$clean['content']}','photo/{$clean['dir']}',now())";
	}else{
		$query = "INSERT INTO `tg_dir`(tg_name,tg_type,tg_password,tg_content,tg_dir,tg_date) VALUES ('{$clean['name']}','{$clean['type']}','{$clean['password']}','{$clean['content']}','photo/{$clean['dir']}',now())";
	}
	execute($link,$query);
	if(mysqli_affected_rows($link) == 1) {
		close($link);
		_location('恭喜您，目录添加成功!','photo.php');
	}else{
		_alert_back('很遗憾，目录添加失败！');
	}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/blog.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript">
		window.onload = function(){
			var fm = document.getElementsByTagName('form')[0];
			var pass = document.getElementById('pass');

			fm[1].onclick = function() {
				pass.style.display = 'none';
			};
			fm[2].onclick = function() {
				pass.style.display = 'block';
			};
			fm.onsubmit = function() {
				if(fm.name.value.length < 2 || fm.name.value.length > 20) {
					alert('相册名不得少于2位或者大于20位');
					fm.name.value = '';
					fm.name.focus();
					return false;
				}
				//只有当私密被选中时才判断密码
				if(fm[2].checked) {
					if(fm.password.value.length < 6) {
						alert('密码不得少于6位');
						fm.password.value = '';
						fm.password.focus();
						return false;
					}
				}
				return true;
			};
		};
	</script>
</head>
<body>
	<?php require ROOT_PATH.'includes/header.inc.php' ?>
	<div class="photo">
		<h2>添加相册目录</h2>
		<form action="" method="post">
		<dl>
			<dd>
				相册名称：<input type="text" name="name" />
			</dd>
			<dd>
				相册类型：
				<input type="radio" name="type" value="0" checked="checked" />公开　
				<input type="radio" name="type" value="1" />私密</dd>
			<dd id="pass">
				相册密码：<input type="password" name="password" />
			</dd>
			<dd>
				相册描述：<textarea name="content" ></textarea>
			</dd>
			<dd><input type="submit" name="submit" value="添加目录" /></dd>
		</dl>
		</form>
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>