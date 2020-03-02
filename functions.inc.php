<?php
require_once('config.inc.php');
function cp_to_utf8($text) {
	$out='';
	$size=strlen($text);
	$pos=0;
	$count=0;
	$nb=0;
	$nb2=0;
	$nb3=0;
	$nb4=0;
	//$nb5=0;
	//$nb6=0;
	$c='';
	while($pos<$size) {
		$nb=ord($text[$pos]);
		$cond1 = $nb<=0x7F ;
		$cond2 = ($nb & 0xE0) == 0xC0 ;
		$cond3 = ($nb & 0xF0) == 0xE0 ;
		$cond4 = ($nb & 0xF8) == 0xF0 ;
		if($cond1) {
			//UTF8 1byte
			$out.=chr($nb);
			++$pos;
		}
		else if($cond2) {
			// 110x xxxx
			$nb2=ord($text[$pos+1]);
			assert( ($nb2&0xBF)==$nb2 ) or die('error2');
			$c=( ($nb & 0x1F) << 6 ) | ($nb2 & 0x3F);
			assert( $c<256 || ( (($c>>8)==0xFF) & ($c&0xFFFF00FF<256) ) );
			$out.=chr($c);
			$pos+=2;
		}
		else if($cond3) {
			// 1110 xxxx
			$nb2=ord($text[$pos+1]);
			$nb3=ord($text[$pos+2]);
			assert( ($nb2&0xBF)==$nb2 ) or die('error3a');
			assert( ($nb3&0xBF)==$nb3 ) or die('error3b');
			$c=( ($nb & 0x1F) << 12 ) | ( ($nb2 & 0x3F) << 6 ) | ($nb3 & 0x3F);
			/*if($c>256) {
				var_dump( ($c<256) );
				var_dump( ($c>>8) );
				var_dump( ($c>>8)==0xFF );
				var_dump( ($c&0xFFFF00FF) );
				var_dump( (($c&0xFFFF00FF)<256) );
				var_dump( ($c<256) || ( (($c>>8)==0xFF) && (($c&0xFFFF00FF)<256) ) );
				die();pre();
			}//*/
			assert( ($c<256) || ( (($c>>8)==0xFF) && (($c&0xFFFF00FF)<256) ) ) or die(var_dump($c, true));
			$out.=chr($c);
			$pos+=3;
		}
		else if($cond4) {
			// 1111 0xxx
			$nb2=ord($text[$pos+1]);
			$nb3=ord($text[$pos+2]);
			$nb4=ord($text[$pos+3]);
			assert( ($nb2&0xBF)==$nb2 ) or die('error4a');
			assert( ($nb3&0xBF)==$nb3 ) or die('error4b');
			assert( ($nb4&0xBF)==$nb4 ) or die('error4c');
			$c=( ($nb & 0x1F) << 18 ) | ( ($nb2 & 0x3F) << 12 ) | ( ($nb3 & 0x3F) << 6 ) | ($nb4 & 0x3F);
			assert( ($c<256) || ( (($c>>8)==0xFF) && (($c&0xFFFF00FF)<256) ) ) or die(var_dump($c, true));
			$out.=chr($c);
			$pos+=4;
		}
		else {
			var_dump('error');
			var_dump( decbin(ord($text[0])), decbin(ord($text[1])) );
			var_dump($pos);
			var_dump(decbin($nb), $cond1, $cond2, $cond3, $cond4);
			var_dump( dechex(ord($text[$pos])), dechex(ord($text[$pos+1])), dechex(ord($text[$pos+2])), dechex(ord($text[$pos+3])) );flush();
			die();break;return;
		}
	}
	return $out;
}
/**
 * Evaluate the formula in JS Challenge code
 * $formula - The JS forumat extracted from HTML 
 **/
function cf_formula_calculate($formula){
	$formula = str_replace(array('!+[]', '+!![]', '+![]'),array('+1','+1','+1'),$formula);
	$formula = str_replace(array('+[]'),array('+0'),$formula);
	$formula = str_replace(array(')+(',')/+('),array(').(',')/('),$formula);

	return eval('return ' . $formula . ';');
}

/**
 * Extract the JS code and solve the challenge to get answer value
 * $content - HTML content of challenge page
 **/
function cf_bypass_solve_challenge($content){
	global $url,$url_info;
	preg_match('/setTimeout\(function\(\)\{(.*)\}, 4000\);/s', $content, $matches);
	
	$main = $matches[1];
	$lines = explode(';', $main);
	$p1 = $lines[0];
	
	// find the variable name first
	preg_match('/, (.*)={"(.*)":/', trim($p1), $matches);
	$variable = $matches[1] . '.' . $matches[2];
	
	// find first formula
	preg_match('/":(.*)\}/', $p1, $matches);
	
	$formula1 = $matches[1];
	
	$answer = cf_formula_calculate($formula1);
	
	$operator = array('-=','*=','+=');
	foreach($lines as $line){
		$lines = trim($line);
		
		if($line == '' || strpos($line, $variable) === false) continue;
		
		foreach($operator as $op){
			if(strpos($line, $variable . $op) !== false){
				$formula = str_replace($variable . $op, '', $line);
				switch($op){
					case '-=':
						$answer -= cf_formula_calculate($formula);
						break;
					case '*=':
						$answer *= cf_formula_calculate($formula);
						break;
					case '+=':
						$answer += cf_formula_calculate($formula);
						break;
				}
			}
		}
	}
	
	// 15 is the domain length of novelplanet.com		
	//$answer = 15 + round($answer, 10);
	$answer = strlen($url_info['host']) + round($answer, 10);
	
	return $answer;
}
function get_url($url, $postdata=NULL) {
	$ch = curl_init();
	$cookies = COOKIEFILE;
	if (substr(PHP_OS, 0, 3) == 'WIN') {
		$cookies = str_replace('\\','/', getcwd().'/'.$cookies);
	}
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	if(!is_null($postdata)&&!empty($postdata)) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	}
	$res = curl_exec($ch);
	$GLOBALS['ar'] = curl_getinfo($ch);
	//$GLOBALS['ar']['url'] = str_replace(array('?','&'),array(' ?',' &'), $GLOBALS['ar']['url']);
	$GLOBALS['ar']['url'] = str_replace(array('?','&'),array('<br/> ?','<br/> &'), $GLOBALS['ar']['url']);
	$GLOBALS['msg'] = curl_errno($ch).' - '.curl_error($ch);
	curl_close($ch);
	$ch = NULL;
	return $res;
}
function get_paste2($url) {
	$arr=array(
		'http'=>array(
			'verify_peer'=>true,
			'allow_self_signed'=>true,
			'ignore_errors'=>true,
			//'user_agent'=>'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)',
			'header'=>'X-Requested-With: JSONHttpRequest',
		),
		'https'=>array(
			'verify_peer'=>true,
			'allow_self_signed'=>true,
			'ignore_errors'=>true,
			//'user_agent'=>'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)',
			'header'=>'X-Requested-With: JSONHttpRequest',
		),
	);
	$ctx=stream_context_create($arr);
	$data=@file_get_contents($url,false,$ctx);
	if($data===false) return false;
	$url2=parse_url($url);
	$data2=json_decode($data);
	$input=json_decode($data2->data, true);
	$password = $pw = $url2['fragment'];
	$salt = base64_decode($input['salt']);
	$digest   = hash_pbkdf2('sha256', $password, $salt, $input['iter'], 0, true);
	$cipher   = $input['cipher'] . '-' . $input['ks'] . '-' . $input['mode'];
	$ct       = substr(base64_decode($input['ct']), 0, - $input['ts'] / 8);
	$tag      = substr(base64_decode($input['ct']), - $input['ts'] / 8);
	$iv       = base64_decode($input['iv']);
	$adata    = $input['adata'];
	$dt = openssl_decrypt($ct, $cipher, $digest, OPENSSL_RAW_DATA, $iv, $tag, $adata);
	$decrypt=base64_decode($dt);
	$decrypt=cp_to_utf8($decrypt);
	return $decrypt;
}
function get_paste($url) {
	$decrypt = get_paste2($url);
	$text = zlib_decode($decrypt);
	return $text;
}
function letobe($hex8)
{
	assert(strlen($hex8)==8);
	return substr($hex8, 6, 2).substr($hex8, 4, 2).substr($hex8, 2, 2).substr($hex8, 0, 2);
}
?>