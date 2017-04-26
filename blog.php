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
	<script type="text/javascript" src="js/blog.js"></script>
</head>
<body>
	<?php require ROOT_PATH.'includes/header.inc.php' ?>
	<div class="blog">
		<h2>博友列表</h2>
		<?php  
			$query = "SELECT tg_id,tg_username,tg_face,tg_sex FROM `tg_user` ORDER BY tg_reg_time DESC LIMIT $pagenum,$pagesize";
			$result = execute($link,$query);
			while($data = mysqli_fetch_assoc($result)) {
				$html = array();
				$html['id'] = $data['tg_id'];
				$html['username'] = $data['tg_username'];
				$html['sex'] = $data['tg_sex'];
				$html['face'] = $data['tg_face'];
				$html = _html($html);
		?>
		<dl>
			<dd class="user"><?php echo $html['username'] ?>[<?php echo $html['sex'] ?>]</dd>
			<dt><img src="<?php echo $html['face'] ?>" alt="<?php echo $html['username'] ?>" /></dt>
			<dd class="message"><a href="" name="message" title="<?php echo $html['id'] ?>">发消息</a></dd>
			<dd class="friend">加好友</dd>
			<dd class="guset">写留言</dd>
			<dd class="flower">送　花</dd>
		</dl>
		<?php 
		}
		mysqli_free_result($result);// 销毁结果集，释放结果内存
		_page(1);//分页风格 
		?>		
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>