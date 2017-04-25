<?php
//错误信息提示函数  
function _alert_back($info) {
	echo "<script type='text/javascript'>alert('".$info."');history.back();</script>";
	exit();
};
//跳转
function _location($info, $url){
	if(!empty($info)){
		echo "<script type='text/javascript'>alert('$info');location.href='$url';</script>";
		exit();
	}else {
		header('Location:'.$url);
	}
};
//登录状态的判断
function _login_state() {
	if(isset($_COOKIE['username'])) {
		_alert_back('您已经登录，请不要重复登录或注册！');
	}
}
//验证码
function _check_vcode($first_vcode,$end_vcode) {
	if($first_vcode != $end_vcode) {
		_alert_back('验证码不正确!');
	}
}
//判断表单是否需要转义
function _mysql_string($string) {
	if(!GPC) {
		return @mysqli_real_escape_string($string);
	}
	return $string;
}
//创建唯一标识符
function _sha1_uniqid() {
	return sha1(uniqid(rand(),true));
}
//分页函数参数
function _paging($sql, $size) {
	//全部都需要定义为全局变量才能访问的到
	global $page,$num,$pageabsolute,$pagesize,$pagenum,$link;
	if(isset($_GET['page'])) {
		$page = $_GET['page'];
		if(empty($page) || $page < 0 || !is_numeric($page)) {
			$page = 1;
		}else{
			$page = intval($page);
		}
	}else{
		$page = 1;
	}
	$pagesize = $size;
	$query = $sql;
	$num = num($link, $query);
	if($num == 0){
		$pageabsolute = 1;
	}else{
		$pageabsolute  = ceil($num / $pagesize);//计算分页数
	}
	if($page > $pageabsolute) {
		$page = $pageabsolute;
	}
	$pagenum = ($page-1) * $pagesize; //每页从第几条开始
}

//分页函数
function _page($type) {
	//这里如果不声明全局变量的话，该函数将无法获得$page,$pageabsolute,$num的值(可以用传递参数的办法，但是比较麻烦)
	global $page,$pageabsolute,$num;
	if($type == 1){
		echo '<div class="page">';
		echo '<ul>'; 
			for($i=0;$i<$pageabsolute;$i++) {
				if($page == ($i+1)){
					echo '<li><a href="'.SCRIPT.'.php?page='.($i+1).'" class="selected">'.($i+1).'</a></li>';
				}else{
					echo '<li><a href="'.SCRIPT.'.php?page='.($i+1).'">'.($i+1).'</a></li>';
				}
			}
		echo '</ul>';
		echo '</div>';
	}elseif ($type == 2) {
		echo '<div class="page_text">';
		echo '<ul>';
		echo '<li>'.$page.'/'.$pageabsolute.' 页 |</li>';
		echo '<li>共有<strong>'.$num.'</strong>个会员 |</li>';
					if($page == 1){
						echo '<li> 首页 |</li>';
						echo '<li> 上一页 |</li>';
					}else{
						echo '<li><a href="'.SCRIPT.'.php"> 首页 </a>|</li>';
						echo '<li><a href="'.SCRIPT.'.php?page='.($page-1).'"> 上一页 </a>|</li>';
					}
					if($page == $pageabsolute) {
						echo '<li> 下一页 |</li>';
						echo '<li> 尾页 |</li>';
					}else{
						echo '<li><a href="'.SCRIPT.'.php?page='.($page+1).'"> 下一页 </a>|</li>';
						echo '<li><a href="'.SCRIPT.'.php?page='.$pageabsolute.'"> 尾页 </a>|</li>';
					}	
		echo '</ul>';
		echo '</div>';
	}
}
?>