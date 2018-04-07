<?
include_once("./dbAccess/conn.php");	
include_once("./dbAccess/CommFunc.php");
include_once("./dbAccess/MailFunc.php");
include_once("./dbAccess/FileIOFunc.php");
include_once("./dbAccess/ProductCategoryFunc.php");
include_once("./dbAccess/ProductFunc.php");
include_once("./dbAccess/DownloadFormatFileFunc.php");
include_once("./dbAccess/3D_SpecDetailDownloadFormatFunc.php");


/*
var_dump($_POST);  exit;*/
if(isset($_POST['cadaction']))
{
	$cadaction = $_POST['cadaction'];	
	$prod_cat_id = $_POST['id'];
	$prod_id = $_POST['prod_id'];
	$selectItem = $_POST['selectItem'];
	$mailto = $_POST['mailto'];	
	$mailtoName = $_POST['mailtoname'];	
	$spec_key = $_POST['spec_key'];
	$item_key = $_POST['item_key'];
}

if(isset($_GET['cadaction']))
{
	$cadaction = $_GET['cadaction'];	
	$prod_cat_id = $_GET['id'];
	$prod_id = $_GET['prod_id'];
	$spec_key = $_GET['spec_key'];
	$item_key = $_GET['item_key'];
}

if($spec_key == 'COMMON')
{
	$fileRoot = './'.$UploadfileRoot.'3d_download/';
}
else
{
	$fileRoot = './'.$UploadfileRoot.'3d_specdetail_download/';
}
	
if(($cadaction == 'email') && (($mailto == '' )||($mailtoName == '')))
{
	echo 'Error : 請給收件人姓名與 Mail address.<br>';
	
}
else if($cadaction=='pdf')
{
	if($spec_key == 'COMMON')
	{
		$rule = get_3D_download_format_file_Select_Rule('PROD_ID',$prod_id);
		$rule .= get_3D_download_format_file_Select_Rule('FILEPATH','%.pdf');
		
		$queryresult = get_3D_download_format_file_raw($rule,'%');
		$pdffile = '';
		 
		if($queryresult['REC_CNT']>0)
		{
			$pdffile = getSQLResultInfo($queryresult['DATA'],'filepath');
		} 
		if($pdffile != '')
		{
			echo 'download file.<br>';
			header("Location: ".$fileRoot.$pdffile); // download file		
		}
		else
		{
			echo 'No upload pdf file.<br>';		
		}
	}
	else
	{
		$rule = get_3D_SpecDetail_download_format_file_Select_Rule('PROD_ID',$prod_id);
		$rule .= get_3D_SpecDetail_download_format_file_Select_Rule('SPEC_KEY',$spec_key);
		$rule .= get_3D_SpecDetail_download_format_file_Select_Rule('ITEM_KEY',$item_key);
		$rule .= get_3D_SpecDetail_download_format_file_Select_Rule('FILEPATH','%.pdf');
		
		$queryresult = get_3D_SpecDetail_download_format_file_raw($rule,'%');
		$pdffile = '';
		 
		if($queryresult['REC_CNT']>0)
		{
			$pdffile = getSQLResultInfo($queryresult['DATA'],'filepath');
		} 
		if($pdffile != '')
		{
			echo 'download file.<br>';
			header("Location: ".$fileRoot.$pdffile); // download file		
		}
		else
		{
			echo 'No upload pdf file.<br>';		
		}	
	}
}
else
{
	if(isset($prod_cat_id))
	{	
		//打包檔案				
		// get selected options
		$filelist=array();
		if(isset($selectItem))
		{
			$selected = $selectItem; 
			$spec_list = '';
			foreach ($selected as $option)
			{
				if($option != '')
				{
					$spec_list .= $option."\n";
					array_push($filelist,$option);
				}
			}
		}	
		$zipname = archiveFile($filelist,$fileRoot,'commonfile.zip');	 
		if($zipname != '')
		{
			if($cadaction == 'download')
			{				
				header("Location: $zipname"); // download file		
				readfile($zipname);				
			}
			else if ($cadaction == 'email')
			{

				//get product category info 
				$rule = get_Products_Category_Select_Rule("ID",$prod_cat_id);
				$queryProductCat = get_Products_Category_List($rule);
				$product_root = getSQLResultInfo($queryProductCat,'Product_Root');

				// get product info
				$rule = get_Products_Select_Rule('ID',$prod_id);						
				$queryProduct = get_Products_List($rule);
				$product_name = getSQLResultInfo($queryProduct,'name');

				/* define mail information */ 			
				$mailSubject = "ob3DHost - CAD by email";
				$mailContent = "產品名稱 : ".$product_name."\n";
				$mailContent .= "產品階層 : ".$product_root."\n";
				$mailContent .= "對應規格 : ".$spec_list;		
				$returnMsg = sendMail($mailtoName,$mailto,$mailSubject,$mailContent,$zipname,$mailcc);		
				echo $returnMsg['MSG'];
			}
			$redirectUrl = 'standard_product-structure-display.php?level=final&id='.$id.'&prod_id='.$prod_id;			
			header("location:".$redirectUrl); 
		}
		echo '<br>';	
	}
	
}	
?> 

<button type="button" class="btn btn-success"  onclick="javascript:history.go(-1)"> <i class="icon-cross2"></i> 上一頁 </button>