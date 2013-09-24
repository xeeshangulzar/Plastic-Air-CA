<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";

$pageTitle = 'Contact Information';

$phones=(!empty($_REQUEST["phones"]))?str_replace("'", "", $_REQUEST["phones"]):"";
$fax=(!empty($_REQUEST["fax"]))?str_replace("'", "", $_REQUEST["fax"]):"";
$description=(!empty($_REQUEST["description"]))?str_replace("'", "", $_REQUEST["description"]):"";
$email=(!empty($_REQUEST["email"]))?str_replace("'", "", $_REQUEST["email"]):"";
$address=(!empty($_REQUEST["address"]))?str_replace("'", "`", $_REQUEST["address"]):"";
$address_map=(!empty($_REQUEST["address_map"]))?str_replace("'", "`", $_REQUEST["address_map"]):"";
$contact_emails=(!empty($_REQUEST["contact_emails"]))?str_replace("'", "", $_REQUEST["contact_emails"]):"";

$processSafeForm=(!empty($_REQUEST["processSafeForm"]))?$_REQUEST["processSafeForm"]:"";
$_SESSION["processSafe"]=(empty($_SESSION["processSafe"]))?"1":$_SESSION["processSafe"];

$err="";
$mess="";

if(!empty($_REQUEST["process"]) && !empty($processSafeForm) && $_SESSION["processSafe"]==$processSafeForm) {

	$_SESSION["processSafe"]++;
	
	if(!empty($_REQUEST["delete_top"]) && !empty($$tableID)){
		$sql="SELECT * FROM contacts WHERE contactsID = '1'";
		$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		if($row=mysql_fetch_assoc($result)){	
		@unlink($_SERVER['DOCUMENT_ROOT'].$row['picture2']);
			$sql="UPDATE contacts SET picture2='' WHERE contactsID = '1'";
			mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		}
	}
	if(!empty($_REQUEST["deleteImage"]) && !empty($$tableID)){
		$sql="SELECT * FROM contacts WHERE contactsID = '1'";
		$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		if($row=mysql_fetch_assoc($result)){	
		@unlink($_SERVER['DOCUMENT_ROOT'].$row['picture']);
			$sql="UPDATE contacts SET picture='' WHERE contactsID = '1'";
			mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		}
	}

	//mandatory fields
	if($phones=="") $err.="<li>Phone</li>";
	//if($description=="") $err.="<li>Description</li>";
	if($fax=="") $err.="<li>Fax</li>";
	if($email=="") $err.="<li>Email</li>";
	if($address=="") $err.="<li>Address</li>";
	
	//So message will be written to DB
	if($err=="") {	
		$addImageSQL=""; 
		$pictpath=uplPictures($_FILES["File"],
								"/dat/contacts/1.jpg",
								"500", "500",
								"4048000",
								"1",
								"",
								"0");
		if($pictpath!=""){	$addImageSQL="picture='".$pictpath."',";}
		$add_top_pictureSQL=""; 
		$pictpath=uplPictures($_FILES["top_picture"],
								"/dat/contacts/1_2.jpg",
								"500", "500",
								"4048000",
								"1",
								"",
								"0");
		if($pictpath!=""){	$add_top_pictureSQL="picture2='".$pictpath."',";}//headline='".$headline."',
		
 		$sSQL="UPDATE contacts SET $addImageSQL $add_top_pictureSQL
			phones='$phones',description='$description',fax='$fax',email='$email',address='$address', address_map='$address_map' ";
//die($sSQL) 		;
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		$mess="Information Saved.";
	}
	if (!empty($err)) {
		$err="<u>The following is required</u>:<ul>".$err."</ul>";
	}
}

$sql="SELECT * FROM contacts";
$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
if ($row=mysql_fetch_assoc($result)) {
  foreach($row as $k=>$v) $$k=$v;
}
?><FORM id="ff1" name="ff1" action="" method="post" enctype="multipart/form-data" >
<link rel="stylesheet" type="text/css" media="all" href="/includes/calendar/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<input type="hidden" name="process" id="process" value="1">
<input type="hidden" name="processSafeForm" id="processSafeForm" value="<?=$_SESSION["processSafe"]?>" />
<input type="hidden" name="deleteImage" id="deleteImage" value="">
<input type="hidden" name="delete_top" id="delete_top" value="">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td valign="top" class="content">
<table border="0" cellspacing="0" cellpadding="0" class="text" width="100%">
<tr>
<td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pageTabs">
<tr><td width="2" background="/adm/img/tabs_bottom_bg_left.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
<td width="200" class="arial20_Grey" align="center"><?=$pageTitle?> <img src="/adm/img/arrowGreyRightBig.jpg" width="5" height="9" align="absmiddle" /></td>
<td align="left"><table border="0" cellspacing="0" cellpadding="0" height="35">
<tr><td width="1" background="/adm/img/tabs_bg_right.jpg"></td>
<td background="/adm/img/tabs_bg.jpg"><a href="/adm/<?=$folderName?>/?searchFromSession=1" class="tabLink">View All</a></td>
<td width="1" background="/adm/img/tabs_bg_right.jpg"></td>
<?php /*?><td width="1"></td>
<td width="2" background="/adm/img/tabs_active_bg_left.jpg"></td>
<td background="/adm/img/tabs_active_bg.jpg"><a href="/adm/<?=$folderName?>/addEdit.php" class="tabLink"><b>Add / Edit </b></a></td>
<td width="2" background="/adm/img/tabs_active_bg_right.jpg"></td></tr><?php */?>
</table></td>
<td width="2" background="/adm/img/tabs_bottom_bg_right.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td></tr></table></td>
</tr>
<tr><td class="contentCell"><table border="0" cellspacing="0" cellpadding="3" class="text" width="100%">
<tr><td>&nbsp;</td><td>&nbsp;</td></tr><?
if (!empty($err)) { ?><tr><td colspan="2" class="err"><?=$err?></td></tr><? } 
if (!empty($mess)) { ?><tr><td colspan="2" class="message"><?=$mess?></td></tr><? }
?><tr><td width="130px" align="right">Phone: </td>
<td align="left"><input type="text" name="phones" id="phones" value="<?=htmlspecialchars($phones)?>" class="inputField" style="width:200px;" /></td></tr>
<tr><td width="130px" align="right">Fax: </td>
<td align="left"><input type="text" name="fax" id="fax" value="<?=htmlspecialchars($fax)?>" class="inputField" style="width:200px;" /></td></tr>
<tr><td width="130px" align="right">Email: </td>
<td align="left"><input type="text" name="email" id="email" value="<?=htmlspecialchars($email)?>" class="inputField" style="width:200px;" /></td></tr>
<tr><td width="130px" align="right">Address: </td>
<td align="left"><textarea type="text" name="address" id="address" class="inputField" style="width:400px; height:70px;"><?=$address?></textarea></td></tr>
<tr><td width="130px" align="right">Address Google Map: </td>
<td align="left"><textarea type="text" name="address_map" id="address_map" class="inputField" style="width:400px; height:70px;"><?=$address_map?></textarea></td></tr>

<tr><td width="130px" align="right">Emails for request/contact forms (separated by ;): </td>
<td align="left"><textarea name="contact_emails" id="contact_emails" class="inputField" style="width:200px; height:70px;"><?=htmlspecialchars($contact_emails)?></textarea></td></tr>

<tr><td width="130px" align="right" valign="top">Description: </td>
<td colspan="3"><?
$oFCKeditor = new FCKeditor('description') ;
$oFCKeditor->BasePath = '/inc/fckeditor/' ;
$oFCKeditor->Width = '800' ;
$oFCKeditor->Height = '200' ;
$oFCKeditor->ToolbarSet = 'Basic' ;
$oFCKeditor->Value = stripslashes($description);
$oFCKeditor->Create() ;
?></td></tr>
<tr><td width="130px" align="right" valign="top">Map: <br />(500x500)</td>
<td align="left" colspan="3" valign="top"><input type="file" name="top_picture" id="top_picture" /></td></tr><?
if(!empty($picture2)) {?><tr><td width="130px" align="right" valign="top">&nbsp;</td>
					<td align="left" colspan="3"><img src="<?=$picture2?>" /><br />
<a href="#" style="color:#F00;" onclick="delete_top_picture(1); return false;">delete</a></td></tr><? }
?><tr><td colspan="2"><hr /></td></tr>

<tr><td width="130px" align="right" valign="top">Map 2: <br />(500x500 max)</td>
<td align="left" colspan="3" valign="top"><input type="file" name="File" id="File" /></td></tr><?
if(!empty($picture)) {?><tr><td width="130px" align="right" valign="top">&nbsp;</td>
					<td align="left" colspan="3"><img src="<?=$picture?>" /><br />
<a href="#" style="color:#F00;" onclick="deleteImages(1); return false;">delete</a></td></tr><? }
?><tr><td colspan="2"><hr /></td></tr>
<tr><td></td><td height="40" align="left" valign="middle"><input type="image" src="/adm/img/btn_save.png" /></tr></table></td></tr></table></td></tr>
</table></FORM><?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/footer.php"; ?>
<script type="text/javascript" language="javascript">
function delete_top_picture(id){
	if(confirm(" File will be deleted permanenty please confirm. ")){
		document.getElementById('delete_top').value = id; 
		document.getElementById('ff1').submit();
	}}
function deleteImages(id){
	if(confirm(" File will be deleted permanenty please confirm. ")){
		document.getElementById('deleteImage').value = id; 
		document.getElementById('ff1').submit();
	}}
</script>