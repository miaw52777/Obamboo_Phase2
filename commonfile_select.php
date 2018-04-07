<?
include_once("./dbAccess/conn.php");	
include_once("./dbAccess/CommFunc.php");
include_once("./dbAccess/FileIOFunc.php");
include_once("./dbAccess/DownloadFormatFileFunc.php");
include_once("./dbAccess/3D_SpecDetailDownloadFormatFunc.php");
 
	
if(isset($_GET['id']))
{
	$id=$_GET['id'];
	$prod_id=$_GET['prod_id'];
}
$redirectUrl = 'standard_product-structure-display.php?level=final&id='.$id.'&prod_id='.$prod_id;
if(isset($_GET['cadaction']))
{
	$cadaction = $_GET['cadaction'];
}
if(isset($_GET['spec_key']))
{
	$spec_key = $_GET['spec_key'];
	 
}
if(isset($_GET['item_key']))
{
	$item_key = $_GET['item_key'];
}
 
?>
<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<title>3DHost | Obamboo Inc.</title>
<meta name="description" content="3DHost">
<meta name="keywords" content="3DHost">
<meta name="author" content="obamboo.com" />
<meta name="copyright" content="&copy; obamboo inc." />
<meta name="audience" content="all" />
<meta name="robots" content="index,follow,all" />
<meta name="distribution" content="Global" />
<meta name="rating" content="Safe For Kids" />
<meta name="REVISIT-AFTER" content="7 DAYS" />
<link rel="apple-touch-icon" sizes="60x60" href="app-assets/images/ico/apple-icon-60.png">
<link rel="apple-touch-icon" sizes="76x76" href="app-assets/images/ico/apple-icon-76.png">
<link rel="apple-touch-icon" sizes="120x120" href="app-assets/images/ico/apple-icon-120.png">
<link rel="apple-touch-icon" sizes="152x152" href="app-assets/images/ico/apple-icon-152.png">
<link rel="shortcut icon" type="image/x-icon" href="app-assets/images/ico/favicon.ico">
<link rel="shortcut icon" type="image/png" href="app-assets/images/ico/favicon-32.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="app-assets/fonts/icomoon.css">
<link rel="stylesheet" type="text/css" href="app-assets/fonts/flag-icon-css/css/flag-icon.min.css">
<link rel="stylesheet" type="text/css" href="app-assets/vendors/css/sliders/slick/slick.css">
<link rel="stylesheet" type="text/css" href="app-assets/vendors/css/extensions/pace.css">
<link rel="stylesheet" type="text/css" href="app-assets/vendors/css/forms/selects/select2.min.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap-extended.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/app.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/colors.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-content-menu.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-overlay-menu.css">
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body data-open="click" data-menu="vertical-content-menu" data-col="2-columns" class="vertical-layout vertical-content-menu 2-columns  fixed-navbar">
<form class="form form-horizontal" action="commonfile_select-download.php" method="post">
<div class="form-group">
  <div style="padding-left: 10px;"><button type="button" class="btn btn-success" onclick="location.href='<?echo $redirectUrl;?> '">回前頁</button></div>
  <h5 style="padding-top: 30px; padding-left: 10px;">常用檔案 歡迎下載 : </h5> 
  <h5> 
  <?
	  if($cadaction == 'download')
	  {
		echo '<div style="padding-bottom: 20px; padding-left: 10px;"><button type="submit" class="btn btn-success">確認並下載</button></div>';
	  }
	  else
	  {		  
		  echo '<div style="padding-top: 10px; padding-left: 10px;">收件人姓名 : <input type="text" name="mailtoname"/></div>';
		  echo '<div style="padding-top: 10px; padding-left: 10px;">收件人信箱 : <input type="text" name="mailto" /></div>';
		  echo '<div style="padding-top: 10px; padding-bottom: 20px; padding-left: 10px;"><button type="submit" class="btn btn-success">確認並 MAIL</button></div>';	  		  
	  }
  ?>  
  </h5>
  <select class="select2-size-sm form-control" id="small-multiple" multiple="multiple" name = "selectItem[]">
    <optgroup label="2D / 3D" >
	<?	
		if($spec_key=='COMMON')
		{			
			
			$rule = get_3D_download_format_file_Select_Rule('PROD_ID',$prod_id);
			$queryresult = get_3D_download_format_file($rule,'%');
			
			while($temp = mysql_fetch_assoc($queryresult['DATA']))
			{
				$category = $temp['category'];
				$common = $temp['common'];
				$filepath = $temp['filepath'];	
		
				if($common == 'T')
				{
					$is_select = 'selected';
				}
				else 
				{
					$is_select = '';
				}
				
				echo '<option value="'.$filepath.'" '.$is_select.'>'.$category.'</option>'."\n";
			}
		}
		else
		{
			$rule = get_3D_SpecDetail_download_format_file_Select_Rule('PROD_ID',$prod_id);
			$rule .= get_3D_SpecDetail_download_format_file_Select_Rule('SPEC_KEY',$spec_key);
			$rule .= get_3D_SpecDetail_download_format_file_Select_Rule('ITEM_KEY',$item_key);
		
			$queryresult = get_3D_SpecDetail_download_format_file($rule,'%');
			
			while($temp = mysql_fetch_assoc($queryresult['DATA']))
			{
				$category = $temp['category'];
				$common = $temp['common'];
				$filepath = $temp['filepath'];	
		
				if($common == 'T')
				{
					$is_select = 'selected';
				}
				else 
				{
					$is_select = '';
				}
				
				echo '<option value="'.$filepath.'" '.$is_select.'>'.$category.'</option>';
			}
			
		}
	?>
   
    </optgroup>
  </select>  
</div>
<?
echo '<input type="hidden" name="id" value="'.$id.'"/>';
echo '<input type="hidden" name="prod_id" value="'.$prod_id.'"/>';
echo '<input type="hidden" name="spec_key" value="'.$spec_key.'"/>';
echo '<input type="hidden" name="item_key" value="'.$item_key.'"/>';
echo '<input type="hidden" name="cadaction" value="'.$cadaction.'"/>';
?>
</form>
<script src="app-assets/js/core/libraries/jquery.min.js" type="text/javascript"></script> 
<script src="app-assets/vendors/js/ui/tether.min.js" type="text/javascript"></script> 
<script src="app-assets/js/core/libraries/bootstrap.min.js" type="text/javascript"></script> 
<script src="app-assets/vendors/js/ui/perfect-scrollbar.jquery.min.js" type="text/javascript"></script> 
<script src="app-assets/vendors/js/ui/unison.min.js" type="text/javascript"></script> 
<script src="app-assets/vendors/js/ui/blockUI.min.js" type="text/javascript"></script> 
<script src="app-assets/vendors/js/ui/jquery.matchHeight-min.js" type="text/javascript"></script> 
<script src="app-assets/vendors/js/ui/jquery-sliding-menu.js" type="text/javascript"></script> 
<script src="app-assets/vendors/js/sliders/slick/slick.min.js" type="text/javascript"></script> 
<script src="app-assets/vendors/js/ui/screenfull.min.js" type="text/javascript"></script> 
<script src="app-assets/vendors/js/extensions/pace.min.js" type="text/javascript"></script> 
<script src="app-assets/vendors/js/ui/headroom.min.js" type="text/javascript"></script> 
<script src="app-assets/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script> 
<script src="app-assets/js/core/app-menu.js" type="text/javascript"></script> 
<script src="app-assets/js/core/app.js" type="text/javascript"></script> 
<script src="app-assets/js/scripts/ui/fullscreenSearch.js" type="text/javascript"></script> 
<script src="app-assets/js/scripts/forms/select/form-select2.js" type="text/javascript"></script>
</body>
</html>
