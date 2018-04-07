<?
include_once("../dbAccess/conn.php");	
include_once("../dbAccess/ProductCategoryFunc.php");
include_once("../dbAccess/ProductFunc.php");
include_once("../dbAccess/FileIOFunc.php");
include_once("../dbAccess/CommFunc.php");

$msg = '';
$paction = $_POST['action'];
$id = $_POST['id']; // product id
$product_id = $_POST['prod_id']; // product id
$pname = $_POST['product_name'];	
$proot = $_POST['rootOptionSelect'];
$pvalid = $_POST['valid'];	
$desc = $_POST['content_encode_str'];		
$pseq = $_POST['Seq'];	
$filepath1 = $_FILES["file1"]["name"]; 
$filepath2 = $_FILES["file2"]["name"]; 
$filepath3 = $_FILES["file3"]["name"]; 
$filepath4 = $_FILES["file4"]["name"]; 
 
$fileRoot = '../'.$UploadfileRoot.'products/';

if($pvalid=="")
{
	$pvalid = 'F';
}
else
{
	$pvalid = 'T';
}

/*
var_dump($_POST);
var_dump($_FILES['file']);
exit;
*/
 
if($paction == 'UPDATE_DESC')
{
	$is_success = true;	
	echo 'Start to update description... <br>';		
	$msg = updateProduct_description($product_id,urldecode(base64_decode($desc)), '../'.$UploadfileRoot.'product_desc/');
	if($msg != '')  
	{					
		$is_success = false;
		echo $msg;
	}	
}
else if($paction == "刪除") // modify
{
	$rule = get_Products_Select_Rule('ID',$product_id);	
	$result = get_Products_List($rule);

	$is_success = true;
    if(mysql_num_rows($result) == 0)
	{
		$msg = 'Record not found.<br>';
		$is_success = false;
	}
	else
	{		
		echo 'Start to delete files... <br>';
		echo '------------------------------------<br>';
		for($i=1;$i<=4;$i++)
		{			
			$file = getSQLResultInfo($result,'filepath_'.$i);			
			
			echo 'Delete file '.$i.' => '.$file.'<br>';			
			// delete file
			if (!file_exists($fileRoot.$file)) 
			{
				$msg = ''; // file not exists, perform to delete DB data				
			}	
			elseif($file == '')
			{
				$msg = 'filepath_'.$i.' => no filepath.<br>';
				echo $msg;
			}
			else
			{ 
				$msg = deleteFile($fileRoot,$file); 
				if($msg != '')  
				{					
					$is_success = false;					
				}
				else
				{
					echo 'Delete file ok.<br>';
				}
			}
			if(!$is_success) echo 'Error : '.$msg.'<br>';
			echo '------------------------------------<br>';
		}				
	}
	// if delete file successfully, then delete db record
	if($is_success)
	{		
		echo 'Start to delete db record...<br>';
		$msg = deleteProduct($product_id);
		if($msg != "")
		{
			echo 'delete db record :'.$msg.'<br>';
			$is_success = false;
		}
	}
}
elseif($paction == "新增") // modify
{
	$is_success = true;
	
	// get product category id by root 
	$rule = get_Products_Category_Select_Rule('NAME',$proot);
	if($proot == '')
	{
		echo 'No choose product category.<br>';
		$is_success = false;
	}
	$checkData = get_Products_Category_List($rule);
	if(mysql_num_rows($checkData) == 0)
	{
		$msg = 'No this prodcut category information.';
		$is_success = false;
	}
	else
	{
		$product_cat_id = getSQLResultInfo($checkData,'id');	
	}
	
	// start to insert information to product table
	if($is_success)
	{	
		$msg = insertProduct($product_cat_id,$pname,$pvalid,$pseq,$desc);
		if($msg == "") // db insert error, don't upload file
		{
			echo 'Insert data successfully... <br> Start to upload file...<br>';		
			echo '---------------------------<br>'; 
			for($i=1;$i<=4;$i++) // loop for 4 files uploading
			{
				$filepath = $_FILES["file".$i]["name"];  // get file path				
				if($filepath!='')
				{		
					echo 'Start to upload file '.$i.' => '.$fileRoot.$filepath.' ...<br>';
					$msg = uploadFile($fileRoot, $_FILES['file'.$i]); // perform uploading files
					if($msg=='')
					{
						// upload file successfully						
						$rule = get_Products_Select_Rule('NAME',$pname);	
						$rule .= get_Products_Select_Rule('PROD_CAT_ID',$product_cat_id);	
						$result = get_Products_List($rule);
						$product_id = getSQLResultInfo($result,'product_id');									
						$msg = updateProductFilepath($product_id,'filepath_'.$i,$filepath);
						if($msg != "")
						{
							$is_success = false; // update db filepath fail						
						}
						else
						{
							echo 'Upload successfully.<br>'	;					
						}
					}
					else $is_success = false; // upload file fail			
					
					if(!$is_success) echo $msg.'<br>';
					echo '---------------------------<br>'; 
				}
				
			}
		}	
		else
		{
			$is_success = false;
			echo 'Insert Product Error : '.$msg.'<br>';
		}	
	}
}
else //修改
{
	$is_success = true;	
	// get product category id by root 
	$rule = get_Products_Category_Select_Rule('NAME',$proot);
	$checkData = get_Products_Category_List($rule);
	
	if(mysql_num_rows($checkData) == 0)
	{
		$msg = 'No this prodcut category information.';
		$is_success = false;
	}
	else
	{
		$product_cat_id = getSQLResultInfo($checkData,'id');						
	}
	 
	if($is_success)
	{		
		$msg = updateProduct($product_id,$product_cat_id,$pname,$pvalid,$pseq);
		if($msg == '') // db update error, don't upload file
		{
			echo 'Update db record successfully... <br>';
			echo '---------------------------<br>'; 
			for($i=1;$i<=4;$i++) // loop for 4 files uploading
			{
				$filepath = $_FILES["file".$i]["name"];  // get file path				
				if($filepath!='')
				{		
					echo 'Start to upload file '.$i.' => '.$fileRoot.$filepath.' ...<br>';
					$msg = uploadFile($fileRoot, $_FILES['file'.$i]); // perform uploading files
					if($msg=='')
					{
						// upload file successfully
						$msg = updateProductFilepath($product_id,'filepath_'.$i,$filepath);
						if($msg != "")
						{
							$is_success = false; // update db filepath fail						
						}						
					}
					else $is_success = false; // upload file fail			
					
					echo $msg.'<br>'; 
					echo '---------------------------<br>'; 
				}
				
			}
		}
		else
		{
			$is_success = false;
			echo $msg.'<br>';
		}
	}

}
if($paction == 'UPDATE_DESC')
{
	$url = 'products-edit.php?action=編輯&id='.$id.'&prod_id='.$product_id;
}
else
{
	$url = 'products.php';
}
if($is_success)
{
	echo $paction.'成功<br>';	
    header("location:".$url); // 自動跳轉
	echo 'Loading, please wait ...<br> Redirect automatically...';
}
echo '<br><br>';
?>

<button type="button" class="btn btn-warning mr-1"  onclick="javascript:history.go(-1)"> <i class="icon-cross2"></i> 上一頁 </button>
<button type="button" class="btn btn-warning mr-1" onclick="location.href='<? echo $url; ?>';">產品管理 </button>