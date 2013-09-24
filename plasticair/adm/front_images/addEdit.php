<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";

$tableName="front_images";
$tableID="front_imagesID";
$folderName="front_images";
$pageTitle="Front Images";
$filesPath="/dat/front_images/";

//$$tableID=(!empty($_REQUEST[$tableID]))?str_replace("'","",$_REQUEST[$tableID]):"0";
$front_imagesID=(!empty($_REQUEST["front_imagesID"]))?toSQL($_REQUEST["front_imagesID"]):"";
$image_title=(!empty($_REQUEST["image_title"]))?toSQL($_REQUEST["image_title"]):"";
$phone=(!empty($_REQUEST["phone"]))?toSQL($_REQUEST["phone"]):"";
$manufacturerID=(!empty($_POST["manufacturerID"]))?($_POST["manufacturerID"]):"";
$answer=(!empty($_REQUEST["answer"]))?toSQL($_REQUEST["answer"]):"";
$features=(!empty($_REQUEST["features"]))?toSQL($_REQUEST["features"]):"";
$links=(!empty($_REQUEST["links"]))?toSQL($_REQUEST["links"]):"";
$image_descr=(!empty($_REQUEST["image_descr"]))?str_replace("'", "`", $_REQUEST["image_descr"]):"";
$dateCreated=(!empty($_REQUEST["dateCreated"]))?toSQL($_REQUEST["dateCreated"]):"";
$processSafeForm=(!empty($_REQUEST["processSafeForm"]))?$_REQUEST["processSafeForm"]:"";
$_SESSION["processSafe"]=(empty($_SESSION["processSafe"]))?"1":$_SESSION["processSafe"];

$err="";
$mess="";

if(!empty($_REQUEST["process"]) && !empty($processSafeForm) && $_SESSION["processSafe"]==$processSafeForm) {

	$_SESSION["processSafe"]++;

	if(!empty($_REQUEST["deleteImage"]) && !empty($front_imagesID)){
		$sql="SELECT * FROM front_images WHERE front_imagesID = '".toSQL($_REQUEST["deleteImage"])."' AND tableName = '".$tableName."' AND tableIDNum = '".$front_imagesID."'";
		$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		if($row=mysql_fetch_assoc($result)){	
		@unlink($_SERVER['DOCUMENT_ROOT'].$row['filePath']);
			$sql="DELETE FROM front_images WHERE front_imagesID = '".toSQL($_REQUEST["deleteImage"])."' AND tableName = '".$tableName."' AND tableIDNum = '".$front_imagesID."'";
			mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		}
	}
	if(!empty($_REQUEST["deleteFile"]) && !empty($front_imagesID)){
		$sql="SELECT * FROM files WHERE filesID = '".toSQL($_REQUEST["deleteFile"])."' AND tableName = '".$tableName."' AND tableIDNum = '".$front_imagesID."'";
		$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		if($row=mysql_fetch_assoc($result)){	
		@unlink($_SERVER['DOCUMENT_ROOT'].$row['filePath']);
			$sql="DELETE FROM files WHERE filesID = '".toSQL($_REQUEST["deleteFile"])."' AND tableName = '".$tableName."' AND tableIDNum = '".$front_imagesID."'";
			mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		}
	}

	//mandatory fields
	if($front_imagesID==""){$front_imagesID="0";}
	if($image_title==""){$err.="<li>image_title</il> ";}
	
	//So message will be written to DB
	if($err=="")
	{			
		if($front_imagesID=="0"){
			$sSQLcat="SELECT MAX(position) AS maxID FROM ".$tableName."";
			$max_position=0;
			$resultcat=mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
			if ($row = mysql_fetch_array($resultcat)){$max_position=$row['maxID']+1;}
			
			$sSQL="INSERT INTO ".$tableName."(dateCreated, position)
								VALUES(NOW(), '".$max_position."')";
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
			$front_imagesID=mysql_insert_id();
		} 
		
		$addImageSQL=""; 
		if(!empty($_FILES["File"]['name'])){
//			$sSQL="INSERT INTO front_images (
//										created,
//										tableName,
//										tableID,
//										tableIDNum
//									) VALUES (
//										NOW(),
//										'".$tableName."',
//										'".$tableID."',
//										'".$front_imagesID."')";
//				$uploads_result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
				
			//$front_imagesID=mysql_insert_id();
			$pictpath=uplPictures($_FILES["File"],
									"/dat/front_images/".$front_imagesID.".jpg",
									"262",
									"660",
									"4048000",
									"1",
									"",
									"");
			list($ww, $hh)=@getimagesize($_SERVER['DOCUMENT_ROOT']."/dat/front_images/".$front_imagesID.".jpg");
		//470 350
			if($hh>51){$ww=(int)($ww*51/$hh);$hh=51; }
			if($ww<50){$hh=(int)($hh*50/$ww);$ww=50; }
			$pictpath_s=uplPictures($_FILES["File"],
									"/dat/front_images/".$front_imagesID."_s.jpg",
									"$hh",
									"$ww",
									"4048000",
									"1",
									"",
									"1");
			//print "PP->".$pictpath;
			if($pictpath!=""){
				$addImageSQL=" filePath='/dat/front_images/".$front_imagesID.".jpg', filePath_s='/dat/front_images/".$front_imagesID."_s.jpg', "; 
//				$sSQL="UPDATE front_images SET filePath='/dat/front_images/".$front_imagesID.".jpg'
//								WHERE front_imagesID='".$front_imagesID."'";
							}
//				else{$sSQL="DELETE FROM front_images WHERE front_imagesID='".$front_imagesID."'";}
//				mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
			}
		//
//		if(!empty($_POST['ord'])){
//			foreach($_POST['ord'] as $k=>$v){
//					$sSQL="INSERT INTO front_images_order (front_imagesID, dateCreated, manufacturerID, ord) VALUES 
//						('".$front_imagesID."', NOW(), '$k', '$v') ON DUPLICATE KEY UPDATE ord='$v'";
//						//print $sSQL."<br />";
//						mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
//				}
//			
//			}
		$sSQL="UPDATE settings SET last_update=NOW() WHERE settingsID='1'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		$sSQL="UPDATE ".$tableName." SET ".$addImageSQL." 
				image_title='".toSQL($image_title)."',
				image_descr='".toSQL($image_descr)."',
				links='".toSQL($links)."'
				WHERE  ".$tableID." = '".$front_imagesID."'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		//print $sSQL;
		//uploading files
		if (!empty($_FILES['FileUpload']['name'])){
			$documentUploadFolder=$_SERVER['DOCUMENT_ROOT']."/dat/files/";
			$documentUploadFolderLink="/dat/files/";
			$fileTitle=(!empty($_REQUEST["fileTitle"]))?str_replace("'","",$_REQUEST["fileTitle"]):"";
			
			$upload_error = (!empty($_FILES["FileUpload"]['error']))?str_replace("'","`",$_FILES["FileUpload"]['error']):"";
			$upload_name = (!empty($_FILES["FileUpload"]['name']))?str_replace("'","`",$_FILES["FileUpload"]['name']):"";
			$upload_tmp_name = (!empty($_FILES["FileUpload"]['tmp_name']))?str_replace("'","`",$_FILES["FileUpload"]['tmp_name']):"";
			$upload_type = (!empty($_FILES["FileUpload"]['type']))?str_replace("'","`",$_FILES["FileUpload"]['type']):"";
			$upload_size = (!empty($_FILES["FileUpload"]['size']))?str_replace("'","`",$_FILES["FileUpload"]['size']):"";
			//print $upload_tmp_name;
			if($upload_error == UPLOAD_ERR_OK) {
				$upload_extention = strtolower(substr($upload_name, strrpos($upload_name, '.') + 1, strlen($upload_name)));
				$arrAllow=array("jpeg", "jpg", "png", "gif", "pdf", "doc", "docx");//, "BMP", "TIFF"
				if (!in_array($upload_extention, $arrAllow)) { 
					$err.= "Uploaded File extension should be .jpg, .jpeg, .png, .pdf, .doc, .docx or .gif<br />";
					$sSQL="DELETE FROM files WHERE filesID='".$filesID."' AND tableName='".$tableName."' AND tableIDNum='".$front_imagesID."'";
				
					$uploads_result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
				}
				else{
					$sSQL="INSERT INTO files(
											dateCreated,
											tableName,
											tableID,
											tableIDNum
										) VALUES (
											NOW(),
											'".$tableName."',
											'".$tableID."',
											'".$front_imagesID."')";
					$uploads_result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
					
					$filesID=mysql_insert_id();
					$documentUploadFolderLink=$documentUploadFolderLink.$filesID.".".$upload_extention;
					$sSQL="UPDATE files SET
										fileTitle='".$fileTitle."',
										filePath='".$documentUploadFolderLink."',
										fileSize='".$upload_size."',
										fileExt='".$upload_extention."'
									WHERE filesID='".$filesID."'
										AND tableName='".$tableName."'
										AND tableIDNum='".$front_imagesID."'";
				
					$uploads_result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
			
		
					if(!file_exists($documentUploadFolder)) {
						mkdir($documentUploadFolder, 0777, true);
					}
					move_uploaded_file($upload_tmp_name, $documentUploadFolder.$filesID.".".$upload_extention);
					}
				//echo $documentUploadFolder.$filesID.".".$upload_extention;
			}
		}
		
		$mess="Information Saved.";
	}
	if (!empty($err)) {
		$err="<u>The following is required</u>:<ul>".$err."</ul>";
	}
}
if (!empty($front_imagesID)) {
	$sql="SELECT * FROM ".$tableName." WHERE  ".$tableID." = '".$front_imagesID."'";
	$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
	$row=mysql_fetch_assoc($result);
	foreach ($row as $k=>$v) $$k=$v;
}
?><link rel="stylesheet" type="text/css" media="all" href="/inc/calendar/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="/inc/calendar/calendar.js"></script>
<script type="text/javascript" src="/inc/calendar/calendar-en.js"></script>
<script type="text/javascript" src="/inc/calendar/calendar-setup.js"></script><FORM id="ff1" name="ff1" action="" method="post" enctype="multipart/form-data" >
<input type="hidden" name="process" id="process" value="1">
<input type="hidden" name="deleteFile" id="deleteFile" value="">
<input type="hidden" name="deleteImage" id="deleteImage" value="">
<input type="hidden" name="processSafeForm" id="processSafeForm" value="<?=$_SESSION["processSafe"]?>" />
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td valign="top" class="content">
<table border="0" cellspacing="0" cellpadding="0" class="text" width="100%">
<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pageTabs">
<tr><td width="2" background="/adm/img/tabs_bottom_bg_left.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
<td width="200" class="arial20_Grey" align="center"><?=$pageTitle?> <img src="/adm/img/arrowGreyRightBig.jpg" width="5" height="9" align="absmiddle" /></td>
<td align="left"><table border="0" cellspacing="0" cellpadding="0" height="35">
<tr><td width="1" background="/adm/img/tabs_bg_right.jpg"></td>
<td background="/adm/img/tabs_bg.jpg"><a href="/adm/<?=$folderName?>/?searchFromSession=1" class="tabLink">View All</a></td>
                          <td width="4" background="/adm/img/tabs_active_bg_right_tr.jpg"></td>
                          <td width="2" background="/adm/img/tabs_active_bg_left.jpg"></td>
                          <td background="/adm/img/tabs_active_bg.jpg"><a href="/adm/<?=$folderName?>/addEdit.php" class="tabLink">Add New</a></td>
                          <td width="4" background="/adm/img/tabs_active_bg_right_tr.jpg"></td></tr></table></td>
<td width="2" background="/adm/img/tabs_bottom_bg_right.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td></tr></table></td></tr>
<tr><td class="contentCell"><table border="0" cellspacing="0" cellpadding="3" class="text" width="100%"><tr><td>&nbsp;</td><td>&nbsp;</td></tr><?
    if (!empty($err)) { ?><tr><td colspan="2" class="err"><?=$err?></td></tr><?    } 
    if (!empty($mess)) { ?><tr><td colspan="2" class="message"><?=$mess?></td></tr><?    }
?><tr><td align="right">Title: </td>
<td><input type="text" name="image_title" id="image_title" value="<?=$image_title?>" class="inputField" style="width:500px;" /></td></tr>
<tr><td align="right">Links: </td>
<td><input type="text" name="links" id="links" value="<?=$links?>" class="inputField" style="width:500px;" /></td></tr>
<tr><td align="right">Description: </td>
<td><textarea type="text" name="image_descr" id="image_descr" class="inputField" style="width:500px; height:70px;"><?=$image_descr?></textarea></td></tr>
<?php /*?><tr><td align="right">Date:&nbsp;</td>
<td><i><span id="dateCreatedDisplay"><?=(!empty($dateCreated) && $dateCreated!="0000-00-00")?date("F d, Y", strtotime($dateCreated)):date("F d, Y")?></span></i>&nbsp;	
    <input type="hidden" name="dateCreated" id="dateCreated" value="<?=(!empty($dateCreated) && $dateCreated!="0000-00-00")?date("Y-m-d",strtotime($dateCreated)):date("Y-m-d")?>" />
    <input type="hidden" name="dateCreatedaa" id="dateCreatedaa" value="dateCreatedIsHere" />
<a href="#" id="calfrom"><img src="/adm/img/calendar.gif" border="0" align="absmiddle" /></a>
<script type="text/javascript">
Calendar.setup({
    checkField     :    "dateCreatedaa",     // id of the input field
    inputField     :    "dateCreated",     // id of the input field
    displayArea    :    "dateCreatedDisplay",
    ifFormat       :    "%Y-%m-%d",  // format of the input field
    daFormat       :    "%B %d, %Y",
    timeFormat     :    "24",     
    showsTime      :    false,       // will display a time selector
    button         :    "calfrom",   // trigger for the calendar (button ID)
    singleClick    :    true            // double-click mode
});
</script></td></tr><?php */?>
    <tr>
      <td width="130px" align="right" valign="top">Picture: (660x262)</td>
      <td align="left" colspan="3" valign="top"><input type="file" name="File" id="File" /></td></tr><? 
	  	$sSQL="SELECT * FROM front_images WHERE front_imagesID='".$front_imagesID."'";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
		?><tr><td align="left" colspan="4"><?
    while($row=mysql_fetch_assoc($result)) {?><div style="float:left; border:#999 1px solid; margin:5px;">
		<img src="<?=$row['filePath']?>?ttt=<?=mktime()?>" /><br />
		<img src="<?=$row['filePath_s']?>?ttt=<?=mktime()?>" /><br /><?php /*?>
<a href="#" style="color:#F00;" onclick="deleteImages(<?=$row['front_imagesID']?>); return false;">delete</a><?php */?></div><?
     }?></td></tr>
    <tr><td colspan="2"><hr /></td></tr>
<?php /*?>    <tr><td width="130px" align="right" valign="top">Upload File: </td>
      <td align="left" colspan="3"><input type="file" name="FileUpload" id="FileUpload" /></td></tr>
     <tr><td width="130px" align="right">File Title: </td>
      <td align="left"><input type="text" name="fileTitle" id="fileTitle" value="" class="inputField" style="width:200px;" /></td></tr><?php */?>
<tr><td height="40" colspan="2" align="center" valign="middle"><input type="image" src="/adm/img/btn_save.png" accesskey="s"/>
<input type="hidden" name="front_imagesID" id="front_imagesID" value="<?=$front_imagesID?>" />&nbsp;</td></tr>
</table></td></tr></table></td></tr></table></FORM>
<script type="text/javascript" language="javascript">
function deleteFiles(id){
	if(confirm(" File will be deleted permanenty please confirm. "))	{
		document.getElementById('deleteFile').value = id; 
		document.getElementById('ff1').submit();
	}}
function deleteImages(id){
	if(confirm(" File will be deleted permanenty please confirm. "))	{
		document.getElementById('deleteImage').value = id; 
		document.getElementById('ff1').submit();
	}}
</script><?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/footer.php";
?>