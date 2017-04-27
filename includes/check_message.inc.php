<?php  
function _check_content($string){
	if(mb_strlen($string,'utf-8') > 200){
		_alert_back('短信内容不得多于200位');
	}
	return $string;
}
?>