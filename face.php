<?php 
define("IN_TG", true);
define("SCRIPT","face");//定义常量表示本页内容
require dirname(__FILE__).'/includes/common.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/opener.js"></script>
</head>
<body>
	<div class="face">
		<h3>选择头像</h3>
		<dl>
			<?php foreach(range(1, 9) as $num) { ?>
			<dd><img src="face/face0<?php echo $num?>.jpg" alt="face/face0<?php echo $num?>.jpg" title="头像<?php echo $num?>"></dd>
			<?php } ?>
		</dl>
		<dl>
			<?php foreach(range(10, 21) as $num) { ?>
			<dd><img src="face/face<?php echo $num?>.jpg" alt="face/face<?php echo $num?>.jpg" title="头像<?php echo $num?>"></dd>
			<?php } ?>
		</dl>
	</div>
</body>
</html>