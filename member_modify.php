<?php 
session_start(); 
define("IN_TG", true);
define("SCRIPT","member_modify");//定义常量表示本页内容
require dirname(__FILE__).'/includes/common.inc.php';
$link = connect();
//修改资料
if(isset($_POST['submit'])) {
	include_once ROOT_PATH.'includes/check_register.inc.php'; //引入验证函数库
	_check_vcode($_POST['vcode'],$_SESSION['vcode']);//判断验证码
	$query = "SELECT tg_username,tg_uniqid FROM `tg_user` WHERE tg_username='{$_COOKIE['username']}' LIMIT 1";
	$result = execute($link,$query);
	$data = mysqli_fetch_assoc($result);
	if(mysqli_num_rows($result) == 1){
		//为了防止cookie伪造，还需比较唯一标识符
		if($data['tg_uniqid'] != $_COOKIE['uniqid']) {
			_alert_back('唯一标识符异常!');
		}
		$clean = array();
		$clean['password'] = _check_modify_password($_POST['password'],6);
		$clean['sex']      = _check_sex($_POST['sex']);
		$clean['face']     = _check_face($_POST['face']);
		$clean['email']    = _check_email($_POST['email'],6,40);
		$clean['qq']       = _check_qq($_POST['qq']);
		$clean['url']      = _check_url($_POST['url']);
		if(empty($clean['password'])) {
			$query = "UPDATE `tg_user` SET tg_sex='{$clean['sex']}', tg_face='{$clean['face']}',tg_email='{$clean['email']}',tg_qq='{$clean['qq']}',tg_url='{$clean['url']}' WHERE tg_username='{$_COOKIE['username']}'";
		}else{
			$query = "UPDATE `tg_user` SET tg_password='{$clean['password']}',tg_sex='{$clean['sex']}', tg_face='{$clean['face']}',tg_email='{$clean['email']}',tg_qq='{$clean['qq']}',tg_url='{$clean['url']}' WHERE tg_username='{$_COOKIE['username']}'";
		}		
		execute($link, $query);
		if(mysqli_affected_rows($link) == 1){
			_location('恭喜您，修改成功!','member.php');
		}else{
			_location('很遗憾，没有任何数据被修改！','member_modify.php');	
		}
	}
}

//显示资料
if(isset($_COOKIE['username'])) {
	$query = "SELECT tg_username,tg_sex,tg_face,tg_email,tg_url,tg_qq FROM `tg_user` WHERE tg_username='{$_COOKIE['username']}'";
	$result = execute($link, $query);
	if($data = mysqli_fetch_assoc($result)) {
		$html = array();
		$html['username'] = $data['tg_username'];
		$html['sex'] = $data['tg_sex'];
		$html['face'] = $data['tg_face'];
		$html['email'] = $data['tg_email'];
		$html['url'] = $data['tg_url'];
		$html['qq'] = $data['tg_qq'];
		$html = _html($html);
		//性别选择
		if($html['sex'] == '男') {
			$html['sex_html'] = '<input type="radio" name="sex" value="男" checked="checked" /> 男 <input type="radio" name="sex" value="女" /> 女 ';
		}else{
			$html['sex_html'] = '<input type="radio" name="sex" value="男" /> 男 <input type="radio" name="sex" value="女"  checked="checked"/> 女 ';
		}
		//头像选择

	}else{
		_alert_back('用户不存在!');
	}
}else{
	_alert_back('非法登录!');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>个人中心</title>
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/register.js"></script>
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
			<h2>会员管理中心</h2>
			<form action="" method="post">
			<dl>
				<dd>用　户&nbsp;名：<?php echo $html['username']?></dd>
				<dd>密　　码：<input type="password" name="password" />　(*留空则不需要修改)</dd>
				<dd>性　　别：<?php echo $html['sex_html']?></dd>
				<dd>头　　像：
					<input type="hidden" name="face" value="face/face01.jpg" id="imgip" />
					<img src="face/face01.jpg" alt="头像选择" id="faceimg" class="imgstyle" />
				</dd>
				<dd>电子邮件：<input type="text" name="email" value="<?php echo $html['email']?>"></dd>
				<dd>主　　页：<input type="text" name="url" value="<?php echo $html['url']?>"></dd>
				<dd>Q　　 Q：<input type="text" name="qq" value="<?php echo $html['qq']?>"></dd>
				<dd>验　证&nbsp;码: <input type="text" name="vcode" style="width: 90px" />
					<img src="show_code.php" alt="看不清楚，换一张" align="absmiddle" onclick="javascript:newgdcode(this,this.src);" class="code" /></dd>
				<dd><input type="submit" name="submit" value="修改资料" /></dd>	
			</dl>
			</form>
		</div>
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>