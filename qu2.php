<?php
require_once('config.inc.php');
require('functions.inc.php');

function shutdown() {
	global $con, $con2;
	echo '<br/><br/>Shutting down.<br/>';
	var_dump($con, $con2);
}
register_shutdown_function('shutdown');

$res=file_get_contents('url2.data');
preg_match_all('#<p>([-a-zA-Z0-9 ?:\'’,!.]+)\s*(<strong>\((?:Completed|Suspend)\)</strong>)?\s*<small class="content is-small"><code><abbr class="timeago" title="([^"]+)">([^<]+)</abbr></code></small></p>\s*<ul>\s*<li>\s*(<a(?: target="_blank")? href="(?:[^"]+)">(?:[^<]+)</a>\s*(?:\|\s*)?|<strong>Missing (?:\d+(?:-\d+)?)</strong>\s*)+\s*</li>\s*</ul>#isU', $res, $matches);

$data2=$matches[0];
array_shift($matches);
array_pop($matches);
array_pop($matches);

var_dump(count($matches[0]));

/*echo '<table border="1">'."\r\n";
echo "\t".'<tr>'."\r\n";
echo "\t\t".'<th>Novel</th>'."\r\n";
echo "\t\t".'<th>Status</th>'."\r\n";
echo "\t\t".'<th>Last Modified</th>'."\r\n";
echo "\t".'</tr>'."\r\n";
for($i=0;$i<count($matches[0]);++$i) {
	echo "\t".'<tr>'."\r\n";
	echo "\t\t".'<td>'.$matches[0][$i].'</td>'."\r\n";
	echo "\t\t".'<td>'.strip_tags($matches[1][$i]).'</td>'."\r\n";
	echo "\t\t".'<td>'.$matches[2][$i].'</td>'."\r\n";
	echo "\t".'</tr>'."\r\n";
}
echo '</table>'."\r\n";//*/

$links=$links2=array();
foreach($data2 as $k=>$d)
{
	preg_match_all('#<a(?: target="_blank")? href="([^"]+)">([^<]+)</a>#isU',$d, $matches3);
	array_shift($matches3);
	$links[$matches[0][$k]]=array_combine($matches3[1],$matches3[0]);
	$links2=array_merge($links2,$matches3[0]);
}
$novels=array_filter(array_map('trim',file('novels.txt')));
var_dump($novels);
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

flush();

$name2='';
$con=0;
$l='a';

foreach($links3 as $name => $links2) {
	$name2=$name;
	//$name2=str_replace(array('\''),'’', $name2);
	$name2=str_replace(array('’'),'\'', $name2);
	$name2=str_replace(array(':'),'-', $name2);
	$name2=str_replace(array('?'),'', $name2);
	$name2=preg_replace('#\.+$#','',$name2);
	if(!file_exists($name2)) {
		if(file_exists('__others/'.$name2)) {
			rename('__others/'.$name2, $name2);
		}
		else {
			mkdir($name2);
		}
	}
	else if(file_exists('__others/'.$name2)) {
		var_dump('__others/'.$name2);
	}
	if(substr($name2, 0, 1)!=$l) {
		var_dump($l);flush();
		$l=substr($name2, 0, 1);
	}
	foreach($links2 as $k => $v) {
		$n = $name2.'/'.$k.'.htm';
		$n2 = $n.'.gz';
		if( (!SAVEHTM || file_exists($n)) && (!SAVEGZ || file_exists($n2)) ) continue;
		++$con;
		$data3 = get_paste2($v);
		if($data3===false) {
			var_dump($n, $v);
			echo '<br/>';
			continue;
		}
		$data4 = zlib_decode($data3);
		//$crc=sprintf("%08x",crc32($data4));
		$crc=hash('crc32b', $data4);
		$crc=letobe($crc);
		$crc=hex2bin($crc);
		$size=strlen($data4);
		$size=sprintf("%08x",$size);
		$size=letobe($size);
		$size=hex2bin($size);
		//file_put_contents($n2, "\x1f\x8b\x08\x00\x00\x00\x00\x00".$data3.$crc.$size);//deflate
		if(SAVEGZ) file_put_contents($n2, "\x1f\x8b\x08\x00\x00\x00\x00\x00\x00\x00".$data3.$crc.$size);//zlib
		if(SAVEHTM) file_put_contents($n, $data4);
	}
	flush();
}
if(true) {
	var_dump($l);flush();
	$l=substr($name2, 0, 1);
}
flush();
echo 'Priority done<br/>';

var_dump($con);
$con2=0;
$l='a';

foreach($links4 as $name => $links2) {
	$name2=$name;
	//$name2=str_replace(array('\''),'’', $name2);
	$name2=str_replace(array('’'),'\'', $name2);
	$name2=str_replace(array(':'),'-', $name2);
	$name2=str_replace(array('?'),'', $name2);
	$name2=preg_replace('#\.+$#','',$name2);
	if(!file_exists('__others/'.$name2)) 
		if(!file_exists($name2)) {
			rename($name2, '__others/'.$name2);
		}
		else {
			mkdir('__others/'.$name2);
		}
	}
	else if(file_exists($name2)) {
		var_dump($name2);
	}
	if(substr($name2, 0, 1)!=$l) {
		var_dump($l);flush();
		$l=substr($name2, 0, 1);
	}
	foreach($links2 as $k => $v) {
		$n = '__others/'.$name2.'/'.$k.'.htm';
		$n2 = $n.'.gz';
		if( (!SAVEHTM || file_exists($n)) && (!SAVEGZ || file_exists($n2)) ) continue;
		++$con2;
		$data3 = get_paste2($v);
		if($data3===false) {
			var_dump($n, $v);
			echo '<br/>';
			continue;
		}
		$data4 = zlib_decode($data3);
		$crc=hash('crc32b', $data4);
		$crc=letobe($crc);
		$crc=hex2bin($crc);
		$size=strlen($data4);
		$size=sprintf("%08x",$size);
		$size=letobe($size);
		$size=hex2bin($size);
		//file_put_contents($n2, "\x1f\x8b\x08\x00\x00\x00\x00\x00".$data3.$crc.$size);//deflate
		if(SAVEGZ) file_put_contents($n2, "\x1f\x8b\x08\x00\x00\x00\x00\x00\x00\x00".$data3.$crc.$size);//zlib
		if(SAVEHTM) file_put_contents($n, $data4);
	}
	flush();
}
if(true) {
	var_dump($l);flush();
	$l=substr($name2, 0, 1);
}
flush();
echo 'Completely done<br/>';

var_dump($con2);
echo '<br/>'.date('Y-m-d H:i:s');
?>