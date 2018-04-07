<?php

// download.php
function get_3D_SpecDetail_download_format_file_Select_Rule($col, $value)
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
		else if(strtoupper($col)  == "D_ID")
		{
			$rule = sprintf(' and d_id = %s ',GetSqlValueString($value,'text'));			
		}	
		else if(strtoupper($col)  == "SPEC_KEY")
		{
			$rule = sprintf(' and spec_key = %s ',GetSqlValueString($value,'text'));			
		}
		else if(strtoupper($col)  == "ITEM_KEY")
		{
			$rule = sprintf(' and item_key = %s ',GetSqlValueString($value,'text'));			
		}		
		elseif(strtoupper($col) == 'FILEPATH')
		{
			$rule = sprintf(' and upper(filepath) like %s ',GetSqlValueString($value,'text'));			
		}		
	}
	if($printLog == 'T')
	{
		echo 'rule='.$rule.'<br>';
	}
	return $rule;

}

// 取得 download file list
function get_3D_SpecDetail_download_format_file_raw($rule='')
{		
	include_once("CommFunc.php");		
	
	if($rule == '') $rule = ' and 1=0 ';
	if($rule == '%') $rule = '';
	
	
	
	if($printLog=='T')
	{
		echo 'rule='.$rule.'<br>';
	}
			
	$sql = "select *
			from 3d_spec_detail_download_format_file a
			 where 1=1
			 ".$rule."
			";
			
	$returnMsg = QuerySQL($sql);
	return $returnMsg;
}

// 取得 download file list
function get_3D_SpecDetail_download_format_file($rule='',$rule_main='')
{		
	include_once("CommFunc.php");		
	
	if($rule == '') $rule = ' and 1=0 ';
	if($rule == '%') $rule = '';
	if($rule_main == '%') $rule_main = '';
	
	
	if($printLog=='T')
	{
		echo 'rule='.$rule.'<br>';
	}
			
	$sql = "select *
			from(
					SELECT a.category, a.d_id, b.f_id, b.filepath,a.common, b.spec_key,b.item_key
					FROM 
					(
					 select * 
					 from download_format 
					 where 1=1 	
							".$rule_main."
					 ) a 
					left join
					(select * 
					 from 3d_spec_detail_download_format_file 
					 where 1=1 	
							".$rule."
					 )b  on a.d_id = b.d_id
				 )a
			 where 1=1
			";
			 
			 
	$returnMsg = QuerySQL($sql);
	return $returnMsg;
}


// 新增 download format filepath
function insert_3D_SpecDetail_Download_Format_File($prod_id,$d_id,$spec_key,$item_key,$filepath)
{		
	include_once('CommFunc.php');
	$sql = sprintf("INSERT INTO 3d_spec_detail_download_format_file (product_id,d_id,spec_key,item_key,filepath) VALUES ('%s','%s','%s','%s','%s')"
				  ,$prod_id,$d_id,$spec_key,$item_key,$filepath);	
	 
	$msg = ExecuteSQL($sql);
	return $msg;
}


// 更新 download format filepath by file id
function update_3D_SpecDetail_Download_Format_Filepath($id, $filepath)
{
	include_once('CommFunc.php');
	$sql = sprintf("update 3d_spec_detail_download_format_file
					  set filepath = '%s'
					  where 1=1
							and f_id = '%s'
					"
					 ,$filepath,$id);					
			
	 $msg = ExecuteSQL($sql);

 	 return $msg;
}
 // delete download format filepath by file id
function delete_3D_SpecDetail_Download_Format_Filepath($id)
{
	include_once('CommFunc.php');
	$sql = sprintf("delete from 3d_spec_detail_download_format_file					  
					  where 1=1
							and f_id = '%s'
					"
					 ,$id);					
			
	 $msg = ExecuteSQL($sql);

 	 return $msg;
}
 
?>

