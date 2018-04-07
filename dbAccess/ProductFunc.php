<?php

// 取得 Product 查詢條件
function get_Products_Select_Rule($col, $value)
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
		if(strtoupper($col)  == "PROD_CAT_ID")
		{
			$rule = sprintf(' and product_cat_id = %s ',GetSqlValueString($value,'text'));			
		}
		else if(strtoupper($col)  == "ID")
		{
			$rule = sprintf(' and product_id = %s ',GetSqlValueString($value,'text'));			
		}		
		elseif(strtoupper($col) == 'NAME')
		{
			$rule = sprintf(' and name = %s ',GetSqlValueString($value,'text'));			
		}		
		elseif(strtoupper($col) == 'VALID')
		{
			$rule = sprintf('and valid = %s',GetSqlValueString($value,'text'));
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
function get_Products_List($rule='')
{	
	include("conn.php");		
	
	if($rule == '') $rule = ' and 1=0 ';
	if($rule == '%') $rule = '';
	
	
	if($printLog=='T')
	{
		echo 'rule='.$rule.'<br>';
	}
			
	$sql = "select t.* from products t	  
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

 

// 新增產品
function insertProduct($product_cat_id, $name,$valid,$seq,$desc)
{
	include_once("CommFunc.php");	
		
	$sql = sprintf("INSERT INTO products (product_cat_id, name,valid, seq,description) VALUES ('%s','%s','%s','%s','%s')"
				  ,$product_cat_id,$name,$valid,$seq,$desc);	
	
	$msg = ExecuteSQL($sql);
	return $msg;
}

function insertProductAndFilepath($product_cat_id, $name,$valid,$seq,$desc,$filepath_1,$filepath_2,$filepath_3,$filepath_4)
{
	include_once("CommFunc.php");	
		
	$sql = sprintf("INSERT INTO products (product_cat_id, name,valid, seq,description,filepath_1,filepath_2,filepath_3,filepath_4) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s')"
				  ,$product_cat_id,$name,$valid,$seq,$desc,$filepath_1,$filepath_2,$filepath_3,$filepath_4);		
	$msg = ExecuteSQL($sql);
	return $msg;
}

// 更新產品資訊
function updateProduct($prod_id, $product_cat_id, $name,$valid,$seq)
{
	include_once("CommFunc.php");	
 
	$sql = sprintf("update products
					  set name = '%s', valid = '%s', seq = '%s', product_cat_id = '%s'
					  where 1=1
							and product_id = '%s'"
					 ,$name,$valid,$seq,$product_cat_id,$prod_id);					
			 
	 $msg = ExecuteSQL($sql);
 	 return $msg;

}

// 更新產品說明
function updateProduct_description($prod_id,$desc,$fileroot)
{
	include_once("CommFunc.php");
	$rule = get_Products_Select_Rule('ID',$prod_id);
	$checkData = get_Products_List($rule);
	if(mysql_num_rows($checkData) == 0) return 'No this record.';
	/*
	$sql = sprintf("update products
				  set  description = '%s'
				  where 1=1
						and product_id = '%s'"
				 ,$desc,$prod_id);						 	
	 $msg = ExecuteSQL($sql);*/
	 
	 $outputfile = $fileroot.$prod_id.'.txt';
	 unlink($outputfile);
	 file_put_contents($outputfile, $desc);	 
	 if(!file_exists($outputfile))
	 {
		 $msg = 'fail to create file for store description.<br>';
	 }
	 
 	 return $msg;

}

// 更新產品說明
function getProduct_description($prod_id,$fileRoot)
{	  

	 $filepath = $fileRoot.'product_desc/'.$prod_id.'.txt';
	// var_dump( $filepath);
	 if(file_exists($filepath))
	 {	
		$file = fopen($filepath, "r") or die("Unable to open file!");
		$desc = fread($file,filesize($filepath));
		fclose($file);
	 }
	 else 
	 {
		 
	 }
 	 return $desc;

}

//更新上傳檔案路徑
function updateProductFilepath($prod_id,$colname,$filepath)
{
	include_once("CommFunc.php");
	$sql = sprintf("update products
					  set %s = '%s'
					  where 1=1
							and product_id = '%s' "
					 ,$colname, $filepath,$prod_id);			
	 $msg = ExecuteSQL($sql);
 	 return $msg;
}




function deleteProduct($id)
{
	include_once("conn.php");
	include_once("CommFunc.php");
	
	$sql = sprintf("delete from products where 1=1 and product_id = '%s'",$id);			
    
	$msg = ExecuteSQL($sql);
	return $msg;
}

		 
?>

