<?php  
session_start();
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","message"); //定义常量表示本页内容
$link = connect();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>写短信</title>
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/blog.js"></script>
	<script type="text/javascript">
		function newgdcode(obj,url) {
			obj.src = url + '?nowtime=' + new Date().getTime();
		}
	</script>
</head>
<body>
	<div class="message">
		<h3>写短信</h3>
		<form action="" method="post">
			<dl>
				<dd><input type="text" name=""></dd>
				<dd><textarea name="content" id="" cols="30" rows="10"></textarea></dd>
				<dd>验　证&nbsp;码: 
					<input type="text" name="vcode" style="width: 90px" />
					<img src="show_code.php" alt="看不清楚，换一张" align="absmiddle" onclick="javascript:newgdcode(this,this.src);" class="code" />
				</dd>
				<dd><input type="submit" name="submit" value="发送" /></dd>
			</dl>
		</form>
	</div>
</body>
</html>
