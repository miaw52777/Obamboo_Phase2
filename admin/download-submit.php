<?
include_once("../dbAccess/conn.php");	
include_once("../dbAccess/DownloadFormatFileFunc.php");
include_once("../dbAccess/3D_SpecDetailDownloadFormatFunc.php");	
include_once("../dbAccess/CommFunc.php");
include_once("../dbAccess/FileIOFunc.php");


$msg = '';
$prod_cat_id = $_POST['id'];
$prod_id = $_POST['prod_id'];
$pageaction = $_POST['pageaction'];
$perform_action = '';
if(isset($_POST['key_del']))
{
	$pkey = $_POST['key_del'];
	$perform_action = 'DELETE';
}
else
{
	$pkey = $_POST['key'];
}

$daction = '';
if(isset($_POST['daction']))
{

	$daction= $_POST['daction'];						
	$spec_key = $_POST['spec_key'];	
	$item_key = $_POST['item_key'];	
}


$keyArr = explode('_',$pkey);
$d_id = $keyArr[0];
$f_id = $keyArr[1];
	
/*	
var_dump($_POST);
 exit;	
		*/					


//$key = "file_".$prod_id.'_'.$d_id.'_'.$f_id;
$key = "file_".$pkey;
$filepath = $_FILES[$key]["name"]; 

/*
var_dump($filepath);
echo '<br>';*/

if($daction == 'specdownload')
{
	$fileRoot = '../'.$UploadfileRoot.'3d_specdetail_download/';
}
else
{
	$fileRoot = '../'.$UploadfileRoot.'3d_download/';
}			
 
if($perform_action == 'DELETE')
{
	if($daction == 'specdownload')
	{
		$rule = get_3D_SpecDetail_download_format_file_Select_Rule('PROD_ID',$prod_id);
		$rule .= get_3D_SpecDetail_download_format_file_Select_Rule('SPEC_KEY',$spec_key);
		$rule .= get_3D_SpecDetail_download_format_file_Select_Rule('ITEM_KEY',$item_key);
		$rule_main = get_3D_SpecDetail_download_format_file_Select_Rule('D_ID',$d_id);
		$result = get_3D_SpecDetail_download_format_file($rule.$rule_main,$rule_main);
	}
	else
	{
		$rule = get_3D_download_format_file_Select_Rule('PROD_ID',$prod_id);
		$rule_main = get_3D_download_format_file_Select_Rule('D_ID',$d_id);
		$result = get_3D_download_format_file($rule.$rule_main,$rule_main);
	}
	
	$is_success = $result['RESULT'];
	$msg = $result['MSG']; 		
	if($is_success)
	{
		$f_id = getSQLResultInfo($result['DATA'],'f_id');
		if($f_id != "")
		{	
			$filepath = getSQLResultInfo($result['DATA'],'filepath');
			echo 'Start to delete db record <br>'; 
			if($daction == 'specdownload')			
			{
				$msg = delete_3D_SpecDetail_Download_Format_Filepath($f_id);				
			}
			else
			{
				$msg = delete_3D_Download_Format_Filepath($f_id);				
			}
			if($msg != "")
			{
				$is_success = false; // update db filepath fail						
			}	
			else
			{
				echo 'Start to delete file => '.$fileRoot.$filepath.' ...<br>'; 
				deleteFile($fileRoot,$filepath); // delete old file			
			}			 
		}
	}
	else
	{
		echo $msg;		
	}
	 
}
else if($filepath == '')
{
	$is_success = false;
	echo 'fail : please choose a file.';
}
else if(($prod_id != "") && ($d_id != ""))
{
	if($daction == 'specdownload')
	{
		$rule = get_3D_SpecDetail_download_format_file_Select_Rule('PROD_ID',$prod_id);
		$rule .= get_3D_SpecDetail_download_format_file_Select_Rule('SPEC_KEY',$spec_key);
		$rule .= get_3D_SpecDetail_download_format_file_Select_Rule('ITEM_KEY',$item_key);
		$rule_main = get_3D_SpecDetail_download_format_file_Select_Rule('D_ID',$d_id);
		$result = get_3D_SpecDetail_download_format_file($rule.$rule_main,$rule_main);
	}
	else
	{
		$rule = get_3D_download_format_file_Select_Rule('PROD_ID',$prod_id);
		$rule_main = get_3D_download_format_file_Select_Rule('D_ID',$d_id);
		$result = get_3D_download_format_file($rule.$rule_main,$rule_main);
	}
	$is_success = $result['RESULT'];
	$msg = $result['MSG']; 		
	
	/*echo '<br>';	var_dump($result); echo '<br>';echo '<br>';	
	print_r(mysql_fetch_array($result['DATA']));
	echo '<br>';echo '<br>';*/

	if($is_success)
	{
		$f_id = getSQLResultInfo($result['DATA'],'f_id');
		if($f_id != "")
		{	
			$old_file = getSQLResultInfo($result['DATA'],'filepath');
			if(($old_file != '') && ($filepath == $old_file))
			{
				// 檔案相同
			}
			else if(($old_file != '') && ($filepath != $old_file))
			{
				deleteFile($fileRoot,$old_file); // delete old file			
				echo 'Start to upload file => '.$fileRoot.$filepath.' ...<br>';
				$msg = uploadFile($fileRoot, $_FILES[$key]); // perform uploading files			
				
				if($msg=='')
				{
					// upload file successfully
					if($daction == 'specdownload')
					{
						$msg = update_3D_SpecDetail_Download_Format_Filepath($f_id, $filepath);
					}
					else
					{
						$msg = update_3D_Download_Format_Filepath($f_id, $filepath);
					}
					if($msg != "")
					{
						$is_success = false; // update db filepath fail						
					}						
				}
				else $is_success = false; // upload file fail	
			}			
			 
		}
		else		
		{
			// new, insert data to db
			if($daction == 'specdownload')
			{
				$msg = insert_3D_SpecDetail_Download_Format_File($prod_id,$d_id,$spec_key,$item_key,$filepath);			
			}
			else
			{
				$msg = insert_3D_Download_Format_File($prod_id,$d_id,$filepath);			
			}			
			
			if($msg=='')
			{
				echo 'Insert data successfully... <br>Start to upload file '.$i.' => '.$fileRoot.$filepath.' ...<br>';
				$msg = uploadFile($fileRoot, $_FILES[$key]); // perform uploading files 
				if($msg != "")
				{
					$is_success = false; // update db filepath fail						
				}
			}
			else $is_success = false; // upload file fail					
			
		}
	}
	if(!$is_success) echo $msg.'<br>'; 
	
}
else
{
	$is_success = false;
	echo 'fail : parameters are empty.';
}
if($daction == 'specdownload')
{
	$param = 'pageaction='.$pageaction.'&id='.$prod_cat_id.'&prod_id='.$prod_id.'&spec_key='.$spec_key.'&item_key='.$item_key.'&daction=specdownload';
}
else
{
	$param = 'pageaction='.$pageaction.'&id='.$prod_cat_id.'&prod_id='.$prod_id;
}

if($is_success)
{	
	echo '上傳成功<br>';
    header("location:download.php?".$param); // 自動跳轉
	echo 'Loading, please wait ...<br> Redirect automatically...';
}
echo '<br><br>';
?>

<button type="button" class="btn btn-warning mr-1"  onclick="javascript:history.go(-1)"> <i class="icon-cross2"></i> 上一頁 </button>
<button type="button" class="btn btn-warning mr-1" onclick="location.href='download.php?<?echo $param; ?>';">產品 3D 下載 </button>