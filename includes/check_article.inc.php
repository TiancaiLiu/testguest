<?php  
function _check_title($string, $min, $max) {
	$string = trim($string); //去掉两边多余空格
	//对长度的判断
	if(mb_strlen($string,'utf-8') < $min || mb_strlen($string,'utf-8') > $max) {
		_alert_back('标题长度不得小于'.$min.'位或者大于'.$max.'位');
	}
	return _mysql_string($string);
}
?>