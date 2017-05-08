<?php  
session_start();
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","message"); //定义常量表示本页内容
$link = connect();
if(!isset($_COOKIE['username'])) {
	_alert_close('您还未登录！');
}
//发短信
if(isset($_POST['submit'])) {
	_check_vcode($_POST['vcode'],$_SESSION['vcode']);//判断验证码
	$query = "SELECT tg_username,tg_uniqid FROM `tg_user` WHERE tg_username='{$_COOKIE['username']}' LIMIT 1";
	$result = execute($link,$query);
	$data = mysqli_fetch_assoc($result);
	if(mysqli_num_rows($result) == 1){
		//为了防止cookie伪造，还需比较唯一标识符
		if($data['tg_uniqid'] != $_COOKIE['uniqid']) {
			_alert_back('唯一标识符异常!');
		}
		include_once ROOT_PATH.'includes/check_message.inc.php'; //引入验证函数库
		$clean = array();
		$clean['touser'] = $_POST['touser'];
		$clean['fromuser'] = $_COOKIE['username'];
		$clean['content'] = $_POST['content'];
		$clean = _mysql_string($clean);
		if($clean['touser'] == $clean['fromuser']) {
			_alert_close('请不要给自己发消息！');
		}
		$query = "INSERT INTO `tg_message`(tg_touser,tg_fromuser,tg_content,tg_date) VALUES ('{$clean['touser']}','{$clean['fromuser']}','{$clean['content']}',now())";
		execute($link, $query);
		if(mysqli_affected_rows($link) == 1){
			close($link);
			_alert_close('短信发送成功！');
		}else{
			close($link);
			_alert_back('短信发送失败！');
		}
	}
}
//数据获取
if(isset($_GET['id'])) {
	$query = "SELECT tg_username FROM `tg_user` WHERE tg_id={$_GET['id']} LIMIT 1";
	$result = execute($link, $query);
	if(mysqli_num_rows($result) == 1){
		$data = mysqli_fetch_assoc($result);
		$html = array();
		$html['username'] = $data['tg_username'];
		$html['touser'] = $data['tg_username'];
		$html = _html($html);
	}else{
		_alert_back('此用户不存在！');
	}
}else{
	_alert_back('非法操作！');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
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
			<input type="hidden" name="touser" value="<?php echo $html['touser'] ?>" />
			<dl>
				<dd><input type="text" name="" readonly="readonly" value="To:<?php echo $html['username'] ?>"></dd>
				<dd><textarea name="content" id="" cols="30" rows="10"></textarea></dd>
				<dd>验　证　码: 
					<input type="text" name="vcode" style="width: 90px" />
					<img src="show_code.php" alt="看不清楚，换一张" align="absmiddle" onclick="javascript:newgdcode(this,this.src);" class="code" />
				</dd>
				<dd><input type="submit" name="submit" value="发送" /></dd>
			</dl>
		</form>
	</div>
</body>
</html>
