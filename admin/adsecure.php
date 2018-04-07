<?php
/*
此文件為後端文件 使用者不應直接拜訪此頁面
*/
if(!isset($_SESSION)){
	session_name("obamboo_backend");
	session_set_cookie_params("1209600"); // 14days = 3600 * 24 * 14
	session_start();
}

if(!function_exists('json_encode'))
{
	include('JSON.php');
	function json_encode($val)
	{
		$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		return $json->encode($val);
	}

	function json_decode($val)
	{
		$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		return $json->decode($val);
	}
}
  

if(!function_exists("is_login")){
	function is_login()	{
		return isset($_SESSION['loginuid'])&& ($_SESSION['loginuid']!="logout") && 	$_SESSION['loginuid'];
	}	
}

if(!function_exists("is_admin")){
	function is_admin()	{
		return isset($_SESSION['adminlevel'])&& ($_SESSION['adminlevel']=="a");
	}
}
if(!function_exists("is_CSRF")){
	function is_CSRF()	{
		$site = "http://localhost/ob3DHost_code/admin/index.php";
		return ($_SERVER['HTTP_REFERER'] != $site);
	}	
}
$UID = isset($_SESSION['loginuid']) ? $_SESSION['loginuid'] : 0;

//echo $_SESSION['loginuid'];
?>