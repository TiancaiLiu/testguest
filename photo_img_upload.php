<?php
session_start();
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","photo_img_upload"); //定义常量表示本页内容
$link = connect();
if(!isset($_COOKIE['username'])) {
	_alert_back('非法登录');
}
if(isset($_GET['id'])) {
	$query = "SELECT id,tg_dir FROM `tg_dir` WHERE id='{$_GET['id']}' LIMIT 1";
	$result = execute($link, $query);
	if(mysqli_num_rows($result) == 1) {
		$data = mysqli_fetch_assoc($result);
		$html = array();
		$html['id'] = $data['id'];
		$html['dir'] = $data['tg_dir'];
		$html = _html($html);
	}else{
		_alert_back('不存在此相册');
	}

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/blog.js"></script>
	<script type="text/javascript">
		function centerWindow(url,name,height,width) {
			var left = (screen.width-width)/2;
			var top = (screen.height-height)/2;
			window.open(url,name,'height='+height+',width='+width+',top='+top+',left='+left);
		}
		window.onload = function() {
			var up = document.getElementById('up');
			up.onclick = function() {
				centerWindow('upload.php?dir='+this.title,'upload','100','400');
			};
		};
	</script>
</head>
<body>
	<?php require ROOT_PATH.'includes/header.inc.php' ?>
	<div class="photo">
		<h2>上传图片</h2>
		<form action="" method="post">
		<dl>
			<dd>
				图片名称：<input type="text" name="name" />
			</dd>
			<dd>
				相册地址：<input type="text" readonly="readonly" name="url" id="url" /><a href="javascript:;" title="<?php echo $html['dir'] ?>" id="up">上传</a>
			<dd>
				相册描述：<textarea name="content" ></textarea>
			</dd>
			<dd><input type="submit" name="submit" value="上传图片" /></dd>
		</dl>
		</form>
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>