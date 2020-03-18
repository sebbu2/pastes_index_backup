<?php
if(extension_loaded('xdebig')) {
	//ini_set('xdebug.default_enable', 0);
	//ini_set('xdebug.coverage_enable ', 0);
	//ini_set('xdebug.profiler_enable ', 0);
	ini_set('xdebug.remote_enable ', 0);
}
if(extension_loaded('uopz')) {
	uopz_allow_exit(1);
}
require_once('config.inc.php');
require('functions.inc.php');

function shutdown() {
	global $con, $con2, $con3, $con4;
	echo '<br/><br/>Shutting down.<br/>';
	var_dump($con, $con2, $con3, $con4);
}
register_shutdown_function('shutdown');

$res=file_get_contents('url2.data');
preg_match_all('#<p>([-a-zA-Z0-9 ?:\'’,!.]+)\s*(<strong>\((?:Completed|Suspend)\)</strong>)?\s*<small class="content is-small"><code><abbr class="timeago" title="([^"]+)">([^<]+)</abbr></code></small></p>\s*<ul>\s*<li>\s*(<a(?: target="_blank")? href="(?:[^"]+)">(?:[^<]+)</a>\s*(?:\|\s*)?|<strong>Missing (?:\d+(?:-\d+)?)</strong>\s*)+\s*</li>\s*</ul>#isU', $res, $matches);

$data2=$matches[0];
array_shift($matches);
array_pop($matches);
array_pop($matches);

var_dump(count($matches[0]));

$links=$links2=array();
foreach($data2 as $k=>$d)
{
	preg_match_all('#<a(?: target="_blank")? href="([^"]+)">([^<]+)</a>#isU',$d, $matches3);
	array_shift($matches3);
	$links[$matches[0][$k]]=$matches3[1];
}
$novels=array_filter(array_map('trim',file('novels.txt')));
//var_dump($novels);
function skip($novel) {
	global $novels;
	return in_array($novel,$novels);
}
function skip2($novel) {
	global $novels;
	return !in_array($novel,$novels);
}
$links3=array_filter($links, 'skip', ARRAY_FILTER_USE_KEY);
$links4=array_filter($links, 'skip2', ARRAY_FILTER_USE_KEY);

$coef=0;
if(SAVEHTM) ++$coef;
if(SAVEGZ) ++$coef;

flush();

$name2='';
$con=0;

foreach($links3 as $name => $pastes) {
	$name2=$name;
	//$name2=str_replace(array('\''),'’', $name2);
	$name2=str_replace(array('’'),'\'', $name2);
	$name2=str_replace(array(':'),'-', $name2);
	$name2=str_replace(array('?'),'', $name2);
	$name2=preg_replace('#\.+$#','',$name2);
	if(!file_exists($name2)) {
		echo '<br/>'.$name2.' is missing.<br/><br/>';
		continue;
	}
	var_dump($name2);
	//var_dump($pastes);
	$files=glob($name2.'/*');
	//var_dump($files);//die();
	$found_total=0;
	foreach($files as $file) {
		$found=false;
		if($found_total<$coef*count($pastes)) {
			foreach($pastes as $paste) {
				if(strpos($file, $paste)!==FALSE) {
					$found=true;
					++$found_total;
					break;
				}
			}
			if(!$found) {
				++$con;
				var_dump($file);
				unlink($file);
			}
		}
		else {
			++$con;
			var_dump($file);
			unlink($file);
		}
	}
	//if($con>=1) die();
	flush();
}
flush();
echo 'Priority done 1<br/>';

var_dump($con);
$name2='';
$con2=0;

foreach($links4 as $name => $pastes) {
	$name2=$name;
	//$name2=str_replace(array('\''),'’', $name2);
	$name2=str_replace(array('’'),'\'', $name2);
	$name2=str_replace(array(':'),'-', $name2);
	$name2=str_replace(array('?'),'', $name2);
	$name2=preg_replace('#\.+$#','',$name2);
	if(!file_exists('__others/'.$name2)) {
		echo '<br/>'.$name2.' is missing.<br/><br/>';
		continue;
	}
	var_dump($name2);
	//var_dump($pastes);
	$files=glob('__others/'.$name2.'/*');
	//var_dump($files);//die();
	$found_total=0;
	foreach($files as $file) {
		$found=false;
		if($found_total<$coef*count($pastes)) {
			foreach($pastes as $paste) {
				if(strpos($file, $paste)!==FALSE) {
					$found=true;
					++$found_total;
					break;
				}
			}
			if(!$found) {
				++$con2;
				var_dump($file);
				unlink($file);
			}
		}
		else {
			++$con2;
			var_dump($file);
			unlink($file);
		}
	}
	//if($con2>=1) die();
	flush();
}
flush();
echo 'Completely done 1<br/>';

var_dump($con2);

$res=file_get_contents('urlb2.data');
preg_match_all('#<p>([-a-zA-Z0-9 ?:\'’,!.]+)\s*(<strong>\((?:Completed|Suspend)\)</strong>)?\s*<small class="content is-small"><code><abbr class="timeago" title="([^"]+)">([^<]+)</abbr></code></small></p>\s*<ul>\s*<li>\s*(<a(?: target="_blank")? href="(?:[^"]+)">(?:[^<]+)</a>\s*(?:\|\s*)?|<strong>Missing (?:\d+(?:-\d+)?)</strong>\s*)+\s*</li>\s*</ul>#isU', $res, $matches);

$data2=$matches[0];
array_shift($matches);
array_pop($matches);
array_pop($matches);

var_dump(count($matches[0]));

$links=$links2=array();
foreach($data2 as $k=>$d)
{
	preg_match_all('#<a(?: target="_blank")? href="([^"]+)">([^<]+)</a>#isU',$d, $matches3);
	array_shift($matches3);
	$links[$matches[0][$k]]=$matches3[1];
}
$novels=array_filter(array_map('trim',file('novels2.txt')));
//var_dump($novels);

$links3=array_filter($links, 'skip', ARRAY_FILTER_USE_KEY);
$links4=array_filter($links, 'skip2', ARRAY_FILTER_USE_KEY);

$name2='';
$con3=0;

foreach($links3 as $name => $pastes) {
	$name2=$name;
	//$name2=str_replace(array('\''),'’', $name2);
	$name2=str_replace(array('’'),'\'', $name2);
	$name2=str_replace(array(':'),'-', $name2);
	$name2=str_replace(array('?'),'', $name2);
	$name2=preg_replace('#\.+$#','',$name2);
	if(!file_exists($name2)) {
		echo '<br/>'.$name2.' is missing.<br/><br/>';
		continue;
	}
	var_dump($name2);
	//var_dump($pastes);
	$files=glob($name2.'/*');
	//var_dump($files);//die();
	$found_total=0;
	foreach($files as $file) {
		$found=false;
		if($found_total<$coef*count($pastes)) {
			foreach($pastes as $paste) {
				if(strpos($file, $paste)!==FALSE) {
					$found=true;
					++$found_total;
					break;
				}
			}
			if(!$found) {
				++$con3;
				var_dump($file);
				unlink($file);
			}
		}
		else {
			++$con3;
			var_dump($file);
			unlink($file);
		}
	}
	//if($con3>=1) die();
	flush();
}
flush();
echo 'Priority done 1<br/>';

var_dump($con3);
$name2='';
$con4=0;

foreach($links4 as $name => $pastes) {
	$name2=$name;
	//$name2=str_replace(array('\''),'’', $name2);
	$name2=str_replace(array('’'),'\'', $name2);
	$name2=str_replace(array(':'),'-', $name2);
	$name2=str_replace(array('?'),'', $name2);
	$name2=preg_replace('#\.+$#','',$name2);
	if(!file_exists('__others/'.$name2)) {
		echo '<br/>'.$name2.' is missing.<br/><br/>';
		continue;
	}
	var_dump($name2);
	//var_dump($pastes);
	$files=glob('__others/'.$name2.'/*');
	//var_dump($files);//die();
	$found_total=0;
	foreach($files as $file) {
		$found=false;
		if($found_total<$coef*count($pastes)) {
			foreach($pastes as $paste) {
				if(strpos($file, $paste)!==FALSE) {
					$found=true;
					++$found_total;
					break;
				}
			}
			if(!$found) {
				++$con4;
				var_dump($file);
				unlink($file);
			}
		}
		else {
			++$con4;
			var_dump($file);
			unlink($file);
		}
	}
	//if($con4>=1) die();
	flush();
}
flush();
echo 'Completely done 2<br/>';

var_dump($con4);

echo '<br/>'.date('Y-m-d H:i:s');
