<?php
require_once('config.inc.php');
ob_start();
$dirnames=array(
	'*',
	'__others/*',
);
$dirnames_excludes=array(
	array('__others','__old'),
	NULL,
);
$filenames=array(
	''=>'*.htm',
	'gz'=>'*.htm.gz',
);

function readable($value) {
	static $units=array('','k','m','g','t','p','e','z','y');
	if($value==0) return '0';
	$s=floor(log($value)/log(1024));
	$text=number_format( ($value/pow(1024,$s)), 2).$units[$s];
	return $text;
}

echo '<pre>';

$counts=array();
$stats=array();
$sizes=array();
$globals=array(
	'count'=>0,'chapters'=>0,'size'=>0,'avg'=>0,
);
$avgs=array();

foreach($dirnames as $k1 => $dirpattern)
{ // 2
	$dirs=glob($dirpattern);
	$dirs=array_filter($dirs, 'is_dir');
	if(array_key_exists($k1, $dirnames_excludes) && is_array($dirnames_excludes[$k1])) {
		$dirs=array_diff($dirs, $dirnames_excludes[$k1]);
	}
	echo 'dirs'.$k1.'=';var_dump(count($dirs));
	foreach($filenames as $k2=>$filepattern)
	{ // 2
		$key=$k1.$k2;
		$stats[$key]=array();
		$sizes[$key]=array();
		$global=array(
			'count'=>0,'chapters'=>0,'size'=>0,'avg'=>0,
		);
		foreach($dirs as $a => $dir)
		{ //loop dirs
			$files=glob($dir.'/'.$filepattern);
			$sizesf=array_map('filesize', $files);
			$counts[$key][$dir]=count($files);
			$sizes[$key][$dir]=array_sum($sizesf);
			$s=0;
			$nb=0;
			$avg=0;
			foreach($files as $b => $file) {
				//loop files
				$s+=$sizesf[$b];
				
				$ch=explode('/',$file);
				$ch=array_pop($ch);
				$ch=explode('.',$ch);
				$ch=$ch[0];
				$ch=explode('-',$ch);
				$ch=array_map('trim',$ch);
				if(count($ch)==1) ++$nb;
				else {
					$nb_=($ch[1]-$ch[0]+1);
					if($nb_<=0) var_dump($file,$ch,$nb_);
					$nb+=$nb_;
				}
			}//files
			if($s==0 || $nb==0) $avg=0;
			else $avg=($s/$nb);
			
			$stat=array(
				'count'=>$counts[$key][$dir],'chapters'=>$nb,'size'=>$s, 'avg'=>$avg,
			);
			$stats[$key][$dir]=$stat;
			foreach($global as $k=>$v) $global[$k]+=$stat[$k];
			if($k2=='') {
				foreach($global as $k=>$v) $globals[$k]+=$stat[$k];
				$avgs[$dir]=$stat['avg'];
			}
		}//dirs
		if(array_sum($counts[$key])==0) continue;
		echo 'pastes'.$key.'=';var_dump(array_sum($counts[$key]));
		echo 'sizes'.$key.'=';var_dump(readable(array_sum($sizes[$key])));
		if($global['chapters']!=0) $global['avg']=$global['size']/$global['chapters'];
		else $global['avg']=0;
		echo 'stats'.$key.'=';var_dump($global);
	}//2
}//2
if($globals['chapters']>0)
{
	if($globals['chapters']!=0) $globals['avg']=$globals['size']/$globals['chapters'];
	else $globals['avg']=0;
	echo 'globalstats=';var_dump($globals);
	echo 'avgs=';var_dump(array('min'=>min($avgs),'max'=>max($avgs)));
}
echo '</pre>';
$data=ob_get_flush();
if(SAVESTAT) file_put_contents('stats.htm',$data);
echo '<br/>'.date('Y-m-d H:i:s');
?>