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

/**
 * _check_uniqid 检测唯一标识符
 * @access public
 * @param string $first_uniqid 
 * @param string $end_uniqid
 * @return string 过滤后的标识符
 */
function _check_uniqid($first_uniqid,$end_uniqid) {
	if((strlen($first_uniqid) != 40) || ($first_uniqid != $end_uniqid)) {
		_alert_back('唯一标识符异常');
	}
	return _mysql_string($first_uniqid);
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
	//限制敏感用户名
	$user[0] = '刘敬雄';
	$user[1] = '张三';
	$user[2] = '李四';
	if(in_array($string, $user)) {
		_alert_back('敏感用户名不得注册');
	}
	//将用户名转义,防止sql注入
	return _mysql_string($string);
}

/**
 * _check_password 验证密码
 * @param string $first_pass 表单传值
 * @param string $end_pass 表单传值
 * @param int $min_num 最小位数
 * @return string $first_pass 返回加密后的密码
 */
function _check_password($first_pass, $end_pass, $min_num) {
	if(strlen($first_pass) < $min_num) {
		_alert_back('密码不得少于'.$min_num.'位');
	}
	if($first_pass != $end_pass) {
		_alert_back('两次密码输入不一致');
	}
	return  _mysql_string(sha1(md5($first_pass)));
}

function _check_modify_password($string, $min_num) {
	if(!empty($string)) {
		if(strlen($string) < $min_num) {
			_alert_back('密码不得少于'.$min_num.'位');
		}
	}else{
		return null;
	}
	return _mysql_string(sha1(md5($string)));
}

/**
 * _check_question 验证密码提示
 * @access public
 * @param string $srting 表单传值
 * @param int $min_num 最小位数
 * @param int $max_num 最大位数
 * @return string $string 返回过滤后的密码提示
 */
function _check_question($string, $min_num, $max_num) {
	$string = trim($string);
 	if(mb_strlen($string,'utf-8') < $min_num || mb_strlen($string,'utf-8') > $max_num) {
		_alert_back('密码提示长度不得小于'.$min_num.'位或者大于'.$max_num.'位');
	}
	
	return _mysql_string($string);
}

/**
 * _check_answer 验证回答
 * @access public
 * @param string $question 密码提示
 * @param string $answer 密码回答
 * @param int $min_num 最小位数
 * @param int $max_num 最大位数
 * @return string $string 返回加密后的密码回答
 */
function _check_answer($question, $answer, $min_num, $max_num) {
	$answer = trim($answer);
 	if(mb_strlen($answer,'utf-8') < $min_num || mb_strlen($answer,'utf-8') > $max_num) {
		_alert_back('密码提示长度不得小于'.$min_num.'位或者大于'.$max_num.'位');
	}
	if($question == $answer) {
		_alert_back('密码提示和密码回答不得相同');
	}

	return  _mysql_string(sha1(md5($answer)));
}

/**
 * _check_email 性别
 * @access public
 * @param string $string 表单传值
 * @return string 
 */
function _check_sex($string) {
	return _mysql_string($string);
}

/**
 * _check_email 头像
 * @access public
 * @param string $string 表单传值
 * @return string 
 */
function _check_face($string) {
	return _mysql_string($string);
}

/**
 * _check_email 邮箱校验(正则校验)
 * @access public
 * @param string $string 表单传值
 * @return string $string 返回邮箱
 */
function _check_email($string,$min_num,$max_num) {
	if(!preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/', $string)) {
		_alert_back('邮箱格式不正确！');
	}		
	if(mb_strlen($string) < $min_num || mb_strlen($string) > $max_num) {
		_alert_back('邮箱长度不得不合法！');
	}			
	return  _mysql_string($string);
}

/**
 * _check_qq qq校验(正则校验)
 * @access public
 * @param string $string 表单传值
 * @return string $string 返回邮箱
 */
function _check_qq($string) {
	if(!empty($string)) {
		if(!preg_match('/^[1-9]{1}[0-9]{4,9}$/', $string)) {
			_alert_back('QQ号码不正确！');
		}		
	}else {
		return null;
	}
	return  _mysql_string($string);
}

/**
 * _check_url 个人主页校验(正则校验)
 * @access public
 * @param string $string 表单传值
 * @return string $string 返回邮箱
 */
function _check_url($string) {
	if(!empty($string) && !($string == "http://")) {
		if(!preg_match('/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/', $string)) {
			_alert_back('网址不正确！');
		}		
	}else {
		return null;
	}
	return  _mysql_string($string);
}
?>