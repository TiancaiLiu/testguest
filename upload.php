<?php  
session_start();
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","upload"); //定义常量表示本页内容
$link = connect();
if(!isset($_COOKIE['username'])) {
	_alert_back('非法登录');
}
if(isset($_POST['submit'])) {
	//设置上传图片支持的类型
	$files = array('image/jpeg','image/pjpeg','image/png','image/x-png','image/gif');
	//判断上传的图片是不是数组里的一种
	if(is_array($files)) {
		if(!in_array($_FILES['userfile']['type'], $files)) {
			_alert_back('支持上传的图片格式有jpg,png,gif');
		}
	}
	//判断文件错误的类型
	if($_FILES['userfile']['error'] > 0){
		switch ($_FILES['userfile']['error']) {
			case '1':
				_alert_back('上传文件超过指定值1');
				break;
			case '2':
				_alert_back('上传文件超过指定值2');
				break;
			case '1':
				_alert_back('部分文件被上传');
				break;
			case '1':
				_alert_back('没有任何文件上传');
				break;
		}
		exit;
	}
	//判断配置大小
	if($_FILES['userfile']['size'] > 1000000){
		_alert_back('上传文件大小不得超过1M');
	}
	//获取文件的拓展名
	$extra = explode('.', $_FILES['userfile']['name']);
	$name = $_POST['dir'].'/'.time().'.'.$extra[1];
	//移动文件
	if(is_uploaded_file($_FILES['userfile']['tmp_name'])) {
		if(!@move_uploaded_file($_FILES['userfile']['tmp_name'], $name)){
			_alert_back('移动失败!');
		}else{
			//_alert_back('上传成功!');
			echo "<script>alert('上传成功！');window.opener.document.getElementById('url').value='$name';window.close();</script>";
		}
	}else{
		_alert_back('上传的临时文件不存在！');
	}
}
//接收dir
if(!isset($_GET['dir'])) {
	_alert_back('非法操作！');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/blog.js"></script>
	<script type="text/javascript">
		function newgdcode(obj,url) {
			obj.src = url + '?nowtime=' + new Date().getTime();
		}
	</script>
</head>
<body>
	<div class="upload" style="padding: 20px;">
		<form enctype="multipart/form-data" action="" method="post">
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
			选择图片：<input type="file" name="userfile" />
			<input type="hidden" name="dir" value="<?php echo $_GET['dir'] ?>" />
			<input type="submit" name="submit" value="上传" />
		</form>
	</div>
</body>
</html>
