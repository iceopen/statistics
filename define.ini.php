<?php
header("Content-Type:text/html;charset=utf-8");
define('APP_DEBUG', true);
if(APP_DEBUG){
	ini_set('html_errors',true);
	ini_set('display_errors',true);
}
//由于多应用情况下，目录由应用自己设置，核心系统就需要定义主程序的路径
define("SITE_PATH"	,	str_ireplace('\\','/',dirname(__FILE__)));
function SD($data){
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}
?>