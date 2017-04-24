<?php  
if(!defined("IN_TG")) {
	exit('Access Defined!');
}
?>
<div class="header">
	<h1><a href="index.php">多用户留言</a></h1>
	<ul>
		<li><a href="index.php">首页</a></li>
		<?php  
			if(isset($_COOKIE['username'])) {
				echo '<li><a href="member.php">'.$_COOKIE['username'].'●个人中心</a></li> ';
			}else{
				echo '<li><a href="register.php">注册</a></li> ';
				echo '<li><a href="login.php">登录</a></li> ';
			}
		?>
		<li><a href="blog.php">博友</a></li>	
		<li><a href="">风格</a></li>
		<li><a href="">管理</a></li>
		<?php  
			if(isset($_COOKIE['username'])) {
				echo '<li><a href="logout.php">退出</a></li>';
			}
		?>
	</ul>
</div>