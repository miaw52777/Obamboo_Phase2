<?
include_once("../dbAccess/conn.php");	
include_once("../dbAccess/DownloadFormatFunc.php");
include_once("../dbAccess/CommFunc.php");


$msg = '';

$paction = $_POST['action'];
$pid = $_POST['d_id']; 
$pcategory = $_POST['category'];	
$pcommon = $_POST['common'];	

if($pcommon != "")	
{
	$pcommon = 'T';	
}
else
{
	$pcommon = 'F';	
}

//var_dump($_POST);
//exit;
if($paction == "")
{
	$is_success = false;	
	echo 'No Action. <br>';
}
else if($paction == "刪除") // modify
{
	$rule = get_DownloadFormat_Select_Rule('D_ID',$pid);	
	$result = get_DownloadFormat_List($rule);

	$is_success = true;
    if(mysql_num_rows($result) == 0)
	{
		$msg = 'Record not found.<br>';		
		$is_success = false;
		echo $msg.'<br>';
	}
	else
	{			
		$msg = deleteDownloadFormat($pid);
		if($msg != "")
		{
			echo $msg.'<br>';
			$is_success = false;
		}			
	}
	 
}
elseif($paction == "新增") // modify
{
	$is_success = true;	
	$msg = insertDownloadFormat($pcategory,$pcommon);	
	if($msg != "") // db insert error, don't upload file	
	{
		$is_success = false;
		echo $msg.'<br>';
	}	
	
}
else //修改
{
	$is_success = true;	
	 
	$msg = updateDownloadFormat($pid, $pcategory,$pcommon);	
	if($msg != '') // db update error, don't upload file	
	{
		$is_success = false;
		echo $msg.'<br>';
	}
}

if($is_success)
{
	echo $paction.'成功<br>';
    header("location:download-format.php"); // 自動跳轉
	echo 'Loading, please wait ...<br> Redirect automatically ...';
}
echo '<br><br>';
?>

<button type="button" class="btn btn-warning mr-1"  onclick="javascript:history.go(-1)"> <i class="icon-cross2"></i> 上一頁 </button>
<button type="button" class="btn btn-warning mr-1" onclick="location.href='download-format.php';">3D 下載格式名稱 </button>