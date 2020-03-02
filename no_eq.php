<?php
require_once('config.inc.php');
if(SAVEHTM && SAVEGZ) {
	function parse($file) {
		var_dump($file);
		unlink($file);
	}
	$files=glob('*/*.htm');
	foreach($files as $file)
	{
		if(!file_exists($file.'.gz')) parse($file);
	}
	$files=glob('*/*.htm.gz');
	foreach($files as $file)
	{
		if(!file_exists(substr($file,0,-3))) parse($file);
	}
	$files=glob('__others/*/*.htm');
	foreach($files as $file)
	{
		if(!file_exists($file.'.gz')) parse($file);
	}
	$files=glob('__others/*/*.htm.gz');
	foreach($files as $file)
	{
		if(!file_exists(substr($file,0,-3))) parse($file);
	}
	print('cleaned doubles.');
}
else
{
	print('disabled because it needs both SAVEHTM and SAVEGZ.');
}
echo '<br/>'.date('Y-m-d H:i:s');
?>