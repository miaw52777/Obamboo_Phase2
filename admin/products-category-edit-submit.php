<?
include_once("../dbAccess/conn.php");	
include_once("../dbAccess/ProductCategoryFunc.php");
include_once("../dbAccess/FileIOFunc.php");
include_once("../dbAccess/CommFunc.php");
include('./adsecure.php');


if(!is_login())
{
	header("Location: index.php?page=".$_SERVER['REQUEST_URI']);
	exit;
} 

$msg = '';


$paction = $_POST['action'];
$pid = $_POST['id'];
$pProductName = $_POST['category'];	
$proot = $_POST['rootOptionSelect'];
$pvalid = $_POST['valid'];		
$pseq = $_POST['Seq'];	
$filepath = $_FILES["file"]["name"]; 
$fileRoot = '../'.$UploadfileRoot;
if($pvalid=="")
{
	$pvalid = 'F';
}
else
{
	$pvalid = 'T';
}
//var_dump($_POST);
//var_dump($_FILES['file']);
//exit;


if($paction == "刪除") // modify
{
	$rule = get_Products_Category_Select_Rule('ID',$pid);	
	$result = get_Products_Category_List($rule);

	$is_success = true;
    if(mysql_num_rows($result) == 0)
	{
		$msg = 'Record not found.';
		$is_success = false;
	}
	else
	{
		$file = getSQLResultInfo($result,'filepath');
		// delete file
		if (!file_exists($fileRoot.$file)) $msg=''; // file not exists, perform to delete DB data
		elseif($file == '')
		{
			$msg = 'No filepath was uploaded.';
		}
		else
		{ 
			$msg = deleteFile($fileRoot,$file); 
			if($msg != "")  $is_success = false;
		}			
		if(!$is_success) echo $msg.'<br>';
		
		if($is_success) 
		{
			echo 'Start to delete db record...<br>';
			$msg = deleteProductCategory($pid);
			if($msg != "")
			{
				echo $msg.'<br>';
				$is_success = false;
			}
	    }
	}
	
}
elseif($paction == "新增") // modify
{
	$is_success = true;
	$msg = insertProductCategory($pid, $pProductName,$proot,$pvalid,$pseq);
	if($msg == "") // db insert error, don't upload file
	{
		echo 'Insert data successfully... <br> Start to upload file...<br>';
		if($filepath!='')
		{		
			$msg = uploadFile($fileRoot,$_FILES['file']);
			if($msg=='')
			{
				$msg = updateProductCategoryFilepath($pProductName,$filepath);
				if($msg != "")
				{
					$is_success = false; // update db filepath fail
				}
			}
			else
			{
				$is_success = false; // upload file fail				
			}
			echo $msg.'<br>';
		}
	}
	else
	{
		$is_success = false;
		echo $msg.'<br>';
	}	
}
else //修改
{
	$is_success = true;
	$msg = updateProductCategory($pid,$pProductName,$proot,$pvalid,$pseq);
	if($msg == '') // db update error, don't upload file
	{
	 	echo 'Update db record successfully... <br> Start to upload file=> '.$filepath.' ...<br>';
		if($filepath!='')
		{		
			$msg = uploadFile($fileRoot,$_FILES['file']);
			if($msg=='')
			{
				$msg = updateProductCategoryFilepath($pProductName,$filepath);
				if($msg != "")
				{
					$is_success = false; // update db filepath fail
				}
			}
			else $is_success = false; // upload file fail
			
			echo $msg.'<br>';
			
		}
	}
	else
	{
		$is_success = false;
		echo $msg.'<br>';
	}
}

if($is_success)
{
	echo $paction.'成功<br>';
    header("location:products-category.php"); // 自動跳轉
	echo 'Loading, please wait ...<br> Redirect automatically...';
}
echo '<br><br>';
?>

<button type="button" class="btn btn-warning mr-1"  onclick="javascript:history.go(-1)"> <i class="icon-cross2"></i> 上一頁 </button>
<button type="button" class="btn btn-warning mr-1" onclick="location.href='products-category.php';">產品類別 </button>