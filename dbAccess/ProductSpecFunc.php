<?php

// 取得 Product 查詢條件
function get_ProductSpec_Select_Rule($col, $value)
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
		if(strtoupper($col) == "ID")
		{
			$rule = sprintf(' and product_id = %s ',GetSqlValueString($value,'text'));			
		}
		else if(strtoupper($col)  == "S_ID")
		{
			$rule = sprintf(' and s_id = %s ',GetSqlValueString($value,'text'));			
		}			
		elseif(strtoupper($col) == 'NAME')
		{
			$rule = sprintf(' and name = %s ',GetSqlValueString($value,'text'));			
		}		
		elseif(strtoupper($col) == 'UNIT')
		{
			$rule = sprintf('and unit = %s',GetSqlValueString($value,'text'));
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
function get_ProductSpec_List($rule='')
{	
	include("conn.php");		
	
	if($rule == '') $rule = ' and 1=0 ';
	if($rule == '%') $rule = '';
	
	
	if($printLog=='T')
	{
		echo 'rule='.$rule.'<br>';
	}
			
	$sql = "select t.* 
				,(select count(1) from specifications_item z where 1=1 and z.s_id=t.s_id)	item_cnt
		    from specifications t	  
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


// 新增 Spec
function insertProductSpec($id, $name,$seq,$unit)
{
	include_once("CommFunc.php");	
		
	$sql = sprintf("INSERT INTO specifications (product_id, name, seq,unit) VALUES ('%s','%s','%s','%s')"
				  ,$id,$name,$seq,$unit);	
	
	$msg = ExecuteSQL($sql);
	return $msg;
}

// 更新產品規格
function updateProductSpec($id, $name,$unit,$seq)
{
	include_once("CommFunc.php");	
	
	$sql = sprintf("update specifications
					  set name = '%s', unit = '%s', seq = '%s'
					  where 1=1
							and s_id = '%s'"
					 ,$name,$unit,$seq,$id);					
			
	 $msg = ExecuteSQL($sql);

 	 return $msg;

}

function deleteProductSpec($id)
{	
	include_once("CommFunc.php");
	
	$sql = sprintf("delete from specifications where 1=1 and s_id = '%s'",$id);			
    
	$msg = ExecuteSQL($sql);
	return $msg;
}
		 
?>

