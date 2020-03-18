<?php
//supposed to show novels that aren't in both list, nor in the 2 index pages (ie, removed from it)
require_once('config.inc.php');
require('functions.inc.php');

function shutdown() {
	global $con, $con2, $con3, $con4;
	echo '<br/><br/>Shutting down.<br/>';
	var_dump($con, $con2, $con3, $con4);
}
//register_shutdown_function('shutdown');

$res=file_get_contents('url2.data');
preg_match_all('#<p>([-a-zA-Z0-9 ?:\'’,!.]+)\s*(<strong>\((?:Completed|Suspend)\)</strong>)?\s*<small class="content is-small"><code><abbr class="timeago" title="([^"]+)">([^<]+)</abbr></code></small></p>\s*<ul>\s*<li>\s*(<a(?: target="_blank")? href="(?:[^"]+)">(?:[^<]+)</a>\s*(?:\|\s*)?|<strong>Missing (?:\d+(?:-\d+)?)</strong>\s*)+\s*</li>\s*</ul>#isU', $res, $matches);

$novels1=$matches[1];

$res=file_get_contents('urlb2.data');
preg_match_all('#<p>([-a-zA-Z0-9 ?:\'’,!.]+)\s*(<strong>\((?:Completed|Suspend)\)</strong>)?\s*<small class="content is-small"><code><abbr class="timeago" title="([^"]+)">([^<]+)</abbr></code></small></p>\s*<ul>\s*<li>\s*(<a(?: target="_blank")? href="(?:[^"]+)">(?:[^<]+)</a>\s*(?:\|\s*)?|<strong>Missing (?:\d+(?:-\d+)?)</strong>\s*)+\s*</li>\s*</ul>#isU', $res, $matches);

$novels2=$matches[1];
unset($matches);

function ren($name) {
	$name2=$name;
	$name2=str_replace(array('’'),'\'', $name2);
	$name2=str_replace(array(':'),'-', $name2);
	$name2=str_replace(array('?'),'', $name2);
	$name2=preg_replace('#\.+$#','',$name2);
	return $name2;
}
$novels=array_merge($novels1, $novels2);
$novels=array_map('ren', $novels);

$dirs=glob('*');
$dirs=array_filter($dirs, 'is_dir');

$rest=array_diff($dirs, $novels, array('__old','__others'));

var_dump($rest);

chdir('__others');
$dirs=glob('*');
$dirs=array_filter($dirs, 'is_dir');

$rest=array_diff($dirs, $novels, array('__old','__others'));
chdir('..');
var_dump($rest);

echo '<br/>'.date('Y-m-d H:i:s');
