<?php 
session_start();
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
define("SCRIPT","article"); //定义常量表示本页内容
$link = connect();
//接受处理回复
if(isset($_POST['submit'])) {
	//验证码禁用状态的判断
	if($system['code'] == 1){
		_check_vcode($_POST['vcode'],$_SESSION['vcode']);//判断验证码
	}
	$clean = array();
	$clean['reid'] = $_POST['reid'];
	$clean['type'] = $_POST['type'];
	$clean['title'] = $_POST['title'];
	$clean['content'] = $_POST['content'];
	$clean['username'] = $_COOKIE['username'];
	$clean = _mysql_string($clean);
	$query = "INSERT INTO `tg_article`(reid,tg_username,tg_type,tg_title,tg_content,tg_date) VALUES ('{$clean['reid']}','{$clean['username']}','{$clean['type']}','{$clean['title']}','{$clean['content']}',now())";
	execute($link, $query);
	if(mysqli_affected_rows($link) == 1){
		$sql = "UPDATE `tg_article` SET tg_commendcount=tg_commendcount+1 WHERE id='{$clean['reid']}'";
		execute($link, $sql);
		$clean['id'] = mysqli_insert_id($link);//获取刚刚新增数据的id
		close($link);
		_location('恭喜您，回帖成功!','article.php?id='.$clean['reid']);
	}else{
		close($link);
		_location('很遗憾，回帖失败！','addblog.php');
	}
}
//读取数据
if(isset($_GET['id'])) {
	$sql = "UPDATE `tg_article` SET tg_readcount=tg_readcount+1 WHERE id='{$_GET['id']}' LIMIT 1";
	execute($link, $sql);
	$query = "SELECT * FROM `tg_article` WHERE reid=0 AND id='{$_GET['id']}'";
	$result_content = execute($link, $query);
	if($data_content = mysqli_fetch_assoc($result_content)) {
		$html = array();
		$html['reid'] = $data_content['id'];
		$html['username'] = $data_content['tg_username'];
		$html['title'] = $data_content['tg_title'];
		$html['type'] = $data_content['tg_type'];
		$html['content'] = $data_content['tg_content'];
		$html['readcount'] = $data_content['tg_readcount'];
		$html['commendcount'] = $data_content['tg_commendcount'];
		$html['last_modify_date'] = $data_content['tg_last_modify'];
		$html['date'] = $data_content['tg_date'];

		//创建一个全局变量，做个带参分页
		global $id;
		$id = 'id='.$html['reid'].'&';
		//查用户信息
		$sql = "SELECT tg_id,tg_face,tg_sex,tg_email,tg_url FROM `tg_user` WHERE tg_username='{$html['username']}'";
		$result_user = execute($link, $sql);
		if($data_user = mysqli_fetch_assoc($result_user)) {
			$html['userid'] = $data_user['tg_id'];
			$html['sex'] = $data_user['tg_sex'];
			$html['face'] = $data_user['tg_face'];
			$html['email'] = $data_user['tg_email'];
			$html['url'] = $data_user['tg_url'];
			$html = _html($html);

			//读取回帖
			_paging("SELECT COUNT(id) FROM `tg_article` WHERE reid='{$html['reid']}'",3);
		}else{
			//该用户已被删除
		}
		//var_dump($html);
	}else{
		_alert_back('不存在这个主题！');
	}
}else{
	_alert_back('非法操作！');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/blog.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript">
		window.onload = function() {
			var re = document.getElementsByName("re");
			for(var i=0;i<re.length;i++){
				re[i].onclick = function(){
			 		document.getElementsByTagName("form")[0].title.value=this.title;
			 	};
			}
		};
		function newgdcode(obj,url) {
			obj.src = url + '?nowtime=' + new Date().getTime();
		}
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
	<div class="article">
		<h2>帖子详情</h2>
		<?php if($page == 1) {?>
		<div class="subject">
			<dl>
				<dd class="nav"><?php echo $html['username'] ?>(<?php echo $html['sex'] ?>)[楼主]</dd>
				<dt><img src="<?php echo $html['face'] ?>" alt="<?php echo $html['face'] ?>" /></dt>
				<dd class="message"><a href="javascript:;" name="message" title="<?php echo $html['userid'] ?>">发消息</a></dd>
				<dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $html['userid'] ?>">加好友</a></dd>
				<dd class="guset"><a href="javascript:addFlower(<?php echo $html['userid'] ?>)" name="flower">送　花</a></dd>
				<dd class="flower">写留言</dd>
				<dd class="email">邮件：<?php echo $html['email'] ?></dd>
				<dd class="url">网址：<?php echo $html['url'] ?></dd>
			</dl>
			<div class="content">
				<div class="user">
					<span>
						<?php
							if(isset($_COOKIE['username'])){
								if($html['username'] == $_COOKIE['username']) {
									echo '【<a href="article_modify.php?id='.$html['reid'].'">编辑</a>】';
								}
							}
						 ?>
					1#
					</span>
					<?php echo $html['username'] ?>　|　发表于：<?php echo $html['date'] ?>
				</div>
				<h3>主题：<?php echo $html['title'] ?> <img src="images/btn<?php echo $html['type']?>.png" alt="btn" /></h3>
				<div class="detail">
					<?php echo $html['content'] ?>
				</div>
				<div class="read">
					<p>最后更新时间:<?php echo $html['last_modify_date'] ?></p>
					阅读量：(<?php echo $html['readcount'] ?>) 评论量：(<?php echo $html['commendcount'] ?>)
				</div>
			</div>
		</div>
		<p class="line"></p>
		<?php } ?>
		<?php
			$i = 2;//楼层参数
			$query = "SELECT * FROM `tg_article` WHERE reid='{$html['reid']}' ORDER BY tg_date ASC LIMIT $pagenum,$pagesize";
			$result = execute($link, $query);
			while ($data = mysqli_fetch_assoc($result)) {
				$html['username'] = $data['tg_username'];
				$html['type'] = $data['tg_type'];
				$html['retitle'] = $data['tg_title'];
				$html['content'] = $data['tg_content'];
				$html['date'] = $data['tg_date'];
				//查用户信息
				$sql = "SELECT tg_id,tg_face,tg_sex,tg_email,tg_url FROM `tg_user` WHERE tg_username='{$html['username']}'";
				$result_user = execute($link, $sql);
				if($data_user = mysqli_fetch_assoc($result_user)) {
					$html['userid'] = $data_user['tg_id'];
					$html['sex'] = $data_user['tg_sex'];
					$html['face'] = $data_user['tg_face'];
					$html['email'] = $data_user['tg_email'];
					$html['url'] = $data_user['tg_url'];
					$html = _html($html);
				}else{
					//该用户可能被删除
				}
		?>
		<div class="subject">
			<dl>
				<dd class="nav"><?php echo $html['username'] ?>(<?php echo $html['sex'] ?>)</dd>
				<dt><img src="<?php echo $html['face'] ?>" alt="<?php echo $html['face'] ?>" /></dt>
				<dd class="message"><a href="javascript:;" name="message" title="<?php echo $html['userid'] ?>">发消息</a></dd>
				<dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $html['userid'] ?>">加好友</a></dd>
				<dd class="guset"><a href="javascript:addFlower(<?php echo $html['userid'] ?>)" name="flower">送　花</a></dd>
				<dd class="flower">写留言</dd>
				<dd class="email">邮件：<?php echo $html['email'] ?></dd>
				<dd class="url">网址：<?php echo $html['url'] ?></dd>
			</dl>
			<div class="content">
				<div class="user">
					<?php if(isset($_COOKIE['username'])){echo '<span><a href="#ree" name="re" title="回复'.($i+(($page-1)*$pagesize)).'楼的'.$html['username'].'">回复</a></span>';}?>
					<span><?php echo $i+(($page-1)*$pagesize); ?>#</span><?php echo $html['username'] ?>　|　发表于：<?php echo $html['date'] ?>
				</div>
				<h3>主题：<?php echo $html['retitle'] ?> <img src="images/btn<?php echo $html['type']?>.png" alt="btn" /></h3>
				<div class="detail">
					<?php echo $html['content'] ?>
				</div>
			</div>
		</div>
		<p class="line"></p>
		<?php 
			$i++; 
			}
			mysqli_free_result($result);// 销毁结果集，释放结果内存 
			_page(1,'条帖子');
		?>
		
		<?php if(isset($_COOKIE['username'])) {?>
		<div class="add">
		<form action="" method="post" name="addblog">
			<input type="hidden" name="reid" value="<?php echo $html['reid'] ?>" />
			<input type="hidden" name="type" value="<?php echo $html['type'] ?>" />
			<dl>
				<dd>标　题：<input type="text" name="title" value="回复:<?php echo $html['title'] ?>" />　(*必填，2-40位)</dd>
				<dd>
					<textarea name="content"></textarea>
				</dd>
				<?php if($system['code'] == 1) { ?>
				<dd>验 证 码：
					<img src="show_code.php" alt="看不清楚，换一张" align="absmiddle" onclick="javascript:newgdcode(this,this.src);" class="code" />
					<input type="text" name="vcode" style="width: 90px" />
				</dd>
				<?php } ?>
				<dd>
					<input type="submit" name="submit" value="发布" />
				</dd>
			</dl>
		</form>		
		</div>
		<?php } ?>
	</div>
	<a name="ree"></a>
	<?php require ROOT_PATH.'includes/footer.inc.php' ?>
</body>
</html>