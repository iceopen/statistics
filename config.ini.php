<?php
if (!defined('THINK_PATH')) exit();
return array(
	'DB_TYPE'		=>	'mysql',			//数据库类型
	'DB_HOST'		=>	'localhost',		//服务器地址
	'DB_NAME'		=>	'statistics',	//数据库名
	'DB_USER'		=>	'root',				//用户名
	'DB_PWD'		=>	'',					//密码
	'DB_PREFIX'		=>	's_',				//数据库表前缀
	'DB_CHARSET'	=>	'utf8',
	'URL_MODEL'             => 2,
	'URL_PATHINFO_DEPR'     => '/',
	'APP_AUTOLOAD_PATH' => 'ORG.Util',
);
?>