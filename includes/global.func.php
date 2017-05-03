<?php
//错误信息提示函数  
function _alert_back($info) {
	echo "<script type='text/javascript'>alert('".$info."');history.back();</script>";
	exit();
};

//弹出并关闭自己
function _alert_close($info) {
	echo "<script type='text/javascript'>alert('".$info."');window.close();</script>";
	exit();
}
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
		if(is_array($string)) {
			foreach ($string as $key => $value) {
				$string[$key] = _mysql_string($value);
			}
		}else{
			$string = @mysqli_real_escape_string($string);
		}
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
		if(empty($page) || $page <= 0 || !is_numeric($page)) {
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
function _page($type,$content='个会员') {
	//这里如果不声明全局变量的话，该函数将无法获得$page,$pageabsolute,$num的值(可以用传递参数的办法，但是比较麻烦)
	global $page,$pageabsolute,$num,$id;
	if($type == 1){
		echo '<div class="page">';
		echo '<ul>'; 
			for($i=0;$i<$pageabsolute;$i++) {
				if($page == ($i+1)){
					echo '<li><a href="'.SCRIPT.'.php?'.$id.'page='.($i+1).'" class="selected">'.($i+1).'</a></li>';
				}else{
					echo '<li><a href="'.SCRIPT.'.php?'.$id.'page='.($i+1).'">'.($i+1).'</a></li>';
				}
			}
		echo '</ul>';
		echo '</div>';
	}elseif ($type == 2) {
		echo '<div class="page_text">';
		echo '<ul>';
		echo '<li>'.$page.'/'.$pageabsolute.' 页 |</li>';
		echo '<li>共有<strong>'.$num.'</strong>'.$content.' |</li>';
					if($page == 1){
						echo '<li> 首页 |</li>';
						echo '<li> 上一页 |</li>';
					}else{
						echo '<li><a href="'.SCRIPT.'.php"> 首页 </a>|</li>';
						echo '<li><a href="'.SCRIPT.'.php?'.$id.'page='.($page-1).'"> 上一页 </a>|</li>';
					}
					if($page == $pageabsolute) {
						echo '<li> 下一页 |</li>';
						echo '<li> 尾页 |</li>';
					}else{
						echo '<li><a href="'.SCRIPT.'.php?'.$id.'page='.($page+1).'"> 下一页 </a>|</li>';
						echo '<li><a href="'.SCRIPT.'.php?'.$id.'page='.$pageabsolute.'"> 尾页 </a>|</li>';
					}	
		echo '</ul>';
		echo '</div>';
	}
}
//字符串过滤(用到递归调用)
function _html($string) {
	if(is_array($string)) {
		foreach ($string as $key => $value) {
			$string[$key] = _html($value);
		}
	}else{
		$string = htmlspecialchars($string);
	}
	return $string;
}
//省略显示
function _title($string) {
	if(mb_strlen($string,'utf-8') > 4){
		$string = mb_substr($string, 0, 4, 'utf-8').'...';
	}
	return $string;
}

//设置xml
function _set_xml($xmlfile, $clean) {
	$fp = @fopen('new.xml', 'w');
	if(!$fp) {
		exit('系统错误，文件不存在！');
	}
	flock($fp, LOCK_EX);
	$string = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\r\n";
	fwrite($fp, $string, strlen($string));
	$string = "<vip>\r\n";
	fwrite($fp, $string, strlen($string));
	$string = "\t<id>{$clean['id']}</id>\r\n";
	fwrite($fp, $string, strlen($string));
	$string = "\t<username>{$clean['username']}</username>\r\n";
	fwrite($fp, $string, strlen($string));
	$string = "\t<sex>{$clean['sex']}</sex>\r\n";
	fwrite($fp, $string, strlen($string));
	$string = "\t<face>{$clean['face']}</face>\r\n";
	fwrite($fp, $string, strlen($string));
	$string = "\t<email>{$clean['email']}</email>\r\n";
	fwrite($fp, $string, strlen($string));
	$string = "\t<url>{$clean['url']}</url>\r\n";
	fwrite($fp, $string, strlen($string));
	$string = "</vip>";
	fwrite($fp, $string, strlen($string));
	flock($fp,LOCK_UN);
	fclose($fp);
}
//获取xml
function _get_xml($xmlfile) {
	$html = array();
	if(file_exists($xmlfile)) {
		$xml = file_get_contents($xmlfile);
		preg_match_all('/<vip>(.*)<\/vip>/s', $xml, $dom);
		//var_dump($dom);
		foreach ($dom[1] as $value) {
			preg_match_all('/<id>(.*)<\/id>/s', $value, $id);
			preg_match_all('/<username>(.*)<\/username>/s', $value, $username);
			preg_match_all('/<sex>(.*)<\/sex>/s', $value, $sex);
			preg_match_all('/<face>(.*)<\/face>/s', $value, $face);
			preg_match_all('/<email>(.*)<\/email>/s', $value, $email);
			preg_match_all('/<url>(.*)<\/url>/s', $value, $url);
			$html['id'] = $id[1][0];
			$html['username'] = $username[1][0];
			$html['sex'] = $sex[1][0];
			$html['face'] = $face[1][0];
			$html['email'] = $email[1][0];
			$html['url'] = $url[1][0];
		}
	}else{
		echo '文件不存在！';
	}
	return $html;
}
?>