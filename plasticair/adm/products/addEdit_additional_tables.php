<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";

$tableName="products";
$tableID="productsID";
$folderName="products";
$pageTitle="Products";
$filesPath="/dat/products/";

$arr_dimension=array("Height Overall", "Seat Height", "Width Incl. Arms", "Weight Lbs.", "Frame size", "Seat foam", "Ctn", "Weight—lbs", "Packed", "Cube", "Fabric", "Arms");
$arr_features=array("Standard Control", "Seat Height", "Seat Angle", "Back Height", "Back Angle", "Locking Ability", "Arm Width Adjustment");

$$tableID=(!empty($_REQUEST[$tableID]))?str_replace("'","",$_REQUEST[$tableID]):"0";
$productsID=(!empty($_REQUEST["productsID"]))?addslashes($_REQUEST["productsID"]):"";
$title=(!empty($_REQUEST["title"]))?str_replace("'", "", $_REQUEST["title"]):"";
$links=(!empty($_REQUEST["links"]))?str_replace("'", "", $_REQUEST["links"]):"";
$product_categoryID=(!empty($_POST["product_categoryID"]))?($_POST["product_categoryID"]):"";
$description=(!empty($_REQUEST["description"]))?addslashes($_REQUEST["description"]):"";
$show_home=(!empty($_REQUEST["show_home"]))?str_replace("'", "", $_REQUEST["show_home"]):"";
$products=(!empty($_REQUEST["products"]))?str_replace("'", "", $_REQUEST["products"]):"";
$similar=(!empty($_REQUEST["similar"]))?str_replace("'", "", $_REQUEST["similar"]):"";
$categories=(!empty($_REQUEST["categories"]))?str_replace("'", "", $_REQUEST["categories"]):"";
$processSafeForm=(!empty($_REQUEST["processSafeForm"]))?$_REQUEST["processSafeForm"]:"";
$parts=(empty($_POST['partsID'])?'':(implode(",", $_POST['partsID'])));
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
	if(!empty($_REQUEST["delete_additional"]) && !empty($$tableID)){		
			$sSQL="DELETE FROM products_option WHERE products_optionID = '".addslashes($_REQUEST["delete_additional"])."'";
			//echo $sSQL;
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);		
		}
	if(!empty($_REQUEST["delete_additional1"]) && !empty($$tableID)){		
			$sSQL="DELETE FROM products_option1 WHERE products_option1ID = '".addslashes($_REQUEST["delete_additional1"])."'";
			//echo $sSQL;
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);		
		}
	//mandatory fields
	if($productsID==""){$$tableID="0";}
	if($title==""){$err.="<li>Title</il> ";}
	
	//So message will be written to DB
	if($err=="")
	{			
		if($$tableID=="0"){
			$sSQLcat="SELECT MAX(position) AS maxID FROM ".$tableName."";
			$max_position=0;
			$resultcat=mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
			if ($row = mysql_fetch_array($resultcat)){$max_position=$row['maxID']+1;}
			
			$sSQL="INSERT INTO ".$tableName."(dateCreated, position)
								VALUES(NOW(), '".$max_position."')";
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
			$$tableID=mysql_insert_id();
		} 
		
		
		if(!empty($_POST['_po_title'])){
			$sSQLcat="SELECT MAX(position) AS maxID FROM products_option WHERE productsID='".$$tableID."'";
			$max_position=1;
			$resultcat=mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
			if ($row = mysql_fetch_array($resultcat)){$max_position=$row['maxID']+1;}
			
			$sSQL="INSERT INTO products_option (dateCreated, po_title, productsID, position) VALUES 
				(NOW(), '".str_replace("'", "`", trim($_POST['_po_title']))."', '".$$tableID."', '$max_position')";
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
			}
		if(!empty($_POST['po_title'])){//po_bgcolor='".addslashes($_POST['po_bgcolor_'.$k])."',
			foreach($_POST['po_title'] as $k=>$v){
				$sSQL="UPDATE products_option SET 
						edited=NOW(), 
						
						po_title='".str_replace("'", "", $v)."', 
						position='".addslashes($_POST['po_position_'.$k])."',
						description='".addslashes($_POST['description_'.$k])."'
					WHERE products_optionID='".str_replace("'", "", $k)."'";
				mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);}
			}
	
			
		if(!empty($_POST['fileTitle_'])){
			foreach($_POST['fileTitle_'] as $k=>$v){
				$sSQL="UPDATE files SET fileTitle='".str_replace("'", "`", $v)."' WHERE filesID='".str_replace("'", "`", $k)."'";
				//echo $sSQL;
				mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
				}
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
									"500",
									"500",
									"4048000",
									"1",
									"",
									"");
			$pictpath=uplPictures($_FILES["File"],
									"/dat/products/".$site_imagesID."_s.jpg",
									"130",
									"130",
									"4048000",
									"1",
									"",
									"1");
			//print "PP->".$pictpath;
			if($pictpath!=""){
				$sSQL="UPDATE site_images SET filePath='/dat/products/".$site_imagesID.".jpg',
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
//			}
		$sSQL="UPDATE ".$tableName." SET ".$addImageSQL." 
				title='".toSQL($title)."',
				show_home='".(empty($show_home)?'0':'1')."',
				product_categoryID='".($product_categoryID)."',
				parts='".(empty($_POST['partsID'])?'':(implode(",", $_POST['partsID'])))."',
				description='".($description)."'
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
				$arrAllow=array("jpeg", "jpg", "png", "gif", "pdf", "doc", "docx", "wmv", "mpeg");//, "BMP", "TIFF"
				if (!in_array($upload_extention, $arrAllow)) { 
					$err.= "Uploaded File extension should be .jpg, .jpeg, .png, .pdf, .doc, .wmv, .mpeg, .docx or .gif<br />";
					//$sSQL="DELETE FROM files WHERE filesID='".$filesID."' AND tableName='".$tableName."' AND tableIDNum='".$$tableID."'";				
//					$uploads_result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
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
		


/////////////////////// start manage additional tables//////////////////////////////////////


	if(!empty($_REQUEST["delete_additional1"]) && !empty($$tableID)){		
			$sSQL="DELETE FROM products_option1 WHERE products_option1ID = '".addslashes($_REQUEST["delete_additional1"])."'";
			//echo $sSQL;
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);		
		}
	if(!empty($_REQUEST["delete_additional_delete_table"]) && !empty($$tableID)){		
			$sSQL="DELETE FROM products_option1_main WHERE products_option1_mainID = '".addslashes($_REQUEST["delete_additional_delete_table"])."'";
			//echo $sSQL;
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);		
			$sSQL="DELETE FROM products_option1 WHERE products_option1_mainID = '".addslashes($_REQUEST["delete_additional_delete_table"])."'";
			//echo $sSQL;
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);		
		}
		
		
		if(!empty($_POST['_po_title_main_'])){
			
			$sSQLcat="SELECT MAX(position) AS maxID FROM products_option1_main WHERE ".$tableID."='".$$tableID."'";
			$max_position=1;
			$result=mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
			if ($row = mysql_fetch_array($result)){$max_position=$row['maxID']+1;}
			
			$sSQL="INSERT INTO products_option1_main (dateCreated, ".$tableID.", field_qty, po_title, position) VALUES 
			(NOW(), '".$$tableID."', '".str_replace("'", "", $_POST['_field_qty_'])."', '".addslashes($_POST['_po_title_main_'])."', '$max_position')";
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
			
			
			
			}
		if(!empty($_POST['field_qty_'])){
			foreach($_POST['field_qty_'] as $k=>$v){
				$sSQL="UPDATE products_option1_main SET field_qty='".str_replace("'", "", $v)."', 
					description='".addslashes($_POST['description_'.$k])."', position='".addslashes($_POST['position_'.$k])."',
					po_title ='".addslashes($_POST['po_title_main_'.$k])."'
					WHERE products_option1_mainID='".str_replace("'", "", $k)."'";
					mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
				if(!empty($_POST['_po_title1_'.$k])){
					$sSQLcat="SELECT MAX(position) AS maxID FROM products_option1 WHERE products_option1_mainID='".str_replace("'", "", $k)."'";
					$max_position=1;
					$result=mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
					if ($row = mysql_fetch_array($result)){$max_position=$row['maxID']+1;}
					$sSQL="INSERT INTO products_option1 (products_option1_mainID, ".$tableID.", dateCreated, po_title, position) VALUES 
					('".str_replace("'", "", $k)."', '".$$tableID."', NOW(), '".addslashes($_POST['_po_title1_'.$k])."', '$max_position')";
					mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
					}
				}
			}
		
		if(!empty($_POST['po_title1'])){
			foreach($_POST['po_title1'] as $k=>$v){
				$sSQL="UPDATE products_option1 SET 
						edited=NOW(), 
						po_title='".str_replace("'", "", $v)."', 
						field_1='".(empty($_POST['field_1'][$k])?'':addslashes($_POST['field_1'][$k]))."',
						field_2='".(empty($_POST['field_2'][$k])?'':addslashes($_POST['field_2'][$k]))."',
						field_3='".(empty($_POST['field_3'][$k])?'':addslashes($_POST['field_3'][$k]))."',
						field_4='".(empty($_POST['field_4'][$k])?'':addslashes($_POST['field_4'][$k]))."',
						field_5='".(empty($_POST['field_5'][$k])?'':addslashes($_POST['field_5'][$k]))."',
						field_6='".(empty($_POST['field_6'][$k])?'':addslashes($_POST['field_6'][$k]))."',
						field_7='".(empty($_POST['field_7'][$k])?'':addslashes($_POST['field_7'][$k]))."',
						field_8='".(empty($_POST['field_8'][$k])?'':addslashes($_POST['field_8'][$k]))."',
						field_9='".(empty($_POST['field_9'][$k])?'':addslashes($_POST['field_9'][$k]))."',
						position='".addslashes($_POST['po_position1_'][$k])."'
						WHERE products_option1ID='".str_replace("'", "", $k)."'";
					mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
			}
			}
			
///////////////////////////////////// end manage additional tables ///////////////////////////////////	


	
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
?><FORM id="ff1" name="ff1" action="" method="post" enctype="multipart/form-data" >
<input type="hidden" name="process" id="process" value="1">
<input type="hidden" name="deleteFile" id="deleteFile" value="">
<input type="hidden" name="deleteFeatured" id="deleteFeatured" value="">
<input type="hidden" name="deleteDimension" id="deleteDimension" value="">
<input type="hidden" name="delete_additional" id="delete_additional" value="">
<input type="hidden" name="delete_additional_delete_table" id="delete_additional_delete_table" value="">
<input type="hidden" name="delete_additional1" id="delete_additional1" value="">
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
    ?><tr valign="top"><td align="right">Product Category</td><td><select name="product_categoryID" class="inputField"><option value="">Select Category</option><? 	  
	$sql="SELECT c.* FROM product_category c ORDER BY c.title";
	$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
	$nn=0;
	while($row=mysql_fetch_assoc($result)){$nn++;
		?><option value="<?=$row['product_categoryID']?>" <?=($product_categoryID==$row['product_categoryID'])?' selected="selected"':""?>><?=$row['title']?></option><? 
     }
	?></select></td></tr>
<tr><td align="right">Title: </td>
<td><input type="text" name="title" id="title" value="<?=$title?>" class="inputField" style="width:400px;" /></td></tr>
<tr><td align="right">Show on Home: </td>
<td><input type="checkbox" name="show_home" id="show_home" value="1"<?=empty($show_home)?'':' checked="checked"'?> /></td></tr>
<?php /*?><tr><td align="right">Links: </td>
<td><input type="text" name="links" id="links" value="<?=$links?>" class="inputField" style="width:400px;" /></td></tr>
<tr><td align="right">Price : </td>
<td><input type="text" name="price" id="price" value="<?=$price?>" class="inputField" style="width:200px;" /></td></tr><?php */?>
<tr><td valign="top" align="right">Description: </td><td><?
$oFCKeditor = new FCKeditor('description') ;
$oFCKeditor->BasePath = '/inc/fckeditor/' ;
$oFCKeditor->Width = '800' ;
$oFCKeditor->Height = '200' ;
$oFCKeditor->ToolbarSet = 'Default' ;
$oFCKeditor->Value = stripslashes($description);
$oFCKeditor->Create() ;
?></td></tr>
<tr><td colspan="2"><div><b>Parts/Accessories</b></div>
<table border="0" cellspacing="1" cellpadding="2" bgcolor="#CCCCCC"><tr bgcolor="#FFFFFF" class="text"><?
$sSQL="SELECT * FROM parts";
	$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
	$nn=0;
	while($row=mysql_fetch_assoc($result)){ $nn++;
		?><td><input type="checkbox" value="<?=$row['partsID']?>"<?=strpos(".,".$parts.",", ",".$row['partsID'].",")?' checked="checked"':''?> name="partsID[]" />&nbsp;<?=$row['title']?></td><?
		if($nn>=3){?></tr><tr bgcolor="#FFFFFF"><? $nn=0;} }
?></tr></table>


</td></tr>
<tr><td colspan="2"><hr /></td></tr>

<tr><td width="130px" align="right" valign="top">Picture: <br />
(500x500)</td>
<td align="left" colspan="3" valign="top"><input type="file" name="File" id="File" /></td></tr><? 
	  	$sSQL="SELECT * FROM site_images 
	  		WHERE tableName='".$tableName."' AND tableID='".$tableID."' AND tableIDNum='".$$tableID."'";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
		?><tr><td align="left" colspan="4"><?
    while($row=mysql_fetch_assoc($result)) {?><div style="float:left; border:#999 1px solid; margin:5px;">
		<img src="<?=$row['filePath']?>?ttt=<?=mktime()?>" /><br /><img src="<?=$row['filePath_s']?>?ttt=<?=mktime()?>" /><br />
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
			?><tr><td align="left"><input name="fileTitle_[<?=$row['filesID']?>]" id="fileTitle_[<?=$row['filesID']?>]" value="<?=$row['fileTitle']?>" class="inputField" style="width:200px;" /></td>
        <td align="left"><a style="color:#666" href="<?=$row['filePath']?>" target="_blank"><?=$row['filePath']?></a></td>
        <td align="center"><a href="#" style="color:#F00;" onclick="deleteFiles(<?=$row['filesID']?>); return false;">DEL</a></td></tr><?
       }?></table></td></tr><?
     }
?><tr><td colspan="2"><hr /></td></tr>

<tr><td bgcolor="#f0f0f0" colspan="2">Additional</td></tr><?
$sSQL="SELECT * FROM products_option WHERE productsID='".$$tableID."' ORDER BY position";
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
while($row=mysql_fetch_assoc($result)){ 
	?><tr><td align="right">Title</td><td><input name="po_title[<?=$row['products_optionID']?>]" value='<?=$row['po_title']?>' class="inputField" style="width:400px;" /></td></tr>
    <?php /*?><tr><td align="right">Background color</td><td><select name="po_bgcolor_<?=$row['products_optionID']?>" class="inputField"><option value="000000">Black</option><option<?=$row['po_bgcolor']=='ffffff'?' selected="selected"':''?> value="ffffff">White</option></select></td></tr><?php */?>
    <tr><td align="right">Order</td><td><input name="po_position_<?=$row['products_optionID']?>" value='<?=$row['position']?>' class="inputField" size='2' /></td></tr>
<tr valign="top"><td align="right" style="border-bottom:#999 1px solid;">Description<br />
<br />
<br />
<br />
<br />
<a href="#" onclick="deleteAdditional(<?=$row['products_optionID']?>); return false">delete</a></td><td style="border-bottom:#999 1px solid;"><?
	$oFCKeditor = new FCKeditor('description_'.$row['products_optionID']) ;
	$oFCKeditor->BasePath = '/inc/fckeditor/' ;
	$oFCKeditor->Width = '800' ;
	$oFCKeditor->Height = '200' ;
	$oFCKeditor->ToolbarSet = 'Default' ;
	$oFCKeditor->Value = stripslashes($row['description']) ;
	$oFCKeditor->Create() ;
?></td></tr><? }

?><tr><td>Add option</td><td><input type="text" name="_po_title" id="_po_title" value="" class="inputField" style="width:400px;" /></td></tr><?

/////////////////////////////////////////// additional tables ///////////////////////////////////////////

?><tr><td bgcolor="#f0fff0" colspan="2" style="border-top:#CCC 2px solid;">Additional Tables:</td></tr><?

$bg_table="ffffff";
$sSQL="SELECT * FROM products_option1_main WHERE productsID='".$$tableID."' ORDER BY position";
$result2=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
while($row2=mysql_fetch_assoc($result2)){
	?><tr><td colspan="2" bgcolor="#<?=$bg_table?>"><table border="0" cellspacing="1" cellpadding="1" class="text" style="border-bottom:#CCC 2px solid;">
    <tr><td>Table Title:</td><td colspan="4"><input name="po_title_main_<?=$row2['products_option1_mainID']?>" class="inputField" style="width:300px;" value='<?=$row2['po_title']?>' /></td></tr>
	<tr><td> #Cols: </td><td><select name="field_qty_[<?=$row2['products_option1_mainID']?>]" class="inputField"><?
for($ii=0;$ii<=9;$ii++){ ?><option value="<?=$ii?>"<?=$row2['field_qty']==$ii?' selected="selected"':''?>><?=$ii?></option><? }
?></select></td>
<td> Position: </td><td><input name="position_<?=$row2['products_option1_mainID']?>" value='<?=$row2['position']?>' class="inputField" size='2' /></td>
<td><a href="#" onclick="delete_table(<?=$row2['products_option1_mainID']?>); return false;">Delete Table</a></td></tr>
		<tr valign="top"><td>Description: </td><td colspan="6"><textarea name="description_<?=$row2['products_option1_mainID']?>" class="inputField" style="width:280px; height:80px;"><?=stripslashes($row2['description'])?></textarea></td></tr><?


	$sSQL="SELECT * FROM products_option1 WHERE productsID='".$$tableID."' AND products_option1_mainID='".$row2['products_option1_mainID']."' ORDER BY position";
	$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
	$nn=0; $bg=""; $n_qty=$row2['field_qty'];
	while($row=mysql_fetch_assoc($result)){$nn++; 
		if($nn==1){ $bg="f0f0ff"; }
		?><tr bgcolor="#<?=$bg?>">
	<td><input name="po_position1_[<?=$row['products_option1ID']?>]" value='<?=$row['position']?>' class="inputField" size='2' /></td><td><input name="po_title1[<?=$row['products_option1ID']?>]" value='<?=$row['po_title']?>' class="inputField" style="width:80px;" /></td><?
	for($ii=1;$ii<=$n_qty;$ii++){ ?><td><input name="field_<?=$ii?>[<?=$row['products_option1ID']?>]" value='<?=$row['field_'.$ii]?>' class="inputField" style="width:80px;" /></td><? }
	
	?><td><a href="#" onclick="deleteAdditional1(<?=$row['products_option1ID']?>); return false">delete</a></td></tr><? 
	$bg="ffffff"; }
	
	?><tr><td>Add row</td><td><input type="text" name="_po_title1_<?=$row2['products_option1_mainID']?>" value="" class="inputField" style="width:80px;" /></td></tr></table></td></tr><?
$bg_table=$bg_table=="ffffff"?'f0f0f7':'ffffff';
}
?><tr><td colspan="2" bgcolor="#FFeeFF">Add Table</td></tr>
<tr><td> #Cols: </td><td><select name="_field_qty_" class="inputField"><?
for($ii=0;$ii<=9;$ii++){ ?><option value="<?=$ii?>"><?=$ii?></option><? }
?></select></td></tr>
<tr><td style="border-bottom:#CCC 2px solid;">Table Title: </td><td style="border-bottom:#CCC 2px solid;"><input name="_po_title_main_" class="inputField" style="width:280px;" /></td></tr><?

/////////////////////////////////////////// additional tables ///////////////////////////////////////////


?><tr><td height="40" colspan="2" align="center" valign="middle"><input type="image" src="/adm/img/btn_save.png" accesskey="s" onclick="if(document.ff1.FileUpload.value!='' && document.ff1.fileTitle.value==''){alert('Enter File Title please');return false;}" />
<input type="hidden" name="<?=$tableID?>" id="<?=$tableID?>" value="<?=$$tableID?>" />&nbsp;</td>
</tr></table></td></tr></table></td></tr></table></FORM>
<script type="text/javascript" language="javascript">
function deleteAdditional(id){
	if(confirm(" Information will be deleted permanenty please confirm."))	{
		document.getElementById('delete_additional').value = id; 
		document.getElementById('ff1').submit();
	}}
function deleteAdditional1(id){
	if(confirm(" Information will be deleted permanenty please confirm."))	{
		document.getElementById('delete_additional1').value = id; 
		document.getElementById('ff1').submit();
	}}
function delete_table(id){
	if(confirm(" Information will be deleted permanenty please confirm."))	{
		document.getElementById('delete_additional_delete_table').value = id; 
		document.getElementById('ff1').submit();
	}}
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