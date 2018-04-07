<?
include_once("../dbAccess/conn.php");	
include_once("../dbAccess/ProductSpecItemFunc.php");
include_once("../dbAccess/CommFunc.php");
include('./adsecure.php');


if(!is_login())
{
	header("Location: index.php?page=".$_SERVER['REQUEST_URI']);
	exit;
} 

$msg = '';

$paction = $_POST['action'];
$prod_cat_id = $_POST['id']; // product category id
$prod_id = $_POST['prod_id']; // product id
$psid = $_POST['s_id']; // spec id
$pid = $_POST['item_id']; // spec item id
$pname = $_POST['item'];	
$pseq = $_POST['no'];	

//var_dump($_POST);
//echo '<br>';

if($paction == "刪除") // modify
{
	$rule = get_ProductSpecItem_Select_Rule('ITEM_ID',$pid);	
	$result = get_ProductSpecItem_List($rule);

	$is_success = true;
    if(mysql_num_rows($result) == 0)
	{
		$msg = 'Record not found.<br>';
		$is_success = false;
	}
	else
	{			
		$msg = deleteProductSpecItem($pid);
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
	$msg = insertProductSpecItem($psid, $pname,$pseq);	
	if($msg != "") // db insert error, don't upload file	
	{
		$is_success = false;
		echo $msg.'<br>';
	}	
	
}
else //修改
{
	$is_success = true;	
	 
	$msg = updateProductSpecItem($pid, $pname,$pseq);	
	if($msg != '') // db update error, don't upload file	
	{
		$is_success = false;
		echo $msg.'<br>';
	}
}

if($is_success)
{
	echo $paction.'成功<br>';
    header("location:specifications-select.php?id=".$prod_cat_id."&prod_id=".$prod_id."&s_id=".$psid); // 自動跳轉
	echo 'Loading, please wait ...<br> Redirect automatically...';
}
echo '<br><br>';
?>

<button type="button" class="btn btn-warning mr-1"  onclick="javascript:history.go(-1)"> <i class="icon-cross2"></i> 上一頁 </button>
<button type="button" class="btn btn-warning mr-1" onclick="location.href='specifications-select.php?<?echo "id=".$prod_cat_id."&prod_id=".$prod_id."&s_id=".$psid; ?>';">產品規格選項 </button>