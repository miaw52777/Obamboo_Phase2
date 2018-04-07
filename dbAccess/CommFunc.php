<?
// 字串斷行
function stringWrap($string,$length=18,$append="<br>") 
{
  
  $result = wordwrap($string,$length,$append,true);

  return $result;
}

// Query SQL 並回傳成功或錯誤訊息
function QuerySQL($sql)
{
	include("conn.php");
	$returnMsg = array();
	
	$result = mysql_query($sql,$link);	
	$msg = mysql_error($link);
	
	if($printLog=='T')
	{
		echo $sql.'<br>';
	}
	//送出查詢並取得結果
	$result = mysql_query($sql,$link);
	
	
	if (!$result) 
	{ 
		$returnMsg['RESULT'] = false;
		$returnMsg['MSG'] = 'Sql Error : '.mysql_error();
		$returnMsg['DATA']= '';
		$returnMsg['REC_CNT']= 0;		
		$returnMsg['SQL']= $sql;		
	}
	else
	{		
		if($printLog=='T')
		{ 
			echo var_dump($result).'<br>';
			echo 'Record count : '.mysql_num_rows($result).'<br>';
			$data = mysql_fetch_assoc($result);			
		}
		$returnMsg['RESULT'] = true;
		$returnMsg['MSG'] = ''; 
		$returnMsg['DATA']= $result;
		$returnMsg['REC_CNT']= mysql_num_rows($result);
		$returnMsg['SQL']= $sql;		
	}
	return $returnMsg;
}

// Execute SQL 
function ExecuteSQL($sql,$printLog = 'F')
{
	include("conn.php");
			
	 mysql_query($sql,$link);	
	 $msg = mysql_error($link);
	 
	 //$printLog='T';
	 if($printLog=='T')
	 {
	 	echo $sql.'<br>';
	 }	
		
	 if($msg == '') return $msg;
	 else
	 {		
  		return 'Execute sql Fail : '.mysql_errno($link) . ": " . $msg . "\n";
 	}
}

function getSQLResultInfo($result,$col)
{
	if(mysql_num_rows($result)>0)
	{
		mysql_data_seek($result,0);
		$dataRow = mysql_fetch_assoc($result);	
		//print_r($dataRow);
        return $dataRow[$col];							
	}
} 

		
// convert sql result to array
function convertToAssocArray($queryResult)
{
	$array = array();
	$i=0;
	if(mysql_num_rows($queryResult) > 0)
	{
		mysql_data_seek($queryResult,0);
		while($temp = mysql_fetch_assoc($queryResult))
		{
			$array[$i]=$temp;
			$i++;
		}	
		return $array;
	}
	return null;
}



function CompareArrayByFilter($source, $filter_array)
{
	$result_array = array();
	foreach ($source as $dataRow) 
	{ 
		$key_match_cnt=0;	 
		
		// compare column values
		reset($filter_array);
		while (list($key,$value) = each($filter_array)) 
		{
			// column exists in array
			if (array_key_exists($key,$dataRow))
			{	  
				// compare value
				if($dataRow[$key] == $value)
				{
					$key_match_cnt++;
				}
				else
				{
					break; // not match search next record
				}
			}
		} 
		// check compare result
		if(($key_match_cnt==count($filter_array)))
		{
			array_push($result_array,$dataRow);
		}
	} 
	return $result_array;
	
}


function SplitString($str,$delimeter = ",")
{	
	$tmpArr = explode($delimeter,$str);
	//print_r($tmpArr );
	//echo '<br><br>';
	$result = array();
	$tmpIndex=0;

	for ($i = 0; $i < count($tmpArr); $i++)
	{	
		if(trim($tmpArr[$i]) != '') // remove empty
		{			
			$result[$tmpIndex] = $tmpArr[$i];
			$tmpIndex++;
		}
	}	
	
	return $result;
	
} 
function getNowTime()
{
   date_default_timezone_set("Asia/Taipei"); 
   $rectime = date("Y-m-d H:i:s"); 
   return $rectime;
}
?>

