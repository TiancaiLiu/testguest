<?php
session_start(); 
define("IN_TG", true);
define("SCRIPT","manage_set");//定义常量表示本页内容
require dirname(__FILE__).'/includes/common.inc.php';
$link = connect();
//身份访问验证
if(!(isset($_COOKIE['username']) && isset($_SESSION['admin']))) {
	_alert_back('非法登录！');
}
//修改数据
if(isset($_POST['submit'])) {
	$clean = array();
	$clean['webname'] = $_POST['webname'];
	$clean['article'] = $_POST['article'];
	$clean['blog'] = $_POST['blog'];
	$clean['photo'] = $_POST['photo'];
	$clean['skin'] = $_POST['skin'];
	$clean['string'] = $_POST['string'];
	$clean['code'] = $_POST['code'];
	$clean['register'] =$_POST['register'];
	$clean = _mysql_string($clean);
	$query = "UPDATE `tg_system` SET tg_webname='{$clean['webname']}',tg_article='{$clean['article']}',tg_blog='{$clean['blog']}',tg_photo='{$clean['photo']}',tg_skin='{$clean['skin']}',tg_string='{$clean['string']}',tg_code='{$clean['code']}',tg_register='{$clean['register']}' WHERE id=1 LIMIT 1";
	execute($link,$query);
	if(mysqli_affected_rows($link) == 1) {
		close($link);
		_location('恭喜您，修改成功!','manage_set.php');
	}else{
		_alert_back('很遗憾，没有任何数据被修改！','manage_set.php');
	}
}
//读取数据
$query = "SELECT * FROM `tg_system` WHERE id=1";
$result = execute($link,$query);
if(mysqli_num_rows($result) == 1){
	$data = mysqli_fetch_assoc($result);
	$html = array();
	$html['webname'] = $data['tg_webname'];
	$html['article'] = $data['tg_article'];
	$html['blog'] = $data['tg_blog'];
	$html['photo'] = $data['tg_photo'];
	$html['skin'] = $data['tg_skin'];
	$html['string'] = $data['tg_string'];
	//$html['post'] = $data['tg_post'];
	$html['code'] =$data['tg_code'];
	$html['register'] =$data['tg_register'];
	$html = _html($html);
	//文章
	if($html['article'] == 8){
		$html['article_html'] = '<select name="article"><option value="8" selected="selected">每页8篇</option><option value="14">每页14篇</option></select>';
	}elseif($html['article'] == 14){
		$html['article_html'] = '<select name="article"><option value="8">每页8篇</option><option value="14" selected="selected">每页14篇</option></select>';
	}
	//博友
	if($html['blog'] == 10){
		$html['blog_html'] = '<select name="blog"><option value="10" selected="selected">每页10人</option><option value="15">每页15人</option></select>';
	}elseif($html['blog'] == 15){
		$html['blog_html'] = '<select name="blog"><option value="10">每页10人</option><option value="15" selected="selected">每页15人</option></select>';
	}
	//相册
	if($html['photo'] == 8){
		$html['photo_html'] = '<select name="photo"><option value="8" selected="selected">每页8张</option><option value="12">每页12张</option></select>';
	}elseif($html['photo'] == 12){
		$html['photo_html'] = '<select name="photo"><option value="8">每页8张</option><option value="12" selected="selected">每页12张</option></select>';
	}
	//皮肤
	if($html['skin'] == 1){
		$html['skin_html'] = '<select name="skin"><option value="1" selected="selected">1号皮肤</option><option value="2">2号皮肤</option><option value="3">3号皮肤</option></select>';
	}elseif($html['skin'] == 2){
		$html['skin_html'] = '<select name="skin"><option value="1" >1号皮肤</option><option value="2" selected="selected">2号皮肤</option><option value="3">3号皮肤</option></select>';
	}elseif($html['skin'] == 3){
		$html['skin_html'] = '<select name="skin"><option value="1" >1号皮肤</option><option value="2">2号皮肤</option><option value="3" selected="selected">3号皮肤</option></select>';
	}
	//启用验证码
	if($html['code'] == 1){
		$html['code_html'] = '<input type="radio" name="code" value="1" checked="checked" />启用　<input type="radio" name="code" value="0" />禁用';
	}else{
		$html['code_html'] = '<input type="radio" name="code" value="1" />启用　<input type="radio" name="code" value="0" checked="checked" />禁用';
	}
	//开放注册
	if($html['register'] == 1){
		$html['register_html'] = '<input type="radio" name="register" value="1" checked="checked" />启用　<input type="radio" name="register" value="0" />禁用';
	}else{
		$html['register_html'] = '<input type="radio" name="register" value="1" />启用　<input type="radio" name="register" value="0" checked="checked" />禁用';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/register.js"></script>
</head>
<body>
	<?php require ROOT_PATH.'includes/header.inc.php' ?>
	<div class="member">
		<?php require ROOT_PATH.'includes/manage.inc.php' ?>
		<div class="member_main">
			<h2>后台管理中心</h2>
			<form action="" method="post">
			<dl>
				<dd>网　站　名　称：<input type="text" name="webname" value="<?php echo $html['webname']?>" /></dd>
				<dd>文章每页列表数：<?php echo $html['article_html']?></dd>
				<dd>博客每页列表数：<?php echo $html['blog_html']?></dd>
				<dd>相册每页列表数：<?php echo $html['photo_html']?></dd>
				<dd>网站 默 认 皮 肤：<?php echo $html['skin_html']?></dd>
				<dd>非法 字 符 过 滤：<input type="text" name="string" value="<?php echo $html['string'] ?>" />(*请用‘|’隔开)</dd>
				<dd>是否 启 用 验 证：<?php echo $html['code_html']?></dd>
				<dd>是否 开 放 注 册：<?php echo $html['register_html']?></dd>
				<dd><input type="submit" name="submit" value="修改系统设置" /></dd>
			</dl>
			</form>
		</div>
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>