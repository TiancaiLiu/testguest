<?php
//错误信息提示函数  
function _alert_back($info) {
	echo "<script type='text/javascript'>alert('".$info."');history.back();</script>";
	exit();
};
//跳转
function _location($info, $url){
	echo "<script type='text/javascript'>alert('$info');location.href='$url';</script>";
};
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
?>