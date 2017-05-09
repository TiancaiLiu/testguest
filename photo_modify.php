<?php
session_start();
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","photo_modify"); //定义常量表示本页内容
$link = connect();
//身份访问验证
if(!(isset($_COOKIE['username']) && isset($_SESSION['admin']))) {
	_alert_back('非法登录！');
}
//读取数据
if(isset($_GET['id'])) {
	$query = "SELECT id,tg_name,tg_type,tg_content,tg_face FROM `tg_dir` WHERE id='{$_GET['id']}'";
	$result = execute($link, $query);
	if(mysqli_num_rows($result) == 1){
		$data = mysqli_fetch_assoc($result);
		$html = array();
		$html['id'] = $data['id'];
		$html['name'] = $data['tg_name'];
		$html['type'] = $data['tg_type'];
		$html['content'] = $data['tg_content'];
		$html['face'] = $data['tg_face'];
		$html = _html($html);
	}
}else{
	_alert_back('非法操作!');
}
//修改数据
if(isset($_POST['submit'])) {
	$clean = array();
	$clean['id'] = $_POST['id'];
	$clean['name'] = $_POST['name'];
	$clean['type'] = $_POST['type'];
	if($clean['type'] == 1){
		$clean['password'] = sha1($_POST['password']);
	}
	$clean['face'] = $_POST['face'];
	$clean['content'] = $_POST['content'];
	$clean = _mysql_string($clean);
	if($clean['type'] == 0){
		$query = "UPDATE `tg_dir` SET tg_name='{$clean['name']}',tg_password=NULL,tg_type='{$clean['type']}',tg_face='{$clean['face']}',tg_content='{$clean['content']}' WHERE id='{$clean['id']}' LIMIT 1";
	}else{
		$query = "UPDATE `tg_dir` SET tg_name='{$clean['name']}',tg_password='{$clean['password']}',tg_type='{$clean['type']}',tg_face='{$clean['face']}',tg_content='{$clean['content']}' WHERE id='{$clean['id']}' LIMIT 1";
	}
	execute($link,$query);
	if(mysqli_affected_rows($link) == 1) {
		close($link);
		_location('恭喜您，目录修改成功!','photo.php');
	}else{
		_alert_back('很遗憾，目录修改失败！');
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
				相册名称：<input type="text" name="name" value="<?php echo $html['name'] ?>" />
			</dd>
			<dd>
				相册类型：
				<input type="radio" name="type" value="0" <?php if($html['type'] == 0){echo 'checked="checked"';} ?> />公开　
				<input type="radio" name="type" value="1" <?php if($html['type'] == 1){echo 'checked="checked"';} ?> />私密</dd>
			<dd id="pass" <?php if($html['type'] == 1){echo 'style="display:block"';} ?>>
				相册密码：<input type="password" name="password" />
			</dd>
			<dd>
				相册封面：<input type="text" name="face" value="<?php echo $html['face'] ?>"/>
			</dd>
			<dd>
				相册描述：<textarea name="content" ><?php echo $html['content'] ?></textarea>
			</dd>
			<input type="hidden" name="id" value="<?php echo $html['id'] ?>" />
			<dd><input type="submit" name="submit" value="修改目录" /></dd>
		</dl>
		</form>
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>