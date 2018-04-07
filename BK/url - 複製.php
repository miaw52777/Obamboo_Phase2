<?php

include_once("./dbAccess/getip.php");	
include_once("./frontendFunc.php");
include_once("./dbAccess/ProductFunc.php");
include('./secure.php');
//@session_start();


$s = isset($_POST['s']) ? $_POST['s'] : '';

$o = isset($_POST['o']) ? $_POST['o'] : '';

 

if ($s != '' and $s == $_SESSION[getRequestIP().'_'.$o]) 
{
	$prod_id = $_POST['prod_id'];
	$spec_key = $_POST['spec_key'];
	$item_key = $_POST['item_key'];
	//var_dump($_POST);
	$link = get_3D_link($prod_id,$spec_key,$item_key);
	//var_dump($link);
	echo $link;
	 
}

$_SESSION[getRequestIP().'_'.$o] = '';

//@session_destroy();
