<?php
$config = require 'config.ini.php';
//设定项目配置
$array = array(
		//语言包
		'LANG_SWITCH_ON' => true,
		'DEFAULT_LANG' => 'zh-cn', // 默认语言
		'LANG_AUTO_DETECT' => false, // 自动侦测语言
		'LANG_LIST' => 'zh-cn', //必须写可允许的语言列表
		//分组配置
		'APP_GROUP_LIST' => 'Home,Mobile,Wap,Api,Tool', //Home 默认网页平台、Moblie智能手机平台、Wap普通手机平台、Api网站接口平台
		'DEFAULT_GROUP' => 'Home',
		//路由配置
		'URL_ROUTER_ON' => true, // 是否开启URL路由
		'URL_ROUTE_RULES' => include CONF_PATH . '/router.php', // 默认路由规则，注：分组配置无法替代
);
//合并输出配置
return array_merge($config, $array);
?>