<?php
include('./secure.php');
include('./dbAccess/conn.php');
include_once('./dbAccess/CommFunc.php');

if(isset($_POST['user-name']) && isset($_POST['user-password']))
{	
	$sql = sprintf("SELECT * FROM priv_control_ut t where 1=1 and type = '%s' and user = '%s' and pass = '%s' "
			 ,'frontend', $_POST['user-name'], $_POST['user-password']);
	
	$returnMsg = QuerySQL($sql);		
	if(($returnMsg['RESULT']) && ($returnMsg['REC_CNT'] > 0))
	{
		// check priv pass
		$_SESSION['loginuid'] = '1';
		echo json_encode(array('success' => true));
		header("Location: standard_product-structure-list.php");
	}
	else
	{
		// check priv fail, pls retry
		echo json_encode(array('success' => false)); 
		header("Location: login.php");
	}
	exit;
}

/*
// log for login status
include('./dbAccess/getip.php');
$insertSQL = sprintf("INSERT INTO adminvisitlog (ipstamp) VALUES (%s)",  
					GetSQLValueString(getRequestIP(),'text'));
mysql_query($insertSQL);
*/
if(!is_login())
{
	header("Location: login.php");
	exit;
} 
?>