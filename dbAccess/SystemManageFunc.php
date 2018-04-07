<?php

// 取得 Product 查詢條件
function get_System_Management_item_Select_Rule($col, $value)
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
		else if(strtoupper($col)  == "NAME")
		{
			$rule = sprintf(' and name = %s ',GetSqlValueString($value,'text'));			
		}		
		elseif(strtoupper($col) == 'VALID')
		{
			$rule = sprintf('and valid = %s',GetSqlValueString($value,'text'));
		}		
	}
	if($printLog == 'T')
	{
		echo 'rule='.$rule.'<br>';
	}
	return $rule;

}


function get_System_Management($rule='')
{	
	include_once("CommFunc.php");
	
	if($rule == '') $rule = ' and 1=0 ';
	if($rule == '%') $rule = '';
	
	
	if($printLog=='T')
	{
		echo 'rule='.$rule.'<br>';
	}
			
	$sql = "select t.* from system_management_item t	  
   		    where 1=1
			  	".$rule." 
			";
			 
	return QuerySQL($sql);
}
 

function updateSystem_Management_valid($id,$valid)
{
	include_once("CommFunc.php");	
 
	$sql = sprintf("update system_management_item
					  set valid = '%s'
					  where 1=1
							and id = '%s'"
					 ,$valid,$id);					

	 $msg = ExecuteSQL($sql);
 	 return $msg;

}
 
 
/************************  UDATA *******************************/

function get_System_Management_item_udata_Select_Rule($col, $value)
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
			$rule = sprintf(' and system_id = %s ',GetSqlValueString($value,'text'));			
		}
		else if(strtoupper($col)  == "NAME")
		{
			$rule = sprintf(' and name = %s ',GetSqlValueString($value,'text'));			
		}		
		elseif(strtoupper($col) == 'SEQ')
		{
			$rule = sprintf('and seq = %s',GetSqlValueString($value,'text'));
		}		
	}
	if($printLog == 'T')
	{
		echo 'rule='.$rule.'<br>';
	}
	return $rule;

}


function get_System_Management_udata($rule='')
{	
	include_once("CommFunc.php");
	
	if($rule == '') $rule = ' and 1=0 ';
	if($rule == '%') $rule = '';
	
	
	if($printLog=='T')
	{
		echo 'rule='.$rule.'<br>';
	}
			
	$sql = "select t.* from system_management_item_udata t	  
   		    where 1=1
			  	".$rule." 
			";
			 
	return QuerySQL($sql);
}

function insertSystem_Management_udata($system_id,$name,$content)
{
	include_once("CommFunc.php");	
 
	$sql = sprintf("insert system_management_item_udata(system_id,name,content) VALUES ('%s','%s','%s','%s','%s')"							
					 ,$system_id,$name,$content);					

	 $msg = ExecuteSQL($sql);
 	 return $msg;

}  
 
function updateSystem_Management_udata($system_id,$seq,$name,$content)
{
	include_once("CommFunc.php");	
 
	$sql = sprintf("update system_management_item_udata
					  set content = '%s'
					  where 1=1
							and system_id = '%s'
							and seq = '%s'
							and name = '%s'
							"							
					 ,$content,$system_id,$seq,$name);					

	 $msg = ExecuteSQL($sql);
 	 return $msg;

}
 

		 
?>

