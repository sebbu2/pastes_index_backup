<?php
require_once('config.inc.php');
$dirs=glob('*');
$dirs=array_filter($dirs, 'is_dir');
$dirs=array_diff($dirs,array('__others'));

foreach($dirs as $dir)
{
	if(SAVEHTM) {
		$files=glob($dir.'/*.htm');
		$chs=array();
		foreach($files as $file)
		{
			if(filesize($file)==0) { unlink($file); continue; }
			$ch=explode('/',$file);
			$ch=$ch[1];
			$ch=explode('.',$ch);
			$ch=$ch[0];
			$ch=explode('-',$ch);
			$ch=array_map('trim',$ch);
			if(array_key_exists($ch[0],$chs)) {
				var_dump($dir,$chs[$ch[0]],$ch);
				echo '<br/>';
				if(count($ch)==1 && $chs[$ch[0]]>$ch[0]) unlink($dir.'/'.$ch[0].'.htm');
				else if($chs[$ch[0]] > $ch[1]) unlink($dir.'/'.$ch[0].' - '.$ch[1].'.htm');
				else if($chs[$ch[0]] < $ch[1]) unlink($dir.'/'.$ch[0].' - '.$chs[$ch[0]].'.htm');
				if(SAVEGZ) {
					if(count($ch)==1 && $chs[$ch[0]]>$ch[0]) unlink($dir.'/'.$ch[0].'.htm.gz');
					else if($chs[$ch[0]] > $ch[1]) unlink($dir.'/'.$ch[0].' - '.$ch[1].'.htm.gz');
					else if($chs[$ch[0]] < $ch[1]) unlink($dir.'/'.$ch[0].' - '.$chs[$ch[0]].'.htm.gz');
				}
			}
			if(count($ch)==1) $chs[$ch[0]]=$ch[0];
			else $chs[$ch[0]]=$ch[1];
		}
	}
	if(SAVEGZ) {
		$files=glob($dir.'/*.htm.gz');
		$chs=array();
		foreach($files as $file)
		{
			if(filesize($file)==0 || filesize($file)==18) { unlink($file); continue; }
			$ch=explode('/',$file);
			$ch=$ch[1];
			$ch=explode('.',$ch);
			$ch=$ch[0];
			$ch=explode('-',$ch);
			$ch=array_map('trim',$ch);
			if(array_key_exists($ch[0],$chs)) {
				var_dump($dir,$chs[$ch[0]],$ch);
				echo '<br/>';
				if(SAVEHTM) {
					if(count($ch)==1 && $chs[$ch[0]]>$ch[0]) unlink($dir.'/'.$ch[0].'.htm');
					else if($chs[$ch[0]] > $ch[1]) unlink($dir.'/'.$ch[0].' - '.$ch[1].'.htm');
					else if($chs[$ch[0]] < $ch[1]) unlink($dir.'/'.$ch[0].' - '.$chs[$ch[0]].'.htm');
				}
				if(count($ch)==1 && $chs[$ch[0]]>$ch[0]) unlink($dir.'/'.$ch[0].'.htm.gz');
				else if($chs[$ch[0]] > $ch[1]) unlink($dir.'/'.$ch[0].' - '.$ch[1].'.htm.gz');
				else if($chs[$ch[0]] < $ch[1]) unlink($dir.'/'.$ch[0].' - '.$chs[$ch[0]].'.htm.gz');
			}
			if(count($ch)==1) $chs[$ch[0]]=$ch[0];
			else $chs[$ch[0]]=$ch[1];
		}
	}
}
$dirs2=glob('__others/*');
$dirs2=array_filter($dirs2, 'is_dir');
foreach($dirs2 as $dir)
{
	if(SAVEHTM) {
		$files=glob($dir.'/*.htm');
		$chs=array();
		foreach($files as $file)
		{
			if(filesize($file)==0) { unlink($file); continue; }
			$ch=explode('/',$file);
			$ch=$ch[2];
			$ch=explode('.',$ch);
			$ch=$ch[0];
			$ch=explode('-',$ch);
			$ch=array_map('trim',$ch);
			if(array_key_exists($ch[0],$chs)) {
				var_dump($dir,$chs[$ch[0]],$ch);
				echo '<br/>';
				if(count($ch)==1 && $chs[$ch[0]]>$ch[0]) unlink($dir.'/'.$ch[0].'.htm');
				else if($chs[$ch[0]] > $ch[1]) unlink($dir.'/'.$ch[0].' - '.$ch[1].'.htm');
				else if($chs[$ch[0]] < $ch[1]) unlink($dir.'/'.$ch[0].' - '.$chs[$ch[0]].'.htm');
				if(SAVEGZ) {
					if(count($ch)==1 && $chs[$ch[0]]>$ch[0]) unlink($dir.'/'.$ch[0].'.htm.gz');
					else if($chs[$ch[0]] > $ch[1]) unlink($dir.'/'.$ch[0].' - '.$ch[1].'.htm.gz');
					else if($chs[$ch[0]] < $ch[1]) unlink($dir.'/'.$ch[0].' - '.$chs[$ch[0]].'.htm.gz');
				}
			}
			if(count($ch)==1) $chs[$ch[0]]=$ch[0];
			else $chs[$ch[0]]=$ch[1];
		}
	}
	if(SAVEGZ) {
		$files=glob($dir.'/*.htm.gz');
		$chs=array();
		foreach($files as $file)
		{
			if(filesize($file)==0 || filesize($file)==18) { unlink($file); continue; }
			$ch=explode('/',$file);
			$ch=$ch[2];
			$ch=explode('.',$ch);
			$ch=$ch[0];
			$ch=explode('-',$ch);
			$ch=array_map('trim',$ch);
			if(array_key_exists($ch[0],$chs)) {
				var_dump($dir,$chs[$ch[0]],$ch);
				echo '<br/>';
				if(SAVEHTM) {
					if(count($ch)==1 && $chs[$ch[0]]>$ch[0]) unlink($dir.'/'.$ch[0].'.htm');
					else if($chs[$ch[0]] > $ch[1]) unlink($dir.'/'.$ch[0].' - '.$ch[1].'.htm');
					else if($chs[$ch[0]] < $ch[1]) unlink($dir.'/'.$ch[0].' - '.$chs[$ch[0]].'.htm');
				}
				if(count($ch)==1 && $chs[$ch[0]]>$ch[0]) unlink($dir.'/'.$ch[0].'.htm.gz');
				else if($chs[$ch[0]] > $ch[1]) unlink($dir.'/'.$ch[0].' - '.$ch[1].'.htm.gz');
				else if($chs[$ch[0]] < $ch[1]) unlink($dir.'/'.$ch[0].' - '.$chs[$ch[0]].'.htm.gz');
			}
			if(count($ch)==1) $chs[$ch[0]]=$ch[0];
			else $chs[$ch[0]]=$ch[1];
		}
	}
}
echo '<br/>'.date('Y-m-d H:i:s');
?>