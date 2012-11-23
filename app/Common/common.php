<?php
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = false)
{
	if (function_exists("mb_substr"))
		$slice = mb_substr($str, $start, $length, $charset);
	elseif (function_exists('iconv_substr')) {
		$slice = iconv_substr($str, $start, $length, $charset);
		if (false === $slice) {
			$slice = '';
		}
	} else {
		$re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("", array_slice($match[0], $start, $length));
	}
	return $suffix ? $slice . '...' : $slice;
}

// 自动转换字符集 支持数组转换
function auto_charset($fContents, $from = 'gbk', $to = 'utf-8')
{
	$from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
	$to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
	if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
		//如果编码相同或者非字符串标量则不转换
		return $fContents;
	}
	if (is_string($fContents)) {
		if (function_exists('mb_convert_encoding')) {
			return mb_convert_encoding($fContents, $to, $from);
		} elseif (function_exists('iconv')) {
			return iconv($from, $to, $fContents);
		} else {
			return $fContents;
		}
	} elseif (is_array($fContents)) {
		foreach ($fContents as $key => $val) {
			$_key = auto_charset($key, $from, $to);
			$fContents[$_key] = auto_charset($val, $from, $to);
			if ($key != $_key)
				unset($fContents[$key]);
		}
		return $fContents;
	}
	else {
		return $fContents;
	}
}

//加密函数
function encrypt($txt, $key = null)
{
	if (empty($key))
		$key = C('SECURE_CODE');
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=+";
	$nh = rand(0, 64);
	$ch = $chars [$nh];
	$mdKey = md5($key . $ch);
	$mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
	$txt = base64_encode($txt);
	$tmp = '';
	$i = 0;
	$j = 0;
	$k = 0;
	for ($i = 0; $i < strlen($txt); $i++) {
		$k = $k == strlen($mdKey) ? 0 : $k;
		$j = ($nh + strpos($chars, $txt [$i]) + ord($mdKey [$k++])) % 64;
		$tmp .= $chars [$j];
	}
	return $ch . $tmp;
}

//解密函数
function decrypt($txt, $key = null)
{
	if (empty($key))
		$key = C('SECURE_CODE');
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=+";
	$ch = $txt [0];
	$nh = strpos($chars, $ch);
	$mdKey = md5($key . $ch);
	$mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
	$txt = substr($txt, 1);
	$tmp = '';
	$i = 0;
	$j = 0;
	$k = 0;
	for ($i = 0; $i < strlen($txt); $i++) {
		$k = $k == strlen($mdKey) ? 0 : $k;
		$j = strpos($chars, $txt [$i]) - $nh - ord($mdKey [$k++]);
		while ($j < 0)
			$j += 64;
		$tmp .= $chars [$j];
	}
	return base64_decode($tmp);
}

/**
 *
 +--------------------------------------------------------------------
 * Description 友好显示时间
 +--------------------------------------------------------------------
 * @param int $time 要格式化的时间戳 默认为当前时间
 +--------------------------------------------------------------------
 * @return string $text 格式化后的时间戳
 +--------------------------------------------------------------------
 * @author yijianqing
 +--------------------------------------------------------------------
 */
function mdate($time = NULL) {
	$text = '';
	$time = $time === NULL || $time > time() ? time() : intval($time);
	$t = time() - $time; //时间差 （秒）
	if ($t == 0){
		$text = '刚刚';
	}else if ($t < 60){
		$text = $t . '秒前'; // 一分钟内
	}else if ($t < 60 * 60){
		$text = floor($t / 60) . '分钟前'; //一小时内
	}else if ($t < 60 * 60 * 24){
		$text = floor($t / (60 * 60)) . '小时前'; // 一天内
	}else if ($t < 60 * 60 * 24 * 2){
		$text = '昨天 ' . date('H:i', $time); //两天内
	}else if ($t < 60 * 60 * 24 * 3){
		$text = '前天 ' . date('H:i', $time); // 三天内
	}else if ($t < 60 * 60 * 24 * 30){
		$text = date('m月d日 H:i', $time); //一个月内
	}else if ($t < 60 * 60 * 24 * 365){
		$text = date('m月d日', $time); //一年内
	}else{
		$text = date('Y年m月d日', $time); //一年以前
	}
	return $text;
}
//压缩html : 清除换行符,清除制表符,去掉注释标记
function htmlClean($string){
	$string = str_replace("\r\n", '', $string); //清除换行符
	$string = str_replace("\n", '', $string); //清除换行符
	$string = str_replace("\t", '', $string); //清除制表符
	$pattern = array (
			"/> *([^ ]*) *</", //去掉注释标记
			"/[\s]+/",
			"/<!--[^!]*-->/",
			"/\" /",
			"/ \"/",
			"'/\*[^*]*\*/'"
	);
	$replace = array (
			">\\1<",
			" ",
			"",
			"\"",
			"\"",
			""
	);
	return preg_replace($pattern, $replace, $string);
}

/**
 * 发送短消息给开发者
 * @param string $msg
 * @author Ice <iceinto@mallog.com.cn>
 */
function sendDevSms($msg){
	file_get_contents('http://quanapi.sinaapp.com/fetion.php?u=15195859371&p=227705lbc&to=13305148882&m=' . $msg);
}

?>