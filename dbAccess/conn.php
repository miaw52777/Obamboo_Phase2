<?php

include ("GetSQLValueString.php");
if(!function_exists('json_encode'))
{
	include('JSON.php');
	function json_encode($val)
	{
		$json = new Services_JSON();
		return $json->encode($val);
	}

	function json_decode($val)
	{
		$json = new Services_JSON();
		return $json->decode($val);
	}
}
	
$debug = 'T';
$printLog = 'F';
$UploadfileRoot = "uploadfiles/";

if($debug=='F') 
{
	$sql_hostname = "localhost";
	$sql_database = "obamwvvq_ob3DHostcode";
	$sql_username = "obamwvvq_ob3DHostcode";
	$sql_password = "xvr.2lJ&gfa;";
}
else
{
	$sql_hostname = "localhost";
	$sql_database = "obamwvvq_ob3DHostcode";
	$sql_username = "root";
	$sql_password = "00000000"; 
}

$link = mysql_connect($sql_hostname, $sql_username, $sql_password) or trigger_error(mysql_error(),E_USER_ERROR);

mysql_query("SET NAMES 'utf8'"); 
mysql_query("SET CHARACTER_SET_CLIENT=utf8"); 
mysql_query("SET CHARACTER_SET_RESULTS=utf8");
mysql_select_db($sql_database, $link) or die("Opps some thing went wrong");
?>
