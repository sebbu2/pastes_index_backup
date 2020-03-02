<?php
require_once('config.inc.php');
foreach(array('url1.data','url2.data','urlb1.data','urlb2.data') as $fic)
{
	@unlink($fic);
}
echo '<br/>'.date('Y-m-d H:i:s');
?>