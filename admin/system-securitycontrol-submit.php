<?

include_once("../dbAccess/conn.php");	
include_once("../dbAccess/SystemManageFunc.php");
include_once("../dbAccess/CommFunc.php");


/*
var_dump($_POST);
exit;
*/


if(isset($_POST['system_security_id']))
{
	$pid = $_POST['system_security_id'];	
	$pvalid = $_POST['system_security_valid'];	

	if($pvalid=="")
	{
		$pvalid = 'F';
	}
	else
	{
		$pvalid = 'T';
	}
	
	$is_success = false;
	$returnMsg = updateSystem_Management_valid($pid,$pvalid);
	if($returnMsg == '')
	{
		$is_success = true; // update successfully
	}
	else
	{
		$is_success = false;
		echo $returnMsg.'<br>';
	}
	
}
else
{
	$is_success = false;
	echo 'No this maintain item.<br>';
	
}
$url = 'system.php';
if($is_success)
{
	echo 'Update 成功<br>';	
    header("location:".$url); // 自動跳轉
	echo 'Loading, please wait ...<br> Redirect automatically...';
}
echo '<br><br>';
?>

<button type="button" class="btn btn-warning mr-1"  onclick="javascript:history.go(-1)"> <i class="icon-cross2"></i> 上一頁 </button>
<button type="button" class="btn btn-warning mr-1" onclick="location.href='<? echo $url; ?>';">系統管理</button>
 