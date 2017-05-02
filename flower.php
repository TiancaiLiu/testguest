<?php  
define("IN_TG", true);//定义一个常量，防止恶意调用includes里的内部文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
$link = connect();
$touserid = $_POST['touserid'];
$query = "UPDATE `tg_user` SET flower=flower+1 WHERE tg_id=$touserid";
execute($link, $query);
if(mysqli_affected_rows($link) == 1) {
	$response = array(
			'errno' => '0',
			'errmsg' => 'success',
			'data' => true,
		);
}else{
	$response = array(
			'errno' => '-1',
			'errmsg' => 'fail',
			'data' => false,
		);
}
echo json_encode($response);
?>
