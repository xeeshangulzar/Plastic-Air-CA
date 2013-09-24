<?

function uplPictures($inputFile, $sFolderPictures, $h="500", $w="500", $maxFileSize="4048000", $resizeType='1', $sMask='', $delTMP='1',$SMtext='0') {
	//resizeType=1 - image gets resized proportionally if it is too big, using $h and $w as maximum parameters
	//resizeType=2 - nomatter what image gets resize to the $h and $w size
	global $err;
	$thumbnail="";
	//echo $sFolderPictures;
	if(!empty($inputFile['name']))
	{
		$image_path=$inputFile['tmp_name'];
		$photoFileNametmp = $inputFile['name'];
		$fileNamePartstmp = explode(".", $photoFileNametmp);
		$fileExtensiontmp = strtolower(end($fileNamePartstmp)); // part behind last dot
		$arrAllow=array("jpeg", "jpg", "png", "gif");//, "BMP", "TIFF"
		if (!in_array($fileExtensiontmp, $arrAllow)) { 
			$err.= "Picture's extension should be .jpg, .jpeg, .png, or .gif<br />";
		}
		if($inputFile['size']>$maxFileSize) {
			$ssize=sprintf("%01.2f", $inputFile['size']/1024);
			$err.= "Your file is ".$ssize."KB. Max allowe file size is 500KB.<br>";
		}	
		list($widthMax, $heightMax)=getimagesize($inputFile['tmp_name']);
		if (($widthMax + $heightMax) > 14000) $err.="The image you upload is too wide<br>";
		//echo $err;
		if(empty($err)) {//if there is no errors 
			list($widthF,$heightF)=getimagesize($image_path);
			if ($resizeType=='1') {
				if($widthF>$w) {
					$neededWidth=$w;
					if (($heightF*$w/$widthF)<=$h) {
						$neededHeight=$heightF*$w/$widthF;
					} else {
						$neededHeight=$h; 
						$neededWidth=$widthF*$h/$heightF;
					}
				} elseif ($heightF>$h) { 
					$neededHeight=$h; 
					$neededWidth=$widthF*$h/$heightF;
				} else {
					$neededHeight=$heightF; 
					$neededWidth=$widthF;
				}
			} else if ($resizeType=='2') {
				$neededWidth=$w; 
				$neededHeight=$h;
			} else {
				$err.="System error: invalid function parameter has been given for resizeType<br>";
				return false;
			}				
			$newFile=$_SERVER['DOCUMENT_ROOT'].$sFolderPictures; //print $newFile; exit;
			$newFileArr=explode("/",$newFile);
			$lastElem=$newFileArr[count($newFileArr)-1];
			substr($newFile,0,$newFile-strlen($lastElem)-1); 
			$newFileFolder=substr($newFile,0,$newFile-strlen($lastElem)-1);
			if (!is_dir($newFileFolder)) {
				mkdir($newFileFolder,0755);
			}
			//	$newFile=$_SERVER['DOCUMENT_ROOT'].$uploadDirFile;//print $newFile;
			//echo "HE";
			// /usr/bin/convert
			// /usr/local/bin/convert
			//echo $newFile. "".$image_path." hi";
			// /etc/httpd/conf/magic'
			$sCommand="/usr/local/bin/convert -geometry ".$neededWidth."x".$neededHeight." \"" .$image_path."\" \"".$newFile."\" ";			
			$vvv=exec( $sCommand, $exec_output, $exec_retval );
			$old_error_reporting = error_reporting(E_ALL & ~(E_WARNING));
			
			//echo "<br>";
			//echo $sCommand; 
			//print_r ($exec_output);
			
			if($exec_retval!='0')
			{
				$thumbnail=""; 
			}
			else
			{
				$thumbnail=$sFolderPictures; 
				if ($SMtext==1) {
					switch(strtolower($fileExtensiontmp)){
						case "jpeg":
						case "jpg":
							$im=imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'].$thumbnail);
							break;
						case "gif":
							$im=imagecreatefromgif($_SERVER['DOCUMENT_ROOT'].$thumbnail);
							break;
						case "png":
							$im=imagecreatefrompng($_SERVER['DOCUMENT_ROOT'].$thumbnail);
							break;						
					}	
					
					// Create some colors
					$white = imagecolorallocate($im, 255, 255, 255);
					$grey = imagecolorallocate($im, 128, 128, 128);
					$black = imagecolorallocate($im, 0, 0, 0);
					$red = imagecolorallocate($im, 255, 0, 0);
					
					// The text to draw
					$text = 'www.StudentsMetro.com';
					// Replace path by your own font path
					$font=$_SERVER['DOCUMENT_ROOT']."/inc/fonts/AMBROSIA_DEMO.ttf";
				
					// Add text
					if ($neededWidth>=550) {
						$fontSize=17;
						$intX=$neededWidth-220;
					} else if ($neededWidth<550 && $neededWidth>=350) {
						$fontSize=15;
						$intX=$neededWidth-195;
					} else {
						$fontSize=12;
						$intX=$neededWidth-160;
					}
					imagettftext($im, $fontSize, 0, $intX, $neededHeight-8, $white, $font, $text);
					imagettftext($im, $fontSize, 0, $intX+1, $neededHeight-9, $black, $font, $text);
					
					//putting image to the file
					imagejpeg($im,$_SERVER['DOCUMENT_ROOT'].$thumbnail,100);
					imagedestroy($im);
				}
				$im=imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'].$thumbnail);
				imagejpeg($im,$_SERVER['DOCUMENT_ROOT'].$thumbnail,90);
				imagedestroy($im);
				
				
			}
		}
		if($delTMP=='1')
		{
			if(file_exists($inputFile['tmp_name'])) {
				@unlink($inputFile['tmp_name']);
			}
		}
	}
	return $thumbnail;
}	
?>