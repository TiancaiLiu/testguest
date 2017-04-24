<?php
//防止恶意访问  
if(!defined("IN_TG")) {
	exit('Access Defined!');
}
if(!function_exists('_alert_back')) {
	exit('_alert_back()函数不存在,请检查!');
}
if(!function_exists('_mysql_string')) {
	exit('_mysql_string()函数不存在,请检查!');
}
//设置cookie
function _setcookie($username,$uniqid,$time) {
	switch ($time) {
		case '0':
			setcookie('username', $username);
			setcookie('uniqid', $uniqid);
			break;
		case '1':
			setcookie('username', $username, time()+86400);
			setcookie('uniqid', $uniqid, time()+86400);
			break;
		case '1':
			setcookie('username', $username, time()+604800);
			setcookie('uniqid', $uniqid, time()+604800);
			break;
		case '1':
			setcookie('username', $username, time()+2592000);
			setcookie('uniqid', $uniqid, time()+2592000);
			break;
	}
}
/**
 * _check_username表示检测并过滤用户名
 * @access public
 * @param string $sting 表单传值
 * @param int $min_num 最小位数
 * @param int $max_num 最大位数
 * @return string 过滤后的用户名
 */
function _check_username($string, $min_num, $max_num) {
	$string = trim($string); //去掉两边多余空格
	//对长度的判断
	if(mb_strlen($string,'utf-8') < $min_num || mb_strlen($string,'utf-8') > $max_num) {
		_alert_back('用户名长度不得小于'.$min_num.'位或者大于'.$max_num.'位');
	}
	//限制敏感字符
	$char_pattern = '/[<>\'\"\ \　]/';
	if(preg_match($char_pattern, $string)) {
		_alert_back('用户名不得包含敏感字符');
	}

	//将用户名转义,防止sql注入
	return _mysql_string($string);
}

/**
 * _check_password 验证密码
 * @param string $string 表单传值
 * @param int $min_num 最小位数
 * @return string $string 返回加密后的密码
 */
function _check_password($string, $min_num) {
	if(strlen($string) < $min_num) {
		_alert_back('密码不得少于'.$min_num.'位');
	}
	
	return  _mysql_string(sha1(md5($string)));
}

/**
 * _check_password 验证保留时间
 * @param string $string 表单传值
 * @return string $string 返回值
 */
function _check_time($string) {
	$time = array('0','1','2','3');
	if(!in_array($string, $time)) {
		_alert_back('保留方式出错');
	}
	return _mysql_string($string);
}





?>