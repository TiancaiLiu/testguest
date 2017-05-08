<?php
session_start();
define("IN_TG", true);
define("SCRIPT","article_modify");//定义常量表示本页内容
require dirname(__FILE__).'/includes/common.inc.php';
$link = connect();
if(!isset($_COOKIE['username'])) {
	_alert_back('您还未登陆，请登陆后再发帖！');
}
//读取数据
if(isset($_GET['id'])) {
	$query = "SELECT tg_username,tg_title,tg_type,tg_content FROM `tg_article` WHERE reid=0 AND id='{$_GET['id']}'";
	$result = execute($link, $query);
	if(mysqli_num_rows($result) == 1) {
		$data = mysqli_fetch_assoc($result);
		$html = array();
		$html['id'] = $_GET['id'];
		$html['username'] = $data['tg_username'];
		$html['title'] = $data['tg_title'];
		$html['type'] = $data['tg_type'];
		$html['content'] = $data['tg_content'];
		$html = _html($html);
		//判断权限
		if($_COOKIE['username'] != $html['username']) {
			_alert_back('您没有权限修改！');
		}
	}else{
		_alert_back('此帖不存在！');
	}
}else{
	_alert_back('非法操作！');
}

//提交修改后的数据
if(isset($_POST['submit'])) {
	include_once ROOT_PATH.'includes/check_article.inc.php'; //引入验证函数库
	_check_vcode($_POST['vcode'],$_SESSION['vcode']);//判断验证码
	$clean = array();
	$clean['id'] = $_POST['id'];
	$clean['type'] = $_POST['type'];
	$clean['title'] = _check_title($_POST['title'],2,40);
	$clean['content'] = $_POST['content'];
	$clean = _mysql_string($clean);
	//var_dump($clean);
	$query = "UPDATE `tg_article` SET tg_type='{$clean['type']}',tg_title='{$clean['title']}',tg_content='{$clean['content']}',tg_last_modify=now() WHERE id='{$clean['id']}'";
	execute($link,$query);
	if(mysqli_affected_rows($link) == 1){		
		close($link);
		_location('恭喜您，编辑成功!','article.php?id='.$clean['id']);
	}else{
		close($link);
		_location('很遗憾，编辑失败！','article_modify.php?id='.$clean['id']);
	}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript">
		function newgdcode(obj,url) {
			obj.src = url + '?nowtime=' + new Date().getTime();
		}
	</script>
</head>
<body>
	<?php require ROOT_PATH.'includes/header.inc.php' ?>
	<div class="modify">
		<h2>编辑帖子</h2>
		<form action="" method="post" name="modify">
			<input type="hidden" name="id" value="<?php echo $html['id'] ?>" />
			<dl>
				<dt>请认真填写以下内容</dt>
				<dd>
					类　型：
					<?php  
						foreach (range(1, 12) as $num) {
							if($num == $html['type']){
								echo '<input type="radio" name="type" value="'.$num.'" checked="checked" />';
							}else{
								echo '<input type="radio" name="type" value="'.$num.'"/>';	
							}
							echo '<img src="images/btn'.$num.'.png" alt="类型">　';
							if($num == 6){
								echo '<br />　　　 　';
							}
						}
					?>
				</dd>
				<dd>标　题：<input type="text" name="title" value="<?php echo $html['title'] ?>" />　(*必填，2-40位)</dd>
				<dd>
					<textarea name="content"><?php echo $html['content'] ?></textarea>
				</dd>
				<dd>验 证 码：
					<img src="show_code.php" alt="看不清楚，换一张" align="absmiddle" onclick="javascript:newgdcode(this,this.src);" class="code" />
					<input type="text" name="vcode" style="width: 90px" />
				</dd>
				<dd>
					<input type="submit" name="submit" value="发布" />
				</dd>
			</dl>
		</form>
	</div>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>