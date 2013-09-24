<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";

$tableName="representative";
$tableID="representativeID";
$folderName="representative";
$pageTitle="Representative";
$filesPath="/dat/representative/";

$sSQL="SELECT * FROM countryCodes ORDER BY countryType, countryName ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
$arr_countryCodes=array();
while($row1=mysql_fetch_array($result1)){
		$arr_countryCodes[$row1['countryCode']]=$row1['countryName'];}

$$tableID=(!empty($_REQUEST[$tableID]))?str_replace("'","",$_REQUEST[$tableID]):"0";
$representativeID=(!empty($_REQUEST["representativeID"]))?addslashes($_REQUEST["representativeID"]):"";
$countryCode=(!empty($_REQUEST["countryCode"]))?addslashes($_REQUEST["countryCode"]):"";
$stateCode=(!empty($_REQUEST["stateCode"]))?addslashes($_REQUEST["stateCode"]):"";
$title=(!empty($_REQUEST["title"]))?addslashes($_REQUEST["title"]):"";
$address=(!empty($_REQUEST["address"]))?addslashes($_REQUEST["address"]):"";
$phones=(!empty($_REQUEST["phones"]))?addslashes($_REQUEST["phones"]):"";
$emails=(!empty($_REQUEST["emails"]))?addslashes($_REQUEST["emails"]):"";
$company_name=(!empty($_REQUEST["company_name"]))?addslashes($_REQUEST["company_name"]):"";
$processSafeForm=(!empty($_REQUEST["processSafeForm"]))?$_REQUEST["processSafeForm"]:"";
$_SESSION["processSafe"]=(empty($_SESSION["processSafe"]))?"1":$_SESSION["processSafe"];

$err="";
$mess="";

if(!empty($_REQUEST["process"]) && !empty($processSafeForm) && $_SESSION["processSafe"]==$processSafeForm) {

	$_SESSION["processSafe"]++;

	if(!empty($_REQUEST["deleteFile"]) && !empty($$tableID))
	{
		$sql="SELECT * FROM files WHERE filesID = '".addslashes($_REQUEST["deleteFile"])."' AND tableName = '".$tableName."' AND tableIDNum = '".$$tableID."'";
		$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		if($row=mysql_fetch_assoc($result)){	
		@unlink($_SERVER['DOCUMENT_ROOT'].$row['filePath']);
			$sql="DELETE FROM files WHERE filesID = '".addslashes($_REQUEST["deleteFile"])."' AND tableName = '".$tableName."' AND tableIDNum = '".$$tableID."'";
			mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
		}
	}

	//mandatory fields
	if($representativeID==""){$$tableID="0";}
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
		/*$pictpath=uplPictures($_FILES["File"],
								"/dat/representative/".$$tableID.".jpg",
								"45",
								"45",
								"4048000",
								"1",
								"",
								"0");
		if($pictpath!="")
		{
			$addImageSQL="picture='".$pictpath."',";
		}*/
		
		$sSQL="UPDATE ".$tableName." SET ".$addImageSQL." 
						title='".toSQL($title)."',
						address='".toSQL($address)."',
						emails='".toSQL($emails)."',
						company_name='".toSQL($company_name)."',
						countryCode='".toSQL($countryCode)."',
						stateCode ='".toSQL($stateCode)."',
						phones='".toSQL($phones)."'
					    WHERE  ".$tableID." = '".$$tableID."'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		//uploading files
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
?><link rel="stylesheet" type="text/css" media="all" href="/inc/calendar/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="/inc/calendar/calendar.js"></script>
<script type="text/javascript" src="/inc/calendar/calendar-en.js"></script>
<script type="text/javascript" src="/inc/calendar/calendar-setup.js"></script>
<FORM id="ff1" name="ff1" action="" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="process" id="process" value="1">
        <input type="hidden" name="deleteFile" id="deleteFile" value="">
		<input type="hidden" name="processSafeForm" id="processSafeForm" value="<?=$_SESSION["processSafe"]?>" />
		<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" class="content">
              <table border="0" cellspacing="0" cellpadding="0" class="text" width="100%">
				  <tr>
<td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pageTabs">
                    <tr>
                      <td width="2" background="/adm/img/tabs_bottom_bg_left.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                      <td width="150" class="arial20_Grey" align="center"><?=$pageTitle?> <img src="/adm/img/arrowGreyRightBig.jpg" width="5" height="9" align="absmiddle" /></td>
                      <td align="left"><table border="0" cellspacing="0" cellpadding="0" height="35">
                        <tr>
                          <td width="2" background="/adm/img/tabs_bg_left.jpg"></td>
                          <td background="/adm/img/tabs_bg.jpg"><a href="/adm/<?=$folderName?>/?searchFromSession=1" class="tabLink"><b>View All</b></a></td>
                          <td width="1" background="/adm/img/tabs_bg_right.jpg"></td>
                          <td width="2" background="/adm/img/tabs_active_bg_left.jpg"></td>
                          <td background="/adm/img/tabs_active_bg.jpg"><a href="/adm/<?=$folderName?>/addEdit.php" class="tabLink">Add New</a></td>
                          <td width="4" background="/adm/img/tabs_active_bg_right_tr.jpg"></td>
<td background="/adm/img/tabs_bg.jpg"><a href="/adm/<?=$folderName?>/sorting.php" class="tabLink">Sorting</a></td>
<td width="1" background="/adm/img/tabs_bg_right.jpg"></td>
                        </tr>
                      </table></td>
                      <td width="2" background="/adm/img/tabs_bottom_bg_right.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                    </tr>
                  </table></td></tr>
<tr><td class="contentCell"><table border="0" cellspacing="0" cellpadding="3" class="text" width="100%"><tr><td>&nbsp;</td><td>&nbsp;</td></tr><?
if (!empty($err)) { ?><tr><td colspan="2" class="err"><?=$err?></td></tr><? } 
if (!empty($mess)) { ?><tr><td colspan="2" class="message"><?=$mess?></td></tr><? }
?><tr><td align="right">Title: </td><td><input type="text" name="title" id="title" value="<?=$title?>" class="inputField" style="width:400px;" /></td></tr>

<tr><td align="right">Country: </td>
<td><select name="countryCode" onchange="fill_province(this)" class="inputField" style="width:200px;"><option value="">Select Country</option><?

foreach($arr_countryCodes as $k=>$v){
		?><option value="<?=$k?>" <?=$countryCode==$k?"selected='selected'":""?> ><?=$v?></option><?
		}?></select></td></tr>


<tr><td align="right">Province/State: </td><td><div id="div_stateCode"><?
if($countryCode=="CA" || $countryCode=="US") {?><select name="stateCode" id="stateCode" class="inputField" style="width:200px;"><option value="">Select Province/State</option><?
$sSQL="SELECT stateCode, stateName FROM stateCodes WHERE countryCode='".$countryCode."' ORDER BY stateType DESC, stateName ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
while($row1=mysql_fetch_assoc($result1)){
		?><option value="<?=$row1['stateCode']?>" <?=$stateCode==$row1['stateCode']?"selected='selected'":""?> ><?=$row1['stateName']?></option><?
		}?></select><? } 
elseif(!empty($countryCode)){ ?><input name="stateCode" value="<?=$stateCode?>" class="inputField" style="width:200px;" /><? }
else{ ?><? }?></div></td></tr>

<?php /*?><tr><td align="right">Company Name: </td><td><input type="text" name="company_name" id="company_name" value="<?=$company_name?>" class="inputField" style="width:400px;" /></td></tr><?php */?>
<tr><td align="right">Email: </td><td><input type="text" name="emails" id="emails" value="<?=$emails?>" class="inputField" style="width:400px;" /></td></tr>
<tr><td align="right">Address: </td><td><textarea name="address" id="address" class="inputField" style="width:200px; height:100px;"><?=$address?></textarea></td></tr>
<tr><td align="right">Phones: </td><td><textarea name="phones" id="phones" class="inputField" style="width:200px; height:100px;"><?=$phones?></textarea></td></tr>
<?php /*?><tr><td width="130px" align="right" valign="top">Picture: <br />
(100x100 max)</td>
<td align="left" colspan="3" valign="top"><input type="file" name="File" id="File" /></td></tr>
<tr><td align="left" colspan="4"><?
    if(!empty($picture)) {?><div style="float:left; border:#999 1px solid; margin:5px;">
		<img src="<?=$picture?>" /><br />
<a href="#" style="color:#F00;" onclick="deleteImages(); return false;">delete</a></div><?
     }?></td></tr><?php */?>
    <tr><td colspan="2"><hr /></td></tr>

<?php /*?><tr><td colspan="2"><hr /></td></tr>
<tr><td width="130px" align="right" valign="top">Upload File: </td>
<td align="left" colspan="3"><input type="file" name="FileUpload" id="FileUpload" /></td></tr>
<tr><td width="130px" align="right">File Title: </td>
<td align="left"><input type="text" name="fileTitle" id="fileTitle" value="" class="inputField" style="width:200px;" /></td></tr><? 
if(!empty($$tableID)) {?><tr><td width="130px" align="right" valign="top">Uploaded Files: </td>
<td align="left" colspan="3">
<table cellpadding="3" cellspacing="0" class="text" border="1">
<tr><td style="min-width:100px;"><b>Name</b></td><td style="min-width:100px;"><b>Link</b></td>
<td align="center"><b>Options</b></td></tr><? 
$sql="SELECT * FROM files 
            WHERE tableName = '".$tableName."' 
            AND tableID = '".$tableID."' 
            AND tableIDNum = '".$$tableID."'";
$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
while($row=mysql_fetch_assoc($result)){ 
	?><tr><td align="left"><?=$row['fileTitle']?></td>
	<td align="left"><a style="color:#666" href="<?=$row['filePath']?>" target="_blank"><?=$row['filePath']?></a><?
    if(strtolower($row['fileExt'])=="jpg" || strtolower($row['fileExt'])=="png" || strtolower($row['fileExt'])=="gif" || strtolower($row['fileExt'])=="jpeg"){ ?><br /><img src="<?=$row['filePath']?>" /><? }
	?></td>
	<td align="center"><a href="#" style="color:#F00;" onclick="deleteFiles(<?=$row['filesID']?>); return false;">DEL</a></td>
	</tr><?
}?></table></td></tr><? }
?><?php */?><tr><td height="40" colspan="2" align="center" valign="middle"><input type="image" src="/adm/img/btn_save.png" accesskey="s" /><input type="hidden" name="<?=$tableID?>" id="<?=$tableID?>" value="<?=$$tableID?>" />&nbsp;</td>
</tr></table></td></tr></table></td></tr></table></FORM>
<script type="text/javascript" language="javascript">
function deleteFiles(id)
{
	if(confirm(" File will be deleted permanenty please confirm. "))
	{
		document.getElementById('deleteFile').value = id; 
		document.getElementById('ff1').submit();
	}
}
</script><?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/footer.php";
?>
<script language="javascript" type="text/javascript">
function openLanguage(id)
{
	if(document.getElementById("language"+id).style.display == "none")
	{document.getElementById("language"+id).style.display = "block";}
	else 
	{document.getElementById("language"+id).style.display = "none";}
}
function fill_province(o_obj){
	makeRequest("get_province.php?countryCode="+o_obj.value+"&<?=$$tableID?>="+document.getElementById('<?=$tableID?>').value+"&ttt="+getJsTime(), fill_province1, o_obj);
	}
function fill_province1(content, arg){
	document.getElementById("div_stateCode").innerHTML=content;
}
</script>