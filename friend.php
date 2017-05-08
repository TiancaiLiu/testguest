<?php  
session_start();
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","friend"); //定义常量表示本页内容
$link = connect();
if(!isset($_COOKIE['username'])) {
	_alert_close('您还未登录！');
}
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
			_alert_close('请不要添加自己！');
		}
		$query = "SELECT id FROM `tg_friend` WHERE (tg_touser='{$clean['touser']}' AND tg_fromuser='{$clean['fromuser']}') OR (tg_touser='{$clean['fromuser']}' AND tg_fromuser='{$clean['touser']}') LIMIT 1";
		$result = execute($link, $query);
		if(mysqli_num_rows($result) == 1){
			_alert_close('你们已经是好友了，可能是对方未通过您的请求！无需再添加！');
		}else{
			$sql = "INSERT INTO `tg_friend`(tg_touser,tg_fromuser,tg_content,tg_date) VALUES ('{$clean['touser']}','{$clean['fromuser']}','{$clean['content']}',now())";
			execute($link, $sql);
			if(mysqli_affected_rows($link) == 1){
				close($link);
				_alert_close('好友添加成功吗，请等待对方验证！');
			}else{
				close($link);
				_alert_back('添加失败！');
			}
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
		<h3>添加好友</h3>
		<form action="" method="post">
			<input type="hidden" name="touser" value="<?php echo $html['touser'] ?>" />
			<dl>
				<dd><input type="text" name="" readonly="readonly" value="To:<?php echo $html['username'] ?>"></dd>
				<dd><textarea name="content" id="" cols="30" rows="10">我非常想和你交朋友！</textarea></dd>
				<dd>验　证　码: 
					<input type="text" name="vcode" style="width: 90px" />
					<img src="show_code.php" alt="看不清楚，换一张" align="absmiddle" onclick="javascript:newgdcode(this,this.src);" class="code" />
				</dd>
				<dd><input type="submit" name="submit" value="添加好友" /></dd>
			</dl>
		</form>
	</div>
</body>
</html>
