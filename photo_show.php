<?php
session_start();
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","photo_show"); //定义常量表示本页内容
$link = connect();
if(isset($_GET['id'])) {
	$query = "SELECT id FROM `tg_dir` WHERE id='{$_GET['id']}' LIMIT 1";
	$result = execute($link, $query);
	if(mysqli_num_rows($result) == 1) {
		$data = mysqli_fetch_assoc($result);
		$html = array();
		$html['id'] = $data['id'];
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
	<script type="text/javascript" src="js/jquery.js"></script>
</head>
<body>
	<?php require ROOT_PATH.'includes/header.inc.php' ?>
	<div class="photo">
		<h2>相册列表</h2>
		<?php if(isset($_COOKIE['username'])) {?>
			<p><a href="photo_img_upload.php?id=<?php echo $html['id'] ?>">上传照片</a></p>
		<?php } ?>
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>