<?
include_once("../dbAccess/conn.php");	
include_once("../dbAccess/ProductCategoryFunc.php");
include_once("../dbAccess/FileIOFunc.php");
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
						$itemArr[$itemCount][$data->val(1,$col,$sheet)] = $val;					
						if($col==$data->colcount($sheet)) $itemCount++;
					}
					$out .= "</td>";
				}
			}
			if($row == 1)
			{
				$out .= "\n\t\t<td style=\"$style\"" . ($colspan > 1?" colspan=$colspan":"") . ($rowspan > 1?" rowspan=$rowspan":"") . ">";
				$out .= htmlentities('RESULT')."</td>";
			}
			else
			{
				if ($data->val($row,1,$sheet) != "")
				{	
					if(($row-1) < 10)
					{
						$rindex = '0'.($row-1);
					}
					else
					{
						$rindex = ($row-1);
					}
					$out .= "\n\t\t<td>:UPLOADRESULT_".$rindex."</td>";		
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
	$proot = $_POST['rootOptionSelect'];
	$importfilename = $_FILES["file"]["tmp_name"]; 
	$data = new Spreadsheet_Excel_Reader($importfilename);

	$returnMsg = dumpImportResult($data,false,false); 	
	
	for($i=0;$i<count($returnMsg['ITEM']);$i++)
	{
		$ProductCatName = trim($returnMsg['ITEM'][$i]['類別']);
		$pseq = trim($returnMsg['ITEM'][$i]['排序']);
		$filepath = trim($returnMsg['ITEM'][$i]['圖片檔名']);
		$pvalid = trim(strtoupper($returnMsg['ITEM'][$i]['顯示']));
		if(($pvalid == 'V')||($pvalid == 'Y')||($pvalid == 'T'))
		{
			$pvalid = 'T';
		}
		else
		{
			$pvalid = 'F';
		}
		
		if($ProductCatName == '')
		{
			$msg = 'Error : Product Category must be given.';
		}
		else
		{
			$msg = insertProductCategory('', $ProductCatName,$proot,$pvalid,$pseq,$filepath);		
			if($msg == '') $msg = 'Upload OK';
		}
		
		if(($i+1) < 10)
		{
			$rindex = '0'.($i+1);
		}
		else
		{
			$rindex = ($i+1);
		}		
		
		$returnMsg['EXCELHTML'] = str_replace(':UPLOADRESULT_'.$rindex,$msg,$returnMsg['EXCELHTML']);		
	}
	echo $returnMsg['EXCELHTML'];
 ?>
<button type="button" class="btn btn-warning mr-1"  onclick="javascript:history.go(-1)"> <i class="icon-cross2"></i> 上一頁 </button>
<button type="button" class="btn btn-warning mr-1" onclick="location.href='products-category.php';">產品類別 </button>

</body>
</html>
