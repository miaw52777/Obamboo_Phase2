<?php

function get_3D_Select_Rule($col, $value)
{	
	$rule = "";
	if($printLog == 'T')
	{
		echo 'col='.strtoupper($col).'<br>';
		echo 'value='.$value.'<br>';
	}	
	
	if($value=='%') 
	{
		$rule = '';
	}
	else
	{
		if(strtoupper($col) == "PROD_ID")
		{
			$rule = sprintf(' and a.product_id = %s ',GetSqlValueString($value,'text'));			
		}
		else if(strtoupper($col)  == "S_ID")
		{
			$rule = sprintf(' and a.s_id = %s ',GetSqlValueString($value,'text'));			
		}			
		elseif(strtoupper($col) == 'NAME')
		{
			$rule = sprintf(' and a.name = %s ',GetSqlValueString($value,'text'));			
		}		
		elseif(strtoupper($col) == 'UNIT')
		{
			$rule = sprintf('and a.unit = %s',GetSqlValueString($value,'text'));
		}
		elseif(strtoupper($col)  == "ITEM")
		{
			$rule = sprintf(' and b.item = %s ',GetSqlValueString($value,'text'));			
		}		
	}
	if($printLog == 'T')
	{
		echo 'rule='.$rule.'<br>';
	}
	return $rule;

}
 

function get_3D_SpecItem($rule='')
{	
	include("conn.php");		
	
	if($rule == '') $rule = ' and 1=0 ';
	if($rule == '%') $rule = '';
	
	
	if($printLog=='T')
	{
		echo 'rule='.$rule.'<br>';
	}
			
	$sql = "SELECT a.product_id,a.s_id,a.name,a.unit,b.item,b.item_id,b.filepath,b.link
			FROM specifications a, specifications_item  as b
			where 1=1	
				and a.s_id = b.s_id
				".$rule."
			order by s_id
			";
			 
	if($printLog=='T')
	{
		echo $sql.'<br>';
	}
	
	$result = mysql_query($sql,$link) or die('Sql Error : '.mysql_error());

	if (!$result) 
	{ 
		die('Invalid query: ' . mysql_error());
	}
	else
	{		
		if($printLog=='T')
		{ 
			echo var_dump($result).'<br>';
			echo 'Record count : '.mysql_num_rows($result).'<br>';
			$data = mysql_fetch_assoc($result);			
		}
		return $result;
	}
}
 

function get_3D_CommonFile_Select_Rule($col, $value)
{	
	$rule = "";
	if($printLog == 'T')
	{
		echo 'col='.strtoupper($col).'<br>';
		echo 'value='.$value.'<br>';
	}	
	
	if($value=='%') 
	{
		$rule = '';
	}
	else
	{
		if(strtoupper($col) == "PROD_ID")
		{
			$rule = sprintf(' and product_id = %s ',GetSqlValueString($value,'text'));			
		}
		else if(strtoupper($col)  == "FILEPATH")
		{
			$rule = sprintf(' and filepath = %s ',GetSqlValueString($value,'text'));			
		}			
		elseif(strtoupper($col) == 'LINK')
		{
			$rule = sprintf(' and link = %s ',GetSqlValueString($value,'text'));			
		}				
	}
	if($printLog == 'T')
	{
		echo 'rule='.$rule.'<br>';
	}
	return $rule;

}

// 3d common file
function get_3D_CommonFile($rule='')
{	
	include("conn.php");		
	include_once('CommFunc.php');
	
	if($rule == '') $rule = ' and 1=0 ';
	if($rule == '%') $rule = '';
	
	
	if($printLog=='T')
	{
		echo 'rule='.$rule.'<br>';
	}
			
	$sql = "SELECT t.*					
			FROM 3d_common_file t
			where 1=1					
				".$rule."			
			";
			 
	$returnMsg = QuerySQL($sql);
	return $returnMsg;
	
}


function insert3D_CommonFile($id,$filepath,$link)
{		
	include_once('CommFunc.php');
	$sql = sprintf("INSERT INTO 3d_common_file (product_id, filepath,link) VALUES ('%s','%s','%s')"
				  ,$id,$filepath,$link);	
	
	$msg = ExecuteSQL($sql);
	return $msg;
}


function update3D_CommonFile($id, $filepath,$link)
{
	include_once('CommFunc.php');
	
	$sql = sprintf("update 3d_common_file
					  set filepath = '%s', link = '%s'
					  where 1=1
							and product_id = '%s'"
					 ,$filepath,$link,$id); 
			
	 $msg = ExecuteSQL($sql);
	 return $msg;
}

// only update filepath
function update3D_CommonFile_filepath($id, $filepath)
{
	include_once('CommFunc.php');
 	$sql = sprintf("update 3d_common_file
					  set filepath = '%s'
					  where 1=1
							and product_id = '%s'"
					 ,$filepath,$id);		
			
	 $msg = ExecuteSQL($sql);
	 return $msg;
}
// only update link
function update3D_CommonFile_link($id, $link)
{
	include_once('CommFunc.php');
 	$sql = sprintf("update 3d_common_file
					  set link = '%s'
					  where 1=1
							and product_id = '%s'"
					 ,$link,$id);
			
	 $msg = ExecuteSQL($sql);
	 return $msg;
	
}


function get_3D_SpecDetail_Select_Rule($col, $value)
{	
	$rule = "";
	if($printLog == 'T')
	{
		echo 'col='.strtoupper($col).'<br>';
		echo 'value='.$value.'<br>';
	}	
	
	if($value=='%') 
	{
		$rule = '';
	}
	else
	{
		if(strtoupper($col) == "PROD_ID")
		{
			$rule = sprintf(' and product_id = %s ',GetSqlValueString($value,'text'));			
		}
		else if(strtoupper($col)  == "SPECKEY")
		{
			$rule = sprintf(' and spec_key = %s ',GetSqlValueString($value,'text'));			
		}	
		else if(strtoupper($col)  == "ITEMKEY")
		{
			$rule = sprintf(' and item_key = %s ',GetSqlValueString($value,'text'));			
		}
		else if(strtoupper($col)  == "SPECKEY_LIKE")
		{
			$rule = (" and spec_key like '%".$value."%'");						
		}	
		else if(strtoupper($col)  == "ITEMKEY_LIKE")
		{
			$rule = (" and item_key like '%".$value."%'");			
		}		
		elseif(strtoupper($col) == 'FILEPATH')
		{
			$rule = sprintf(' and filepath = %s ',GetSqlValueString($value,'text'));			
		}		
		elseif(strtoupper($col) == 'LINK')
		{
			$rule = sprintf('and link = %s',GetSqlValueString($value,'text'));
		} 
		elseif(strtoupper($col) == 'MODEL')
		{
			if($value == "NOT_NULL")
			{
				$rule = sprintf("and model <> '' ");
			}
			else
			{
				$rule = sprintf('and model = %s',GetSqlValueString($value,'text'));
			}
		} 		
	}
	if($printLog == 'T')
	{
		echo 'rule='.$rule.'<br>';
	}
	return $rule;

}
 
// 3D spec detail information
function get_3D_SpecDetail($rule='')
{	
	include("conn.php");		
	
	if($rule == '') $rule = ' and 1=0 ';
	if($rule == '%') $rule = '';
	
	
	if($printLog=='T')
	{
		echo 'rule='.$rule.'<br>';
	}
			
	$sql = "
			select t.*
			FROM(SELECT a.*
					 ,(LENGTH(a.spec_key)- LENGTH( REPLACE ( a.spec_key, '_', '') ) )+1 AS spec_cnt	            
					 ,(SELECT count(1) FROM specifications z WHERE 1=1 and z.product_id = a.product_id) s_spec_cnt            
				 FROM 3d_spec_detail a
				 where 1=1
						".$rule."
				 )t
			where 1=1
				and t.spec_cnt=t.s_spec_cnt
					
									
			";
		 
	return QuerySQL($sql);
}
 
function insert3D_SpecDetail($id,$speckey,$itemkey,$filepath,$link,$model)
{		
	include_once('CommFunc.php');
	$sql = sprintf("INSERT INTO 3d_spec_detail (product_id,spec_key,item_key, filepath,link,model) VALUES ('%s','%s','%s','%s','%s','%s')"
				  ,$id,$speckey,$itemkey,$filepath,$link,$model);	
 
	$msg = ExecuteSQL($sql);
	return $msg;
}


 // only update filepath
function update3D_SpecDetail_filepath($speckey,$itemkey, $filepath)
{
	include_once('CommFunc.php');
 	$sql = sprintf("update 3d_spec_detail
					  set filepath = '%s'
					  where 1=1
							and spec_key = '%s'
							and item_key = '%s'"
					 ,$filepath,$speckey,$itemkey);		
			
	 $msg = ExecuteSQL($sql);
	 return $msg;
}
// only update link
function update3D_SpecDetail_link($speckey,$itemkey, $link)
{
	include_once('CommFunc.php');
 	$sql = sprintf("update 3d_spec_detail
					  set link = '%s'
					  where 1=1
							and spec_key = '%s'
							and item_key = '%s'"
					 ,$link,$speckey,$itemkey);		
			
	 $msg = ExecuteSQL($sql);
	 return $msg;
}

 // only update model
function update3D_SpecDetail_model($speckey,$itemkey,$model)
{
	include_once('CommFunc.php');
 	$sql = sprintf("update 3d_spec_detail
					  set model = '%s'
					  where 1=1
							and spec_key = '%s'
							and item_key = '%s'"
					 ,$model,$speckey,$itemkey);		
			
	 $msg = ExecuteSQL($sql);
	 return $msg;
}

function sendMailWhenfilechange($prod_cat_id,$prod_id,$spec_name,$mailFilepath)
{
	include_once("../dbAccess/conn.php");		
	include_once("../dbAccess/CommFunc.php");	
	include_once("../dbAccess/MailFunc.php");
	include_once("../dbAccess/ProductCategoryFunc.php");
	include_once("../dbAccess/ProductFunc.php");
	
	//get product category info 
	$rule = get_Products_Category_Select_Rule("ID",$prod_cat_id);
	$queryProductCat = get_Products_Category_List($rule);
	$product_root = getSQLResultInfo($queryProductCat,'Product_Root');

	// get product info
	$rule = get_Products_Select_Rule('ID',$prod_id);						
	$queryProduct = get_Products_List($rule);
	$product_name = getSQLResultInfo($queryProduct,'name');


	/* define mail information */ 
	$mailtoName = "Anita";
	$mailto = "anita_test_one@obamboo.com";
	$mailcc = "obtrader@gmail.com";
	$mailSubject = "ob3DHost - 上傳檔案通知";
	$mailContent = "產品名稱 : ".$product_name."\n";
	$mailContent .= "產品階層 : ".$product_root."\n";
	$mailContent .= "產品規格 : ".$spec_name."\n";		
	return sendMail($mailtoName,$mailto,$mailSubject,$mailContent,$mailFilepath,$mailcc);

} 


function getProgress($filepath,$link)
{
	if(($filepath == "") && ($link == ""))
	{
		$progress = 0;
	}
	else if(($filepath != "") && ($link != ""))
	{
		$progress = 100;
	}
	else
	{
		$progress = 50;
	}
	return $progress;	
}
?>

