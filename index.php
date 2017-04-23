<?php  
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","index");//定义常量表示本页内容
$link = connect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>Document</title>
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
</head>
<body>
<?php require ROOT_PATH.'includes/header.inc.php' ?>
	<div class="list">
		<h2>帖子列表</h2>
	</div>
	<div class="user">
		<h2>新进会员</h2>
	</div>
	<div class="pics">
		<h2>我的相册</h2>
	</div>
<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>