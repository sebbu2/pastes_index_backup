<?php
require_once('config.inc.php');
require('functions.inc.php');
$url = URL2;
$postdata='';
$url_info = parse_url($url);

$ar=array();
$msg = '';
if(!file_exists('urlb1.data')) {
	$res = get_url($url);
	$ar = $GLOBALS['ar'];
	$msg = $GLOBALS['msg'];
	sleep(5);
	if($msg==='0 - ') file_put_contents('urlb1.data', $res);
}
else {
	$res = file_get_contents('urlb1.data');
}
var_dump($msg);
var_dump($ar);

preg_match_all('#<form(?:\s+(?:id=["\'](?P<id>[^"\'<>]*)["\']|action=["\'](?P<action>[^"\'<>]*)["\']|\w+=["\'][^"\'<>]*["\']|))+>(?P<content>.*)</form>#isU', $res, $matches);
if(count(array_filter($matches['action']))==0) {
	preg_match('#<title>([^<]+)</title>#',$res, $matches2);
	var_dump($matches2[1]);
	copy('urlb1.data', 'urlb2.data');
	die();
}
else
{
	$id = array_search('/login/login',$matches['action']);
	$content = $matches['content'][$id];
	preg_match_all('#<input(?:\s+(?:name=["\'](?P<name>[^"\'<>]*)["\']|value=["\'](?P<value>[^"\'<>]*)["\']|\w+=["\'][^"\'<>]*["\']|))+/?>#isU', $content, $matches2, PREG_SET_ORDER);

	$url2 = URL2b;
	$postdata='';
	foreach($matches2 as $ar2) {
		if(strlen($ar2['name'])==0) continue;
		if(strlen($postdata)!=0) $postdata.='&';
		$postdata.=$ar2['name'].'=';
		if(array_key_exists('value', $ar2)) $postdata.=$ar2['value'];
		if($ar2['name']=='_xfRedirect' && $ar2['value']=='') $postdata.=parse_url(URL2, PHP_URL_PATH);
		else if($ar2['name']=='login') $postdata.=LOGIN;
		else if($ar2['name']=='password') $postdata.=PASS;
	}

	$ar=array();
	$msg = '';
	if(!file_exists('urlb2.data')) {
		$res = get_url($url2, $postdata);
		$ar = $GLOBALS['ar'];
		$msg = $GLOBALS['msg'];
		sleep(5);
		if($msg==='0 - ') file_put_contents('urlb2.data', $res);
	}
	else {
		$res = file_get_contents('urlb2.data');
	}
	var_dump($msg);
	var_dump($ar);
	preg_match('#<title>([^<]+)</title>#',$res, $matches2);
	var_dump($matches2[1]);
}
echo '<br/>'.date('Y-m-d H:i:s');
?>