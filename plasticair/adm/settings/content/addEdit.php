<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";

$tableName="content";
$tableID="contentID";
$folderName="settings/content";
$pageTitle="Content";
$filesPath="/dat/cat/";

$$tableID=(!empty($_REQUEST[$tableID]))?str_replace("'","",$_REQUEST[$tableID]):"0";
$contentID=(!empty($_REQUEST["contentID"]))?str_replace("'","",$_REQUEST["contentID"]):"";
$title=(!empty($_REQUEST["title"]))?str_replace("'","",$_REQUEST["title"]):"";
$customLink=(!empty($_REQUEST["customLink"]))?str_replace("'","",$_REQUEST["customLink"]):"";
$headline=(!empty($_REQUEST["headline"]))?str_replace("'","",$_REQUEST["headline"]):"";
$description=(!empty($_REQUEST["description"]))?addslashes($_REQUEST["description"]):"";
$processSafeForm=(!empty($_REQUEST["processSafeForm"]))?$_REQUEST["processSafeForm"]:"";
$_SESSION["processSafe"]=(empty($_SESSION["processSafe"]))?"1":$_SESSION["processSafe"];

$err="";
$mess="";

if(!empty($_REQUEST["process"]) && !empty($processSafeForm) && $_SESSION["processSafe"]==$processSafeForm) {

	$_SESSION["processSafe"]++;


	if(!empty($_REQUEST["deleteImage"]) && !empty($$tableID)){
		$sql="SELECT * FROM site_images WHERE site_imagesID = '".addslashes($_REQUEST["deleteImage"])."' AND tableName = '".$tableName."' AND tableIDNum = '".$$tableID."'";
		$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		if($row=mysql_fetch_assoc($result)){	
		@unlink($_SERVER['DOCUMENT_ROOT'].$row['filePath']);
			$sql="DELETE FROM site_images WHERE site_imagesID = '".addslashes($_REQUEST["deleteImage"])."' AND tableName = '".$tableName."' AND tableIDNum = '".$$tableID."'";
			mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		}
	}
	if(!empty($_REQUEST["deleteFile"]) && !empty($$tableID)){
		$sql="SELECT * FROM files WHERE filesID = '".addslashes($_REQUEST["deleteFile"])."' AND tableName = '".$tableName."' AND tableIDNum = '".$$tableID."'";
		$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		if($row=mysql_fetch_assoc($result)){	
		@unlink($_SERVER['DOCUMENT_ROOT'].$row['filePath']);
			$sql="DELETE FROM files WHERE filesID = '".addslashes($_REQUEST["deleteFile"])."' AND tableName = '".$tableName."' AND tableIDNum = '".$$tableID."'";
			mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		}
	} 
	//mandatory fields
	if($contentID==""){$$tableID="0";}
	if($title==""){$err.="<li>Title</il> ";}
	
	//So message will be written to DB
	if($err=="")
	{			
		if($$tableID=="0")
		{
			$sSQL="INSERT INTO ".$tableName."(dateCreated)
								VALUES(NOW())";
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
			$$tableID=mysql_insert_id();
		}
		
		$addImageSQL=""; 
		if(!empty($_FILES["File"]['name'])){
			$sSQL="INSERT INTO site_images (
										dateCreated,
										tableName,
										tableID,
										tableIDNum
									) VALUES (
										NOW(),
										'".$tableName."',
										'".$tableID."',
										'".$$tableID."')";
				$uploads_result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
				
				$site_imagesID=mysql_insert_id();
				$pictpath_s=uplPictures($_FILES["File"], "/dat/content/".$site_imagesID."_s.jpg", "94", "94", "4048000", "1", "", "0");
				$pictpath=uplPictures($_FILES["File"], "/dat/content/".$site_imagesID.".jpg", "400", "400", "4048000", "1", "", "1");
				if($pictpath!=""){	$addImageSQL="picture='".$pictpath."', picture_s='".$pictpath_s."', ";}//
					//print "PP->".$pictpath;
				if($pictpath!=""){
					$sSQL="UPDATE site_images SET filePath='/dat/content/".$site_imagesID.".jpg',
							filePath_s='/dat/content/".$site_imagesID."_s.jpg'
								WHERE site_imagesID='".$site_imagesID."'";}
				else{$sSQL="DELETE FROM site_images WHERE site_imagesID='".$site_imagesID."'";}
				mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
			}
		
		$sSQL="UPDATE settings SET last_update=NOW() WHERE settingsID='1'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		$sSQL="UPDATE ".$tableName." SET ".$addImageSQL." 
						title='".$title."',
						headline='".$headline."',
						customLink='".$customLink."',
						description='".$description."'
					    WHERE  ".$tableID." = '".$$tableID."'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		
		$sSQL="UPDATE site_images SET main_img='0' WHERE tableName='".$tableName."' AND tableID='".$tableID."' AND tableIDNum='".$$tableID."'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
		
		if(!empty($_POST['main_img'])){$sSQL="UPDATE site_images SET main_img='1' WHERE tableName='".$tableName."' AND tableID='".$tableID."' AND tableIDNum='".$$tableID."' AND site_imagesID='".str_replace("'", "", $_POST['main_img'])."'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);}
		
		//uploading files
		if (!empty($_FILES['FileUpload']['name']))
		{
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
				
				$uploads_sql="INSERT INTO files(
										dateCreated,
										tableName,
										tableID,
										tableIDNum
									) VALUES (
										NOW(),
										'".$tableName."',
										'".$tableID."',
										'".$$tableID."')";
				$uploads_result=mysql_query($uploads_sql) or die ("MySQL err: ".mysql_error()."<br/>".$uploads_sql);
				
				$filesID=mysql_insert_id();
				$documentUploadFolderLink=$documentUploadFolderLink.$filesID.".".$upload_extention;
				$uploads_sql="UPDATE files SET
									fileTitle='".$fileTitle."',
									filePath='".$documentUploadFolderLink."',
									fileSize='".$upload_size."',
									fileExt='".$upload_extention."'
								WHERE filesID='".$filesID."'
									AND tableName='".$tableName."'
									AND tableIDNum='".$$tableID."'";
			
				$uploads_result=mysql_query($uploads_sql) or die ("MySQL err: ".mysql_error()."<br/>".$uploads_sql);
		
	
				if(!file_exists($documentUploadFolder)) {
					mkdir($documentUploadFolder, 0777, true);
				}
				move_uploaded_file($upload_tmp_name, $documentUploadFolder.$filesID.".".$upload_extention);
				//echo $documentUploadFolder.$filesID.".".$upload_extention;
			}
		}
		
		$mess="Information Saved.";
	}
	if (!empty($err)) {
		$err="<u>The following is required</u>:<ul>".$err."</ul>";
	}
}
if (!empty($$tableID)) {
	$sql="SELECT * FROM ".$tableName." WHERE  ".$tableID." = '".$$tableID."'";
	$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
	$row=mysql_fetch_assoc($result);
	foreach ($row as $k=>$v) $$k=$v;
}
?>
<FORM id="ff1" name="ff1" action="" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="process" id="process" value="1">
        <input type="hidden" name="deleteFile" id="deleteFile" value="">
        <input type="hidden" name="deleteImage" id="deleteImage" value="">
		<input type="hidden" name="processSafeForm" id="processSafeForm" value="<?=$_SESSION["processSafe"]?>" />
		<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" class="content">
              <table border="0" cellspacing="0" cellpadding="0" class="text" width="100%">
				  <tr>
					<td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pageTabs">
                    <tr>
                      <td width="2" background="/adm/img/tabs_bottom_bg_left.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                      <td width="200" class="arial20_Grey" align="center"><?=$pageTitle?> <img src="/adm/img/arrowGreyRightBig.jpg" width="5" height="9" align="absmiddle" /></td>
                      <td align="left"><table border="0" cellspacing="0" cellpadding="0" height="35">
                        <tr>
                          <td width="1" background="/adm/img/tabs_bg_right.jpg"></td>
                          <td background="/adm/img/tabs_bg.jpg"><a href="/adm/<?=$folderName?>/?searchFromSession=1" class="tabLink">Content Tree</a></td>
                          <td width="1" background="/adm/img/tabs_bg_right.jpg"></td>
                        </tr>
                      </table></td>
                      <td width="2" background="/adm/img/tabs_bottom_bg_right.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                    </tr>
                  </table></td>
				  </tr>
                <tr>
                  <td class="contentCell"><table border="0" cellspacing="0" cellpadding="3" class="text" width="100%">			  
<tr><td>&nbsp;</td><td>&nbsp;</td></tr><?
if (!empty($err)) { ?><tr><td colspan="2" class="err"><?=$err?></td></tr><? } 
if (!empty($mess)) { ?><tr><td colspan="2" class="message"><?=$mess?></td></tr><? }
?><tr><td align="right">Title : </td>
<td><input type="text" name="title" id="title" value="<?=$title?>" class="inputField" style="width:400px;" /></td></tr><?
if($contentID==6){?><tr><td align="right">Link: </td>
<td><input type="text" name="customLink" id="customLink" value="<?=$customLink?>" class="inputField" style="width:400px;" /></td></tr><? } ?>
<?php /*?><tr><td align="right">Headline: </td>
<td><textarea type="text" name="headline" id="headline" class="inputField" style="width:400px; height:50px"><?=$headline?></textarea></td></tr><?php */?>
<tr><td valign="top" align="right">Description : </td>
<td><?
						$oFCKeditor = new FCKeditor('description') ;
						$oFCKeditor->BasePath = '/inc/fckeditor/' ;
						$oFCKeditor->Width = '800' ;
						$oFCKeditor->Height = '400' ;
						$oFCKeditor->ToolbarSet = 'Default' ;
						$oFCKeditor->Value = stripslashes($description);
						$oFCKeditor->Create() ;
?></td></tr><tr>
<td width="130px" align="right" valign="top">Picture: <br />(721x721 max)</td>
<td align="left" colspan="3" valign="top"><input type="file" name="File" id="File" /></td></tr><? 
	  	$sSQL="SELECT * FROM site_images 
	  		WHERE tableName='".$tableName."' AND tableID='".$tableID."' AND tableIDNum='".$$tableID."'";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
		?><tr><td align="left" colspan="4"><?
    while($row=mysql_fetch_assoc($result)) {?><div style="float:left; border:#999 1px solid; margin:5px;"><input type="radio" value="<?=$row['site_imagesID']?>" name="main_img"<?=empty($row['main_img'])?'':' checked="checked"'?> /> Main Image<br />
		<img src="<?=$row['filePath']?>" /><br /><img src="<?=$row['filePath_s']?>" /><br />
<a href="#" style="color:#F00;" onclick="deleteImages(<?=$row['site_imagesID']?>); return false;">delete</a></div><?
     }?></td></tr><?
/*if(!empty($picture)) {?><tr><td width="130px" align="right" valign="top">&nbsp;</td><td align="left" colspan="3"><img src="<?=$picture?>" /><br />
<img src="<?=$picture_s?>" />&nbsp;<a href="#" style="color:#F00;" onclick="deleteImage(<?=$$tableID?>); return false;">DEL</a></td></tr><? }*/
?><tr><td colspan="2"><hr /></td></tr>
<tr><td width="130px" align="right" valign="top">Upload File: </td>
<td align="left" colspan="3"><input type="file" name="FileUpload" id="FileUpload" /></td></tr>
<tr><td width="130px" align="right">File Title: </td>
<td align="left"><input type="text" name="fileTitle" id="fileTitle" value="" class="inputField" style="width:200px;" /></td></tr><? 
		if(!empty($$tableID)) {
                    ?><tr><td width="130px" align="right" valign="top">Uploaded Files: </td>
					  <td align="left" colspan="3">
                      <table cellpadding="3" cellspacing="0" class="text" border="1">
                      <tr><td style="min-width:100px;"><b>Name</b></td>
                        <td style="min-width:100px;"><b>Link</b></td>
                        <td align="center"><b>Options</b></td></tr><? 
$sql="SELECT * FROM files WHERE tableName = '".$tableName."' AND tableID = '".$tableID."' AND tableIDNum = '".$$tableID."'";
$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
while($row=mysql_fetch_assoc($result)){ 
    ?><tr><td align="left"><?=$row['fileTitle']?></td>
    <td align="left"><a style="color:#666" href="<?=$row['filePath']?>" target="_blank"><?=$row['filePath']?></a><br />
<img src="<?=$row['filePath']?>" /></td>
    <td align="center"><a href="#" style="color:#F00;" onclick="deleteFiles(<?=$row['filesID']?>); return false;">DEL</a></td></tr><?  
                       }?></table></td></tr><?
                     }
?><tr><td height="40" colspan="2" align="center" valign="middle"><input type="image" src="/adm/img/btn_save.png" accesskey="s" />
<input type="hidden" name="<?=$tableID?>" id="<?=$tableID?>" value="<?=$$tableID?>" />&nbsp;</td></tr></table></td></tr>
</table></td></tr></table></FORM>
<script type="text/javascript" language="javascript">
<?php /*?>function deleteImage(id){
	if(confirm(" Image will be deleted permanenty please confirm. ")){
		document.getElementById('deleteImg').value = id; 
		document.getElementById('ff1').submit();
	}
}<?php */?>
function deleteImages(id){
	if(confirm(" File will be deleted permanenty please confirm. "))	{
		document.getElementById('deleteImage').value = id; 
		document.getElementById('ff1').submit();
	}}
function deleteFiles(id)
{
	if(confirm(" File will be deleted permanenty please confirm. "))
	{
		document.getElementById('deleteFile').value = id; 
		document.getElementById('ff1').submit();
	}
}
</script>
<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/footer.php";
?>