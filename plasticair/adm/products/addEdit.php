<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";

$tableName="products";
$tableID="productsID";
$folderName="products";
$pageTitle="Products";
$filesPath="/dat/products/";

$sSQL="SELECT * FROM product_category ORDER BY position ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
$arr_parents=array();$arr_product_category=array();
while($row1=mysql_fetch_array($result1)){$arr_parents[$row1['parentID']][$row1['product_categoryID']]=$row1['title'];
		$arr_product_category[$row1['product_categoryID']]=$row1['title'];}
		
$$tableID=(!empty($_REQUEST[$tableID]))?str_replace("'","",$_REQUEST[$tableID]):"0";
$productsID=(!empty($_REQUEST["productsID"]))?addslashes($_REQUEST["productsID"]):"";
$title=(!empty($_REQUEST["title"]))?str_replace("'", "", $_REQUEST["title"]):"";
$price=(!empty($_REQUEST["price"]))?str_replace("'", "", $_REQUEST["price"]):"";
$product_categoryID=(!empty($_POST["product_categoryID"]))?($_POST["product_categoryID"]):"";
$description=(!empty($_REQUEST["description"]))?addslashes($_REQUEST["description"]):"";
$specifications=(!empty($_REQUEST["specifications"]))?str_replace("'", "", $_REQUEST["specifications"]):"";
$parts=(!empty($_REQUEST["parts"]))?str_replace("'", "", $_REQUEST["parts"]):"";
$headline=(!empty($_REQUEST["headline"]))?addslashes($_REQUEST["headline"]):"";
$promotion=(!empty($_REQUEST["promotion"]))?str_replace("'", "", $_REQUEST["promotion"]):"";
$product_categoryID=(!empty($_REQUEST["product_categoryID"]))?str_replace("'", "", $_REQUEST["product_categoryID"]):"";
$processSafeForm=(!empty($_REQUEST["processSafeForm"]))?$_REQUEST["processSafeForm"]:"";
$_SESSION["processSafe"]=(empty($_SESSION["processSafe"]))?"1":$_SESSION["processSafe"];

$err="";
$mess="";

if(!empty($_REQUEST["process"]) && !empty($processSafeForm) && $_SESSION["processSafe"]==$processSafeForm) {

	$_SESSION["processSafe"]++;

	if(!empty($_REQUEST["deleteImage"]) && !empty($$tableID)){
		$sql="SELECT * FROM site_images WHERE site_imagesID = '".str_replace("'", "", $_REQUEST["deleteImage"])."' AND tableName = '".$tableName."' AND tableIDNum = '".$$tableID."'";
		$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		if($row=mysql_fetch_assoc($result)){	
		@unlink($_SERVER['DOCUMENT_ROOT'].$row['filePath']);
		@unlink($_SERVER['DOCUMENT_ROOT'].$row['filePath_s']);
			$sql="DELETE FROM site_images WHERE site_imagesID = '".str_replace("'", "", $_REQUEST["deleteImage"])."' AND tableName = '".$tableName."' AND tableIDNum = '".$$tableID."'";
			mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		}
	}
	if(!empty($_REQUEST["deleteFile"]) && !empty($$tableID)){
		$sql="SELECT * FROM files WHERE filesID = '".str_replace("'", "", $_REQUEST["deleteFile"])."' AND tableName = '".$tableName."' AND tableIDNum = '".$$tableID."'";
		$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		if($row=mysql_fetch_assoc($result)){	
		@unlink($_SERVER['DOCUMENT_ROOT'].$row['filePath']);
			$sql="DELETE FROM files WHERE filesID = '".str_replace("'", "", $_REQUEST["deleteFile"])."' AND tableName = '".$tableName."' AND tableIDNum = '".$$tableID."'";
			mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		}
	}

	//mandatory fields
	if($productsID==""){$$tableID="0";}
	if($title==""){$err.="<li>Title</il> ";}
	
	//So message will be written to DB
	if($err=="")
	{			
		if($$tableID=="0")
		{
			$sSQLcat="SELECT MAX(position) AS maxID FROM ".$tableName."";
			$max_position=0;
			$resultcat=mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
			if ($row = mysql_fetch_array($resultcat)){$max_position=$row['maxID']+1;}
			
			$sSQL="INSERT INTO ".$tableName."(dateCreated, position) VALUES (NOW(), '".$max_position."')";
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
			$pictpath=uplPictures($_FILES["File"],
									"/dat/products/".$site_imagesID.".jpg",
									"900",
									"900",
									"4048000",
									"1",
									"",
									"");
			$pictpath=uplPictures($_FILES["File"],
									"/dat/products/".$site_imagesID."_m.jpg",
									"180",
									"230",
									"4048000",
									"1",
									"",
									"");
			$pictpath=uplPictures($_FILES["File"],
									"/dat/products/".$site_imagesID."_s.jpg",
									"125",
									"90",
									"4048000",
									"1",
									"",
									"1");
			//print "PP->".$pictpath;
			if($pictpath!=""){
				$sSQL="UPDATE site_images SET filePath='/dat/products/".$site_imagesID.".jpg',
								filePath_m='/dat/products/".$site_imagesID."_m.jpg',
								filePath_s='/dat/products/".$site_imagesID."_s.jpg'
								WHERE site_imagesID='".$site_imagesID."'";}
				else{$sSQL="DELETE FROM site_images WHERE site_imagesID='".$site_imagesID."'";}
				mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
			}
		//
//		if(!empty($_POST['ord'])){
//			foreach($_POST['ord'] as $k=>$v){
//					$sSQL="INSERT INTO products_order (productsID, dateCreated, product_categoryID, ord) VALUES 
//						('".$$tableID."', NOW(), '$k', '$v') ON DUPLICATE KEY UPDATE ord='$v'";
//						//print $sSQL."<br />";
//						mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
//				}
//			
//			}//price='".($price)."',clearance='".($clearance)."',promotion='".($promotion)."',
		$sSQL="UPDATE ".$tableName." SET ".$addImageSQL." 
				title='".toSQL($title)."',
				product_categoryID='".toSQL($product_categoryID)."',
				description='".($description)."',
				headline='".($headline)."',
				specifications='".($specifications)."',
				price='".($price)."'
				WHERE  ".$tableID." = '".$$tableID."'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		//print $sSQL;
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
				$arrAllow=array("jpeg", "jpg", "png", "gif", "pdf", "doc", "docx");//, "BMP", "TIFF"
				if (!in_array($upload_extention, $arrAllow)) { 
					$err.= "Uploaded File extension should be .jpg, .jpeg, .png, .pdf, .doc, .docx or .gif<br />";
					$sSQL="DELETE FROM files WHERE filesID='".$filesID."' AND tableName='".$tableName."' AND tableIDNum='".$$tableID."'";
				
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
											'".$$tableID."')";
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
										AND tableIDNum='".$$tableID."'";
				
					$uploads_result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
			
		
					if(!file_exists($documentUploadFolder)) {
						mkdir($documentUploadFolder, 0777, true);
					}
					move_uploaded_file($upload_tmp_name, $documentUploadFolder.$filesID.".".$upload_extention);
					}
				//echo $documentUploadFolder.$filesID.".".$upload_extention;
			}
		}
		
		$sSQL="DELETE FROM products_industry WHERE productsID='".$$tableID."'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
		if(!empty($_POST['industryID'])){
			foreach($_POST['industryID'] as $v){
				$sSQL="INSERT INTO products_industry (productsID, industryID, dateCreated) VALUES ('".$$tableID."', '".str_replace("'", "", $v)."', NOW())";
				//echo $sSQL."<br />";
				mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
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
$categories=empty($categories)?'':$categories;
?><FORM id="ff1" name="ff1" action="" method="post" enctype="multipart/form-data" >
<input type="hidden" name="process" id="process" value="1">
<input type="hidden" name="deleteFile" id="deleteFile" value="">
<input type="hidden" name="deleteFeatured" id="deleteFeatured" value="">
<input type="hidden" name="deleteDimension" id="deleteDimension" value="">
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
<td width="4" background="/adm/img/tabs_active_bg_right_tr.jpg"></td>
<td width="4" background="/adm/img/tabs_active_bg_right_tr.jpg"></td>
<td background="/adm/img/tabs_bg.jpg"><a href="/adm/<?=$folderName?>/sorting.php" class="tabLink">Sorting</a></td>
<td width="1" background="/adm/img/tabs_bg_right.jpg"></td></tr></table></td>
<td width="2" background="/adm/img/tabs_bottom_bg_right.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td></tr></table></td></tr>
<tr><td class="contentCell"><table border="0" cellspacing="0" cellpadding="3" class="text" width="100%"><tr><td>&nbsp;</td><td>&nbsp;</td></tr><?
    if (!empty($err)) { ?><tr><td colspan="2" class="err"><?=$err?></td></tr><?    } 
    if (!empty($mess)) { ?><tr><td colspan="2" class="message"><?=$mess?></td></tr><?    }
    ?><tr><td align="right">Category</td><td><select name="product_categoryID" id="product_categoryID" class="inputField" style="width:200px;"><option value="">Select Category</option><?
foreach($arr_parents[0] as $k=>$v){
		?><option value="<?=$k?>" <?=$product_categoryID==$k?"selected='selected'":""?> ><?=$v?></option><?
		if(!empty($arr_parents[$k])){ 
			foreach($arr_parents[$k] as $kk=>$vv){
				?><option value="<?=$kk?>" <?=$product_categoryID==$kk?"selected='selected'":""?> >-- <?=$vv?></option><?
				}
	}}?></select></td></tr>
<?php /*?><tr valign="top"><td align="right">Category</td><td><table border="0" cellspacing="1" cellpadding="2" class="text"><tr valign="top"><?
$sSQL="SELECT * FROM category ORDER BY position ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
$arr_parents=array();
while($row1=mysql_fetch_array($result1)){$arr_parents[$row1['parentID']][$row1['categoryID']]=$row1['title'];
$categoryID=empty($categoryID)?$row1['categoryID']:$categoryID;}
	
	//$sql="SELECT c.* FROM category c ORDER BY c.title";
	//$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
	$nn=0;
	//while($row=mysql_fetch_assoc($result)){
	foreach($arr_parents[0] as $k=>$v){$nn++;
		?><td style="border:#CCC 1px solid;"><input type="checkbox" name="categoryID[]" value="<?=$k?>" <?=(strpos(",,".$categories.",", ",".$k.","))?' checked="checked"':""?> /><?=$v?><?
        if(!empty($arr_parents[$k])){
			foreach($arr_parents[$k] as $kk=>$vv){ ?><br />&nbsp;---&nbsp;<input type="checkbox" name="categoryID[]" value="<?=$kk?>" <?=(strpos(",,".$categories.",", ",".$kk.","))?' checked="checked"':""?> /><?=$vv?><? }
			}
		?></td><? 
		if($nn>=5){ ?></tr><tr valign='top'><? $nn=0;}
     }
	?></tr></table></td></tr><?php */?>

<tr><td align="right">Title: </td>
<td><input type="text" name="title" id="title" value="<?=$title?>" class="inputField" style="width:400px;" /></td></tr>

<tr><td align="right">Headline: </td>
<td><textarea name="headline" id="headline" class="inputField" style="width:400px;height: 60px;"><?=stripslashes($headline)?></textarea></td></tr>

<tr><td align="right">Price: </td>
<td><input type="text" name="price" id="price" value="<?=$price?>" class="inputField" style="width:200px;" /></td></tr>

<tr><td valign="top" align="right">Description: </td><td><?
$oFCKeditor = new FCKeditor('description') ;
$oFCKeditor->BasePath = '/inc/fckeditor/' ;
$oFCKeditor->Width = '800' ;
$oFCKeditor->Height = '200' ;
$oFCKeditor->ToolbarSet = 'Default' ;
$oFCKeditor->Value = stripslashes($description);
$oFCKeditor->Create() ;
?></td></tr>

<tr><td valign="top" align="right">Specifications: </td><td><?
$oFCKeditor = new FCKeditor('specifications') ;
$oFCKeditor->BasePath = '/inc/fckeditor/' ;
$oFCKeditor->Width = '800' ;
$oFCKeditor->Height = '200' ;
$oFCKeditor->ToolbarSet = 'Default' ;
$oFCKeditor->Value = stripslashes($specifications);
$oFCKeditor->Create() ;
?></td></tr>

<tr><td colspan="2"><hr /></td></tr><?
$sSQL="SELECT i.title, i.industryID, p.productsID FROM industry i LEFT JOIN products_industry p ON (p.industryID=i.industryID AND p.productsID='".$$tableID."') ORDER BY i.title";
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
?><tr><td colspan="4"><div>This product uses in next industries:</div><table border="0" cellspacing="0" cellpadding="0" class="text"><tr><?
$nn=0;
while($row=mysql_fetch_assoc($result)) {
	$nn++;
	?><td><input type="checkbox" name="industryID[]" value="<?=$row['industryID']?>"<?=empty($row['productsID'])?'':' checked="checked"'?> /><?=$row['title']?>&nbsp;&nbsp;</td><?
	if($nn>=4){ ?></tr><tr><? $nn=0;}}
?></tr></table></td></tr><tr><td colspan="2"><hr /></td></tr>

<tr><td width="130px" align="right" valign="top">Picture: <br />
(721x721 max)</td>
<td align="left" colspan="3" valign="top"><input type="file" name="File" id="File" /></td></tr><? 
	  	$sSQL="SELECT * FROM site_images 
	  		WHERE tableName='".$tableName."' AND tableID='".$tableID."' AND tableIDNum='".$$tableID."'";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
		?><tr><td align="left" colspan="4"><?
    while($row=mysql_fetch_assoc($result)) {?><div style="float:left; border:#999 1px solid; margin:5px;">
		<img src="<?=$row['filePath_s']?>" /><br />
<a href="#" style="color:#F00;" onclick="deleteImages(<?=$row['site_imagesID']?>); return false;">delete</a></div><?
     }?></td></tr>
<tr><td colspan="2"><hr /></td></tr>
<tr><td width="130px" align="right" valign="top">Upload File: </td>
<td align="left" colspan="3"><input type="file" name="FileUpload" id="FileUpload" /></td></tr>
<tr><td width="130px" align="right">File Title: </td>
<td align="left"><input type="text" name="fileTitle" id="fileTitle" value="" class="inputField" style="width:200px;" /></td></tr><? 
if(!empty($$tableID)) {?><tr>
<td width="130px" align="right" valign="top">Uploaded Files: </td>
<td align="left" colspan="3">
<table cellpadding="3" cellspacing="0" class="text" border="1">
<tr><td style="min-width:100px;"><b>Name</b></td>
<td style="min-width:100px;"><b>Link</b></td>
<td align="center"><b>Options</b></td></tr><?
       $sql="SELECT * FROM files 
                    WHERE tableName = '".$tableName."' 
                    AND tableID = '".$tableID."' 
                    AND tableIDNum = '".$$tableID."'";
        $result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
        while($row=mysql_fetch_assoc($result)){ 
			?><tr><td align="left"><?=$row['fileTitle']?></td>
        <td align="left"><a style="color:#666" href="<?=$row['filePath']?>" target="_blank"><?=$row['filePath']?></a></td>
        <td align="center"><a href="#" style="color:#F00;" onclick="deleteFiles(<?=$row['filesID']?>); return false;">DEL</a></td></tr><?
       }?></table></td></tr><?
     }?><tr> 
      <td height="40" colspan="2" align="center" valign="middle"><input type="image" src="/adm/img/btn_save.png" accesskey="s"onclick="if(document.ff1.FileUpload.value!='' && document.ff1.fileTitle.value==''){alert('Enter File Title please');return false;}" />
      <input type="hidden" name="<?=$tableID?>" id="<?=$tableID?>" value="<?=$$tableID?>" />&nbsp;</td>
</tr></table></td>
</tr></table></td></tr></table>
</FORM>
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
function del_dimention(id){
	if(confirm("Information will be deleted permanenty please confirm. "))	{
		document.getElementById('deleteDimension').value = id; 
		document.getElementById('ff1').submit();
	}}
function del_feature(id){
	if(confirm("Information will be deleted permanenty please confirm. "))	{
		document.getElementById('deleteFeatured').value = id; 
		document.getElementById('ff1').submit();
	}}
</script><?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/footer.php";
?>