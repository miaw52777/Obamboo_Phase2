<?php
include_once('conn.php');
include_once('CommFunc.php');

function get_Products_Category_Select_Rule($col, $value)
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
		if(strtoupper($col)  == "ID")
		{
			$rule = sprintf(' and id = %s ',GetSqlValueString($value,'text'));			
		}
		elseif(strtoupper($col) == 'NAME')
		{
			$rule = sprintf(' and name = %s ',GetSqlValueString($value,'text'));			
		}
		elseif(strtoupper($col) == 'PARENT_ID')
		{
			if(strtoupper($value) == 'NULL')
			{
				$rule = ' and parent_id is null';
			}
			else
			{
				$rule = sprintf('and parent_id = %s',GetSqlValueString($value,'text'));
			}		
		}
		elseif(strtoupper($col) == 'VALID')
		{
			$rule = sprintf('and valid = %s',GetSqlValueString($value,'text'));
		}
		elseif(strtoupper($col)  == "LEVEL")
		{
			$rule = sprintf(' and Level = %s ',GetSqlValueString($value,'text'));			
		}
		elseif(strtoupper($col)  == "PRODUCT_ROOT")
		{
			$rule = " and Product_Root like '".$value."%' ";			
		}
	}
	if($printLog == 'T')
	{
		echo 'rule='.$rule.'<br>';
	}
	return $rule;

}

function get_Products_Category_List($rule='',$rule_out='')
{		
	
	if($rule == '') $rule = ' and 1=0 ';
	if($rule == '%') $rule = '';
	
	
	if($printLog=='T')
	{
		echo 'rule='.$rule.'<br>';
	}
	
		
	$sql = "select t.* 
	        from
			(select t.*
				,ROUND 
				(   
					(
						LENGTH(t.Product_Root)
						- LENGTH( REPLACE ( t.Product_Root, '/', '') ) 
					) / LENGTH('/')        
				)+1 AS Level				
			 from(SELECT id,name Product_Name,valid VALID, getpath(id)  AS Product_Root,COALESCE(parent_id,0) parent_id,seq,filepath
			 		 ,(select count(1) from products z where 1=1 and z.product_cat_id = t.id) next_level
				  FROM products_category t
				  where 1=1 
				  		 ".$rule."
				  )t
			)t	  
			  where 1=1
				".$rule_out."
				order by seq	
			";
			
			 //$printLog='T';
	if($printLog=='T')
	{
		echo $sql.'<br>';
	}
	//送出查詢並取得結果
	$result = mysql_query($sql) or die('Sql Error : '.mysql_error());

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
			echo $data['id'];
		}
		return $result;
	}
}
 

function insertProductCategory($id, $ProductName,$root,$valid,$seq,$filepath='')
{
	include_once("CommFunc.php");
	// get parent id
	if($root==$ProductName)
	{
		//自己是 root
		$parent_id = 'NULL';
	}
	elseif($root=="")
	{
		//自己是 root
		$parent_id = 'NULL';
	}
	else
	{
		$rule = get_Products_Category_Select_Rule('NAME',$root);
		$result = get_Products_Category_List($rule);
		$parent_id = GetSqlValueString(getSQLResultInfo($result,'id'),'text');
	}
	
	if($filepath != '')
	{
		$sql = sprintf("INSERT INTO products_category (name,parent_id,valid,seq,filepath) VALUES ('%s',%s,'%s','%s','%s')"
						 ,$ProductName,$parent_id,$valid,$seq,$filepath);
	}
	else
	{
		$sql = sprintf("INSERT INTO products_category (name,parent_id,valid,seq) VALUES ('%s',%s,'%s','%s')"
						 ,$ProductName,$parent_id,$valid,$seq);
		
	}
	
	$msg = ExecuteSQL($sql);
	return $msg;
 
}

function getParentIDByName($root)
{
	include_once("CommFunc.php");
	$rule = get_Products_Category_Select_Rule('NAME',$root);
	$result = get_Products_Category_List($rule);
	$parent_id = getSQLResultInfo($result,'id');
	return $parent_id;
}


function updateProductCategory($id,$ProductName,$root,$valid,$seq)
{
	include_once("CommFunc.php");
	$rule = get_Products_Category_Select_Rule('ID',$id);
	$checkData = get_Products_Category_List($rule);
	if(mysql_num_rows($checkData) == 0) return 'Update error : No this record.';
	
	
	// get parent id
	if($root==$ProductName)
	{
		//自己是 root
		$parent_id = 'NULL';
	}
	else
	{
		$rule = get_Products_Category_Select_Rule('NAME',$root);
		$result = get_Products_Category_List($rule);
		$parent_id = GetSqlValueString(getSQLResultInfo($result,'id'),'text');
	}	

 
	$sql = sprintf("update products_category
						  set name = '%s', parent_id = %s, valid = '%s', seq = '%s'
						  where 1=1
								and id = %s"
						 ,$ProductName,$parent_id,$valid,$seq,$id);					
			
	 $msg = ExecuteSQL($sql);
 	 return $msg;

}


function updateProductCategoryFilepath($ProductName,$filepath)
{
	include_once("CommFunc.php");
	$sql = sprintf("update products_category
						  set filepath = '%s'
						  where 1=1
								and name = '%s'"
						 ,$filepath,$ProductName);
			
	 $msg = ExecuteSQL($sql);
 	 return $msg;
}




function deleteProductCategory($id)
{
	include_once("conn.php");
	include_once("CommFunc.php");
	/*
	// search parent root 是否存在
	$rule = get_Products_Category_Select_Rule('ID',$id);
	$reulst = get_Products_Category_List($rule);
	//先把有用到的 update 成空降階
	if(mysql_num_rows($reulst)>0)
	{
		$updateSQL = ''
	}
	*/
	
	$sql = sprintf("delete from products_category where 1=1 and id = '%s'",$id);			
    
	$msg = ExecuteSQL($sql);
	return $msg;
}


// get the root count for product catgory
function getLevel1CategoryCount($selectResult)
{	
	$convertArray = convertToAssocArray($selectResult);	
	$filter_array = array("parent_id" => "0","VALID"=>"T");

	$compare_result = CompareArrayByFilter($convertArray,$filter_array);
	return count($compare_result);
}


function product_category_tree($catid, $selectedLastRoot)
{ 
	$rule = get_Products_Category_Select_Rule('PARENT_ID',$catid);	
	$result = get_Products_Category_List($rule);		 
	echo $selectedLastRoot;
	
	// has child node
	while($temp = mysql_fetch_assoc($result))
	{	
		if($temp['Product_Name'] == $selectedLastRoot)
		{
			$isSelected = 'selected';			
		}
		else
		{
			$isSelected = '';
		}
		  
		
		$rootHeader = '';		
	    $loop_cnt = $temp['Level'];		
		if($temp['Level']>1) $rootHeader = str_repeat('-', $loop_cnt);	
		
		$tmplateForlist = '<option name="root" value=":OriProductRoot" :SELECTED>:HeaderProductRoot</option>';
		
		$sourceStr = array(":OriProductRoot",":HeaderProductRoot", ":SELECTED");
		$replaceStr = array($temp['Product_Name'],$rootHeader.' '.$temp['Product_Name'],$isSelected);
		echo str_replace($sourceStr, $replaceStr,$tmplateForlist);
		product_category_tree($temp['id'],$selectedLastRoot);				
	 
	}
}

function print_category_optionlist($ProductRoot,$ProductLevel,$isSelectRoot=false)
{
		
		$selectRootArray = explode('/',$ProductRoot);
		$selectedLastRoot = $selectRootArray[$ProductLevel-2];
		$selectedRoot = $selectRootArray[$ProductLevel-1];
		
		// get all parent 
		$rule = get_Products_Category_Select_Rule('PARENT_ID','NULL');
		$queryResult = get_Products_Category_List($rule);		
			
		while($temp = mysql_fetch_assoc($queryResult))
		{	
			if($isSelectRoot)
			{
			
				if( ($temp['Product_Name'] == $selectedRoot)  )
				{
					$isSelected = 'selected';
				}	
				else
				{
					$isSelected = '';
				}
				
				$selectedLastRoot = $selectedRoot;
			}
			else
			{
				if($temp['Product_Name'] == $selectedLastRoot)
				{
					$isSelected = 'selected';
				}			 
				else
				{
					$isSelected = '';
				}
			}
			//print level 1
			$tmplateForlist = '<option name="root" value=":OriProductRoot" :SELECTED>:HeaderProductRoot</option>';
					
			$sourceStr = array(":OriProductRoot",":HeaderProductRoot", ":SELECTED");
			$replaceStr = array($temp['Product_Name'],$temp['Product_Name'],$isSelected);
			echo str_replace($sourceStr, $replaceStr,$tmplateForlist);
			product_category_tree($temp['id'],$selectedLastRoot);
			
		}
}
?>

