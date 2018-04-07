<?php
@session_start();
function getRequestIP() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

$o = rand(1, 9);
$_SESSION[getRequestIP().'_'.$o] = uniqid();
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
</head>
<body data-open="click" data-menu="vertical-content-menu" data-col="2-columns" class="vertical-layout vertical-content-menu 2-columns  fixed-navbar">
<nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-light navbar-hide-on-scroll navbar-border navbar-shadow navbar-brand-center">
  <div class="navbar-wrapper">
    <div class="navbar-header">
      <ul class="nav navbar-nav">
        <li class="nav-item mobile-menu hidden-md-up float-xs-left"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5 font-large-1"></i></a></li>
        <li class="nav-item"><a href="index.html" class="navbar-brand nav-link"><img alt="3DHost" src="app-assets/images/logo/3dhost-logo-light.png" data-expand="app-assets/images/logo/3dhost-logo-light.png" data-collapse="app-assets/images/logo/3dhost-logo-light.png" class="brand-logo"></a></li>
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
          <li class="nav-item"><a href="standard_fonray_product-structure-list.php"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Products</span> 
            <!--<span class="tag tag tag-primary tag-pill float-xs-right mr-2">5</span>--> 
            </a>
            <ul class="menu-content">
              <li><a href="standard_fonray_product-structure-list-2.php" class="menu-item">Filter Regulator<br>
                Lubricator (FRL)</a>
                <ul class="menu-content">
                  <li><a href="standard_fonray_product-structure-list-3.php" class="menu-item">Filter Regulator<br>
                    Lubricator</a>
                    <ul class="menu-content">
                      <li class="active"><a href="standard_fonray_product-structure-display.php" class="menu-item">FAC</a></li>
                      <li><a href="" class="menu-item">AC</a></li>
                      <li><a href="" class="menu-item">FRL</a></li>
                      <li><a href="" class="menu-item">EC</a></li>
                      <li><a href="" class="menu-item">UFRLH</a></li>
                    </ul>
                  </li>
                  <li><a href="" class="menu-item">Regulator</a></li>
                  <li><a href="" class="menu-item">Pressure Regulator</a></li>
                  <li><a href="" class="menu-item">Filter</a></li>
                  <li><a href="" class="menu-item">Lubricator</a></li>
                  <li><a href="" class="menu-item">MADV Auto Drain</a></li>
                  <li><a href="" class="menu-item">Exact Regulator</a></li>
                  <li><a href="" class="menu-item">Accurate Filter</a></li>
                  <li><a href="" class="menu-item">High Pressure <br>
                    Filter Regulator</a></li>
                  <li><a href="" class="menu-item">High Pressure Filter</a></li>
                </ul>
              </li>
              <li><a href="" class="menu-item">Solenoid Valves</a></li>
              <li><a href="" class="menu-item">2 Way, 2 Position<br>
                Solenoid Valves</a></li>
              <li><a href="" class="menu-item">Manual Valves,<br>
                Hand Pull Valves</a></li>
              <li><a href="" class="menu-item">Foot Valves</a></li>
              <li><a href="" class="menu-item">Mechanical Valves</a></li>
              <li><a href="" class="menu-item">Standard Cylinders</a></li>
              <li><a href="" class="menu-item">Pneumatic Fittings</a></li>
              <!--<li><a href="" class="menu-item">銅接頭/ 鐵接頭/其他接頭</a></li>-->
              <li><a href="" class="menu-item">APP Punch</a></li>
              <li><a href="" class="menu-item">Fluctuation Flat</a></li>
              <li><a href="" class="menu-item">Other Parts</a></li>
            </ul>
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
              <li class="breadcrumb-item"><a href="standard_fonray_product-structure-list.php"><i class="icon-home3"></i> Products</a> </li>
              <li class="breadcrumb-item"><a href="standard_fonray_product-structure-list-2.php">Filter Regulator Lubricator (FRL)</a> </li>
              <li class="breadcrumb-item"><a href="standard_fonray_product-structure-list-3.php">Filter Regulator Lubricator</a> </li>
              <li class="breadcrumb-item active"><a href="standard_fonray_product-structure-display.php">FAC</a> </li>
            </ol>
          </div>
        </div>
      </div>
      <div id="image-gallery" class="card">
        <div class="card-header">
          <h4 class="card-title">FAC Products gallery</h4>
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
            <div class="row padding-lr-20">
              <figure class="col-lg-3 col-md-6 col-xs-12"> <img class="img-thumbnail img-fluid" src="demo/fonray/filter-regulator-lubricator-FAC001.jpg" alt="" /> </figure>
              <figure class="col-lg-3 col-md-6 col-xs-12"> <img class="img-thumbnail img-fluid" src="demo/fonray/filter-regulator-lubricator-FAC002.jpg" alt="" /> </figure>
              <figure class="col-lg-3 col-md-6 col-xs-12"> <img class="img-thumbnail img-fluid" src="demo/fonray/filter-regulator-lubricator-FAC003.jpg" alt="" /> </figure>
              <figure class="col-lg-3 col-md-6 col-xs-12"> <img class="img-thumbnail img-fluid" src="demo/fonray/filter-regulator-lubricator-FAC001.jpg" alt="" /> </figure>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title" id="from-actions-top-left">FAC Specification</h4>
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
                <div class="alert bg-success alert-icon-left alert-dismissible fade in mb-2" role="alert"> <strong>Experience it!</strong>
                  <p>Available products: 8</p>
                </div>
                <div class="card-text">
                  <div class="table-responsive">
                    <table class="table mb-0">
                      <thead class="bg-teal bg-lighten-4">
                        <tr>
                          <th>MODEL</th>
                          <th>FAC</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th scope="row">Bore</th>
                          <td><select class="select2-theme form-control" id="select2-theme">
                              <option value="32" selected="selected">32</option>
                              <option value="40">40</option>
                              <option value="50">50</option>
                              <option value="63">63</option>
                              <option value="80">80</option>
                              <option value="100">100</option>
                              <option value="125">125</option>
                              <option value="160">160</option>
                              <option value="200">200</option>
                            </select></td>
                          <td>mm</td>
                        </tr>
                        <tr>
                          <th scope="row">Motion</th>
                          <td><select class="select2-theme form-control" id="select2-theme">
                              <option value="Double Acting" selected="selected">Double Acting</option>
                            </select></td>
                          <td></td>
                        </tr>
                        <tr>
                          <th scope="row">Mounting Type</th>
                          <td><select class="select2-theme form-control" id="select2-theme">
                              <option value="Standard Type" selected="selected">Standard Type </option>
                              <option value="Front Flange">Front Flange </option>
                              <option value="Rear Flange">Rear Flange </option>
                              <option value="Foot Mounting">Foot Mounting </option>
                              <option value="Pivot Type">Pivot Type </option>
                              <option value="Clevis Mounting">Clevis Mounting </option>
                              <option value="Trunnion">Trunnion </option>
                            </select></td>
                          <td></td>
                        </tr>
                        <tr>
                          <th scope="row">Operating Speed</th>
                          <td><select class="select2-theme form-control" id="select2-theme">
                              <option value="50~500" selected="selected">50~500</option>
                            </select></td>
                          <td>mm / s</td>
                        </tr>
                        <tr>
                          <th scope="row">Working-pressure Range</th>
                          <td><select class="select2-theme form-control" id="select2-theme">
                              <option value="0.15~0.9" selected="selected">0.15~0.9</option>
                            </select></td>
                          <td></td>
                        </tr>
                        <tr>
                          <th scope="row"></th>
                          <td><button class="ui-button ui-widget ui-corner-all btn-success"><span class="ui-icon icon-archive3"></span> Send</button> <button class="ui-button ui-widget ui-corner-all"><span class="ui-icon icon-trash4"></span> Clear</button></td>
                          <td></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title" id="from-actions-bottom-right">Display generation</h4>
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
                <div class="card-text padding-lr-20 padding-bottom-20 padding-top-20">
                  <h4 class="form-section"><i class="icon-clipboard4"></i> Common files</h4>
                  <button class="ui-button ui-widget ui-corner-all btn-success"><span class="ui-icon icon-eye3"></span> CAD download</button>
                  <button class="ui-button ui-widget ui-corner-all btn-success"><span class="ui-icon icon-eye3"></span> CAD by email</button>
                  <button class="ui-button ui-widget ui-corner-all btn-success"><span class="ui-icon icon-eye3"></span> PDF</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">Previews - 3D</h4>
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
              <div class="video-container">
                <iframe id='3dviewerplayer' type='text/html' width="800" height="600" src='http://www.obamboo.com/3DHost/' frameborder='0' scrolling='no' allowfullscreen webkitallowfullscreen mozallowfullscreen>
                <p>Your browser does not support iframes.</p>
                </iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title" id="from-actions-bottom-right">Filter Regulator Lubricator - FAC Description</h4>
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
                  <p><img src="demo/fonray/description.jpg" alt="" class="img-responsive"></p>
                  <p class="padding-lr-20">FAC is one of Fonray's FRL series products includes 5 model with different port size, max. flow rate, filtration, working-pressure range. The size of FAC's and SMC's series are the same, even the appearance, but the adjuster of lubricator is more convenient than SMC's. For more product information please click on the photo below. </p></div>
                <p class="padding-top-30">
                  <button class="ui-button ui-widget ui-corner-all btn-danger" onclick="javascript:history.go(-1)"><span class="ui-icon icon-eye3"></span> Back </button>
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
  <p class="clearfix text-muted text-sm-center mb-0 px-2"><span class="float-md-left d-xs-block d-md-inline-block">Copyright &copy; 2017 Obamboo Inc., All rights reserved. </span><span class="float-md-right d-xs-block d-md-inline-block"><a href="http://www.obamboo.com/3DHost/contact.html" target="_blank">Contact us</a> <i class="icon-envelop gray"></i></span></p>
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
<!-- BEGIN PAGE VENDOR JS--> 

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
		$.post('url-org.php', {s:"<?php echo $_SESSION[getRequestIP().'_'.$o]; ?>",o:"<?php echo $o; ?>"}, function(data) {
			document.getElementById('3dviewerplayer').src = data;
		});
	});
</script>
</body>
</html>
