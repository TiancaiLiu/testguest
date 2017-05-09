<?php  
session_start();
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","member_job"); //定义常量表示本页内容
$link = connect();
//身份访问验证
if(!(isset($_COOKIE['username']) && isset($_SESSION['admin']))) {
	_alert_back('非法登录！');
}
//分页显示
_paging("SELECT COUNT(*) FROM `tg_user` WHERE tg_level=1",12);
//新增管理员
if(isset($_POST['submit'])) {
	$clean = array();
	$clean['username'] = $_POST['manage'];
	$clean = _mysql_string($clean);
	$query = "UPDATE `tg_user` SET tg_level=1 WHERE tg_username='{$clean['username']}'";
	execute($link, $query);
	if(mysqli_affected_rows($link) == 1){
		close($link);
		_location('管理员添加成功！', 'member_job.php');
	}else{
		close($link);
		_alert_back('管理员添加失败！可能是您输入的用户不存在或者为空');
	}
}
//辞职操作
if(@$_GET['action'] == 'job' && isset($_GET['id'])) {
	$query = "UPDATE `tg_user` SET tg_level=0 WHERE tg_id='{$_GET['id']}' AND tg_username='{$_COOKIE['username']}'";
	execute($link, $query);
	if(mysqli_affected_rows($link) == 1){
		close($link);
		session_destroy();//辞职后要把session值清空，不然还会有管理的功能
		_location('辞职成功！', 'index.php');
	}else{
		close($link);
		_location('辞职失败！可能是您非法操作！');
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/blog.js"></script>
</head>
<body>
	<?php require ROOT_PATH.'includes/header.inc.php' ?>
	<div class="member">
		<?php require ROOT_PATH.'includes/manage.inc.php' ?>
		<div class="member_main">
		<h2>会员列表</h2>
			<table cellspacing="1">
				<tr>
					<th>编号</th>
					<th>会员名称</th>
					<th>邮件</th>
					<th>注册时间</th>
					<th>操作</th>
				</tr>
				<?php  
					$query = "SELECT tg_id,tg_username,tg_email,tg_reg_time FROM `tg_user` WHERE tg_level=1 ORDER BY tg_reg_time DESC LIMIT $pagenum,$pagesize";
					$result = execute($link, $query);
					while($data = mysqli_fetch_assoc($result)) {
						$html = array();
						$html['id'] = $data['tg_id'];
						$html['username'] = $data['tg_username'];
						$html['email'] = $data['tg_email'];
						$html['reg_time'] = $data['tg_reg_time'];
						$html = _html($html);
						if($_COOKIE['username'] == $html['username']) {
							$html['job_html'] = '<a href="member_job.php?action=job&id='.$html['id'].'">辞职</a>';
						}else{
							$html['job_html'] = '无权操作';
						}
				?>
				<tr>
					<td><?php echo $html['id'] ?></td>
					<td><?php echo $html['username'] ?></td>
					<td><?php echo $html['email'] ?></td>
					<td><?php echo $html['reg_time'] ?></td>
					<td><?php echo $html['job_html'] ?></td>
				</tr>
				<?php 
					}
					mysqli_free_result($result);// 销毁结果集，释放结果内存
				?>
			</table>
			<form action="" method="post">
				<input type="text" name="manage" /><input type="submit" value="添加管理员" name="submit" />
			</form>
			<?php _page(2,'个管理员');//分页风格 ?>
		</div>
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>