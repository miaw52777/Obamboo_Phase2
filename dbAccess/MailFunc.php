<?php

function sendMail($mailtoName,$mailto,$Subject,$Content,$Filepath,$mailcc='',$isHtml=false)
{
	include("class.phpmailer.php"); //匯入PHPMailer類別
	date_default_timezone_set("Asia/Taipei"); 
	$mail= new PHPMailer(); //建立新物件
	$mail->CharSet = "UTF-8"; //郵件編碼 內部預設編碼改為UTF-8
	$mail->Encoding = "base64";
	 
	$mail->IsHTML($isHtml); //郵件內容為html ( true || false)

	$mail->From = "obamwvvq@premium4.web-hosting.com";
	$mail->FromName = "obambooq";
	$mail->AddAddress($mailto,$mailtoName); //收件者郵件及名稱

	$mailcclist = explode(',',$mailcc);
	
	for($i=0;$i<count($mailcclist);$i++)
	{	
		if(trim($mailcclist[$i]) != "")
		{
			$mail->AddBCC($mailcclist[$i]); //設定 密件副本收件者
		}
	}
	  
	$mail->Subject = $Subject;  //郵件標題

	$mail->Body = $Content; //郵件內容	
	
	// send mail with attachment  	
	if($Filepath != '')
	{
		$mail->AddAttachment($Filepath); 
	}

	$returnMsg = array();

	// perform send mail	
	if(!$mail->Send()) 
	{
		$returnMsg['RESULT'] = false;
		$returnMsg['MSG'] = "<br>Send Error " . $mail->ErrorInfo;
	} 
	else 
	{
		$returnMsg['RESULT'] = true;
		$returnMsg['MSG'] = "<div align=center>Send Mail ...</div>";
	}
	return $returnMsg; // return send mail result & error msg
}
?>
