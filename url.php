<?php

include_once("./dbAccess/getip.php");	
include('./secure.php');
//@session_start();


$s = isset($_POST['s']) ? $_POST['s'] : '';

$o = isset($_POST['o']) ? $_POST['o'] : '';


if ($s != '' and $s == $_SESSION[getRequestIP().'_'.$o]) 
{
	
	$link3d = base64_decode($_POST['encry_str']);  		
	echo $link3d;
}

$_SESSION[getRequestIP().'_'.$o] = '';

//@session_destroy();



?>