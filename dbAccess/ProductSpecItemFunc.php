<?php

// 取得 Product 查詢條件
function get_ProductSpecItem_Select_Rule($col, $value)
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
		if(strtoupper($col)  == "S_ID")
		{
			$rule = sprintf(' and s_id = %s ',GetSqlValueString($value,'text'));			
		}	
		else if(strtoupper($col)  == "ITEM_ID")
		{
			$rule = sprintf(' and item_id = %s ',GetSqlValueString($value,'text'));			
		}
		elseif(strtoupper($col) == 'ITEM')
		{
			$rule = sprintf(' and item = %s ',GetSqlValueString($value,'text'));			
		}
		elseif(strtoupper($col)  == "SEQ")
		{
			$rule = sprintf(' and seq = %s ',GetSqlValueString($value,'text'));			
		}		
	}
	if($printLog == 'T')
	{
		echo 'rule='.$rule.'<br>';
	}
	return $rule;

}

// 取得 Product list by rule
function get_ProductSpecItem_List($rule='')
{	
	include("conn.php");		
	
	if($rule == '') $rule = ' and 1=0 ';
	if($rule == '%') $rule = '';
	
	
	if($printLog=='T')
	{
		echo 'rule='.$rule.'<br>';
	}
			
	$sql = "select t.* 				
		    from specifications_item t	  
   		    where 1=1
			  	".$rule."
				order by seq
			";
			 
	if($printLog=='T')
	{
		echo $sql.'<br>';
	}
	//送出查詢並取得結果
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


// 新增 Spec item
function insertProductSpecItem($id, $name,$seq)
{
	include_once("CommFunc.php");	
		
	$sql = sprintf("INSERT INTO specifications_item (s_id, item, seq) VALUES ('%s','%s','%s')"
				  ,$id,$name,$seq);	
	
	$msg = ExecuteSQL($sql);
	return $msg;
}

// 更新產品規格 item
function updateProductSpecItem($id, $name,$seq)
{
	include_once("CommFunc.php");	
	
	$sql = sprintf("update specifications_item
					  set item = '%s', seq = '%s'
					  where 1=1
							and item_id = '%s'"
					 ,$name,$seq,$id);					
			
	 $msg = ExecuteSQL($sql);

 	 return $msg;

}

function deleteProductSpecItem($id)
{	
	include_once("CommFunc.php");
	
	$sql = sprintf("delete from specifications_item where 1=1 and item_id = '%s'",$id);			
    
	$msg = ExecuteSQL($sql);
	return $msg;
}
		 
?>

