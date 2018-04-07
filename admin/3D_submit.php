<?
include_once("../dbAccess/conn.php");	
include_once("../dbAccess/3DFunc.php");
include_once("../dbAccess/CommFunc.php");
include_once("../dbAccess/FileIOFunc.php");
include_once("../dbAccess/MailFunc.php");

$msg = '';

$paction = $_POST['action'];
$prod_cat_id = $_POST['id']; 
$pid = $_POST['prod_id']; 
$plink = $_POST['link']; 

$filepath_cmf = $_FILES['file_CMF']['name'];		 

/*
var_dump($_POST);
echo '<br>';
var_dump($_FILES);
echo '<br>';

exit;*/
$fileRoot = '../'.$UploadfileRoot.'3d_commonfile/';


if($paction == "")
{
	$is_success = false;	
	echo 'No Action. <br>';
}
elseif (($paction == "commonfile_store")  || ($paction == "commonfile_edit_store") )
{
	// upload 3D common file
	$is_success = true;	
	$rule = get_3D_CommonFile_Select_Rule('PROD_ID',$pid); 
	$returnMsg = get_3D_CommonFile($rule); // check product is exists
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
				
		// 檔案存在
		$perform_upload=false;
		if(($old_file != '') && ($filepath_cmf != ''))
		{
			echo 'Old file exists=>'.$old_file.'<br>';
		
			if($old_file == $filepath_cmf)
			{
				//檔案相同，不異動資料		
				echo 'New upload file =>'.$filepath_cmf.' and old file '.$old_file.' are the same.<br>';
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
		else if(($filepath_cmf == '') &&($old_file != ''))
		{			
			// only update link		
			$perform_upload=false;
			$is_success = true;	
		}
		else if($filepath_cmf == '')
		{
			echo 'Please select a file.<br>';
			$perform_upload=false;
			$is_success = false;	
		}
		else if(($old_file == '') && ($filepath_cmf != ''))
		{
			$perform_upload=true;
			echo 'New file<br>';
		}
		//開始上傳新的檔案
		echo 'Start to upload file => '.$fileRoot.$filepath_cmf.' ...<br>';
		if($perform_upload)
		{	
			$msg = uploadFile($fileRoot, $_FILES['file_CMF']); // perform uploading files			
		 
			if($msg=='')
			{
				// upload file successfully, next to update db
				$msg = update3D_CommonFile_filepath($pid,$filepath_cmf);							
				if($msg != "")
				{
					$is_success = false;
				}						
				else
				{
					// 上傳成功，發 MAIL
					echo 'start to send mail to inform admin.<br>';
					sendMailWhenfilechange($prod_cat_id,$pid,'通用檔案',$fileRoot.$filepath_cmf);									
				}
			}
			else $is_success = false; 		
			if(!$is_success) echo 'Error:'.$msg.'<br>';
			
			
		}
		if($paction == "commonfile_edit_store")
		{
			if(!$is_success) echo $msg.'<br>';
			else 
			{
				echo 'Start to update link...<br>';
				$msg = update3D_CommonFile_link($pid,$plink);		
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
		// new product	
		if(($paction == "commonfile_edit_store") && ($plink != ""))
		{
			// link not null, first to insert record
			$msg = insert3D_CommonFile($pid,$filepath_cmf,$plink);	
			if($msg == '')			
			{
				echo 'Start to upload file => '.$fileRoot.$filepath_cmf.' ...<br>';
				$msg = uploadFile($fileRoot, $_FILES['file_CMF']); // perform uploading files 				
				if($msg != "")
				{
					$is_success = false; 
				}
			}
			else $is_success = false; 
		}
		else 
		{
			// only update filepath
			echo 'Start to upload file => '.$fileRoot.$filepath_cmf.' ...<br>';
			$msg = uploadFile($fileRoot, $_FILES['file_CMF']); // perform uploading files 				
			if($msg=='')
			{
				echo 'upload file successfully... <br>Start to insert record to db ';
				$msg = insert3D_CommonFile($pid,$filepath_cmf,'');		
				if($msg != "")
				{
					$is_success = false; // update db filepath fail						
				}
			}
			else $is_success = false; // upload file fail	
		}
		echo $msg.'<br>';		
		
	}
	
	
} 
if ($paction == "commonfile_edit_store")
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
	header("location:3d".$jump.".php?id=".$prod_cat_id.'&prod_id='.$pid); // 自動跳轉
	echo 'Loading, please wait ...<br> Redirect automatically ...';
}
echo '<br><br>';
?>

<button type="button" class="btn btn-warning mr-1"  onclick="javascript:history.go(-1)"> <i class="icon-cross2"></i> 上一頁 </button>
<button type="button" class="btn btn-warning mr-1" onclick="location.href='3d<? echo $jump; ?>.php?<? echo "id=".$prod_cat_id.'&prod_id='.$pid; ?> ';">產品3D</button>
