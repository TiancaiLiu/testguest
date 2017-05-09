<?php 
if(!defined("IN_TG")) {
	exit('Access Defined!');
}
if(isset($_COOKIE['username'])){
	//短信提醒
	$query = "SELECT COUNT(*) AS count FROM `tg_message` WHERE tg_state=0 AND tg_touser = '{$_COOKIE['username']}'";
	$result = execute($link, $query);
	$data=mysqli_fetch_assoc($result);
	if(empty($data['count'])){
		$GLOBALS['message'] = '<img src="images/nonews.png" /><strong><a href="member_message.php">(0)</a></strong>';
	}else{
		$GLOBALS['message'] = '<img src="images/news.png" /><strong><a href="member_message.php">('.$data['count'].')</a></strong>';
	}
}
?>
<div class="header">
	<h1><a href="index.php">多用户留言</a></h1>
	<ul>
		<li><a href="index.php">首页</a></li>
		<?php  
			if(isset($_COOKIE['username'])) {
				echo '<li><a href="member.php">'.$_COOKIE['username'].'●个人中心'.$GLOBALS['message'].'</a></li> ';
			}else{
				echo '<li><a href="register.php">注册</a></li> ';
				echo '<li><a href="login.php">登录</a></li> ';
			}
		?>
		<li><a href="blog.php">博友</a></li>	
		<li><a href="photo.php">相册</a></li>
		<li><a href="">风格</a></li>
		<?php  
			if(isset($_COOKIE['username']) && isset($_SESSION['admin'])){
				echo '<li><a href="manage.php">管理</a></li> ';
			}
		?>
		<?php  
			if(isset($_COOKIE['username'])) {
				echo '<li><a href="logout.php">退出</a></li>';
			}
		?>
	</ul>
</div>