<?php  
session_start();
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","message_detail"); //定义常量表示本页内容
$link = connect();
if(!isset($_COOKIE['username'])) {
	_alert_back('您还未登录！');
}
if(isset($_GET['id'])){
	$query = "SELECT id,tg_fromuser,tg_content,tg_date,tg_state FROM `tg_message` WHERE id='{$_GET['id']}' LIMIT 1";
	$result = execute($link, $query);
	if($data = mysqli_fetch_assoc($result)){
		//将state状态设置为1
		if($data['tg_state'] == 0){
			$sql = "UPDATE `tg_message` SET tg_state=1 WHERE id='{$_GET['id']}' LIMIT 1";
			execute($link, $sql);
		}
		$html = array();
		$html['id'] = $data['id'];
		$html['fromuser'] = $data['tg_fromuser'];
		$html['content'] = $data['tg_content'];
		$html['date'] = $data['tg_date'];
		$html = _html($html);
	}else{
		_alert_back('此短信不存在！');
	}
}else{
	_alert_back('非法访问！');
}

//删除操作
if(@$_GET['action'] == 'delete' && isset($_GET['id'])){
	$query = "SELECT id from `tg_message` WHERE id='{$_GET['id']}' lIMIT 1";
	$result = execute($link, $query);
	if(mysqli_num_rows($result)) {
		$sql = "DELETE FROM `tg_message` WHERE id='{$_GET['id']}' LIMIT 1";
		execute($link, $sql);
		if(mysqli_affected_rows($link) == 1) {
			close($link);
			_location('删除成功！', 'member_message.php');
		}else{
			close($link);
			_location('删除失败！');
		}
	}else{
		_alert_back('此短信不存在！');
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/message_detail.js"></script>
	<script type="text/javascript">
		function newgdcode(obj,url) {
			obj.src = url + '?nowtime=' + new Date().getTime();
		}
	</script>
</head>
<body>
	<?php require ROOT_PATH.'includes/header.inc.php' ?>
	<div class="member">
		<?php require ROOT_PATH.'includes/member.inc.php' ?>
		<div class="member_main">
			<h2>短信详情</h2>
			<dl>
				<dd>发信人：<?php echo $html['fromuser'] ?></dd>
				<dd>内容：<strong><?php echo $html['content'] ?></strong></dd>
				<dd>发信时间：<?php echo $html['date'] ?></dd>
				<dd class="button">
					<input type="button" value="返回列表" id="return"/>
					<input type="button" value="删除短信" name="<?php echo $html['id'] ?>" id="delete" />
				</dd>
			</dl>
		</div>	
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>