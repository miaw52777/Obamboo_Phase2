<?
include_once("../dbAccess/conn.php");	
include_once("../dbAccess/3DFunc.php");
include_once("../dbAccess/CommFunc.php");
include_once("../dbAccess/FileIOFunc.php");
include_once("../dbAccess/MailFunc.php");

$msg = '';

$paction = $_POST['pageaction']; 
$prod_cat_id = $_POST['id']; 
$prod_id = $_POST['prod_id']; 
$pkey = $_POST['key']; 
$plink = $_POST['link_'.$pkey]; 
$pmodel = $_POST['model_'.$pkey]; 
$spec_name = $_POST['specitem']; 
$spec_name = "\n".str_replace('<br>',"\n",$spec_name);

$keylist = explode('@',$pkey);

$speckey = $keylist[0];
$itemkey = $keylist[1];

//echo $pkey.'<br>';
$filepath = $_FILES['file_'.$pkey]['name'];		 

/*
var_dump($_POST);
echo '<br>';

var_dump($_FILES['file_'.$pkey]);
echo '<br>';

exit;*/
$fileRoot = '../'.$UploadfileRoot.'3d_spec_detail/';
 


if($paction == "")
{
	$is_success = false;	
	echo 'No Action. <br>';
}
elseif (($paction == "specdetail_store") ||($paction == "specdetail_edit_store")  )
{	
	$is_success = true;	
	$rule = get_3D_SpecDetail_Select_Rule('PROD_ID',$prod_id);											
	$rule .= get_3D_SpecDetail_Select_Rule('SPECKEY',$speckey);											
	$rule .= get_3D_SpecDetail_Select_Rule('ITEMKEY',$itemkey);											
	$returnMsg = get_3D_SpecDetail($rule);	
	//var_dump($returnMsg); echo '<br>';
	if($returnMsg['RESULT'] == false) // query error
	{
		$is_success = false;	
		echo $returnMsg['MSG'].'<br>';			
	}
	else if($returnMsg['REC_CNT'] > 0) // has product
	{ 
		// product exists		
		$old_file = getSQLResultInfo($returnMsg['DATA'],'filepath');	
		
		// update model
		$msg = update3D_SpecDetail_model($speckey,$itemkey,$pmodel);		
		if($msg != "")
		{
			$is_success = false;
		}		
		else
		{			
			// 檔案存在
			$perform_upload=false;
			if(($old_file != '') && ($filepath != ''))
			{
				echo 'Old file exists=>'.$old_file.'<br>';
			
				if($old_file == $filepath)
				{
					//檔案相同，不異動資料		
					echo 'New upload file =>'.$filepath.' and old file '.$old_file.' are the same.<br>';
					$perform_upload=false;
				}
				else
				{
					//檔案不同，砍舊檔案
					$perform_upload=true;
					if((file_exists($fileRoot.$old_file)))
					{
						echo 'File exists, start to delte file<br>';
						deleteFile($fileRoot,$old_file);
						echo 'Delete old file ok.<br>';					
					}
					else
					{
						echo 'File not exists.<br>';					
					}
				}
			}
			else if(($filepath == '') &&($old_file != ''))
			{			
				// only update link		
				$perform_upload=false;
				$is_success = true;	
			}
			else if($filepath == '')
			{
				echo 'Please select a file.<br>';
				$perform_upload=false;
				$is_success = false;	
			}
			else if(($old_file == '') && ($filepath != ''))
			{
				$perform_upload=true;
				echo 'New file<br>';
			}
			
			if($perform_upload)
			{	
				//開始上傳新的檔案
				echo 'Start to upload file => '.$fileRoot.$filepath.' ...<br>';
				$msg = uploadFile($fileRoot, $_FILES['file_'.$pkey]); // perform uploading files			
			 
				if($msg=='')
				{
					// upload file successfully, next to update db
					$msg = update3D_SpecDetail_filepath($speckey,$itemkey,$filepath);							
					if($msg != "")
					{
						$is_success = false;
					}						
					else
					{
						// 上傳成功，發 MAIL
						echo 'start to send mail to inform admin.<br>';
						sendMailWhenfilechange($prod_cat_id,$prod_id,$spec_name,$fileRoot.$filepath);
					}
				}
				else $is_success = false; 		
				if(!$is_success) echo 'Error:'.$msg.'<br>';			
				
			}
		}
		if($paction == "specdetail_edit_store")
		{
			if(!$is_success) echo $msg.'<br>';
			else 
			{
				echo 'Start to update link...<br>';
				$msg = update3D_SpecDetail_link($speckey,$itemkey,$plink);		
				if($msg != "")
				{
					$is_success = false;
				}					
			}
			if(!$is_success) echo $msg.'<br>';
		}
	}
	else
	{	
		$is_success = true;
		if(($plink != "") || ($pmodel != "") || ($filepath != ""))
		{
			if($paction == "specdetail_edit_store")
			{		
				// new product for edit page, update filepath/link/model			 
				echo '(New 3D-Edit)Start to upload file => '.$fileRoot.$filepath.' ...<br>';			
			}
			else
			{
				// new product for noormal page, update filepath/model			 
				echo '(New)Start to upload file => '.$fileRoot.$filepath.' ...<br>';	
				$plink = ''; 
			}
			
			// if file not null then upload
			if($filepath != "")
			{			
				$msg = uploadFile($fileRoot, $_FILES['file_'.$pkey]); // perform uploading files 				
				if($msg=='')
				{
					$is_success = true; // upload successfully
					echo 'upload file successfully... <br>Start to insert record to db ';
				}
				else 
				{
					$is_success = false; // upload file fail	
					echo $msg;
				}
			}
			
			if($is_success)
			{				
				$msg = insert3D_SpecDetail($prod_id,$speckey,$itemkey,$filepath,$plink,$pmodel);	
				if($filepath !="")
				{
					// 上傳成功，發 MAIL
					echo 'start to send mail to inform admin.<br>';
					sendMailWhenfilechange($prod_cat_id,$prod_id,$spec_name,$fileRoot.$filepath);				
				}
			}
			else 
			{
				// upload fail
				$msg = insert3D_SpecDetail($prod_id,$speckey,$itemkey,'',$plink,$pmodel);										
				$is_success = false; 
			}
			
			if($msg != "")
			{
				$is_success = false; // insert db fail
			}
		}	
	
		if(!$is_success) echo $msg.'<br>';		
		
	}
	
	
} 
if ($paction == "specdetail_edit_store")
{
	$jump = '-edit';
} 
else 
{
	$jump = '';
}
if($is_success)
{
	echo $paction.'成功<br>';	
	header("location:3d".$jump.".php?id=".$prod_cat_id.'&prod_id='.$prod_id); // 自動跳轉
	echo 'Loading, please wait ...<br> Redirect automatically ...';
}
echo '<br><br>';
?>

<button type="button" class="btn btn-warning mr-1"  onclick="javascript:history.go(-1)"> <i class="icon-cross2"></i> 上一頁 </button>
<button type="button" class="btn btn-warning mr-1" onclick="location.href='3d<? echo $jump; ?>.php?<? echo "id=".$prod_cat_id.'&prod_id='.$prod_id; ?> ';">產品3D</button>
