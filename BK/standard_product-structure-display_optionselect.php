<?php

include_once("./dbAccess/conn.php");	
include_once("./dbAccess/getip.php");		
include_once("./dbAccess/3DFunc.php");	
include_once("./frontendFunc.php");
include_once("./dbAccess/ProductFunc.php");
include_once("./dbAccess/DownloadFormatFileFunc.php");
include_once("./dbAccess/3D_SpecDetailDownloadFormatFunc.php");
include('./secure.php');

// check login
if(!is_login())
{
	header("Location: login.php");
	exit;
} 
//@session_start();

$o = rand(1, 9);

$_SESSION[getRequestIP().'_'.$o] = uniqid();

$level = 'final';

if(isset($_GET['id']))
{
	$id = $_GET['id']; // product category id
	$prod_id = $_GET['prod_id'];
	$action = 'send_spec_default';
}

if(isset($_POST['id']))
{
	$id = $_POST['id']; // product category id
	$prod_id = $_POST['prod_id'];	
	$action = $_POST['action'];	
	
}

if($level == 'final')
{
    // 確認是否為最後一層
	 $rule = get_Products_Select_Rule('ID',$prod_id);
	 $rule .= get_Products_Select_Rule('VALID','T');
	 $queryresult = get_Products_List($rule);
	// echo mysql_num_rows($queryresult).'<=='.$id.'<br>';
	// var_dump(mysql_fetch_array($queryresult));
	 if(mysql_num_rows($queryresult) > 0)
	 {
		// display Product				
		$Product_name = getSQLResultInfo($queryresult,'name');		
		$filepath_1 = getSQLResultInfo($queryresult,'filepath_1');		
		$filepath_2 = getSQLResultInfo($queryresult,'filepath_2');
		$filepath_3 = getSQLResultInfo($queryresult,'filepath_3');
		$filepath_4 = getSQLResultInfo($queryresult,'filepath_4');
		$description = getProduct_description($prod_id, './'.$UploadfileRoot);
		
	 } 
	 else
	 {
		 $Product_name ='';
		 $filepath_1 = '';
		 $filepath_2 = '';
		 $filepath_3 = '';
		 $filepath_4 = '';
         $description ='';
	 }
	 
	 $titleArray = explode('/',$ProductRoot);
	 $imageFrameTitle = $titleArray[count($titleArray)-1];
	 
} 

// get last layer product category name
$rule = get_Products_Category_Select_Rule('ID',$id);	
$rule = $rule.' '.get_Products_Category_Select_Rule('VALID','T');	
$result = get_Products_Category_List($rule);
//var_dump(mysql_fetch_array($result));
$last_product_cat_name = getSQLResultInfo($result,'Product_Name');
$level = getSQLResultInfo($result,'Level');
 
//var_dump($_POST);
if($action=='clear_spec')
{	
	$link_3d_preview = '';	
}
else if($action=='send_spec')
{	
	$spec_item_arr = array();
	/* get select item value from gui*/
	$rule = get_ProductSpec_Select_Rule('ID',$prod_id);	
	$result = get_ProductSpec_List($rule);
	$i=0;	
//	var_dump(($_POST));
	 
	/* loop for spec list */
	while($temp = mysql_fetch_assoc($result))
	{
		$specid = $temp['s_id'];
		$itemname = $_POST['spec_item_sel_'.$specid];
			
		$rule = get_ProductSpecItem_Select_Rule('S_ID',$specid);
		$rule .= get_ProductSpecItem_Select_Rule('ITEM',$itemname);
		$queryspecitem = get_ProductSpecItem_List($rule);		
		
		$spec_item_arr[$i]['S_ID'] = $specid;
		$spec_item_arr[$i]['ITEM_ID'] = getSQLResultInfo($queryspecitem,'item_id');
		$spec_item_arr[$i]['ITEM'] = $itemname;
		$i++; 
	}
		
	$Result_3D = get_3D_Previewlink($prod_id,$spec_item_arr,false);	
	$link_3d_preview = $Result_3D['3d_preview_link'];
	$spec_key = $Result_3D['spec_key'];
	$item_key = $Result_3D['item_key'];	

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
<link rel="stylesheet" type="text/css" href="app-assets/vendors/css/ui/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap-extended.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/app.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/colors.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-content-menu.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-overlay-menu.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/plugins/ui/jqueryui.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/pages/gallery.min.css">
<meta property="og:title" content="ob3DHost" >
<meta property="og:url" content="http://www.obamboo.com/3DHost/">
<meta property="og:image" content="http://www.obamboo.com/3DHost/3h.png">
<meta property="og:description" content="http://www.obamboo.com/3DHost/" >
<script type="text/javascript">
function Model_change(modelObj)
{		
	var model = modelObj[modelObj.selectedIndex].id;		
	if(model != "")			
	{					
		var modelArray = model.split("@");				
		var specArr = modelArray[0].split("_");				
		var itemArr = modelArray[1].split("_");							
		for (s in specArr) // spec		
		{						
			var sel = document.getElementById("spec_item_sel_" + specArr[s]);								
			for (i in itemArr) // item						
			{								
				for( l in sel.options)									
				{													
					if(sel.options[l].id == itemArr[i])											
					{												
						sel.value = sel.options[l].value;												
						break;											
					}
				}			
 	 		}		
		}			
	}
}
function spec_item_change(speckey_str)
{		
	var specArr = speckey_str.split("_");			
	var item_key = "";		
	var totalItemcnt=specArr.length;		
	for (s in specArr) 			
	{				
		var sel = document.getElementById("spec_item_sel_" + specArr[s]);								
		item_key = item_key + sel.options[sel.selectedIndex].id;							
		totalItemcnt--;				
		if(totalItemcnt>0)					
		{						
			item_key += "_";					
		}			
	}		
	var model_sel = document.getElementById("spec_model_sel");						 		
	model_sel.value = "";		
	for( l in model_sel.options)			
	{						
		if(model_sel.options[l].id == speckey_str+"@"+item_key)					
		{ 					
			model_sel.value = model_sel.options[l].value;						
			break;
	        }
	} 	
}
</script> 

</head>

<body data-open="click" data-menu="vertical-content-menu" data-col="2-columns" class="vertical-layout vertical-content-menu 2-columns  fixed-navbar">
<nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-light navbar-hide-on-scroll navbar-border navbar-shadow navbar-brand-center">
  <div class="navbar-wrapper">
    <div class="navbar-header">
      <ul class="nav navbar-nav">
        <li class="nav-item mobile-menu hidden-md-up float-xs-left"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5 font-large-1"></i></a></li>
        <li><a href="standard_product-structure-list.php" class="navbar-brand nav-link"><img alt="3DHost" src="app-assets/images/logo/3dhost-logo-light.png" class="brand-logo"></a></li>
        <li class="nav-item hidden-md-up float-xs-right"><a data-toggle="collapse" data-target="#navbar-mobile" class="nav-link open-navbar-container"><i class="icon-ellipsis pe-2x icon-icon-rotate-right-right"></i></a></li>
      </ul>
    </div>
    <div class="navbar-container content container-fluid">
      <div id="navbar-mobile" class="collapse navbar-toggleable-sm">
        <ul class="nav navbar-nav">
          <li class="nav-item hidden-sm-down"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5"></i></a></li>
          <li class="nav-item hidden-sm-down"><a href="#" class="nav-link nav-link-expand"><i class="ficon icon-expand2"></i></a></li>
        </ul>
        <ul class="nav navbar-nav float-xs-right">
          <li class="dropdown dropdown-language nav-item"><a id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle nav-link"><i class="flag-icon flag-icon-gb"></i><span class="selected-language">English</span></a>
            <div aria-labelledby="dropdown-flag" class="dropdown-menu"> <a href="#" class="dropdown-item"><i class="flag-icon flag-icon-gb"></i> English</a> <a href="#" class="dropdown-item"><i class="flag-icon flag-icon-cn"></i> China</a> <a href="#" class="dropdown-item"><i class="flag-icon flag-icon-tw"></i> Taiwan</a> <a href="#" class="dropdown-item"><i class="flag-icon flag-icon-fr"></i> French</a> <a href="#" class="dropdown-item"><i class="flag-icon flag-icon-ru"></i> Russia</a> <a href="#" class="dropdown-item"><i class="flag-icon flag-icon-es"></i> Spain</a> <a href="#" class="dropdown-item"><i class="flag-icon flag-icon-de"></i> German</a> <a href="#" class="dropdown-item"><i class="flag-icon flag-icon-jp"></i> Japan</a> <a href="#" class="dropdown-item"><i class="flag-icon flag-icon-ae"></i> العَرَبِيَّة</a> </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
<div class="app-content content container-fluid">
  <div class="content-wrapper">
    <div class="main-menu menu-static menu-light menu-accordion menu-shadow">
      <div class="main-menu-content">
        <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
          <li class="nav-item"><a href="standard_product-structure-list.php"><i class="icon-home3"></i><span data-i18n="nav.category.support" class="menu-title">Home</span></a> </li>
          <li class="nav-item"><a href="standard_product-structure-list.php"><i class="icon-ios-folder"></i><span data-i18n="nav.dash.main" class="menu-title">Products</span> 
            
            <!--<span class="tag tag tag-primary tag-pill float-xs-right mr-2">5</span>--> 
            
            </a>
            <? 
			 
				// 印左邊選單
  			    $rule = get_Products_Category_Select_Rule('PARENT_ID','NULL');	
				$rule = $rule.' '.get_Products_Category_Select_Rule('VALID','T');	
				$queryResult = get_Products_Category_List($rule);
				
				echo '<ul class="menu-content">';
				while($temp = mysql_fetch_assoc($queryResult))
				{	
					// root : leve1 1									
					echo '<li class="active"><a href="standard_product-structure-list.php" class="menu-item">'.stringWrap($temp['Product_Name']).'</a>';
					$level_cnt = 1;			
			 		category_tree($temp['id'], $level, $level_cnt,true,$prod_id);							
			     } 				 
				 echo '</li></ul>';			
			?>
          </li>
          <li class=" nav-item"><a href="http://www.obamboo.com/3DHost/contact.html"><i class="icon-support"></i><span data-i18n="nav.category.support" class="menu-title">Support</span></a> </li>
        </ul>
      </div>
    </div>
    <div class="content-body">
      <div class="content-header row">
        <div class="content-header-left breadcrumbs-left breadcrumbs-top col-md-12 col-xs-12">
          <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="standard_product-structure-list.php"><i class="icon-home3"></i> Home</a> </li>
              <li class="breadcrumb-item"><a href="standard_product-structure-list.php"><i class="icon-ios-folder"></i> Products</a> </li>
              <? 
		 
			  	$itemTitleLink = printLinkTitle($id, '',$level+1,$prod_id);
			  	echo $itemTitleLink; 
		    ?>
            </ol>
          </div>
        </div>
      </div>
      <div id="image-gallery" class="card">
        <div class="card-header">
          <h4 class="card-title"><? echo $Product_name; ?> Products gallery</h4>
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
          <div class="card-block  my-gallery">
            <div class="row">
              <?
			  $fileRoot = './'.$UploadfileRoot.'products/';			  
               
			  if($filepath_1 != '') echo '<figure class="col-lg-3 col-md-6 col-xs-12"> <img class="img-thumbnail img-fluid" src="'.$fileRoot.$filepath_1.'" alt="" /> </figure>'; 
			  if($filepath_2 != '') echo '<figure class="col-lg-3 col-md-6 col-xs-12"> <img class="img-thumbnail img-fluid" src="'.$fileRoot.$filepath_2.'" alt="" /> </figure>'; 
			  if($filepath_3 != '') echo '<figure class="col-lg-3 col-md-6 col-xs-12"> <img class="img-thumbnail img-fluid" src="'.$fileRoot.$filepath_3.'" alt="" /> </figure>'; 
			  if($filepath_4 != '') echo '<figure class="col-lg-3 col-md-6 col-xs-12"> <img class="img-thumbnail img-fluid" src="'.$fileRoot.$filepath_4.'" alt="" /> </figure>'; 			  
              
              
			 ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title" id="from-actions-top-left"><? echo $Product_name; ?> Specifications</h4>
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
              <div class="card-block">
                <div class="alert bg-success alert-icon-left alert-dismissible fade in mb-2" role="alert"> <strong>Experience it !</strong>
                  <p>Available Products</p>
                </div>
                <div class="card-text">
                  <div class="table-responsive">
                    <table class="table mb-0">
                      <thead class="bg-teal bg-lighten-4">
                        <tr>
                          <th>ITEMS</th>
                          <th><? echo $Product_name; ?></th>
                          <th></th>
                        </tr>
                      </thead>
                      <form class="form form-horizontal" action="standard_product-structure-display.php" method="post">
                        <tbody>
                          <?						    $rule = get_3D_SpecDetail_Select_Rule('PROD_ID',$prod_id);													    $rule .= get_3D_SpecDetail_Select_Rule('MODEL','NOT_NULL');													    $specDetailResult = get_3D_SpecDetail($rule);						  						    if($specDetailResult['REC_CNT'] > 0) /* 有 MODEL */						    {																echo '<tr>'."\r\n";																echo '<th scope="row">Model</th>'."\r\n";																	echo '<td><select class="select2-theme form-control" name="spec_model_sel" id="spec_model_sel" onchange="Model_change(this)">'."\r\n";																echo '<option id = "" value="" ></option>'."\r\n";																												while($itemrow = mysql_fetch_assoc($specDetailResult['DATA']))																	{																									$spec_key = $itemrow['spec_key'];																		$item_key = $itemrow['item_key'];																		if($itemrow['model'] != "")									{										echo '<option id="'.$spec_key.'@'.$item_key.'" value="'.$itemrow['model'].'" >'.$itemrow['model'].'</option>'."\r\n";									}																	}								echo '</select></td>'."\r\n";																echo '<td></td>'."\r\n"; /* no unit*/																echo '</tr>'."\r\n";							}		
							if(isset($_POST['id']))
							{							
								printSpecification($prod_id,$spec_item_arr);
							}
							else
							{
								$spec_item_arr = printSpecification($prod_id);
								
								if($action=='send_spec_default')
								{	 
							 
									$Result_3D = get_3D_Previewlink($prod_id,$spec_item_arr,true);																		 
									$link_3d_preview = $Result_3D['3d_preview_link'];	
									$spec_key = $Result_3D['spec_key'];
									$item_key = $Result_3D['item_key'];		 
									
								}
							}
							
							
							echo '<input type="hidden" name="id" value="'.$id.'"/>';
							echo '<input type="hidden" name="prod_id" value="'.$prod_id.'"/>';							
						?>
                          <tr>
                            <th scope="row"></th>
                            <td><button class="ui-button ui-widget ui-corner-all btn-success" name = 'action' value = 'send_spec'><span class="ui-icon icon-eye3"></span> View 3D</button>
                              <button class="ui-button ui-widget ui-corner-all" name = 'action' value = 'clear_spec'><span class="ui-icon icon-trash4"></span> Reset</button></td>
                            <td></td>
                          </tr>
                        </tbody>
                      </form>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?					
						
													
					$spec_param = '&id='.$id.'&prod_id='.$prod_id.'&spec_key='.$spec_key.'&item_key='.$item_key;
					 
						echo '
							<div class="col-md-12">

							  <div class="card">

								<div class="card-header">

								  <h4 class="card-title" id="from-actions-bottom-right">Data Sheet Center</h4>

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

								  <div class="card-block">

									<div class="card-text">
									
									  
										
									  <button class="ui-button ui-widget ui-corner-all btn-success" onclick="location.href=\'commonfile_select.php?cadaction=download'.$spec_param.'\';"><span class="ui-icon icon-eye3"></span> CAD download</button>
									 
									 <button class="ui-button ui-widget ui-corner-all btn-success" onclick="location.href=\'commonfile_select.php?cadaction=email'.$spec_param.'\';"><span class="ui-icon icon-eye3"></span> CAD by email</button>

									  <button class="ui-button ui-widget ui-corner-all btn-success" onclick="location.href=\'commonfile_select-download.php?cadaction=pdf'.$spec_param.'\';"><span class="ui-icon icon-eye3"></span> PDF</button>

									  
										
										
									</div>

								  </div>

								</div>

							  </div>

							</div>';
					
				 

		
			?>
      </div>
      <?
	  if($link_3d_preview != '')
	  {
	  
	  echo '
      <div class="row">

        <div class="col-xs-12">

          <div class="card">

            <div class="card-header">

               <h4 class="card-title">3D in Action</h4>

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

              <div class="video-container" id="3dviewerlinkplayer">
				<iframe id=\'3dviewerplayer\' type=\'text/html\' width="800" height="600" src=\'http://www.obamboo.com/3DHost/\' frameborder=\'0\' scrolling=\'no\' allowfullscreen webkitallowfullscreen mozallowfullscreen>
                
				<p>Your browser does not support iframes.</p>

                </iframe>



              </div>

            </div>

          </div>

        </div>

      </div>';
	  
	  }
	  
	  ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title" id="from-actions-bottom-right"><? echo $last_product_cat_name.' - '.$Product_name.' Description' ?> </h4>
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
              <div class="card-block">
                <div class="card-text"> <? echo $description; ?> </div>
                <p class="padding-top-30">
                  <button class="ui-button ui-widget ui-corner-all btn-danger" onClick="javascript:history.go(-1)"><span class="ui-icon icon-eye3"></span> Back </button>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<footer class="footer navbar-fixed-bottom footer-light">
  <p class="clearfix text-muted text-sm-center mb-0 px-2"><span class="float-md-left d-xs-block d-md-inline-block">Copyright &copy; 2017 Obamboo Inc., All rights reserved. </span><span class="float-md-right d-xs-block d-md-inline-block"><a href="http://www.obamboo.com/3DHost/contact.html" target="_blank">Contact us <i class="icon-envelop gray"></i></a></span></p>
  <div class="dmtop"><i class="icon-arrow-up"></i></div>
</footer>
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
<script src="app-assets/js/core/app-menu.js" type="text/javascript"></script> 
<script src="app-assets/js/core/app.js" type="text/javascript"></script> 
<script src="app-assets/js/scripts/ui/fullscreenSearch.js" type="text/javascript"></script> 
<script src="assets/js/custom.js"></script> 
<script src="app-assets/js/scripts/forms/select/form-select2.min.js" type="text/javascript"></script> 
<script src="app-assets/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script> 
<script src="app-assets/vendors/js/gallery/masonry/masonry.pkgd.min.js" type="text/javascript"></script> 
<script src="app-assets/vendors/js/gallery/photo-swipe/photoswipe.min.js" type="text/javascript"></script> 
<script src="app-assets/vendors/js/gallery/photo-swipe/photoswipe-ui-default.min.js" type="text/javascript"></script> 
<script src="app-assets/js/scripts/gallery/photo-swipe/photoswipe-script.min.js" type="text/javascript"></script> 
<script>

	setTimeout(console.log.bind(console, "Hello world!"));

	$(document).keydown(function(event){

		if(event.keyCode==123){

			//return false;

		}

		else if(event.ctrlKey && event.shiftKey && event.keyCode==73){

			//return false;  //Prevent from ctrl+shift+i

		}

	});

	$(document).ready(function(){
		
		$.post('url.php', {s:"<?php echo $_SESSION[getRequestIP().'_'.$o]; ?>",o:"<?php echo $o; ?>" ,encry_str:'<?php echo base64_encode($link_3d_preview); ?>' }, function(data) {								
							
					//alert(data);					
					document.getElementById('3dviewerplayer').src = data;					
					<? 
						if($action =='send_spec')
						{
							echo 'window.location.href = "#3dviewerlinkplayer"';
						}
					
					?>
					
					 

		});

	});

</script>
</body>
</html>
