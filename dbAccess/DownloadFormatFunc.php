<?php

// 取得 Product 查詢條件
function get_DownloadFormat_Select_Rule($col, $value)
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
		/*if(strtoupper($col)  == "PROD_ID")
		{
			$rule = sprintf(' and prod_id = %s ',GetSqlValueString($value,'text'));			
		}	
		else*/
		if(strtoupper($col)  == "D_ID")
		{
			$rule = sprintf(' and d_id = %s ',GetSqlValueString($value,'text'));			
		}
		else if(strtoupper($col)  == "CAT")
		{
			$rule = sprintf(' and category = %s ',GetSqlValueString($value,'text'));			
		}
		elseif(strtoupper($col) == 'COMMON')
		{
			$rule = sprintf(' and common = %s ',GetSqlValueString($value,'text'));			
		}
		elseif(strtoupper($col)  == "FILEPATH")
		{
			$rule = sprintf(' and filepath = %s ',GetSqlValueString($value,'text'));			
		}		
	}
	if($printLog == 'T')
	{
		echo 'rule='.$rule.'<br>';
	}
	return $rule;

}

// 取得 Product list by rule
function get_DownloadFormat_List($rule='')
{			
	include("conn.php");		
	if($rule == '') $rule = ' and 1=0 ';
	if($rule == '%') $rule = '';	 
			
	$sql = "select t.* 				
		    from download_format t	  
   		    where 1=1
			  	".$rule."				
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


// 新增 download format
function insertDownloadFormat($category,$common)
{		
	include_once('CommFunc.php');
	$sql = sprintf("INSERT INTO download_format (category, common) VALUES ('%s','%s')"
				  ,$category,$common);	
	
	$msg = ExecuteSQL($sql);
	return $msg;
}

// 更新 download format
function updateDownloadFormat($id, $category,$common)
{
	include_once('CommFunc.php');
	$sql = sprintf("update download_format
					  set category = '%s', common = '%s'
					  where 1=1
							and d_id = '%s'"
					 ,$category,$common,$id);					
			
	 $msg = ExecuteSQL($sql);

 	 return $msg;

}
// 更新 download format filepath
function updateDownloadFormat_Filepath($id, $filepath)
{
	include_once('CommFunc.php');
	$sql = sprintf("update download_format
					  set filepath = '%s'
					  where 1=1
							and d_id = '%s'"
					 ,$filepath,$id);					
			
	 $msg = ExecuteSQL($sql);

 	 return $msg;
}

function deleteDownloadFormat($id)
{	 
	include_once('CommFunc.php');	
	$sql = sprintf("delete from download_format where 1=1 and d_id = '%s'",$id);			    
	$msg = ExecuteSQL($sql);
	return $msg;
}
		 
?>

