<?
include_once("../dbAccess/conn.php");	
include_once("../dbAccess/ProductCategoryFunc.php");
include_once("../dbAccess/ProductFunc.php");
include('./adsecure.php');


if(!is_login())
{
	header("Location: index.php?page=".$_SERVER['REQUEST_URI']);
	exit;
} 
				
$Action = '新增';		
//var_dump($_POST);
if(isset($_GET['action']))
{
	 $Action = $_GET['action'];
}
 
if(isset($_GET['prod_id']))
{
	 $ProductID= $_GET['prod_id'];	 
} 
  
	
/* var_dump($ProductID);*/
$queryResult = get_Products_Category_List('%');

$LevelRootCnt = getLevel1CategoryCount($queryResult);

/* get product lists information by product id */
$rule = get_Products_Select_Rule('ID',$ProductID);
$queryResult = get_Products_List($rule);

if(mysql_num_rows($queryResult)>0)
{
	// fetch info	
	$temp = mysql_fetch_assoc($queryResult); 
	$Product_name = $temp['name'];							
	$Product_valid = $temp['valid'];
	$ProductCategoryID = $temp['product_cat_id']; 
	$Product_seq = $temp['seq'];
	$Product_filepath_1 = $temp['filepath_1'];
	$Product_filepath_2 = $temp['filepath_2'];
	$Product_filepath_3 = $temp['filepath_3'];
	$Product_filepath_4 = $temp['filepath_4'];	
	$Product_desc = getProduct_description($ProductID,'../'.$UploadfileRoot);
 
}	  	

/* get product category select id information */
$rule = get_Products_Category_Select_Rule('ID',$ProductCategoryID);											
$selectResult = get_Products_Category_List($rule);	
					
/* set checkbox status from last page select result */
if(mysql_num_rows($selectResult) > 0)
{
	$dataRow = mysql_fetch_assoc($selectResult);	 			
	$ProductCategoryRoot = $dataRow['Product_Root'];	
	$ProductLevel = $dataRow['Level'];	
}
/* var_dump($ProductCategoryID); */
					  
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
<link rel="apple-touch-icon" sizes="60x60" href="../app-assets/images/ico/apple-icon-60.png">
<link rel="apple-touch-icon" sizes="76x76" href="../app-assets/images/ico/apple-icon-76.png">
<link rel="apple-touch-icon" sizes="120x120" href="../app-assets/images/ico/apple-icon-120.png">
<link rel="apple-touch-icon" sizes="152x152" href="../app-assets/images/ico/apple-icon-152.png">
<link rel="shortcut icon" type="image/x-icon" href="../app-assets/images/ico/favicon.ico">
<link rel="shortcut icon" type="image/png" href="../app-assets/images/ico/favicon-32.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<link rel="stylesheet" type="text/css" href="../app-assets/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="../app-assets/fonts/icomoon.css">
<link rel="stylesheet" type="text/css" href="../app-assets/fonts/flag-icon-css/css/flag-icon.min.css">
<link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/sliders/slick/slick.css">
<link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/extensions/pace.css">
<link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/editors/summernote.css">
<link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/editors/codemirror.css">
<link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/editors/theme/monokai.css">
<link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/forms/icheck/icheck.css">
<link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/forms/icheck/custom.css">
<link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/forms/toggle/switchery.min.css">
<link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/forms/selects/select2.min.css">
<link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css">
<link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/buttons/ladda-themeless.min.css">
<link rel="stylesheet" type="text/css" href="../app-assets/css/bootstrap-extended.css">
<link rel="stylesheet" type="text/css" href="../app-assets/css/app.css">
<link rel="stylesheet" type="text/css" href="../app-assets/css/colors.css">
<link rel="stylesheet" type="text/css" href="../app-assets/css/core/menu/menu-types/vertical-multi-level-menu.css">
<link rel="stylesheet" type="text/css" href="../app-assets/css/core/menu/menu-types/vertical-overlay-menu.css">
<link rel="stylesheet" type="text/css" href="../app-assets/css/plugins/forms/switch.css">
<link rel="stylesheet" type="text/css" href="../app-assets/css/core/colors/palette-callout.css">
<link rel="stylesheet" type="text/css" href="../assets/css/style.css">
<link rel="stylesheet" type="text/css" href="showfilename.css">
<script src="showfilename.js" type="text/javascript"></script>

<script type="text/javascript">
 
 
function UploadResult()
{	
  var content = $('[name="content"]').summernote('code');	    
  document.getElementById("content_encode_str").value = btoa(encodeURIComponent(content)); 
  document.forms['summernoteForm'].submit();
}
 
function edit_click()
{	
	$('.summernote-code').summernote({focus: true});
}
 </script>

</head>
<body data-open="hover" data-menu="vertical-mmenu" data-col="2-columns" class="vertical-layout vertical-mmenu 2-columns  fixed-navbar" >
<nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-dark navbar-border navbar-shadow">
  <div class="navbar-wrapper">
    <div class="navbar-header">
      <ul class="nav navbar-nav">
        <li class="nav-item mobile-menu hidden-md-up float-xs-left"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5 font-large-1"></i></a></li>
        <li class="nav-item"><a href="index.php" class="navbar-brand nav-link"><img alt="3DHost" src="../app-assets/images/logo/3dhost-logo-light.png" data-expand="../app-assets/images/logo/3dhost-logo-light.png" data-collapse="../app-assets/images/logo/3dhost-logo-light.png" class="brand-logo"></a></li>
        <li class="nav-item hidden-md-up float-xs-right"><a data-toggle="collapse" data-target="#navbar-mobile" class="nav-link open-navbar-container"><i class="icon-ellipsis pe-2x icon-icon-rotate-right-right"></i></a></li>
      </ul>
    </div>
    <div class="navbar-container content container-fluid">
      <div id="navbar-mobile" class="collapse navbar-toggleable-sm">
        <ul class="nav navbar-nav">
          <li class="nav-item hidden-sm-down"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5"></i></a></li>
          <li class="nav-item nav-search"><a href="#" class="nav-link nav-link-search fullscreen-search-btn"><i class="ficon icon-search7"></i></a></li>
          <li class="nav-item hidden-sm-down"><a href="#" class="nav-link nav-link-expand"><i class="ficon icon-expand2"></i></a></li>
        </ul>
        <ul class="nav navbar-nav float-xs-right">
          <li class="dropdown dropdown-user nav-item"><a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link"><span class="avatar avatar-online"><img src="../app-assets/images/portrait/small/avatar-s-1.png" alt="avatar"><i></i></span><span class="user-name">Admin</span></a>
            <div class="dropdown-menu dropdown-menu-right"> <a href="#" class="dropdown-item"><i class="icon-power3"></i> Logout</a> </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
<div class="main-menu menu-fixed menu-dark menu-accordion menu-bordered menu-shadow">
  <div class="main-menu-content">
    <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
      <li class="nav-item"><a href="products-category.php"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">產品類別</span><span class="tag tag tag-primary tag-pill float-xs-right mr-2"><? echo $LevelRootCnt; ?></span></a> </li>
      <li class="nav-item active"><a href="products.php"><i class="icon-note"></i><span class="menu-title">產品管理</span></a></li>
      <li class="nav-item"><a href="about.php"><i class="icon-office"></i><span class="menu-title">公司介紹</span></a></li>
      <li class="nav-item"><a href="#"><i class="icon-android-globe"></i><span class="menu-title">展覽訊息</span></a></li>
      <li class="nav-item"><a href="#"><i class="icon-android-film"></i><span class="menu-title">影片介紹</span></a></li>
      <li class="nav-item"><a href="#"><i class="icon-android-people"></i></i><span class="menu-title">會員管理</span></a></li>
      <li class="nav-item"><a href="#"><i class="icon-cash"></i><span class="menu-title">商情中心</span></a></li>
      <li class="nav-item"><a href="system.php"><i class="icon-android-settings"></i><span class="menu-title">系統管理</span></a></li>
      <li class="nav-item"><a href="http://www.obamboo.com/3DHost/contact.html"><i class="icon-support"></i><span class="menu-title">Support</span></a> </li>
    </ul>
  </div>
  <div class="main-menu-footer footer-close">
    <div class="header text-xs-center"><a href="#" class="col-xs-12 footer-toggle"><i class="icon-ios-arrow-up"></i></a></div>
    <div class="content">
      <div class="insights">
        <div class="col-xs-12">
          <p>Product Delivery</p>
          <progress value="25" max="100" class="progress progress-xs progress-success">25%</progress>
        </div>
        <div class="col-xs-12">
          <p>Targeted Sales</p>
          <progress value="70" max="100" class="progress progress-xs progress-info">70%</progress>
        </div>
      </div>
      <div class="actions"><a href="javascript: void(0);" data-placement="top" data-toggle="tooltip" data-original-title="Settings"><span aria-hidden="true" class="icon-cog3"></span></a><a href="javascript: void(0);" data-placement="top" data-toggle="tooltip" data-original-title="Lock"><span aria-hidden="true" class="icon-lock4"></span></a><a href="javascript: void(0);" data-placement="top" data-toggle="tooltip" data-original-title="Logout"><span aria-hidden="true" class="icon-power3"></span></a></div>
    </div>
  </div>
</div>
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="content-header row padding-bottom-20">
      <div class="content-header-left col-md-6 col-xs-12">
        <h2 class="content-header-title mb-0">產品管理</h2>
        <div class="row breadcrumbs-top">
          <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="products.php">產品管理</a></li>
              <li class="breadcrumb-item active"><? echo $Action; ?>產品</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
	
    <div class="content-body">
      <section id="bootstrap3">
        <div class="row">
          <div class="col-xs-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title"><? echo $Action ?>產品</h4>
                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                <div class="heading-elements">
                  <ul class="list-inline mb-0">
                    <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                    <li><a data-action="reload"><i class="icon-reload"></i></a></li>
                    <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                    <li><a data-action="close"><i class="icon-cross2"></i></a></li>
                  </ul>
                </div>
              </div>
              <div class="card-body collapse in">
                <div class="card-block padding-lr-20">
                  <form class="form form-horizontal" action="products-edit-submit.php" method="post" enctype="multipart/form-data">
                    <div class="form-body padding-top-40">
                      <div class="form-group row">
                        <label class="col-md-3 label-control" for="projectinput1">產品名稱</label>						
                        <div class="col-md-9">                          
                          <? echo '<input type="text" id="projectinput1" class="form-control" placeholder="產品名稱" name="product_name" value="'.htmlspecialchars($Product_name).'">';	 ?>
						</div>
                      </div>
                      <div class="form-group row">
                        <label class="col-md-3 label-control" for="projectinput2">選擇產品類別</label>
                        <div class="col-md-9">
                          <select id="product_category_select" name="rootOptionSelect" class="form-control">
						  
                             <? 		
							  if($Action == '新增')
							  {
							  	echo '<option name="root" value="" selected>* No Parent Category</option>';								
							  }
							  else
							  {
							  	echo '<option name="root"  value="">* No Parent Category</option>';
							  } 
								print_category_optionlist($ProductCategoryRoot,$ProductLevel+1);
							?>
                          </select>
                        </div>
                      </div>					  	
                      <div class="form-group row">
                        <label class="col-md-3 label-control">上傳圖片 1 500 * 330</label>
                        <div class="col-md-9">
                          <label id="projectinput8" class="file center-block">
                            <input type="button" value="選擇檔案" onClick="this.form.file1.click();">	
							<input type="file" name="file1" id="file1" size="20" class="ifile" onChange="checkit('lbl_file_1',this.value.substr(this.value.lastIndexOf('\\')+1))">
						    <label id="lbl_file_1"><? if($Product_filepath_1 == "") echo '未選取任何檔案'; else echo $Product_filepath_1; ?>
                            <span class="file-custom"></span> </label>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-md-3 label-control">上傳圖片 2 500 * 330</label>
                        <div class="col-md-9">
                          <label id="projectinput8" class="file center-block">
                            	<input type="button" value="選擇檔案" onClick="this.form.file2.click();">	
								<input type="file" name="file2" id="file2" size="20" class="ifile" onChange="checkit('lbl_file_2',this.value.substr(this.value.lastIndexOf('\\')+1))">
								<label id="lbl_file_2"><? if($Product_filepath_2 == "") echo '未選取任何檔案'; else echo $Product_filepath_2; ?>
                            <span class="file-custom"></span> </label>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-md-3 label-control">上傳圖片 3 500 * 330</label>
                        <div class="col-md-9">
                          <label id="projectinput8" class="file center-block">
                            	<input type="button" value="選擇檔案" onClick="this.form.file3.click();">	
								<input type="file" name="file3" id="file3" size="20" class="ifile" onChange="checkit('lbl_file_3',this.value.substr(this.value.lastIndexOf('\\')+1))">
								<label id="lbl_file_3"><? if($Product_filepath_3 == "") echo '未選取任何檔案'; else echo $Product_filepath_3; ?>
                            <span class="file-custom"></span> </label>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-md-3 label-control">上傳圖片 4 500 * 330</label>
                        <div class="col-md-9">
                          <label id="projectinput8" class="file center-block">
                            	<input type="button" value="選擇檔案" onClick="this.form.file4.click();">	
								<input type="file" name="file4" id="file4" size="20" class="ifile" onChange="checkit('lbl_file_4',this.value.substr(this.value.lastIndexOf('\\')+1))">
								<label id="lbl_file_4"><? if($Product_filepath_4 == "") echo '未選取任何檔案'; else echo $Product_filepath_4; ?>
                            <span class="file-custom"></span> </label>
                        </div>
                      </div> 
                      <div class="form-group row">
                        <label class="col-md-3 label-control" for="projectinput3">排序</label>
                        <div class="col-md-9">
						   <?
						  echo '<input type="text" name="Seq" id="projectinput3" class="form-control" placeholder="排序" name="Seq" value = "'.$Product_seq.'">';
						 ?> 
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-md-3 label-control" for="projectinput9">狀態</label>
                        <div class="col-md-9">
                          <div class="card-block">
                            <div class="icheck1">
                              <fieldset>
							  
								 <?
								  if($Product_valid == 'T')
								  {
								 	$valid = 'checked';
								  }
								  else
								  {							  	
								 	$valid = 'unchecked';
								  }
								  echo '<input type="checkbox" id="input-2" name="valid" '.$valid.'>';
								  
								 ?>
                                <label for="input-2">顯示</label>
                              </fieldset>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-actions">
                      <button type="button" class="btn btn-warning mr-1"  onclick="javascript:history.go(-1)"> <i class="icon-cross2"></i> 取消 </button>
                      <button type="submit" class="btn btn-success"> <i class="icon-check2"></i>
					  <? 
					  	if($Action == '刪除')
					    {
							echo $Action;
					    }
						else
						{
							echo '儲存';
						}
					  ?>
						</button>
                    </div>
					<? 
						echo '<input type="hidden" name="prod_id" value="'.$ProductID.'"/>'; 
						echo '<input type="hidden" name="action" value="'.$Action.'"/>';
					?>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">產品規格</h4>
                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                <div class="heading-elements">
                  <ul class="list-inline mb-0">
                    <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                    <li><a data-action="reload"><i class="icon-reload"></i></a></li>
                    <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                    <li><a data-action="close"><i class="icon-cross2"></i></a></li>
                  </ul>
                </div>
              </div>
              <div class="card-body collapse in">
                <div class="card-block padding-lr-20 padding-bottom-20 padding-top-20">
                  <div class="btn-group" role="group" aria-label="Basic example">
					
					<?				  
						$param = 'id='.$ProductCategoryID.'&prod_id='.$ProductID;
						echo '<button type="button" class="btn btn-info" onClick="location.href=\'specifications.php?'.$param.'\';">產品規格</button>';
						echo '<button type="button" class="btn btn-danger" onClick="location.href=\'3d.php?'.$param.'\';">產品 3D</button>';						
					?>
				 
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section id="codeview">
        <div class="row">
          <div class="col-xs-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">編輯產品說明</h4>
                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                <div class="heading-elements">
                  <ul class="list-inline mb-0">
                    <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                    <li><a data-action="reload"><i class="icon-reload"></i></a></li>
                    <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                    <li><a data-action="close"><i class="icon-cross2"></i></a></li>
                  </ul>
                </div>
              </div>		  			  
              <div class="card-body collapse in">
                <div class="card-block padding-lr-20 padding-bottom-20 padding-top-20">
                         
                    <div class="form-group"> 
						<textarea class="summernote-code" name="content" style="height: 400px;">
						  <?
							echo $Product_desc;
						  ?>
						</textarea>
                     </div>
					 <form id="summernoteForm" action="products-edit-submit.php" method="POST" enctype="multipart/form-data">               
					    <button id="edit" class="btn btn-warning" type="button" hidden>
						<button id="save" class="btn btn-success" type="button" hidden>
						
						<button id="cancel" class="btn btn-warning" type="button" onclick="edit_click()"><i class="icon-pencil"></i> 取消</button>					    
						<button id="upload" class="btn btn-success" type="button" onclick="UploadResult()"><i class="icon-save"></i> 儲存</button>				   						 
				 
				  <? 						
						echo '<input type="hidden" name="id" value="'.$ProductCategoryID.'"/>'; 
						echo '<input type="hidden" name="prod_id" value="'.$ProductID.'"/>'; 
						echo '<input type="hidden" name="action" value="UPDATE_DESC"/>';
						echo '<input type="hidden" id="content_encode_str" name="content_encode_str"/>';
					?>
				 </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>
<footer class="footer navbar-fixed-bottom footer-dark">
  <p class="clearfix text-muted text-sm-center mb-0 px-2"><span class="float-md-left d-xs-block d-md-inline-block">Copyright &copy; 2017 Obamboo Inc. All rights reserved. </span><span class="float-md-right d-xs-block d-md-inline-block"><a href="http://www.obamboo.com/3DHost/contact.html" target="_blank">Contact</a> <i class="icon-envelop gray"></i></span></p>
  <div class="dmtop"><i class="icon-arrow-up"></i></div>
</footer>
<script src="../app-assets/js/core/libraries/jquery.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/ui/tether.min.js" type="text/javascript"></script> 
<script src="../app-assets/js/core/libraries/bootstrap.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/ui/perfect-scrollbar.jquery.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/ui/unison.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/ui/blockUI.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/ui/jquery.matchHeight-min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/ui/jquery-sliding-menu.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/sliders/slick/slick.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/ui/screenfull.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/extensions/pace.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/menu/jquery.mmenu.all.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/buttons/spin.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/buttons/ladda.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/forms/icheck/icheck.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/forms/toggle/switchery.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js" type="text/javascript"></script> 
<script src="https://www.google.com/jsapi" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/charts/jquery.sparkline.min.js" type="text/javascript"></script> 
<script src="../app-assets/js/core/app-menu.js" type="text/javascript"></script> 
<script src="../app-assets/js/core/app.js" type="text/javascript"></script> 
<script src="../app-assets/js/scripts/ui/fullscreenSearch.js" type="text/javascript"></script> 
<script src="../app-assets/js/scripts/tables/components/table-components.js" type="text/javascript"></script> 
<script src="../app-assets/js/scripts/tables/datatables/datatable-basic.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/menu/jquery.mmenu.all.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/editors/codemirror/lib/codemirror.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/editors/codemirror/mode/xml/xml.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/editors/summernote/summernote.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/tables/jquery.dataTables.min.js" type="text/javascript"></script> 
<script src="../app-assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js" type="text/javascript"></script> 
<script src="../app-assets/js/scripts/tables/datatables/datatable-styling.min.js" type="text/javascript"></script> 
<script src="../app-assets/js/scripts/editors/editor-summernote.js" type="text/javascript"></script>
<script type="text/javascript">
</script>
</body>
</html>
