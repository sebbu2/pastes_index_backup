<?php
require_once('config.inc.php');
require('functions.inc.php');
$url = URL;
$url_info = parse_url($url);

$ar=array();
$msg = '';
if(!file_exists('url1.data')) {
	$res = get_url($url);
	$ar = $GLOBALS['ar'];
	$msg = $GLOBALS['msg'];
	sleep(5);
	if($msg==='0 - ') file_put_contents('url1.data', $res);
}
else {
	$res = file_get_contents('url1.data');
}
var_dump($msg);
var_dump($ar);

//preg_match('#<form[^>]+action="([^"]+)"[^>]*>(.*)</form>#is', $res, $matches);
preg_match_all('#<form(?:\s+(?:id=["\'](?P<id>[^"\'<>]*)["\']|action=["\'](?P<action>[^"\'<>]*)["\']|\w+=["\'][^"\'<>]*["\']|))+>(?P<content>.*)</form>#isU', $res, $matches);
if(count(array_filter($matches['action']))==0) {
	preg_match('#<title>([^<]+)</title>#',$res, $matches2);
	var_dump($matches2[1]);
	copy('url1.data', 'url2.data');
	die();
}
else
{
	$challenge = cf_bypass_solve_challenge($res);
	$id = array_search('/login/login',$matches['action']);
	$content = $matches['content'][$id];
	preg_match_all('#<input[^>]+name="([^"]+)"(\s*value="([^"]*)"|[^>])*>#is', $content, $matches2);

	$postdata='';
	foreach($matches2[1] as $k=>$v)
	{
		if(strlen($postdata)!=0) $postdata.='&';
		$postdata.=$v.'=';
		if($v!='jschl_answer') $postdata.=urlencode($matches2[3][$k]);
		else $postdata.=$challenge;
	}

	$url2=substr($url, 0, -1).$matches[1];

	if(!file_exists('url2.data')) {
		$res=get_url($url2, $postdata);
		$ar = $GLOBALS['ar'];
		$msg = $GLOBALS['msg'];
		sleep(5);
		if($msg==='0 - ') file_put_contents('url2.data', $res);
	}
	else {
		$res = file_get_contents('url2.data');
	}
	var_dump($msg);
	var_dump($ar);

	preg_match('#<title>([^<]+)</title>#',$res, $matches2);
	var_dump($matches2[1]);

	if(false) {
		$challenge = cf_bypass_solve_challenge($res);
		preg_match('#<form[^>]+action="([^"]+)"[^>]*>(.*)</form>#is', $res, $matches);
		preg_match_all('#<input[^>]+name="([^"]+)"(\s*value="([^"]*)"|[^>])*>#is', $matches[2], $matches2);
		$postdata='';
		foreach($matches2[1] as $k=>$v)
		{
			if(strlen($postdata)!=0) $postdata.='&';
			$postdata.=$v.'=';
			if($v!='jschl_answer') $postdata.=urlencode($matches2[3][$k]);
			else $postdata.=$challenge;
		}
		$url3=substr($url, 0, -1).$matches[1];

		if(!file_exists('url3.data')) {
			$res=get_url($url3, $postdata);
			$ar = $GLOBALS['ar'];
			$msg = $GLOBALS['msg'];
			sleep(5);
			if($msg==='0 - ') file_put_contents('url3.data', $res);
		}
		else {
			$res = file_get_contents('url3.data');
		}
		var_dump($msg);
		var_dump($ar);
		preg_match('#<title>([^<]+)</title>#',$res, $matches2);
		var_dump($matches2[1]);
	}
}
echo '<br/>'.date('Y-m-d H:i:s');
?>