<?php  
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","index");//定义常量表示本页内容
$link = connect();
//读取xml(该方法写在核心函数库中)
$html = _html(_get_xml('new.xml'));
//print_r($html);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>Document</title>
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/blog.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript">
		function addFlower(touserid){
			//alert(touserid);
			var url = "flower.php";
			var data = {"touserid":touserid};
			var success = function(response){
				if(response.errno == 0){
					alert("送花成功！");
				}else{
					alert("送花失败！");
				}
			};
			$.post(url, data, success, "json");
		}
	</script>
</head>
<body>
<?php require ROOT_PATH.'includes/header.inc.php' ?>
	<div class="list">
		<h2>帖子列表</h2>
	</div>
	<div class="user">
		<h2>新进会员</h2>
		<dl>
			<dd class="nav"><?php echo $html['username'] ?>(<?php echo $html['sex'] ?>)</dd>
			<dt><img src="<?php echo $html['face'] ?>" alt="<?php echo $html['face'] ?>" /></dt>
			<dd class="message"><a href="javascript:;" name="message" title="<?php echo $html['id'] ?>">发消息</a></dd>
			<dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $html['id'] ?>">加好友</a></dd>
			<dd class="guset"><a href="javascript:addFlower(<?php echo $html['id'] ?>)" name="flower">送　花</a></dd>
			<dd class="flower">写留言</dd>
			<dd class="email">邮件：<?php echo $html['email'] ?></dd>
			<dd class="url">网址：<?php echo $html['url'] ?></dd>
		</dl>
	</div>
	<div class="pics">
		<h2>我的相册</h2>
	</div>
<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>