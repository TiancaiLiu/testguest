<?php
session_start(); 
define("IN_TG", true);
define("SCRIPT","register");//定义常量表示本页内容
require dirname(__FILE__).'/includes/common.inc.php';
$link = connect();
_login_state();//登录状态
if(isset($_POST['submit'])) {
	include_once ROOT_PATH.'includes/check_register.inc.php'; //引入验证函数库
	_check_vcode($_POST['vcode'],$_SESSION['vcode']);//判断验证码
	$clean = array();
	//可以通过唯一标识符来防止恶意注册，伪装表单跨站攻击等
	$clean['uniqid']   = _check_uniqid($_POST['uniqid'],$_SESSION['uniqid']);
	//active也是一个唯一标识符，用来刚注册的用户进行激活处理,方可登录
	$clean['active']   = _sha1_uniqid();
	$clean['username'] = _check_username($_POST['username'],2,20);
	$clean['password'] = _check_password($_POST['password'],$_POST['notpassword'],6);
	$clean['question'] = _check_question($_POST['question'],2,20);
	$clean['answer']   = _check_answer($_POST['question'],$_POST['answer'],2,20); 
	$clean['sex']      = _check_sex($_POST['sex']);
	$clean['face']     = _check_face($_POST['face']);
	$clean['email']    = _check_email($_POST['email'],6,40);
	$clean['qq']       = _check_qq($_POST['qq']);
	$clean['url']      = _check_url($_POST['url']);
	//var_dump($clean);
	$query = "SELECT * FROM `tg_user` WHERE tg_username='{$clean['username']}'";
	$result =execute($link, $query);
	if(mysqli_num_rows($result)){
		_alert_back('改用户名已被注册，请重新输入！');
	}
	$query = "INSERT INTO `tg_user`(
								tg_uniqid,
								tg_active,
								tg_username,
								tg_password,
								tg_question,
								tg_answer,
								tg_sex,
								tg_face,
								tg_email,
								tg_qq,
								tg_url,
								tg_reg_time,
								tg_last_time,
								tg_last_ip
							) VALUES (
								'{$clean['uniqid']}',
								'{$clean['active']}',
								'{$clean['username']}',
								'{$clean['password']}',
								'{$clean['question']}',
								'{$clean['answer']}',
								'{$clean['sex']}',
								'{$clean['face']}',
								'{$clean['email']}',
								'{$clean['qq']}',
								'{$clean['url']}',
								now(),
								now(),
								'{$_SERVER['REMOTE_ADDR']}'	
							)";
	execute($link,$query);
	if(mysqli_affected_rows($link) == 1){
		_location('恭喜您，注册成功!','active.php?active='.$clean['active']);
	}else{
		_location('很遗憾，注册失败！','register.php');
	}
	close($link);
	
}
$_SESSION['uniqid'] = $uniqid = _sha1_uniqid();//这段代码不能放到前面


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>注册</title>
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
	<div class="register">
		<h2>会员注册</h2>
		<form action="" method="post" name="register">
			<input type="hidden" name="uniqid" value="<?php echo $uniqid?>" />
			<ul>
				<li>
					<label>用&nbsp;&nbsp;户&nbsp;名:</label>
					<input type="text" name="username" />
				</li>
				<li>
					<label>密　　码:</label>
					<input type="password" name="password" />
				</li>
				<li>
					<label>确认密码:</label>
					<input type="password" name="notpassword" />
				</li>
				<li>
					<label>密码提示:</label>
					<input type="text" name="question" />
				</li>
				<li>
					<label>密码回答:</label>
					<input type="text" name="answer" />
				</li>
				<li>
					<label>性　　别:</label>
					<div class='sex'>
				   		<input type="radio" name="sex" value="男" checked="checked" />男<input type="radio" name="sex" value="女" />女
				 	</div>
				</li>
				<li>
					<input type="hidden" name="face" value="face/face01.jpg" id="imgip" />
					<img src="face/face01.jpg" alt="头像选择" id="faceimg" class="imgstyle">
				</li>
				<li>
					<label>电子邮件:</label>
					<input type="text" name="email" />
				</li>
				<li>
					<label>　　QQ:</label>
					<input type="text" name="qq" />
				</li>
				<li>
					<label>个人网址:</label>
					<input type="text" name="url" value="http://" />
				</li>
				<li>
					<label>验&nbsp;&nbsp;证&nbsp;码:</label>
					<img src="show_code.php" alt="看不清楚，换一张" align="absmiddle" onclick="javascript:newgdcode(this,this.src);" class="code" />
					<input type="text" name="vcode" style="width: 90px" />
				</li>
				<li><input type="submit" name="submit" value="注册" /></li>
			</ul>
		</form>
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>