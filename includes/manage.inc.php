<?php  
if(!defined("IN_TG")) {
	exit('Access Defined!');
}
?>
<div class="member_sidebar">
	<h2>管理导航</h2>
	<dl>
		<dt>系统管理</dt>
		<dd><a href="manage.php" <?php if(basename($_SERVER['SCRIPT_NAME'])=='manage.php'){echo 'class="selected"';}?>>后台首页</a></dd>
		<dd><a href="manage_set.php" <?php if(basename($_SERVER['SCRIPT_NAME'])=='manage_set.php'){echo 'class="selected"';}?>>系统设置</a></dd>
	</dl>
	<dl>
		<dt>会员管理</dt>
		<dd><a href="member_list.php">会员列表</a></dd>
		<dd><a href="member_job.php">职务设置</a></dd>
		<dd><a href="#">个人相册</a></dd>
	</dl>
</div>