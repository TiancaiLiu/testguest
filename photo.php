<?php
session_start();
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","photo"); //定义常量表示本页内容
$link = connect();
_paging("SELECT COUNT(*) FROM `tg_dir`",$system['photo']);

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
		<?php  
			$query = "SELECT id,tg_name,tg_type,tg_face FROM `tg_dir` ORDER BY tg_date DESC LIMIT $pagenum,$pagesize";
			$result = execute($link,$query);
			while($data = mysqli_fetch_assoc($result)) {
				$html = array();
				$html['id'] = $data['id'];
				$html['face'] = $data['tg_face'];
				$html['name'] = $data['tg_name'];
				$html['type'] = $data['tg_type'];
				$html = _html($html);
				if($html['type'] == 0) {
					$html['type_html'] = '公开';
				}else{
					$html['type_html'] = '私密';
				}
				if(empty($html['face'])) {
					$html['face_html'] = '';
				}else{
					$html['face_html'] = '<img src="'.$html['face'].'" alt="'.$html['name'].'" width="50px" height="50px"/>';
				}
		?>
		<dl>
			<dt><a href="photo_show.php?id=<?php echo $html['id'] ?>"><?php echo $html['face_html'] ?></a></dt>
			<dd><a href="photo_show.php?id=<?php echo $html['id'] ?>"><?php echo $html['name'] ?></a><br />(<?php echo $html['type_html'] ?>)</dd>
			<?php if(isset($_SESSION['admin']) && isset($_COOKIE['username'])) {?>
				<dd>[<a href="photo_modify.php?id=<?php echo $html['id'] ?>">编辑</a>] [删除]</dd>
			<?php } ?>
		</dl>
		<?php } ?>
		<?php if(isset($_SESSION['admin']) && isset($_COOKIE['username'])) {?>
			<p><a href="photo_add.php">添加目录</a></p>
		<?php } ?>
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>