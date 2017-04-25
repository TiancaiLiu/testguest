<?php 
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","blog"); //定义常量表示本页内容
$link = connect();
//分页显示
_paging("SELECT COUNT(*) FROM `tg_user`",5);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>朋友</title>
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
</head>
<body>
	<?php require ROOT_PATH.'includes/header.inc.php' ?>
	<div class="blog">
		<h2>博友列表</h2>
		<?php  
			$query = "SELECT tg_username,tg_face,tg_sex FROM `tg_user` ORDER BY tg_reg_time DESC LIMIT $pagenum,$pagesize";
			$result = execute($link,$query);
			while($data = mysqli_fetch_assoc($result)) {				
		?>
		<dl>
			<dd class="user"><?php echo $data['tg_username'] ?>[<?php echo $data['tg_sex'] ?>]</dd>
			<dt><img src="<?php echo $data['tg_face'] ?>" alt="<?php echo $data['tg_username'] ?>" /></dt>
			<dd class="message">发消息</dd>
			<dd class="friend">加好友</dd>
			<dd class="guset">写留言</dd>
			<dd class="flower">送　花</dd>
		</dl>
		<?php 
		}
		_page(2); 
		?>		
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>