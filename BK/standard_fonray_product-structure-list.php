<?
include_once("./dbAccess/conn.php");	
include_once("./frontendFunc.php");
include('./secure.php');

// check login
if(!is_login())
{
	header("Location: login.php");
	exit;
} 

$level = $_GET['level'];
$id = $_GET['id'];
$ProductRoot = $_GET['PRODUCT_ROOT'];


/*
echo $level.'<br>';
echo $ProductRoot.'<br>';
echo $id.'<br>';*/

if((!isset($_GET['level']))|| ($level==1))
{
    $imageHtml = printImage(1,'','');
	$imageFrameTitle = 'Full Show Of Our 3D Assets';
	
} 
else 
{
	$titleArray = explode('/',$ProductRoot);
	$imageFrameTitle = $titleArray[count($titleArray)-1];
	$imageHtml =  printImage($level,$ProductRoot,$id);

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
<link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap-extended.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/app.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/colors.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-content-menu.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-overlay-menu.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/plugins/ui/jqueryui.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<meta property="og:title" content="ob3DHost" >
<meta property="og:url" content="http://www.obamboo.com/3DHost/">
<meta property="og:image" content="http://www.obamboo.com/3DHost/3h.png">
<meta property="og:description" content="http://www.obamboo.com/3DHost/" >
</head>
<body data-open="click" data-menu="vertical-content-menu" data-col="2-columns" class="vertical-layout vertical-content-menu 2-columns  fixed-navbar">
<form action="" method="get">
  <nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-light navbar-hide-on-scroll navbar-border navbar-shadow navbar-brand-center">
    <div class="navbar-wrapper">
      <div class="navbar-header">
        <ul class="nav navbar-nav">
          <li class="nav-item mobile-menu hidden-md-up float-xs-left"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5 font-large-1"></i></a></li>
          <li class="nav-item"><a href="standard_fonray_product-structure-list.php" class="navbar-brand nav-link"><img alt="3DHost" src="app-assets/images/logo/3dhost-logo-light.png" class="brand-logo"></a></li>
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
            <li class="nav-item"><a href="standard_fonray_product-structure-list.php"><i class="icon-home3"></i><span data-i18n="nav.category.support" class="menu-title">Home</span></a> </li>
          <li class="nav-item"><a href="standard_fonray_product-structure-list.php"><i class="icon-ios-folder"></i><span data-i18n="nav.dash.main" class="menu-title">Products</span> 
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
					if($id == $temp['id'])
					{
					  $strActive = 'active';
					}			  
					else 
					{
					   $strActive = '';
					}
					echo '<li class="'.$strActive.'"><a href="standard_fonray_product-structure-list.php" class="menu-item">'.stringWrap($temp['Product_Name']).'</a>';
					$level_cnt = 1;					
					
			 		category_tree($temp['id'], $level, $level_cnt,false,$prod_id,$strActive,$id);		
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
                <li class="breadcrumb-item"><a href="standard_fonray_product-structure-list.php"><i class="icon-home3"></i> Home</a> </li>
                <li class="breadcrumb-item"><a href="standard_fonray_product-structure-list.php"><i class="icon-ios-folder"></i> Products</a> </li>
                <? 
			  	$itemTitleLink = printLinkTitle($id, '',$level);
			  	echo $itemTitleLink; 
		    ?>
              </ol>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title"><? echo $imageFrameTitle; ?></h4>
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
              <div class="row padding-top-30">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <div class="row">
                    <?
			  		echo $imageHtml;
			  ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <footer class="footer navbar-fixed-bottom footer-light">
    <p class="clearfix text-muted text-sm-center mb-0 px-2"><span class="float-md-left d-xs-block d-md-inline-block">Copyright &copy; 2017 Obamboo Inc. All rights reserved. </span><span class="float-md-right d-xs-block d-md-inline-block"><a href="http://www.obamboo.com/3DHost/contact.html" target="_blank">Contact <i class="icon-envelop gray"></i></a></span></p>
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
  <script src="app-assets/js/scripts/ui/jquery-ui/buttons-selects.min.js" type="text/javascript"></script> 
  <script src="assets/js/custom.js"></script>
</form>
</body>
</html>
