<?
include_once("./dbAccess/conn.php");	
include_once("./dbAccess/CommFunc.php");
include_once("./dbAccess/ProductCategoryFunc.php");
include_once("./dbAccess/ProductFunc.php");
include_once("./dbAccess/ProductSpecFunc.php");	
include_once("./dbAccess/ProductSpecItemFunc.php");	


//印出連結列 
//ex. Products / Filter Regulator Lubricator ( FRL ) / Filter Regulator Lubricator
function printLinkTitle($pid, $strresult,$plevel, $final_id='')
{		  
	include("./dbAccess/conn.php");	
	
	// print all	
	
	if($plevel-1 == 0)
	{ 		
		if($final_id != '')
		{
			// product link
			$rule = get_Products_Select_Rule('ID',$final_id);	
			$rule = $rule.' '.get_Products_Select_Rule('VALID','T');	
			$queryProdResult = get_Products_List($rule);
			
			$product_name = getSQLResultInfo($queryProdResult,'name');
			$product_cat_id = getSQLResultInfo($queryProdResult,'product_cat_id');
			
			$url_tmp = '<li class="breadcrumb-item"><a href="standard_product-structure-display.php?level=final&id=:id&prod_id=:prod_id"</a>:ProductName</li>';		
			$sourceStr = array(":ProductName", ':id',':prod_id');
			$replaceStr = array($product_name, $product_cat_id,$final_id);
			$strresult .= str_replace($sourceStr, $replaceStr,$url_tmp);	
			
		}
		 
		echo $strresult;
		return;
	}
	 
		 
	$rule = get_Products_Category_Select_Rule('ID',$pid);	
	$rule = $rule.' '.get_Products_Category_Select_Rule('VALID','T');	
	$result = get_Products_Category_List($rule);
		
	
	while($row = mysql_fetch_assoc($result))
	{	 
	
		 $url_tmp = '<li class="breadcrumb-item"><a href=" standard_product-structure-list.php?level=:LEVEL&id=:id&PRODUCT_ROOT=:Product_Root"</a>:ProductName</li>';		
		 $sourceStr = array(":ProductName", ':id',':LEVEL',':Product_Root');
		 $replaceStr = array($row['Product_Name'], $row['id'],$plevel,$row['Product_Root']);
		 $strresult = str_replace($sourceStr, $replaceStr,$url_tmp).$strresult;	

		 printLinkTitle($row['parent_id'], $strresult,$plevel-1, $final_id);
	    
	}	
}
// 印出左邊選單樹狀連結 (Menu)
function category_tree($catid, $now_level, $level_cnt,$is_final=false,$prod_id='',$pstrActive='',$id='')
{
	include("./dbAccess/conn.php");	
	
	$rule = get_Products_Category_Select_Rule('PARENT_ID',$catid);	
	$result = get_Products_Category_List($rule);		
	

	while($row = mysql_fetch_assoc($result)) // has product category
	{	
		$i = 0;
		if ($i == 0) echo '<ul class="menu-content">';
		 
		$lastRoot = str_replace('/'.$row['Product_Name'],'',$row['Product_Root']);
		$url_tmp = 'standard_product-structure-list.php?level=:LEVEL&id=:id&PRODUCT_ROOT=:Product_Root';		
		$sourceStr = array(':id',':LEVEL',':Product_Root');
		$replaceStr = array($catid,$row['Level'],$lastRoot);
		$url = str_replace($sourceStr, $replaceStr,$url_tmp);			
				
		 if($level_cnt == 1)
		 {
			 echo '<li class="'.$pstrActive.'"><a href="'.$url.'" class="menu-item">'.stringWrap($row['Product_Name']).'</a>';						 
		 }		 
		 ELSE if(($level_cnt <= $now_level-1) && ($now_level > 1))
	     {			 
			echo '<li class="active"><a href="'.$url.'" class="menu-item">'.stringWrap($row['Product_Name']).'</a>';						 
		 }
		 else
		 {
			echo '<li class=""><a href="'.$url.'" class="menu-item">'.stringWrap($row['Product_Name']).'</a>';
		 }	
		
		 category_tree($row['id'],$now_level, $level_cnt+1,$is_final,$prod_id,$pstrActive,$id);			
		  
		 echo '</li>';
		 $i++;
		 if ($i > 0)
		 {	 
			echo '</ul>';
		 }	 			 
	}
	
	$rule = get_Products_Select_Rule('PROD_CAT_ID',$catid);
	$rule .= get_Products_Select_Rule('VALID','T');
	$queryProdresult = get_Products_List($rule);		 
	 
	// display Product
	// echo mysql_num_rows($queryProdresult);
	 if(mysql_num_rows($queryProdresult) > 0)
	 {					 
		 echo '<ul class="menu-content">'; 
		 
		 while($rowProd = mysql_fetch_assoc($queryProdresult))
		 {	
			//echo $rowProd['product_cat_id'].'=>'.$catid;
			if(($is_final) && ($rowProd['product_id'] == $prod_id))
			{					
				$strActive = 'active';					
			} 
			else if($rowProd['product_cat_id'] == $id)
			{
				$strActive = 'active';					
			}
			else $strActive = '';
						 
			$url_tmp = '<li class="'.$strActive.'"><a href="standard_product-structure-display.php?level=final&id=:id&prod_id=:prod_id" class="menu-item">'.stringWrap($rowProd['name']).'</a></li>';			  
			$sourceStr = array(':id',':prod_id');
			$replaceStr = array($rowProd['product_cat_id'],$rowProd['product_id']);
			$url = str_replace($sourceStr, $replaceStr,$url_tmp);												
			echo $url;
		 }
		 echo '</ul>';
	 }
	 
}
// 印出圖片連結
function printImage($plevel,$pProductRoot,$pid)
{		  
	include("./dbAccess/conn.php");	
	// print all		
	if($plevel==1)
	{		
		// get root image file		
		$rule = get_Products_Category_Select_Rule('PARENT_ID','NULL');	
		$rule = $rule.' '.get_Products_Category_Select_Rule('VALID','T');	
		$queryResult = get_Products_Category_List($rule);
	}
	else
	{				
		$rule = get_Products_Category_Select_Rule('PARENT_ID',$pid);	
		$rule = $rule.' '.get_Products_Category_Select_Rule('VALID','T');	
		
		$rule_out = get_Products_Category_Select_Rule('LEVEL',$plevel);	
		$rule_out = $rule_out.get_Products_Category_Select_Rule('PRODUCT_ROOT',$pProductRoot);	
		
		$queryResult = get_Products_Category_List($rule,$rule_out);
		
		//echo $rule.'<br>';
		//echo $rule_out;
	}
	$pimageHtml='';	
	
	while($temp = mysql_fetch_assoc($queryResult))
	{	
		// print image html		 
		$templateImg = '
					  <div class="col-xl-3 col-lg-4 col-md-12">
						<div class="card">
						  <div class="card-body">
							<div class="carousel-inner"> <a href="standard_product-structure-list.php?level=:LEVEL&id=:id&PRODUCT_ROOT=:Product_Root"><img src=":Filepath" alt="" class="img-responsive"></a> </div>
							<div class="card-block">
							  <h5 class="card-title txtheight"><a href="standard_product-structure-list.php?level=:LEVEL&id=:id&PRODUCT_ROOT=:Product_Root">:ProductName</a></h5>
							</div>
						  </div>
						</div>
					  </div>';
		
		$sourceStr = array(":ProductName", ":Filepath",':id',':LEVEL',':Product_Root');
		$replaceStr = array($temp['Product_Name'], './'.$UploadfileRoot.$temp['filepath'],$temp['id'],$plevel+1,$temp['Product_Root']);
		$pimageHtml = $pimageHtml.str_replace($sourceStr, $replaceStr,$templateImg);				 
		
		
	}
	
	// check 是否有產品		 
	$rule = get_Products_Select_Rule('PROD_CAT_ID',$pid);	
	$rule = $rule.' '.get_Products_Select_Rule('VALID','T');	
	$queryProdResult = get_Products_List($rule);
	 
	while($tempProd = mysql_fetch_assoc($queryProdResult))
	{
		$templateImg = '
					  <div class="col-xl-3 col-lg-4 col-md-12">
						<div class="card">
						  <div class="card-body">
							<div class="carousel-inner"> <a href="standard_product-structure-display.php?level=final&id=:id&prod_id=:prod_id"><img src=":Filepath" alt="" class="img-responsive"></a> </div>
							<div class="card-block">
							  <h5 class="card-title txtheight"><a href="standard_product-structure-display.php?level=final&id=:id&prod_id=:prod_id">:ProductName</a></h5>
							</div>
						  </div>
						</div>
					  </div>';
		$sourceStr = array(":ProductName", ':id',':prod_id', ":Filepath");
		$replaceStr = array($tempProd['name'], $tempProd['product_cat_id'],$tempProd['product_id'],'./'.$UploadfileRoot.'products/'.$tempProd['filepath_1']);
		$pimageHtml = $pimageHtml.str_replace($sourceStr, $replaceStr,$templateImg);
	}		
	
	return $pimageHtml;
}

// 印出產品規格
function printSpecification($prod_id, $spec_item_arr='')
{	
	$rule = get_ProductSpec_Select_Rule('ID',$prod_id);	
	$result = get_ProductSpec_List($rule);
	
	// loop for spec list		
	$selectHtmlStr = "";		
	$speckey = "";		
	$totalCntSpec= mysql_num_rows($result);	
	while($temp = mysql_fetch_assoc($result))
	{	
		$selectHtmlStr .= '<tr>'."\r\n";
		$selectHtmlStr .= '<th scope="row">'.$temp['name'].'</th>'."\r\n";	
			
		$rule = get_ProductSpecItem_Select_Rule('S_ID',$temp['s_id']);	
		$Itemresult = get_ProductSpecItem_List($rule);
		// loop for spec items						
		$speckey .= $temp['s_id'];				
		$totalCntSpec--;				
		if($totalCntSpec > 0)					
		{						
			$speckey .= "_";					
		}		
		$selectHtmlStr .= '<td><select class="select2-theme form-control" name="spec_item_sel_'.$temp['s_id'].'"'.' id="spec_item_sel_'.$temp['s_id'].'" onchange="spec_item_change(\':SPECKEY\')">'."\r\n";
		$j=0;
		$resultSpec_item = array(); 
		while($itemrow = mysql_fetch_assoc($Itemresult))
		{	
			 
			if(($spec_item_arr =='') && ($i == 0))
			{
				$is_select = 'selected';
				$resultSpec_item[$j]['S_ID'] = $temp['s_id'];
				$resultSpec_item[$j]['ITEM_ID'] = $itemrow['item_id'];
				$resultSpec_item[$j]['ITEM'] = $itemrow['item'];
				$j++;
				
			}
			else if($spec_item_arr != '')
			{
				for ($i=0;$i<count($spec_item_arr);$i++)
				{					
					if($itemrow['item'] == $spec_item_arr[$i]['ITEM'])
					{
						$is_select = 'selected';
						$j++;
						break;
					}
					else $is_select = '';					
				}
			}
			else 
			{
				$is_select = '';
			}
			$selectHtmlStr .= '<option id="'.$itemrow['item_id'].'" value="'.$itemrow['item'].'" '.$is_select.'>'.$itemrow['item'].'</option>'."\r\n";			
			
			$i++;
		}
		$selectHtmlStr .= '</select></td>'."\r\n";
		$selectHtmlStr .= '<td>'.$temp['unit'].'</td>'."\r\n";
		$selectHtmlStr .= '</tr>';
	}		$selectHtmlStr = str_replace(":SPECKEY", $speckey,$selectHtmlStr);		echo $selectHtmlStr;
	return $resultSpec_item;
}
function get_3D_Previewlink($prod_id,$spec_item_arr, $is_default=false)
{
	$link_3d_preview = '';
	/* get options */
	/* get link by spec item matrix */
	$rule = get_3D_SpecDetail_Select_Rule('PROD_ID',$prod_id);												
	$returnMsg = get_3D_SpecDetail($rule);	
	
	if($returnMsg['REC_CNT'] > 0) /* 有上傳檔案 */
	{ 	
		$rule = get_3D_SpecDetail_Select_Rule('PROD_ID',$prod_id);																						
		
		for ($i=0;$i<count($spec_item_arr);$i++)
		{			
	
			$rule .= get_3D_SpecDetail_Select_Rule('SPECKEY_LIKE',$spec_item_arr[$i]['S_ID']);											
			$rule .= get_3D_SpecDetail_Select_Rule('ITEMKEY_LIKE',$spec_item_arr[$i]['ITEM_ID']);		
			 		
		}
	  
		$returnMsg = get_3D_SpecDetail($rule);				
		$link_3d_preview = getSQLResultInfo($returnMsg['DATA'],'link');	
		$spec_key = getSQLResultInfo($returnMsg['DATA'],'spec_key');	
		$item_key = getSQLResultInfo($returnMsg['DATA'],'item_key');	
		 
	}
	if($link_3d_preview == '') //如果 3D 沒有上傳連結用通用檔案
	{ 
		$rule = get_3D_CommonFile_Select_Rule('PROD_ID',$prod_id);												
		$returnMsg = get_3D_CommonFile($rule);		
		
		/* get link by common file */
		if($returnMsg['REC_CNT'] > 0)
		{
			$link_3d_preview = getSQLResultInfo($returnMsg['DATA'],'link');				
		}
		$spec_key = 'COMMON';
		$item_key = 'COMMON';
	}
	$returnResult = array();
	$returnResult['3d_preview_link'] = $link_3d_preview;
	$returnResult['spec_key'] = $spec_key;
	$returnResult['item_key'] = $item_key;
	
	return $returnResult;
}
?>


                        

