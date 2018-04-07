<?php
function archiveFile($filelist,$fileRoot,$zipname)
{	
	if(count($filelist)>0)
	{			
		date_default_timezone_set('Asia/Taipei');
		$timestamp = date('YmdGis', time());		
		$zipname = 'Compressfile/'.$zipname;		
 
		$zip = new ZipArchive;		
		$validFileCnt=0;
		if ($zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) 
		{
			foreach ($filelist as $path) 
			{			   
				if(file_exists($fileRoot.$path))
				{
					$zip->addFile($fileRoot.$path,$path);
					//$zip->addFromString(basename($fileRoot.$path),  file_get_contents($fileRoot.$path)); 
					$validFileCnt++;
				}
			}
			$zip->close();	
			//header("Content-Type: application/octetstream; name=".$zipname); //for IE & Opera
			//header("Content-Type: application/octet-stream; name=".$zipname); //for the rest	
			/*header("Content-Type: application/zip");
			header('Content-disposition: attachment; filename='.$zipname);
			header('Content-Length: ' . filesize($zipname));*/
			//header("Content-Transfer-Encoding: binary");			
		
			//readfile($zipname);
		 
			if($validFileCnt>0)
			{				
				return $zipname;
			}
			else
			{				
				echo 'No upload any file.';
				return '';
			}
		}
		else
		{
			 echo 'Create archive file failed.';
			 return '';
		}		
	}		
	else
	{
		echo 'No file selected.';
		return '';		
	}
}
 function uploadFile($fileRoot,$fileInfo)
 {
   include_once("conn.php");	
 
   $msg = '';
   
   if ($fileInfo['error'] == 0)
   {
		$allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
		$filename = $fileInfo["name"];
		$filetype = $fileInfo["type"];
		$filesize = $fileInfo["size"];		
	
		// Check whether file exists before uploading it
		if(file_exists($fileRoot.$filename))			
		{
			$msg = 'Upload File Error : '.$filename. " already exists.";
		} 
		else
		{
			move_uploaded_file($fileInfo["tmp_name"], $fileRoot.$filename);				
		} 
		
		if(!file_exists($fileRoot.$filename))
		{
			$msg = 'Upload file '.$filename.' fail, file not exists...';				
		}
		
	} 
	else
	{
		$msg =  "Upload File Error: " . $fileInfo["error"];
	}
	return $msg;

 }
 
 function deleteFile($fileRoot,$file)
 {
 		
	date_default_timezone_set('Asia/Taipei');
	$timestamp = date('YmdGis', time());
	$source = $fileRoot.$file;	
	$target = $fileRoot.'BK/'.$timestamp.'_'.$file;
	if (file_exists($source)) 
	{
		$msg = rename($source,$target); // 備份到資料夾
	}
	if (file_exists($source)) 
	{
		$msg = $file.' => delete fail, please try again.<br>';
	}
	else
	{
		$msg = '';
	}
	return $msg;
 
 }
 

 
 
?>

