<?php  
define("IN_TG", true);
define("SCRIPT","member");//定义常量表示本页内容
require dirname(__FILE__).'/includes/common.inc.php';
$link = connect();

if(isset($_COOKIE['username'])) {
	$query = "SELECT tg_username,tg_sex,tg_face,tg_email,tg_url,tg_qq,tg_reg_time,tg_level,flower FROM `tg_user` WHERE tg_username='{$_COOKIE['username']}' LIMIT 1";
	$result = execute($link, $query);
	if($data = mysqli_fetch_assoc($result)) {
		$html = array();
		$html['username'] = $data['tg_username'];
		$html['sex'] = $data['tg_sex'];
		$html['face'] = $data['tg_face'];
		$html['email'] = $data['tg_email'];
		$html['url'] = $data['tg_url'];
		$html['qq'] = $data['tg_qq'];
		$html['reg_time'] = $data['tg_reg_time'];
		$html['flower'] = $data['flower'];
		switch ($data['tg_level']) {
			case '0':
				$html['level'] = '普通会员';
				break;			
			case '1':
				$html['level'] = '管理员';
				break;
			default:
				$html['level'] = '系统错误';
				break;
		}
		$html = _html($html);
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
</head>
<body>
	<?php require ROOT_PATH.'includes/header.inc.php' ?>
	<div class="member">
		<?php require ROOT_PATH.'includes/member.inc.php' ?>
		<div class="member_main">
			<h2>会员管理中心</h2>
			<dl>
				<dd>用户名：<?php echo $html['username']?></dd>
				<dd>性　别：<?php echo $html['sex']?></dd>
				<dd>头　像：<img src="<?php echo $html['face']?>" class="imgstyle"/></dd>
				<dd>电子邮件：<?php echo $html['email']?></dd>
				<dd>主　页：<?php echo $html['url']?></dd>
				<dd>Q　Q：<?php echo $html['qq']?></dd>
				<dd>注册时间：<?php echo $html['reg_time']?></dd>
				<dd>用户级别：<?php echo $html['level']?></dd>
				<dd>收到的花朵：<img src="images/logo4.png">(<?php echo $html['flower'] ?>)</dd>
			</dl>
		</div>
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>