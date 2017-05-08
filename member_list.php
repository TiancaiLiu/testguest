<?php  
session_start();
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","member_list"); //定义常量表示本页内容
$link = connect();
//身份访问验证
if(!(isset($_COOKIE['username']) && isset($_SESSION['admin']))) {
	_alert_back('非法登录！');
}
//分页显示
_paging("SELECT COUNT(*) FROM `tg_user`",12);
//批量删除
if(isset($_POST['submit']) && isset($_POST['ids'])) {
	$clean = array();
	$clean['ids'] = _mysql_string(implode(',', $_POST['ids']));//以“,”分割开获取的id
	$query = "DELETE FROM `tg_user` WHERE tg_id in ({$clean['ids']})";
	execute($link, $query);
	if(mysqli_affected_rows($link)) {
		close($link);
		_location('会员删除成功！', 'member_list.php');
	}else{
		close($link);
		_location('会员删除失败！');
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
		window.onload = function() {
			var all = document.getElementById('all');
			var form = document.getElementsByTagName('form')[0];
			all.onclick = function() {
				//form.elements获取表单内的所有表单
				for(var i=0;i<form.elements.length;i++) {
					if(form.elements[i].name != 'chkall') {
						form.elements[i].checked = form.chkall.checked;
					}
				}
			};
		}
	</script>
</head>
<body>
	<?php require ROOT_PATH.'includes/header.inc.php' ?>
	<div class="member">
		<?php require ROOT_PATH.'includes/manage.inc.php' ?>
		<div class="member_main">
		<h2>会员列表</h2>
			<form action="" method="post">
			<table cellspacing="1">
				<tr>
					<th>编号</th>
					<th>会员名称</th>
					<th>邮件</th>
					<th>注册时间</th>
					<th>操作</th>
				</tr>
				<?php  
					$query = "SELECT tg_id,tg_username,tg_email,tg_reg_time FROM `tg_user` ORDER BY tg_reg_time DESC LIMIT $pagenum,$pagesize";
					$result = execute($link, $query);
					while($data = mysqli_fetch_assoc($result)) {
						$html = array();
						$html['id'] = $data['tg_id'];
						$html['username'] = $data['tg_username'];
						$html['email'] = $data['tg_email'];
						$html['reg_time'] = $data['tg_reg_time'];
						$html = _html($html);
				?>
				<tr>
					<td><?php echo $html['id'] ?></td>
					<td><?php echo $html['username'] ?></td>
					<td><?php echo $html['email'] ?></td>
					<td><?php echo $html['reg_time'] ?></td>
					<td><input type="checkbox" name="ids[]" value="<?php echo $html['id'] ?>" /></td>
				</tr>
				<?php 
					}
					mysqli_free_result($result);// 销毁结果集，释放结果内存
				?>
				<tr>
					<td colspan="5">
						<label for="all">全选<input type="checkbox" name="chkall" id="all" /></label>
						<input type="submit" name="submit" value="批量删除" />
					</td>
				</tr>
			</table>
			</form>
			<?php _page(2,'个会员');//分页风格 ?>
		</div>
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>