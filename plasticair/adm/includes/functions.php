<?
function uplPictures_1($inputFile, $sFolderPictures, $height="721", $width="721", $maxFileSize="4048000", $resizeType='1', $sMask='', $delTMP='1') {global $err;
	$thumbnail="";
	if(!empty($inputFile['name'])){//	$filename = 'test.jpg';		
		$filename = $inputFile['tmp_name'];
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
		
		// Get new dimensions
		list($width_orig, $height_orig) = getimagesize($filename);
		if(empty($err)) {
			$ratio_orig = $width_orig/$height_orig;
			
			if ($width/$height > $ratio_orig) {
			   $width = $height*$ratio_orig;
			} else {
			   $height = $width/$ratio_orig;
			}
			
			// Resample
							
			$newFile=$_SERVER['DOCUMENT_ROOT'].$sFolderPictures; //print $newFile; exit;
			$newFileArr=explode("/",$newFile);
			$lastElem=$newFileArr[count($newFileArr)-1];
			substr($newFile,0,$newFile-strlen($lastElem)-1); 
			$newFileFolder=substr($newFile,0,$newFile-strlen($lastElem)-1);
			
			if (!is_dir($newFileFolder)) {
				mkdir($newFileFolder,0777);
			}
			
			$image_p = imagecreatetruecolor($width, $height);
			switch($fileExtensiontmp){
				case "jpeg";
				case "jpg";
					$image = imagecreatefromjpeg($filename);					
					break;
				case "png";
					$image = imagecreatefrompng($filename);	
					break;
				case "gif";
					$image = imagecreatefromgif($filename);	
					break;				
				}
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
			
			// Output
			//echo $newFile."<br />";
			imagejpeg($image_p, $newFile, 70);
			$thumbnail=$newFile;
		}}
		
		if($delTMP=='1'){
			if(file_exists($inputFile['tmp_name'])){@unlink($inputFile['tmp_name']);}}
		return $thumbnail;}
function uplPictures($inputFile, $sFolderPictures, $h="721", $w="721", $maxFileSize="4048000", $resizeType='1', $sMask='', $delTMP='1') {
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
		$upload_extention = strtolower(end($fileNamePartstmp)); // part behind last dot
		$arrAllow=array("jpeg", "jpg", "png", "gif");//, "BMP", "TIFF"
		if (!in_array($upload_extention, $arrAllow)) { 
			$err.= "Picture's extension should be .jpg, .jpeg, .png, or .gif<br />";
		}
		if($inputFile['size']>$maxFileSize) {
			$ssize=sprintf("%01.2f", $inputFile['size']/1024);
			$err.= "Your file is ".$ssize."KB. Max allowe file size is 500KB.<br>";
		}	
		list($widthMax, $heightMax)=getimagesize($inputFile['tmp_name']);
		if (($widthMax + $heightMax) > 14000) $err.="The image you upload is too wide<br>";
		
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
				mkdir($newFileFolder,0777);
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
				$im=imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'].$thumbnail);
				imagejpeg($im,$_SERVER['DOCUMENT_ROOT'].$thumbnail,70);
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
function uplSpecialPictures($inputFile, $sFolderPictures, $h="299", $w="734", $maxFileSize="4048000", $resizeType='1', $sMask='', $delTMP='1') {
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
		$upload_extention = strtolower(end($fileNamePartstmp)); // part behind last dot
		$arrAllow=array("jpeg", "jpg", "png", "gif");//, "BMP", "TIFF"
		if (!in_array($upload_extention, $arrAllow)) { 
			$err.= "Picture's extension should be .jpg, .jpeg, .png, or .gif<br />";
		}
		if($inputFile['size']>$maxFileSize) {
			$ssize=sprintf("%01.2f", $inputFile['size']/1024);
			$err.= "Your file is ".$ssize."KB. Max allowe file size is 500KB.<br>";
		}	
		list($widthMax, $heightMax)=getimagesize($inputFile['tmp_name']);
		if (($widthMax + $heightMax) > 14000) $err.="The image you upload is too wide<br>";
		
		if(empty($err)) {//if there is no errors 
			list($widthF,$heightF)=getimagesize($image_path);
			if ($widthF != 734 || $heightF != 299) {
				$err.="The special image should be 734x299 pixels!<br>";
				$special = 0;
				return false;
			}
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
				mkdir($newFileFolder,0777);
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
function listPaging($numrows,$curPage,$numPerPage) {
	global $paginHTML;
	$textClass="";
	$linkClass="";
	$part=$curPage;
	if (empty($paginHTML)) {
		$tmpPage=(floor($part / 5))*5;
		$tmpPageMax=$tmpPage+5; 
		$paginHTML.='<table width="100%" cellspacing="0" cellpadding="0" class="'.$textClass.'" border="0">
		  <tr>
			<td width="180" align="left" style="padding-left:3px;"><b>Total Found: '.$numrows.'</b></td>
			<td align="center">';
		if ($tmpPage!=0) { 
			$paginHTML.='<a href="#" class="'.$linkClass.'" onclick="changePage(\''.($tmpPage-1).'\')">&laquo;&laquo;Previous</a> ';
		} 		
		for ($i=0;$i<5;$i++) {
			$tmpMax=($tmpPage+$i+1)*$numPerPage;	
			if ($tmpMax>$numrows) {
				$tmpMax=$numrows;			
				$paginHTML.=' <a href="#" onclick="changePage(\''.($tmpPage+$i).'\')" class="'.$linkClass.'"'.(($tmpPage+$i)==$curPage?'style="font-weight:bold; text-decoration:underline; color=#000000;"':'').'>['.(($tmpPage+$i)*$numPerPage+1).'..'.$tmpMax.']</a> ';
				break;
			}		
			$paginHTML.=' <a href="#" onclick="changePage(\''.($tmpPage+$i).'\')" class="'.$linkClass.'" '.(($tmpPage+$i)==$curPage?'style="font-weight:bold; text-decoration:underline; color=#000000;"':'').'>['.(($tmpPage+$i)*$numPerPage+1).'..'.$tmpMax.']</a> ';			
		}
		if ($tmpMax!=$numrows) { 
				$paginHTML.=' <a href="#" onclick="changePage(\''.($tmpPage+$i).'\')" class="'.$linkClass.'">Next&raquo;&raquo;</a>';
		}
		$paginHTML.='
		</td>
		<td><SELECT name="TmpNumPerPage" class="listedit" onChange="document.getElementById(\'numPerPage\').value=this.value;form1.submit(); return false;" style="text-align: right">
		<OPTION value="25" '.(($numPerPage=="25")?"selected":"").'>25</OPTION>					
		<OPTION value="50" '.(($numPerPage=="50")?"selected":"").'>50</OPTION>					
		<OPTION value="75" '.(($numPerPage=="75")?"selected":"").'>75</OPTION>					
		<OPTION value="100" '.(($numPerPage=="100")?"selected":"").'>100</OPTION>					
		<OPTION value="125" '.(($numPerPage=="125")?"selected":"").'>125</OPTION>					
		<OPTION value="150" '.(($numPerPage=="150")?"selected":"").'>150</OPTION>					
		<OPTION value="175" '.(($numPerPage=="175")?"selected":"").'>175</OPTION>					
		<OPTION value="200" '.(($numPerPage=="200")?"selected":"").'>200</OPTION>					
		<OPTION value="500" '.(($numPerPage=="500")?"selected":"").'>500</OPTION>	
		<OPTION value="1000" '.(($numPerPage=="100")?"selected":"").'>1000</OPTION>	
		<OPTION value="0" '.(($numPerPage=="0")?"selected":"").'>All</OPTION>
		</SELECT>&nbsp;&nbsp;Results per Page&nbsp;&nbsp;</td></tr></table>';
	}
	echo $paginHTML;
}
function paging($totalRecords, $pageItems,$pageNum,$pagingLink="")
{
	
	$pagingLink=($pagingLink!="")?$pagingLink:"javascript: switchPages('[:pageNum:]');";
	
	if($pageNum > 0)
	{
		?><img src="/adm/img/paging_back.png" width="4" height="7" border="0" align="absmiddle">&nbsp; <a href="<?=str_replace("[:pageNum:]",($pageNum-1),$pagingLink)?>" class="arial12_blue"> Previous</a> |<?
	}
	?>
	
	<?
	$totalPages=ceil($totalRecords/$pageItems);
	
	if($totalPages > 5)
	{
		if($pageNum<5)
		{
			for($i=0; $i<6; $i++)
			{
			?>
				<a href="<?=str_replace("[:pageNum:]",$i,$pagingLink)?>" class="<?=($pageNum==$i)?"arial12_blue_bold":"arial12_blue"?>"><?=$i+1?></a>&nbsp;
			<?
			}
			?>...&nbsp;
			<a href="<?=str_replace("[:pageNum:]",($totalPages-1),$pagingLink)?>" class="arial12_blue"><?=$totalPages?></a>
		<?
		}
		elseif($pageNum>=5 && $pageNum<=($totalPages-5))
		{
		?>
		<a href="<?=str_replace("[:pageNum:]","0",$pagingLink)?>" class="arial12_blue">1</a>&nbsp;...&nbsp;
		<a href="<?=str_replace("[:pageNum:]",($pageNum-2),$pagingLink)?>" class="arial12_blue"><?=$pageNum-1?></a>&nbsp;
		<a href="<?=str_replace("[:pageNum:]",($pageNum-1),$pagingLink)?>" class="arial12_blue"><?=$pageNum?></a>&nbsp;
		<a href="<?=str_replace("[:pageNum:]",($pageNum),$pagingLink)?>" class="arial12_blue_bold"><?=$pageNum+1?></a>&nbsp;
		<a href="<?=str_replace("[:pageNum:]",($pageNum+1),$pagingLink)?>" class="arial12_blue"><?=$pageNum+2?></a>&nbsp;
		<a href="<?=str_replace("[:pageNum:]",($pageNum+2),$pagingLink)?>" class="arial12_blue"><?=$pageNum+3?></a>&nbsp;...&nbsp;
		<a href="<?=str_replace("[:pageNum:]",($totalPages-1),$pagingLink)?>" class="arial12_blue"><?=$totalPages?></a>
		<?
		}
		else
		{
			?>
			<a href="<?=str_replace("[:pageNum:]","0",$pagingLink)?>" class="arial12_blue">1</a>&nbsp;...&nbsp;
			<?
			for($i=$totalPages-5; $i<=$totalPages; $i++)
			{
			?>
				<a href="<?=str_replace("[:pageNum:]",($i-1),$pagingLink)?>" class="<?=($pageNum==$i-1)?"arial12_blue_bold":"arial12_blue"?>"><?=$i?></a>&nbsp;
			<?
			}
		}
	}
	else
	{
		for($i=0; $i<6; $i++)
		{
			if($i==$totalPages)break;
			?>
				<a href="<?=str_replace("[:pageNum:]",$i,$pagingLink)?>" class="<?=($pageNum==$i)?"arial12_blue_bold":"arial12_blue"?>"><?=$i+1?></a>&nbsp;
			<?
		}
	}
	?>	
	<?
	if($totalRecords > ($pageNum+1) * $pageItems){
		?>| <a href="<?=str_replace("[:pageNum:]",($pageNum+1),$pagingLink)?>" class="arial12_blue">Next</a> &nbsp;<img src="/adm/img/paging_next.png" width="4" height="7" border="0" align="absmiddle"><?
	}
}
?>