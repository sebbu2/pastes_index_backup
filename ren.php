<?php
require_once('config.inc.php');
$dirs=glob('*');
$dirs=array_filter($dirs, 'is_dir');
$dirs=array_diff($dirs,array('__others'));

foreach($dirs as $dir)
{
	$name2=$dir;
	//$name2=str_replace(array('\''),'’', $name2);
	$name2=str_replace(array('’'),'\'', $name2);
	$name2=str_replace(array(':'),'-', $name2);
	$name2=str_replace(array('?'),'', $name2);
	$name2=preg_replace('#\.+$#','',$name2);
	if($dir !== $name2)
	{
		rename($dir, $name2);
		var_dump($name2);
	}
}
$dirs2=glob('__others/*');
$dirs2=array_filter($dirs2, 'is_dir');
foreach($dirs2 as $dir)
{
	$name2=$dir;
	//$name2=str_replace(array('\''),'’', $name2);
	$name2=str_replace(array('’'),'\'', $name2);
	$name2=str_replace(array(':'),'-', $name2);
	$name2=str_replace(array('?'),'', $name2);
	$name2=preg_replace('#\.+$#','',$name2);
	if($dir !== $name2)
	{
		rename($dir, $name2);
		var_dump($name2);
	}
}
echo '<br/>'.date('Y-m-d H:i:s');
?>