<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";
$mess="";
$err="";

$tableName="front_images";
$tableID="front_imagesID";
$folderName="front_images";
$pageTitle="Front Images";
$arrmanufacturer=array();
//paging
if(!empty($_REQUEST['numOfItems'])){$pageItems = $_REQUEST['numOfItems'];	$_SESSION['SnumOfItems'] = $_REQUEST['numOfItems']; }
elseif(!empty($_SESSION['SnumOfItems'])){$pageItems = $_SESSION['SnumOfItems'];}

$numPerPage=(!empty($_REQUEST["numPerPage"]))?str_replace("'","",$_REQUEST["numPerPage"]):"10";
$sort=(!empty($_REQUEST["sort"]))?str_replace("'","",$_REQUEST["sort"]):$tableID;
$sortdir=(!empty($_REQUEST["sortdir"]))?addslashes($_REQUEST["sortdir"]):"0";
$curPage=(!empty($_REQUEST["curPage"]))?str_replace("'","",$_REQUEST["curPage"]):"0";
$keyWord=(!empty($_REQUEST["keyWord"]))?addslashes(str_replace("[:dubleQuote:]",'"',$_REQUEST["keyWord"])):"search text";
$categories=(empty($_REQUEST["categories"]))?"":$_REQUEST["categories"];
$searchFromSession=(!empty($_REQUEST["searchFromSession"]))?"1":"0";
$bgColor="";

if(!empty($_REQUEST['searchFromSession'])){
	$categories=(!empty($_SESSION[$tableName."_categories"]))?$_SESSION[$tableName."_categories"]:$categories;
	$keyWord=(!empty($_SESSION[$tableName."_keyWord"]))?$_SESSION[$tableName."_keyWord"]:$keyWord;
	$curPage=(!empty($_SESSION[$tableName."_curPage"]))?$_SESSION[$tableName."_curPage"]:$curPage;
	$sort=(!empty($_SESSION[$tableName."_sort"]))?$_SESSION[$tableName."_sort"]:$sort;
	$sortdir=(!empty($_SESSION[$tableName."_sortdir"]))?$_SESSION[$tableName."_sortdir"]:$sortdir;
	$numPerPage=(!empty($_SESSION[$tableName."_numPerPage"]))?$_SESSION[$tableName."_numPerPage"]:$numPerPage;
}
else{
	$_SESSION[$tableName."_categories"]=$categories;
	$_SESSION[$tableName."_keyWord"]=$keyWord;
	$_SESSION[$tableName."_curPage"]=$curPage;
	$_SESSION[$tableName."_sort"]=$sort;
	$_SESSION[$tableName."_sortdir"]=$sortdir;
	$_SESSION[$tableName."_numPerPage"]=$numPerPage;
}

$chkItem=(!empty($_REQUEST["chkItem"]))?$_REQUEST["chkItem"]:"";
$listAction=(!empty($_REQUEST["listAction"]))?$_REQUEST["listAction"]:"";
$delItem=(!empty($_REQUEST["delItem"]))?$_REQUEST["delItem"]:"";

$sSQLwhere="";

if(!empty($_REQUEST["move_ID"])){
		$move_ID=(!empty($_REQUEST["move_ID"]))?str_replace("'","`",$_REQUEST["move_ID"]):"";
		$move_Way=(!empty($_REQUEST["move_Way"]))?str_replace("'","`",$_REQUEST["move_Way"]):"";
		
		$sSQLcat="SELECT * FROM $tableName WHERE $tableID = '".$move_ID."'";
		$resultcat=mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
		if ($row = mysql_fetch_array($resultcat)){
			$oldID = $row['position'];
			if($move_Way == 1){ $add_=1;//up
				$sSQLcat="SELECT position, $tableID FROM $tableName WHERE position<'$oldID' $sSQLwhere ORDER BY position DESC";}
			else{ $add_=-1;//down 
				$sSQLcat="SELECT position, $tableID FROM $tableName WHERE position>'$oldID' $sSQLwhere ORDER BY position ASC";}
				
				$resultcat=mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
				if($row = mysql_fetch_array($resultcat)){
					if($row['position']==$oldID){$row['position']+=$add_;}
					$sSQLcat="UPDATE $tableName SET position='".$row['position']."' WHERE $tableID='".$move_ID."'";
					mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
					$sSQLcat="UPDATE $tableName SET position = '".$oldID."' WHERE $tableID = '".$row[$tableID]."'";
					mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
					}
			}
		else{	$err="Unable to sort page";}
		
		$sSQL="UPDATE settings SET last_update=NOW() WHERE settingsID='1'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
	}

if(!empty($chkItem) && $listAction=="1" && $delItem=="1")
{
	//Delete product(s)
	
	$chkItemString=implode(",",$chkItem);
	if(empty($chkItemString)){ $chkItemString=$chkItem;	}
	$usedRecords = 0;
	$sSQL="SELECT * FROM ".$tableName." WHERE ".$tableID." IN (".addslashes($chkItemString).")";
	$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
	while($row = mysql_fetch_array($result)){
		// deleting associated files
		$sSQL="SELECT * FROM front_images WHERE front_imagesID = '".$row[$tableID]."'";
		$result33=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
		while($row33 = mysql_fetch_array($result33))
		{
			if(!empty($row33['filePath'])){@unlink($_SERVER['DOCUMENT_ROOT'].$row33['filePath']);}
			if(!empty($row33['filePath_s '])){@unlink($_SERVER['DOCUMENT_ROOT'].$row33['filePath_s ']);}
			$sSQL2="DELETE FROM front_images WHERE front_imagesID  = '".$row33['front_imagesID']."'";
			mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br>".$sSQL2);	
		}
		if(!empty($row['picture']))
			{@unlink($_SERVER['DOCUMENT_ROOT'].$row['picture']);}
		$used = 0;		
		if(empty($used))
		{
			$sSQL2="DELETE FROM ".$tableName." WHERE ".$tableID." = '".$row[$tableID]."'";
			mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br>".$sSQL2);	
		}
		else
		{$usedRecords++;}
	}
	if(empty($usedRecords))
	{$mess="Item(s) deleted";}
	else
	{$err= $usedRecords." Items could not be deleted because they are being referenced to in other tables.";}
}

$sortdirSQL=($sortdir!="1")?"DESC":"";
$sSQLsort="";
if(!empty($sort)){	$sSQLsort=" ORDER BY ".$sort." ".$sortdirSQL." ";}
$sSQLsort=" ORDER BY position ";
$searchArr=split(",",$keyWord);
$searchArrStr="";
$zpt="";
foreach($searchArr as $searchVal)
{
	$searchArrStr.=$zpt."'".$searchVal."'";
	$zpt=",";
}
$sSQLwhere="";
if(!empty($keyWord) && $keyWord!="search text"){$sSQLwhere.=" AND ( ".$tableName.".image_title LIKE '%".$keyWord."%')";}


$fromRecord=$curPage*$numPerPage;
$sSQL="SELECT ".$tableName.".* FROM ".$tableName." WHERE 1=1";

$sSQL.=$sSQLwhere;
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
$numrows=mysql_num_rows($result);

$sSQLLimit=" LIMIT ".$fromRecord." , ".$numPerPage;
$sSQL.=$sSQLsort.$sSQLLimit;
?><FORM id="ff1" name="ff1" action="index.php" method="post" >
        <input name="sort" id="sort" type="hidden" value="<?=$sort?>">
        <input name="sortdir" id="sortdir" type="hidden" value="<?=$sortdir?>">
        <input name="curPage" id="curPage" type="hidden" value="0">
        <input name="numPerPage" id="numPerPage" type="hidden" value="<?=$numPerPage?>">
        <input type="hidden" name="process" id="process" value="1">
		<input type="hidden" name="delItem" id="delItem" value="" />
		<input type="hidden" name="search_new" value="" />
<input type="hidden" name="move_ID" id="move_ID" value="" />
<input type="hidden" name="move_Way" id="move_Way" value="" />
		<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="66" valign="top" class="content"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0"><?
             if ($err) {
            ?><tr><td colspan="4">
				<table cellpadding="0" cellspacing="0" border="0">
				<tr class="err"><td style="border:solid #FF0000 1px; padding-left:10px; padding-right:10px"><?=$err?></td></tr></table></td></tr><? } 
			if ($mess) {
            ?><tr>
              	<td colspan="4">
				<table cellpadding="0" cellspacing="0" border="0">
				<tr class="message"><td style="border:solid #00FF00 1px; padding-left:10px; padding-right:10px"><?=$mess?></td></tr></table></td></tr><?
             }?>
            <tr><td height="5px;"></td></tr>
                <tr>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pageTabs">
                    <tr>
                      <td width="2" background="/adm/img/tabs_bottom_bg_left.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                      <td width="150" class="arial20_Grey" align="center"><?=$pageTitle?> <img src="/adm/img/arrowGreyRightBig.jpg" width="5" height="9" align="absmiddle" /></td>
                      <td align="left"><table border="0" cellspacing="0" cellpadding="0" height="35">
                        <tr>
                          <td width="2" background="/adm/img/tabs_active_bg_left.jpg"></td>
                          <td background="/adm/img/tabs_active_bg.jpg"><a href="/adm/<?=$folderName?>/?searchFromSession=1" class="tabLink"><b>View All</b></a></td>
                          <td width="4" background="/adm/img/tabs_active_bg_right_tr.jpg"></td>
                          <td background="/adm/img/tabs_bg.jpg"><a href="/adm/<?=$folderName?>/addEdit.php" class="tabLink">Add New</a></td>
                          <td width="1" background="/adm/img/tabs_bg_right.jpg"></td>
                        </tr>
                      </table></td>
                      <td width="2" background="/adm/img/tabs_bottom_bg_right.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="100%" valign="top" class="contentCell"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="listingTable">
<tr><td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="optionsTable">
<tr valign="middle"><td width="80" align="right" nowrap>Search: </td>
<td><input type="text" name="keyWord" id="keyWord" class="inputField" value="<?=$keyWord?>" onClick="delContent();" onBlur="delContent();"></td>
<td><?php /*?><select name="categories" id="categories" class="inputField" style="width:200px;"><option value="">Select manufacturer</option><? 
$sql="SELECT *  FROM manufacturer ORDER BY title";
$result1=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
while($row1=mysql_fetch_assoc($result1)){
?><option value="<?=$row1['manufacturerID']?>" <?=$categories==$row1['manufacturerID']?"selected='selected'":""?> ><?=$row1['title']?></option><?
       }?></select><?php */?></td>
<td><input type="image" src="/adm/img/btn_go.png" width="23" height="20" onclick="document.ff1.search_new.value='search'" /></td><br />
<td width="100%"></td></tr>
<script type="text/javascript" language="javascript">
function delContent()
{
	if(document.getElementById("keyWord").value=="search text")
	{
		document.getElementById("keyWord").value="";
	}
}
</script></table></td></tr>
<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pagingTable" style="height:32px;">
<tr><td align="left">Listings <?=($fromRecord+1)?>-<?=(($fromRecord+$numPerPage)<=$numrows)?$fromRecord+$numPerPage:$numrows?> of <?=$numrows?>  | Show 
<select name="page" id="page" class="inputField" style="margin:0 4 0 4px;" onchange="switchPerPage(this.value)">
<option value="10" <?=(($numPerPage!="10")?"":"selected")?>>10</option>
<option value="20" <?=(($numPerPage!="20")?"":"selected")?>>20</option>
<option value="30" <?=(($numPerPage!="30")?"":"selected")?>>30</option>
<option value="40" <?=(($numPerPage!="40")?"":"selected")?>>40</option>
<option value="50" <?=(($numPerPage!="50")?"":"selected")?>>50</option></select>
per page </td>
<td align="right"><?=paging($numrows,$numPerPage,$curPage)?></td></tr></table></td></tr>
<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr class="listingTitles">
<td width="8"></td><td width="19" class="orangeCheckbox" align="center"><a href="#" onClick="javascript: setCheckboxes('ff1', !(allChecked)); return false;"><img src="/adm/img/listingTitles_checkbox.jpg" width="21" height="22" border="0"></a></td>
<td width="10"></td>
<td><A class="listingHeaderLinks">Image Title</a></td>
<td><A class="listingHeaderLinks">Links</a></td>
<td><A class="listingHeaderLinks">File Path</a></td>
<?php /*?><td><A href="#" onClick="javascript: sort_list('author'); return false;" class="listingHeaderLinks">Price</a></td><?php */?></tr><?

$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
$bgColor="FFFFFF";
while($row=mysql_fetch_array($result)) {
	?><tr onMouseOver='javascript: act_row(this);' onmouseout="javascript: inact_row(this,'<?=$bgColor?>')"  onClick="window.location='/adm/<?=$folderName?>/addEdit.php?<?=$tableID?>=<?=$row[$tableID]?>'; return false;" class="arial12_black" bgcolor="<?=$bgColor?>">
<td bgcolor='#FFFFFF' height="26">&nbsp;</td>
<td align="center" class="orangeCheckbox"  onClick="event.cancelBubble = true"><input type="checkbox" name="chkItem[]" id="chkItem[]" value="<?=$row[$tableID]?>"></td>
<td  onClick="event.cancelBubble = true"><img src="/adm/img/up.jpg" border="0" style="cursor:pointer;" onclick="movefront_images(<?=$row[$tableID]?>,1);return false;">
<img src="/adm/img/down.jpg" border="0" style="cursor:pointer;" onclick="movefront_images(<?=$row[$tableID]?>,2); return false;"></td>
<td style="padding:3px;"><?=$row['image_title']?></td>
<td style="padding:3px;"><?=$row['links']?></td>
<td style="padding:3px;"><img src="<?=$row['filePath_s']?>" /></td>
<?php /*?><td style="padding:3px;">$<?=number_format($row['price'],2)?></td><?php */?></tr><?
$bgColor=($bgColor=="FFFFFF")?"E6E6E6":"FFFFFF";
} ?></table></td></tr>
<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="listingBottom">
<tr><td><select id="listAction" name="listAction" class="inputField" style="margin:0 4 0 4px;">
<option value="">With Selected</option>
<option value="1">Delete</option>
</select>
<img src="/adm/img/btn_go.png" width="23" height="20" align="absmiddle" onclick="listActionRun()" style="cursor:pointer"></td></tr>
</table></td></tr></table></td></tr></table></td></tr></table></form>
<SCRIPT language="JavaScript">
<!--
function movefront_images(id,way)
{
	document.getElementById('move_ID').value = id;
	document.getElementById('move_Way').value = way;
	document.ff1.submit();
}
function sort_list(skey)
{
	if (document.getElementById("sort").value == skey) {
		document.getElementById("sortdir").value =  1 - document.getElementById("sortdir").value; 
	} else {
		document.getElementById("sort").value = skey;
		document.getElementById("sortdir").value = "0";
	}
	document.ff1.submit(); 
}

function listActionRun()
{
	if(document.getElementById("listAction").value=="1")
	{
		deleteProduct();
	}
	return false;
}

function deleteProduct()
{
	if (confirm('Delete this item(s) (Once it\'s done, there is no way back)?')) {
		document.getElementById("delItem").value='1';
		document.ff1.submit();
	} else {
		return false;
	}
}

var allChecked = false;
function setCheckboxes(the_form, do_check)
{
    var elts      = (typeof(document.forms[the_form].elements['chkItem[]']) != 'undefined')
                  ? document.forms[the_form].elements['chkItem[]']
                  : (typeof(document.forms[the_form].elements['chkItem[]']) != 'undefined')
          ? document.forms[the_form].elements['chkItem[]']
          : document.forms[the_form].elements['chkItem[]'];
    var elts_cnt  = (typeof(elts.length) != 'undefined')
                  ? elts.length
                  : 0;

    if (elts_cnt) {
        for (var i = 0; i < elts_cnt; i++) {
            elts[i].checked = do_check;
        } 
    } else {
        elts.checked        = do_check;
    }
	allChecked = !allChecked;
    return true;
} 

function act_row(t) {
	try {
	t.style.background='#D6E7F3'; 
	t.style.cursor='pointer';
	}
	catch (e) { }
}
function inact_row(t,color) {
	try {
		t.style.background="#"+color; 
	}
	catch (e) { }

}

function switchPages(pageNum)
{
	document.getElementById("curPage").value=pageNum;
	document.ff1.submit();
}

function switchPerPage(pageNum)
{
	document.getElementById("numPerPage").value=pageNum;
	document.ff1.submit();
}
-->
</SCRIPT><? include $_SERVER['DOCUMENT_ROOT']."/adm/includes/footer.php"; ?>