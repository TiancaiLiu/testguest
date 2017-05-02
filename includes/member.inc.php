<?php  
if(!defined("IN_TG")) {
	exit('Access Defined!');
}
?>
<div class="member_sidebar">
	<h2>导航列表</h2>
	<dl>
		<dt>账号管理</dt>
		<dd><a href="member.php" <?php if(basename($_SERVER['SCRIPT_NAME'])=='member.php'){echo 'class="selected"';}?>>个人信息</a></dd>
		<dd><a href="member_modify.php" <?php if(basename($_SERVER['SCRIPT_NAME'])=='member_modify.php'){echo 'class="selected"';}?>>修改资料</a></dd>
	</dl>
	<dl>
		<dt>其他管理</dt>
		<dd><a href="member_message.php">短信查阅</a></dd>
		<dd><a href="member_friend.php">好友设置</a></dd>
		<dd><a href="#">查询花朵</a></dd>
		<dd><a href="#">个人相册</a></dd>
	</dl>
</div>