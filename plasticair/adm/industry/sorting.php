<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";
$mess="";
$err="";

$tableName="industry";
$tableID="industryID";
$folderName="industry";
$pageTitle="Industry";
		
if(!empty($_REQUEST['numOfItems'])){$pageItems = $_REQUEST['numOfItems'];	$_SESSION['SnumOfItems'] = $_REQUEST['numOfItems']; }
elseif(!empty($_SESSION['SnumOfItems'])){$pageItems = $_SESSION['SnumOfItems'];}

$numPerPage=(!empty($_REQUEST["numPerPage"]))?str_replace("'","",$_REQUEST["numPerPage"]):"10";
$sort=(!empty($_REQUEST["sort"]))?str_replace("'","",$_REQUEST["sort"]):$tableID;
$sortdir=(!empty($_REQUEST["sortdir"]))?addslashes($_REQUEST["sortdir"]):"0";
$curPage=(!empty($_REQUEST["curPage"]))?str_replace("'","",$_REQUEST["curPage"]):"0";
$keyWord=(!empty($_REQUEST["keyWord"]))?addslashes(str_replace("[:dubleQuote:]",'"',$_REQUEST["keyWord"])):"search text";
$product_categoryID=(empty($_REQUEST["product_categoryID"]))?"":$_REQUEST["product_categoryID"];
$searchFromSession=(!empty($_REQUEST["searchFromSession"]))?"1":"0";

$bgColor="";

if(!empty($_REQUEST['searchFromSession'])){
	$product_categoryID=(!empty($_SESSION[$tableName."_product_categoryID"]))?$_SESSION[$tableName."_product_categoryID"]:$product_categoryID;
	$keyWord=(!empty($_SESSION[$tableName."_keyWord"]))?$_SESSION[$tableName."_keyWord"]:$keyWord;
	$curPage=(!empty($_SESSION[$tableName."_curPage"]))?$_SESSION[$tableName."_curPage"]:$curPage;
	$sort=(!empty($_SESSION[$tableName."_sort"]))?$_SESSION[$tableName."_sort"]:$sort;
	$sortdir=(!empty($_SESSION[$tableName."_sortdir"]))?$_SESSION[$tableName."_sortdir"]:$sortdir;
	$numPerPage=(!empty($_SESSION[$tableName."_numPerPage"]))?$_SESSION[$tableName."_numPerPage"]:$numPerPage;
}
else{
	$_SESSION[$tableName."_product_categoryID"]=$product_categoryID;
	$_SESSION[$tableName."_keyWord"]=$keyWord;
	$_SESSION[$tableName."_curPage"]=$curPage;
	$_SESSION[$tableName."_sort"]=$sort;
	$_SESSION[$tableName."_sortdir"]=$sortdir;
	$_SESSION[$tableName."_numPerPage"]=$numPerPage;
}

$chkItem=(!empty($_REQUEST["chkItem"]))?$_REQUEST["chkItem"]:"";
$listAction=(!empty($_REQUEST["listAction"]))?$_REQUEST["listAction"]:"";
$delItem=(!empty($_REQUEST["delItem"]))?$_REQUEST["delItem"]:"";

$sSQLwhere=" AND product_categoryID='$product_categoryID'";

$sSQLwhere="";
if(!empty($_REQUEST["move_ID"])){
		$move_ID=(!empty($_REQUEST["move_ID"]))?str_replace("'","`",$_REQUEST["move_ID"]):"";
		$move_Way=(!empty($_REQUEST["move_Way"]))?str_replace("'","`",$_REQUEST["move_Way"]):"";
		
		$sSQLcat="SELECT * FROM $tableName WHERE $tableID = '".$move_ID."'";
		$resultcat=mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
		if ($row = mysql_fetch_array($resultcat)){
			$oldID = $row['position'];
			if($move_Way == 1){ $add_=1;//up
				$sSQLcat="SELECT position, $tableID FROM $tableName WHERE position<='$oldID' AND $tableID<>'".$move_ID."' $sSQLwhere ORDER BY position DESC";}
			else{ $add_=-1;//down 
				$sSQLcat="SELECT position, $tableID FROM $tableName WHERE position>='$oldID' AND $tableID<>'".$move_ID."' $sSQLwhere ORDER BY position ASC";}
				
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


$sSQLsort=" ORDER BY position ";
//
$searchArr=split(",",$keyWord);
$searchArrStr="";
$zpt="";


$sSQL="SELECT ".$tableName.".* FROM ".$tableName." WHERE 1=1";

$sSQL.=$sSQLwhere;
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
$numrows=mysql_num_rows($result);

$sSQL.=$sSQLsort;
?><FORM id="ff1" name="ff1" action="sorting.php" method="post" >
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
?><tr><td colspan="4">
<table cellpadding="0" cellspacing="0" border="0">
<tr class="message"><td style="border:solid #00FF00 1px; padding-left:10px; padding-right:10px"><?=$mess?></td></tr></table></td></tr><?
}?>
<tr><td height="5px;"></td></tr>
<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pageTabs">
<tr><td width="2" background="/adm/img/tabs_bottom_bg_left.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
<td width="250" class="arial20_Grey" align="center"><?=$pageTitle?> <img src="/adm/img/arrowGreyRightBig.jpg" width="5" height="9" align="absmiddle" /></td>
<td align="left"><table border="0" cellspacing="0" cellpadding="0" height="35">
<tr><td width="2" background="/adm/img/tabs_active_bg_left.jpg"></td>
<td background="/adm/img/tabs_bg.jpg"><a href="/adm/<?=$folderName?>/" class="tabLink">View All</a></td>
<td width="4" background="/adm/img/tabs_active_bg_right_tr.jpg"></td>
<td background="/adm/img/tabs_bg.jpg"><a href="/adm/<?=$folderName?>/addEdit.php" class="tabLink">Add New</a></td>
<td width="4" background="/adm/img/tabs_active_bg_right_tr.jpg"></td>
<td background="/adm/img/tabs_active_bg.jpg"><a href="/adm/<?=$folderName?>/sorting.php" class="tabLink"><b>Sorting</b></a></td>
<td width="1" background="/adm/img/tabs_bg_right.jpg"></td></tr></table></td>
<td width="2" background="/adm/img/tabs_bottom_bg_right.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td></tr>
</table></td></tr>

<tr><td height="100%" valign="top" class="contentCell"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="listingTable">

<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr class="listingTitles">
<td width="8"></td><td width="19" class="orangeCheckbox" align="center"><?php /*?><a href="#" onClick="javascript: setCheckboxes('ff1', !(allChecked)); return false;"><img src="/adm/img/listingTitles_checkbox.jpg" width="21" height="22" border="0"></a><?php */?></td>
<td width="10"></td>
<td><A class="listingHeaderLinks">Title</a></td></tr><?

$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
$bgColor="FFFFFF";
while($row=mysql_fetch_array($result)) {
	?><tr onMouseOver='javascript: act_row(this);' onmouseout="javascript: inact_row(this,'<?=$bgColor?>')"  onClick="window.location='/adm/<?=$folderName?>/addEdit.php?<?=$tableID?>=<?=$row[$tableID]?>'; return false;" class="arial12_black" bgcolor="<?=$bgColor?>">
<td bgcolor='#FFFFFF' height="26">&nbsp;</td>
<td align="center" class="orangeCheckbox"  onClick="event.cancelBubble = true"><?php /*?><input type="checkbox" name="chkItem[]" id="chkItem[]" value="<?=$row[$tableID]?>"><?php */?></td>
<td  onClick="event.cancelBubble = true">&nbsp;<img src="/adm/img/up.jpg" border="0" style="cursor:pointer;" onclick="movecontacts(<?=$row[$tableID]?>,1);return false;">
<img src="/adm/img/down.jpg" border="0" style="cursor:pointer;" onclick="movecontacts(<?=$row[$tableID]?>,2); return false;"></td>
<td style="padding:3px;"><?=$row['title']?></td></tr><?
$bgColor=($bgColor=="FFFFFF")?"E6E6E6":"FFFFFF";
} ?></table></td></tr>
</table></td></tr></table></td></tr></table></form>
<SCRIPT language="JavaScript">
<!--
function movecontacts(id,way)
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