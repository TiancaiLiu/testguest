<?php  
session_start();
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","member_message"); //定义常量表示本页内容
$link = connect();
if(!isset($_COOKIE['username'])) {
	_alert_back('您还未登录！');
}
//分页显示
_paging("SELECT COUNT(*) FROM `tg_message` WHERE tg_touser='{$_COOKIE['username']}'",5);

//批量删除
if(isset($_POST['submit']) && isset($_POST['ids'])) {
	$clean = array();
	$clean['ids'] = _mysql_string(implode(',', $_POST['ids']));//以“，”分割开获取的id
	$query = "DELETE FROM `tg_message` WHERE id in ({$clean['ids']})";
	execute($link, $query);
	if(mysqli_affected_rows($link)) {
		close($link);
		_location('删除成功！', 'member_message.php');
	}else{
		close($link);
		_location('删除失败！');
	}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>短信查阅</title>
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
		<?php require ROOT_PATH.'includes/member.inc.php' ?>
		<div class="member_main">
		<h2>短信管理中心</h2>
			<form action="" method="post">
			<table cellspacing="1">
				<tr>
					<th>发信人</th>
					<th>短信内容</th>
					<th>时间</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
				<?php  
					$query = "SELECT id,tg_fromuser,tg_content,tg_date,tg_state FROM `tg_message` WHERE tg_touser='{$_COOKIE['username']}' ORDER BY tg_date LIMIT $pagenum,$pagesize";
					$result = execute($link, $query);
					while($data = mysqli_fetch_assoc($result)) {
						$html = array();
						$html['id'] = $data['id'];
						$html['fromuser'] = $data['tg_fromuser'];
						$html['content'] = $data['tg_content'];
						$html['date'] =$data['tg_date'];
						$html = _html($html);
						if($data['tg_state'] == 0){
							$html['state'] = '<img src="images/no_read.png" alt="未读" title="未读" />';
							$_html['content_html'] = '<strong>'._title($html['content']).'</strong>';
						}else{
							$html['state'] = '<img src="images/read.png" alt="已读" title="已读" />';
							$_html['content_html'] = _title($html['content']);
						}
				?>
				<tr>
					<td><?php echo $html['fromuser'] ?></td>
					<td><a href="message_detail.php?id=<?php echo $html['id'] ?>" title="<?php echo $html['content'] ?>"><?php echo $_html['content_html'] ?></a></td>
					<td><?php echo $html['date'] ?></td>
					<td><?php echo $html['state'] ?></td>
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
			<?php _page(2,'条短信');//分页风格 ?>
		</div>
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>