<?php

include_once("./dbAccess/conn.php");	
include_once("./dbAccess/getip.php");		
include_once("./dbAccess/3DFunc.php");	
include_once("./frontendFunc.php");
include_once("./dbAccess/ProductFunc.php");
include_once("./dbAccess/DownloadFormatFileFunc.php");
include_once("./dbAccess/3D_SpecDetailDownloadFormatFunc.php");include('./frontEnd_CommonHeaders/en_map.php');
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

$level = 'final';$name_lang = "Taiwan";

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
	$model_spec_sel = $_POST['spec_model_sel'];
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

<link rel="stylesheet" type="text/css" href="app-assets/vendors/css/ui/jquery-ui.min.css">

<link rel="stylesheet" type="text/css" href="app-assets/vendors/css/forms/listbox/bootstrap-duallistbox.min.css">

<link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap-extended.css">

<link rel="stylesheet" type="text/css" href="app-assets/css/app.css">

<link rel="stylesheet" type="text/css" href="app-assets/css/colors.css">

<link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-content-menu.css">

<link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-overlay-menu.css">

<link rel="stylesheet" type="text/css" href="app-assets/css/plugins/ui/jqueryui.min.css">

<link rel="stylesheet" type="text/css" href="assets/css/style.css">

<link rel="stylesheet" type="text/css" href="app-assets/css/pages/gallery.min.css">

<link rel="stylesheet" type="text/css" href="app-assets/css/plugins/forms/dual-listbox.min.css">
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
function datasheetDownloadClick()
{	 
	// mail 
	document.getElementById("div_mailtoname").style.display = "none";																	
	document.getElementById("div_mailto").style.display = "none";								
	document.getElementById("div_send").style.display = "none";

	// download	 			
	$("#div_cad_title").dialog({
					title: "常用檔案 歡迎下載 :"
				});
 
	document.getElementById("cadaction").value = "download";
	document.getElementById("btndownload").style.display = "";
} 
function datasheetMailClick()
{
	 // mail
	document.getElementById("div_mailtoname").style.display = "";									
	document.getElementById("div_mailto").style.display = "";								
	document.getElementById("div_send").style.display = "";

	// download	
	$("#div_cad_title").dialog({
					title: "常用檔案 歡迎傳遞 :"
				});
	document.getElementById("cadaction").value = "email";
	document.getElementById("btndownload").style.display = "none";								
}
function AddEnquiry(cookie_str, prod_id)
{
 	if((cookie_str+",").includes(prod_id+","))	
	{
			alert("產品已存在 Enquiry 清單，謝謝!");			
	}
	else
 	{
		if(cookie_str == "")		
		{			
		     document.cookie = "prod_list=" + prod_id;		
		}		
		else
		{
			document.cookie = "prod_list=" + cookie_str + "," + prod_id;
		}
		alert("已加入 Enquiry 清單，謝謝!");
			
	}	
}
</script> 

</head>

<body data-open="click" data-menu="vertical-content-menu" data-col="2-columns" class="vertical-layout vertical-content-menu 2-columns  fixed-navbar">


 <? require_once('./frontEnd_CommonHeaders/header.php');?> 
<div class="app-content content container-fluid">
  <div class="content-wrapper">
     <? require_once('./frontEnd_CommonHeaders/LeftMenu.php');?>
    <div class="content-body">
      <div class="content-header row">
        <div class="content-header-left breadcrumbs-left breadcrumbs-top col-md-12 col-xs-12">
          <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
              <? require_once('./frontEnd_CommonHeaders/LinkHeader.php');				   echo getLinkTitle($name_lang,"Home");				   echo getLinkTitle($name_lang,"Products");				?>
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
                          <?						    
						  $rule = get_3D_SpecDetail_Select_Rule('PROD_ID',$prod_id);													    
						  $rule .= get_3D_SpecDetail_Select_Rule('MODEL','NOT_NULL');													    
						  $specDetailResult = get_3D_SpecDetail($rule);						  						    
						  if($specDetailResult['REC_CNT'] > 0) /* 有 MODEL */						    
						  {																
							  echo '<tr>'."\r\n";																
							  echo '<th scope="row">Model</th>'."\r\n";																	
							  echo '<td><select class="select2-theme form-control" name="spec_model_sel" id="spec_model_sel" onchange="Model_change(this)">'."\r\n";																
							  echo '<option id = "" value="" ></option>'."\r\n";
						      $is_model_sel = '';
							  while($itemrow = mysql_fetch_assoc($specDetailResult['DATA']))																	
							  {																									
								$spec_key = $itemrow['spec_key'];																		
								$item_key = $itemrow['item_key'];																		
								if($itemrow['model'] != "")									
								{		
									if($model_spec_sel == $itemrow['model'])
									{
										$is_model_sel = 'selected';
									}
									else
									{
										$is_model_sel = '';
									}
									echo '<option id="'.$spec_key.'@'.$item_key.'" value="'.$itemrow['model'].'"  '.$is_model_sel.'>'.$itemrow['model'].'</option>'."\r\n";									
								}																	
							 }								
							echo '</select></td>'."\r\n";																
							echo '<td></td>'."\r\n"; /* no unit*/																
							echo '</tr>'."\r\n";							
						   }		
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
                    </table>										<div class="padding-top-30 padding-lr-20 padding-bottom-20 ">                      <button type="button" class="ui-button ui-widget ui-corner-all btn-danger"><i class="icon-heart"></i> Add favorite products</button>                      <button class="ui-button ui-widget ui-corner-all btn-success" onclick="AddEnquiry('<? echo $_COOKIE['prod_list']; ?>','<? echo $prod_id; ?>');"><span class="ui-icon icon-mail6"></span> Add enquiry</button>                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

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

								<? 
									$spec_param = '&id='.$id.'&prod_id='.$prod_id.'&spec_key='.$spec_key.'&item_key='.$item_key;	
								?>

								<div class="card-body collapse in">

								  <div class="card-block">

									<div class="card-text padding-lr-20 padding-bottom-20 padding-top-20">
 
									  <button class="btn btn-success font-medium-1 percent-width-dialog-btn mt-1" onclick="datasheetDownloadClick()" name="download" id ="caddownload"> CAD download</button>
									  <button class="btn btn-success font-medium-1 percent-width-dialog-btn mt-1" onclick="datasheetMailClick()"  name="email" id ="cademail"> CAD by email</button>
									  <?
									 
										echo '<button class="btn btn-success font-medium-1 percent-width-dialog-btn mt-1" onclick="location.href=\'commonfile_select-download.php?cadaction=pdf'.$spec_param.'\';">PDF</button>';
									  ?>
										
										<div class="percent-width-dialog"  id="div_cad_title" >										
									<form class="form form-horizontal" action="commonfile_select-download.php" method="post">                      
											<div class="form-group">
  
												<div class="form-group row" id="div_mailtoname">

											  <label for="inputEmail3" class="col-sm-2 col-form-label">Name</label>

											  <div class="col-sm-10">

												<input type="text" class="form-control" name="mailtoname" placeholder="Name">

											  </div>

											</div>

											<div class="form-group row" id="div_mailto">

											  <label for="inputPassword3" class="col-sm-2 col-form-label">Email</label>

											  <div class="col-sm-10">

												<input type="email" class="form-control" name="mailto" placeholder="Email">

											  </div>

											</div>
											<div class="form-group row" id="div_send">

											  <div class="offset-sm-2 col-sm-10">

												<button type="submit" class="btn btn-primary">Send</button>

											  </div>

											</div>
										
											
											<button type="submit" class="btn btn-primary" id="btndownload">Download</button>
											
							<?				
								
								echo '<input type="hidden" name="id" value="'.$id.'"/>';

								echo '<input type="hidden" name="prod_id" value="'.$prod_id.'"/>';

								echo '<input type="hidden" name="spec_key" value="'.$spec_key.'"/>';

								echo '<input type="hidden" name="item_key" value="'.$item_key.'"/>';

								echo '<input type="hidden" name="cadaction" id="cadaction"  value=""/>';
							?>
                          

							</div>

                      

                      <select multiple="multiple" size="50" class="duallistbox-multi-selection" name = "selectItem[]">

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

									

									if($filepath != "") {echo '<option value="'.$filepath.'" '.$is_select.'>'.$category.'</option>'."\n";}

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

									

									if($filepath != "") {echo '<option value="'.$filepath.'" '.$is_select.'>'.$category.'</option>';}

								}

							}
							
							?>


                      </select>
							</form>
                    </div>
			
				 </div>

              </div>

            </div>

          </div>

        </div>

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

<script src="app-assets/vendors/js/forms/listbox/jquery.bootstrap-duallistbox.min.js" type="text/javascript"></script> 

<script src="app-assets/js/core/app-menu.js" type="text/javascript"></script> 

<script src="app-assets/js/core/app.js" type="text/javascript"></script> 

<script src="app-assets/js/scripts/ui/fullscreenSearch.js" type="text/javascript"></script> 

<script src="assets/js/custom.js"></script> 

<script src="app-assets/js/core/libraries/jquery_ui/jquery-ui.min.js" type="text/javascript"></script> 

<script src="app-assets/js/scripts/ui/jquery-ui/dialog-tooltip.min.js" type="text/javascript"></script> 

<script src="app-assets/js/scripts/forms/listbox/form-duallistbox.min.js" type="text/javascript"></script> 

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
