<?php
@session_start();
function getRequestIP() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}


$s = isset($_POST['s']) ? $_POST['s'] : '';
$o = isset($_POST['o']) ? $_POST['o'] : '';

if ($s != '' and $s == $_SESSION[getRequestIP().'_'.$o]) {
	echo 'https://www.3dvieweronline.com/members/Idfe065cf78f3f3c12b6af5a4f1ee790fb/nNqlmqKgA674xTL'; 
}
$_SESSION[getRequestIP().'_'.$o] = '';
//@session_destroy();