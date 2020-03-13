<?php
if(file_exists('url2.data') && filesize('url2.data')<102400) {
	if(filesize('url1.data')<102400) {
		unlink('url1.data');
		rename('url2.data','url1.data');
	}
	else {
		unlink('url2.data');
		copy('url1.data','url2.data');
	}
}
if(file_exists('urlb2.data') && filesize('urlb2.data')<102400) {
	if(filesize('urlb1.data')<102400) {
		unlink('urlb1.data');
		rename('urlb2.data','urlb1.data');
	}
	else {
		unlink('urlb2.data');
		copy('urlb1.data','urlb2.data');
	}
}
echo '<br/>'.date('Y-m-d H:i:s');
?>