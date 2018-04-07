<?
include_once("../dbAccess/conn.php");	
include_once("../dbAccess/ProductCategoryFunc.php");
include_once("../dbAccess/ProductFunc.php");
include_once("../dbAccess/ProductSpecFunc.php");
include_once("../dbAccess/ProductSpecItemFunc.php");
include_once("../dbAccess/CommFunc.php");
include_once("../dbAccess/Excel_Reader.php");
include('./adsecure.php');


if(!is_login())
{
	header("Location: index.php?page=".$_SERVER['REQUEST_URI']);
	exit;
} 


function dumpImportResult($data,$row_numbers=false,$col_letters=false,$sheet=0,$table_class='excel') 
{		
		$returnMsg = array();
		$itemArr = array();
		$out = "<table class=\"$table_class\" cellspacing=0>";
		if ($col_letters) {
			$out .= "<thead>\n\t<tr>";
			if ($row_numbers) 
			{
				$out .= "\n\t\t<th>&nbsp</th>";
			}
			for($i=1;$i<=$data->colcount($sheet);$i++) 
			{
				$style = "width:" . ($data->colwidth($i,$sheet)*1) . "px;";
				if ($data->colhidden($i,$sheet)) {
					$style .= "display:none;";
				}
				$out .= "\n\t\t<th style=\"$style\">" . strtoupper($data->colindexes[$i]) . "</th>";
			}			
			$out .= "</tr></thead>\n";
		}
		
		
		$out .= "<tbody>\n";
		$itemCount=0;
		for($row=1;$row<=$data->rowcount($sheet);$row++) 
		{
			$rowheight = $data->rowheight($row,$sheet);
			$style = "height:" . ($rowheight*(4/3)) . "px;";
			if ($data->rowhidden($row,$sheet)) {
				$style .= "display:none;";
			}
			$out .= "\n\t<tr style=\"$style\">";
			if ($row_numbers) {
				$out .= "\n\t\t<th>$row</th>";
			}
			
			
			for($col=1;$col<=$data->colcount($sheet);$col++) {
				// Account for Rowspans/Colspans
				$rowspan = $data->rowspan($row,$col,$sheet);
				$colspan = $data->colspan($row,$col,$sheet);
				for($i=0;$i<$rowspan;$i++) {
					for($j=0;$j<$colspan;$j++) {
						if ($i>0 || $j>0) {
							$data->sheets[$sheet]['cellsInfo'][$row+$i][$col+$j]['dontprint']=1;
						}
					}
				}
				if((!$data->sheets[$sheet]['cellsInfo'][$row][$col]['dontprint']) && 
					($data->val($row,1,$sheet) != ""))
				{
					$style = $data->style($row,$col,$sheet);
					if ($data->colhidden($col,$sheet)) {
						$style .= "display:none;";
					}
					$out .= "\n\t\t<td style=\"$style\"" . ($colspan > 1?" colspan=$colspan":"") . ($rowspan > 1?" rowspan=$rowspan":"") . ">";
					$val = $data->val($row,$col,$sheet);
					if ($val=='') { $val=""; }
					else { 
						$val = htmlentities($val); 						
						$link = $data->hyperlink($row,$col,$sheet);
						if ($link!='') {
							$val = "<a href=\"$link\">$val</a>";
						}
					}			
					
					$out .= "<nobr>".nl2br($val)."</nobr>";										
					if($row > 1)
					{
						if($col == 1)
						{
							$itemDetailArr = array();
							$itemDetailCount = 0;
						}
						
						if($col <= 3)
						{
							// specification							
							$itemArr[$itemCount][$data->val(1,$col,$sheet)] = $val;								
							if($col==3) $itemCount++;
						}
						else
						{							
							// specification items									
							if($col % 2 == 1) 
							{
								$specitem = $data->val($row,$col-1,$sheet);
							
								$itemDetailArr[$itemDetailCount][$data->val(1,$col-1,$sheet)] = $specitem;
								$itemDetailArr[$itemDetailCount][$data->val(1,$col,$sheet)] = $val;
								$itemDetailCount++;
								
							}
							$itemArr[$itemCount-1]['SPECITEM'] = $itemDetailArr;
							
						}
					}
					$out .= "</td>";
					
					if(($row == 1)&&($col == 3))
					{
						$out .= "\n\t\t<td style=\"$style\"" . ($colspan > 1?" colspan=$colspan":"") . ($rowspan > 1?" rowspan=$rowspan":"") . ">";
						$out .= htmlentities('RESULT')."</td>";
					}
					else if(($row == 1)&&($col >= 3)&&($col % 2 == 1))
					{
						$out .= "\n\t\t<td style=\"$style\"" . ($colspan > 1?" colspan=$colspan":"") . ($rowspan > 1?" rowspan=$rowspan":"") . ">";
						$out .= htmlentities('RESULT')."</td>";
					}
					else if($row > 1) 
					{
						if(($row-1) < 10)
						{
							$rindex = '0'.($row-1);
						}
						else
						{
							$rindex = ($row-1);
						}
						
						if(($itemDetailCount) < 10)
						{
							$ritemindex = '0'.$itemDetailCount;
						}
						else
						{
							$ritemindex = $itemDetailCount;
						}
						
						if ($data->val($row,1,$sheet) != "")
						{	
							if($col == 3)					
							{
								$out .= "\n\t\t<td>:SPEC_UPLOADRESULT_".($rindex)."</td>";	
							}
							else if(($col > 3)&&($col % 2 == 1))					
							{
								$out .= "\n\t\t<td>:SPECITEM_UPLOADRESULT_".(($rindex).$ritemindex)."</td>";	
							}
						}				
					}
				}
			}
			
			$out .= "</tr>\n";
		}
		$out .= "</tbody></table>";
		
		$returnMsg['EXCELHTML'] = $out;
		$returnMsg['ITEM'] = $itemArr;
		return $returnMsg;
	
}

 
?>
 
<html>
<head>
<style>
table.excel {
	border-style:ridge;
	border-width:1;
	border-collapse:collapse;
	font-family:sans-serif;
	font-size:12px;
}
table.excel thead th, table.excel tbody th {
	background:#CCCCCC;
	border-style:ridge;
	border-width:1;
	text-align: center;
	vertical-align:bottom;
}
table.excel tbody th {
	text-align:center;
	width:20px;
}
table.excel tbody td {
	vertical-align:bottom;
}
table.excel tbody td {
    padding: 0 3px;
	border: 1px solid #EEEEEE;
}
</style>
</head>

<body>

<?php 
	$prod_cat_root = $_POST['rootOptionSelect'];
	$prod_id = $_POST['prod_id'];
	$importfilename = $_FILES["file"]["tmp_name"]; 
	$data = new Spreadsheet_Excel_Reader($importfilename);	
	 
	$returnMsg = dumpImportResult($data,false,false); 	
	
	//print_r($returnMsg['ITEM']);
	
	for($i=0;$i<count($returnMsg['ITEM']);$i++)
	{
		$pname = trim($returnMsg['ITEM'][$i]['產品規格']);
		$pseq = trim($returnMsg['ITEM'][$i]['排序']);
		$punit = trim($returnMsg['ITEM'][$i]['單位']);
		 
		if(($i+1) < 10)
		{
			$rindex = '0'.($i+1);
		}
		else
		{
			$rindex = ($i+1);
		}
		
		if($pname == '')
		{
			$msg = 'Error : Product specification name must be given.';
		}
		else
		{
			$msg = insertProductSpec($prod_id, $pname,$pseq,$punit);				
			if($msg == '') $msg = 'OK';
			for($j=0;$j<count($returnMsg['ITEM'][$i]['SPECITEM']);$j++)
			{				
				$itemname = trim($returnMsg['ITEM'][$i]['SPECITEM'][$j]['規格選項']);
				$itemseq = trim($returnMsg['ITEM'][$i]['SPECITEM'][$j]['排序']);
				
				if($itemname == "")
				{
					$msgItem = 'Empty item name.';
				}
				else
				{
					$rule = get_ProductSpec_Select_Rule('ID',$prod_id);
					$rule .= get_ProductSpec_Select_Rule('NAME',$pname);
					$queryResult = get_ProductSpec_List($rule);	
					$psid = getSQLResultInfo($queryResult,'s_id');														
					if($psid != '')
					{
						$msgItem = insertProductSpecItem($psid, $itemname,$itemseq);	
						if($msgItem == '') $msgItem = 'OK';
					}
					else
					{
						$msgItem = 'Get spec id error.';
					}
					
				}
				
				
				
				if(($j+1) < 10)
				{
					$jindex = '0'.($j+1);
				}
				else
				{
					$jindex = $j+1;
				}
				
				$returnMsg['EXCELHTML'] = str_replace(':SPECITEM_UPLOADRESULT_'.$rindex.$jindex,$msgItem,$returnMsg['EXCELHTML']);		
			}
		}
		
		
		$returnMsg['EXCELHTML'] = str_replace(':SPEC_UPLOADRESULT_'.$rindex,$msg,$returnMsg['EXCELHTML']);		
	}
	echo $returnMsg['EXCELHTML'];
 
	echo '<br>';
 ?>
<button type="button" class="btn btn-warning mr-1"  onclick="javascript:history.go(-1)"> <i class="icon-cross2"></i> 上一頁 </button>
<button type="button" class="btn btn-warning mr-1" onclick="location.href='products.php';">產品管理 </button>

</body>
</html>
