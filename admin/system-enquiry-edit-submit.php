<?
include_once("../dbAccess/CommFunc.php");
include_once("../dbAccess/conn.php");	
include_once("../dbAccess/SystemManageFunc.php");



$system_id = $_POST['system_id'];
$valid = $_POST['system_enquiry_valid'];
$seqArr = $_POST['mail']['SEQ'];
$contentArr = $_POST['mail']['CONTENT'];

if($valid == "")
{
	$valid = "F";
}
else
{
	$valid = "T";
}

updateSystem_Management_valid($system_id,$valid);

for($i=0;$i<count($seqArr);$i++)
{
	updateSystem_Management_udata($system_id,$seqArr[$i],"MAIL",$contentArr[$i]);
}

echo '<br><br>';

header("location:system-enquiry-edit.php?systemid=".$system_id);
?>

<button type="button" class="btn btn-warning mr-1"  onclick="javascript:history.go(-1)"> <i class="icon-cross2"></i> 上一頁 </button>

