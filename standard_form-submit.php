<?
include_once("./dbAccess/conn.php");	
include_once("./dbAccess/ProductCategoryFunc.php");
include_once("./dbAccess/business_enquiryFunc.php");		
include_once("./dbAccess/CommFunc.php");	
include_once("./dbAccess/ProductFunc.php");	
include_once("./dbAccess/MailFunc.php");
include_once("./dbAccess/SystemManageFunc.php");




$msg = '';
 
//var_dump($_POST) ;

$mailtoName = $_POST['name'];
$mailto = $_POST['email'];
$product_id = $_POST['prod_list'];
$mailSubject = "BUSINESS ENQUIRY";
$mailContent = "產品名稱 : "."\n";
$company = $_POST['company'];
$phone = $_POST['phone'];
$country = $_POST['country'];
$comment = $_POST['comment'];
$mailCc = "";

if($mailto == '')
{
	echo 'No mail to address.';
}
else	
{
	if($product_id != '')
	{
		$prodArr = SplitString($product_id,",");														 				 
		if(count($prodArr) > 0)
		{	
			// get mail CC list
			$rule = get_System_Management_item_Select_Rule('NAME','線上詢問');
			$returnMsg = get_System_Management($rule);

			$system_id = getSQLResultInfo($returnMsg['DATA'],'id');			
			
			
			$rule = get_System_Management_item_udata_Select_Rule('ID',$system_id);
			$rule .= get_System_Management_item_udata_Select_Rule('NAME','MAIL');
			$returnMsg = get_System_Management_udata($rule);		
			if($returnMsg['RESULT'])
			{	
				while($temp = mysql_fetch_assoc($returnMsg['DATA']))							
				{		
					if($temp['content'] != "")
					{
						$mailCc .= $temp['content'].',';				
					}
				}
			}
			
			for($i=0;$i<count($prodArr);$i++)
			{ 
				/* get product */
				$rule = get_Products_Select_Rule('ID',$prodArr[$i]);						
				$queryProdResult = get_Products_List($rule);
							
				/* get prod*/
				$prod_name = getSQLResultInfo($queryProdResult,'name');
				$prod_cat_id = getSQLResultInfo($queryProdResult,'product_cat_id');
				
				/* get prod cat name*/	
				$rule = get_Products_Category_Select_Rule('ID',$prod_cat_id);
				$result = get_Products_Category_List($rule);
				$Product_Root = getSQLResultInfo($result,'Product_Root');
										
				$mailContent .= $Product_Root.'/'.getSQLResultInfo($queryProdResult,'name')."\r\n";				
			}
		}
	}
 
	insert_business_enquiry_log($mailtoName,$company,$mailto,$phone,$country,$comment,$product_id);
	$returnMsg = sendMail($mailtoName,$mailto,$mailSubject,$mailContent,'',$mailCc);	
	 
	if($returnMsg['RESULT'])
	{
		header("location:standard_form.php?action=sendok");
	}
	else
	{
		echo $returnMsg['MSG'].'<br>';
	}
	
}
 
echo '<br><br>';


?>

<button type="button" class="btn btn-warning mr-1"  onclick="javascript:history.go(-1)"> <i class="icon-cross2"></i> 上一頁 </button>
