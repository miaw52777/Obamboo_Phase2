<?php


function guid()
{
    if (function_exists('com_create_guid'))
	{
        return com_create_guid();
    }
	else
	{
        mt_srand((double)microtime()*10000);
        $charid = strtoupper(md5(uniqid(rand(), true)));
        
        $uuid = substr($charid, 0, 8)
                .substr($charid, 8, 4)
                .substr($charid,12, 4)
                .substr($charid,16, 4)
                .substr($charid,20,12)
                ;
        return $uuid;
    }
} 

function get_business_enquiry_log_Select_Rule($col, $value)
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
		if(strtoupper($col) == "MAP_KEY")
		{
			$rule = sprintf(' and map_key = %s ',GetSqlValueString($value,'text'));			
		}
		
	}
	if($printLog == 'T')
	{
		echo 'rule='.$rule.'<br>';
	}
	return $rule;

}
 
function get_business_enquiry_log_product_Select_Rule($col, $value)
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
		if(strtoupper($col) == "MAP_KEY")
		{
			$rule = sprintf(' and map_key = %s ',GetSqlValueString($value,'text'));			
		}
		
	}
	if($printLog == 'T')
	{
		echo 'rule='.$rule.'<br>';
	}
	return $rule;

}
 

// ¨ú±o LOG
function get_business_enquiry_log($rule='')
{	
	include_once("CommFunc.php");		
	
	if($rule == '') $rule = ' and 1=0 ';
	if($rule == '%') $rule = '';
	
	if($printLog=='T')
	{
		echo 'rule='.$rule.'<br>';
	}
			
	$sql = "select a.*				
			from business_enquiry_log_bt a 
			where 1=1
			 ".$rule."
			 
			";
			
	$returnMsg = QuerySQL($sql);
	//var_dump($returnMsg);
	return $returnMsg;

}

function get_business_enquiry_log_product($rule='')
{	
	include_once("CommFunc.php");		
	
	if($rule == '') $rule = ' and 1=0 ';
	if($rule == '%') $rule = '';
	
	if($printLog=='T')
	{
		echo 'rule='.$rule.'<br>';
	}
			
	$sql = "select a.*
				   ,getpath(product_cat_id)  AS Product_Root
			FROM
			(
				select a.map_key,a.product_id
				,(select z.name from products z where 1=1 and z.product_id = a.product_id) product_name 
				,(select z.product_cat_id from products z where 1=1 and z.product_id = a.product_id) product_cat_id 
				from business_enquiry_log_product_bt a
				where 1=1
			)a
			where 1=1
			 ".$rule."
			 
			";
			
	$returnMsg = QuerySQL($sql);
 
	return $returnMsg;
}



function insert_business_enquiry_log($name,$company,$email,$phone,$country,$comment,$product_id)
{
	
	include_once("CommFunc.php");	
    if($product_id == '') $product_id = null;					
	
	$map_key = guid();
	$sql = sprintf("INSERT INTO business_enquiry_log_bt (name,company,email,phone,country,comment,map_key,rectime) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s') "
						 ,$name,$company,$email,$phone,$country,$comment,$map_key,getNowTime());
	$msg = ExecuteSQL($sql);
	
	if($product_id != '')
	{
		$prodArr = SplitString($product_id,",");														 
						 
		if($prodArr > 0)
		{								
			for($i=0;$i<count($prodArr);$i++)
			{ 
				$sqlProduct = sprintf("INSERT INTO business_enquiry_log_product_bt (map_key,product_id) VALUES ('%s','%s') "
									 ,$map_key,$prodArr[$i]);	
				 //echo $sqlProduct;
				$msg = ExecuteSQL($sqlProduct);
			}
		}
	}
	
 	return $msg;
}
 

		 
?>

