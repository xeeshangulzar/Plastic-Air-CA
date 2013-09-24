<?
include $_SERVER['DOCUMENT_ROOT']."/admin/inc/header.php";
if(!empty($_SESSION['adminlevel']) && !stristr($adminLevelstring[$_SESSION['adminlevel']],"customers=1"))
{
	header("Location: /admin/home.php");
	exit();
}
$mess="";

$tableName="storeCustomers";
$tableID="storeCustomersID";
$folderName="customers";
$pageTitle="Customers";

//paging
if(!empty($_REQUEST['numOfItems'])){$pageItems = $_REQUEST['numOfItems'];	$_SESSION['SnumOfItems'] = $_REQUEST['numOfItems']; }
elseif(!empty($_SESSION['SnumOfItems'])){$pageItems = $_SESSION['SnumOfItems'];}

$numPerPage=(!empty($_REQUEST["numPerPage"]))?str_replace("'","",$_REQUEST["numPerPage"]):"10";
$sort=(!empty($_REQUEST["sort"]))?str_replace("'","",$_REQUEST["sort"]):$tableID;
$sortdir=(!empty($_REQUEST["sortdir"]))?addslashes($_REQUEST["sortdir"]):"0";
$curPage=(!empty($_REQUEST["curPage"]))?str_replace("'","",$_REQUEST["curPage"]):"0";
$keyWord=(!empty($_REQUEST["keyWord"]))?addslashes(str_replace("[:dubleQuote:]",'"',$_REQUEST["keyWord"])):"search text";
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
else
{
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
if(!empty($chkItem) && $listAction=="1" && $delItem=="1")
{
	//Delete product(s)
	
	$chkItemString=implode(",",$chkItem);
	if(empty($chkItemString))
	{
		$chkItemString=$chkItem;
	}
	$sSQL="SELECT *
			FROM ".$tableName."
			WHERE storeID = '".$_SESSION['storeID']."'
			AND ".$tableID." IN (".addslashes($chkItemString).")";
	$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
	while($row = mysql_fetch_array($result))
	{
		//if( file_exists($_SERVER['DOCUMENT_ROOT'].$row["imageUrl"]) ) {
//			unlink($_SERVER['DOCUMENT_ROOT'].$row["imageUrl"]);
//		}
//		if( file_exists($_SERVER['DOCUMENT_ROOT'].$row["smallImageUrl"]) ) {
//			unlink($_SERVER['DOCUMENT_ROOT'].$row["smallImageUrl"]);
//		}
		$sSQL2="DELETE FROM storeCustomers
					WHERE storeID = '".$_SESSION['storeID']."'
						AND storeCustomersID = '".$row["storeCustomersID"]."'";
		mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br>".$sSQL2);
		$mess="Item(s) deleted";
	}
}
if(!empty($chkItem) && $listAction=="2")
{
	//Approve Customer(s)
	
		$chkItemString=implode(",",$chkItem);
	if(empty($chkItemString))
	{
		$chkItemString=$chkItem;
	}
	$sSQL="SELECT *
			FROM storeCustomers
			WHERE storeID = '".$_SESSION['storeID']."'
				AND storeCustomersID IN (".str_replace("'","",$chkItemString).")";
	$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
	while($row = mysql_fetch_array($result))
	{
		if($row["approvedDate"]=="0000-00-00 00:00:00")
		{
			//Send email that account has been approved and save it to DB.
			$sSQL2="UPDATE storeCustomers SET approved='1', approvedDate=NOW()
					WHERE storeID = '".$_SESSION['storeID']."'
						AND storeCustomersID = '".$row["storeCustomersID"]."'";
			mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br>".$sSQL2);
			$mess="Customer(s) approved";
			
			if($row["email"]!="")
			{
				$message="Dear ".$row["name"]." ".$row["lastName"]."<br>
							<br>
							Your ".$storeName." account has been approved,<br>
							<br>
							Your login information:<br>
							<br>
							Username: ".$row["email"]."<br>
							Password: ".$row["password"]."<br>
							<br>
							Thank you,<br>
							".$storeName;		
				$headers  = "MIME-Version: 1.0\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\n";
				$headers .= "From: ".$storeName." <".$storeMainEmail."> \n";
				$subject=$storeName." Account Approved";
				mail($row["email"],$subject,$message,$headers);
			}
		}
	}
}
$sortdirSQL=($sortdir!="1")?"DESC":"";
$sSQLsort="";
if(!empty($sort))
{
	$sSQLsort=" ORDER BY ".$sort." ".$sortdirSQL." ";
}
//
$searchArr=split(",",$keyWord);
$searchArrStr="";
$zpt="";
foreach($searchArr as $searchVal)
{
	$searchArrStr.=$zpt."'".$searchVal."'";
	$zpt=",";
}
$sSQLwhere="";
if(!empty($keyWord) && $keyWord!="search text")
{
	$sSQLwhere.=" AND ( title LIKE '%".$keyWord."%')";	

}

$fromRecord=$curPage*$numPerPage;
$sSQL="SELECT *
		FROM ".$tableName."
		WHERE storeID='".$_SESSION['storeID']."'";

$sSQL.=$sSQLwhere;
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
$numrows=mysql_num_rows($result);




$sSQLLimit=" LIMIT ".$fromRecord." , ".$numPerPage;
$sSQL.=$sSQLsort.$sSQLLimit;
?>
 <FORM id="ff1" name="ff1" action="index.php" method="post" >
        <input name="sort" id="sort" type="hidden" value="<?=$sort?>">
        <input name="sortdir" id="sortdir" type="hidden" value="<?=$sortdir?>">
        <input name="curPage" id="curPage" type="hidden" value="0">
        <input name="numPerPage" id="numPerPage" type="hidden" value="<?=$numPerPage?>">
        <input type="hidden" name="process" id="process" value="1">
		<input type="hidden" name="delItem" id="delItem" value="" />
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pageTabs">
                    <tr>
                      <td width="2" background="/admin/img/tabs_bottom_bg_left.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                      <td width="140" class="arial20_Grey" align="center"><?=$pageTitle?> <img src="/admin/img/arrowGreyRightBig.jpg" width="5" height="9" align="absmiddle" /></td>
                      <td align="left"><table border="0" cellspacing="0" cellpadding="0" height="35">
                        <tr>
                          <td width="2" background="/admin/img/tabs_active_bg_left.jpg"></td>
                          <td background="/admin/img/tabs_active_bg.jpg"><a href="/admin/<?=$folderName?>/" class="tabLink"><b>View All</b></a></td>
                          <td width="4" background="/admin/img/tabs_active_bg_right_tr.jpg"></td>
                          <td background="/admin/img/tabs_bg.jpg"><a href="/admin/<?=$folderName?>/addEdit.php" class="tabLink">Add New</a></td>
                          <td width="1" background="/admin/img/tabs_bg_right.jpg"></td>
                        </tr>
                      </table></td>
                      <td width="2" background="/admin/img/tabs_bottom_bg_right.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="100%" valign="top" class="contentCell"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="listingTable">
                  <tr>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="optionsTable">
                    <tr valign="middle">
                      <td width="80" align="right" nowrap>Search: </td>
                      <td><input type="text" name="keyWord" id="keyWord" class="inputField" value="<?=$keyWord?>" onClick="delContent();" onBlur="delContent();"></td>
                      <td><input type="image" src="/admin/img/btn_go.png" width="23" height="20" /></td><br />
<td width="100%"></td>
                    </tr>
<script type="text/javascript" language="javascript">
function delContent()
{
	if(document.getElementById("keyWord").value=="search text")
	{
		document.getElementById("keyWord").value="";
	}
}
</script>
                  </table></td>
                </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pagingTable" style="height:32px;">
                      
                        <tr>
                          <td align="left">Listings <?=($fromRecord+1)?>-<?=(($fromRecord+$numPerPage)<=$numrows)?$fromRecord+$numPerPage:$numrows?> | Show <select name="page" id="page" class="inputField" style="margin:0 4 0 4px;" onchange="switchPerPage(this.value)">
						  <option value="10" <?=(($numPerPage!="10")?"":"selected")?>>10</option>
						  <option value="20" <?=(($numPerPage!="20")?"":"selected")?>>20</option>
						  <option value="30" <?=(($numPerPage!="30")?"":"selected")?>>30</option>
						  <option value="40" <?=(($numPerPage!="40")?"":"selected")?>>40</option>
						  <option value="50" <?=(($numPerPage!="50")?"":"selected")?>>50</option>
						  </select>
                            per page </td>
                          <td align="right"><?=paging($numrows,$numPerPage,$curPage)?></td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr class="listingTitles">
                          <td width="8"></td>
                          <td width="19" class="orangeCheckbox" align="center"><a href="#" onClick="javascript: setCheckboxes('ff1', !(allChecked)); return false;"><img src="/admin/img/listingTitles_checkbox.jpg" width="21" height="22" border="0"></a></td>
                          <td width="10"></td>
                          <td><A href="#" onClick="javascript: sort_list('created'); return false;" class="listingHeaderLinks">Date</A></td>
                          <td><A href="#" onClick="javascript: sort_list('name'); return false;" class="listingHeaderLinks">First Name</A></td>
                          <td><A href="#" onClick="javascript: sort_list('lastName'); return false;" class="listingHeaderLinks">Last Name</A></td>
						  <td><A href="#" onClick="javascript: sort_list('customerCompany'); return false;" class="listingHeaderLinks">Company Name</A></td>
						  <td><A href="#" onClick="javascript: sort_list('phone'); return false;" class="listingHeaderLinks">Business Phone</A></td>
						  <td><A href="#" onClick="javascript: sort_list('email'); return false;" class="listingHeaderLinks">Email</A></td>
						  <td><A href="#" onClick="javascript: sort_list('active'); return false;" class="listingHeaderLinks">Active</A></td>
						 <?php /*?> <td><A href="#" onClick="javascript: sort_list('approved'); return false;" class="listingHeaderLinks">Approved</A></td><?php */?>
                        </tr>
						<?
						$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
						$bgColor="FFFFFF";
						while($row=mysql_fetch_array($result)) {
						?>
                        <tr onMouseOver='javascript: act_row(this);' onmouseout="javascript: inact_row(this,'<?=$bgColor?>')"  onClick="window.location='/admin/<?=$folderName?>/addEdit.php?<?=$tableID?>=<?=$row[$tableID]?>'; return false;" class="arial12_black" bgcolor="<?=$bgColor?>">
                          <td bgcolor='#FFFFFF' height="26">&nbsp;</td>
                          <td align="center" class="orangeCheckbox"  onClick="event.cancelBubble = true"><input type="checkbox" name="chkItem[]" id="chkItem[]" value="<?=$row[$tableID]?>"></td>
                          <td bgcolor='#FFFFFF'></td>
                          <td style="padding:3px;"><?=date("M, d Y",strtotime($row["created"]))?></td>
                          <td style="padding:3px;"><?=$row["name"]?></td>
                          <td style="padding:3px;"><?=$row["lastName"]?></td>
						  <td style="padding:3px;"><?=$row["customerCompany"]?></td>
						  <td style="padding:3px;"><?=$row["phone"]?></td>
						  <td style="padding:3px;"><?=$row["email"]?></td>
						  <td style="padding:3px;"><IMG src='/admin/img/chk<?=($row["active"]=="0")?"No":"Yes"?>.gif' border='0' align='middle'></td>
						  <?php /*?><td style="padding:3px;"><IMG src='/admin/img/chk<?=($row["approved"]=="0")?"No":"Yes"?>.gif' border='0' align='middle'></td><?php */?>
                        </tr><?
						$bgColor=($bgColor=="FFFFFF")?"E6E6E6":"FFFFFF";
						} ?>
                      </table></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="listingBottom">
                        <tr>
                          <td><select id="listAction" name="listAction" class="inputField" style="margin:0 4 0 4px;">
						  <option value="">With Selected</option>
						  <option value="1">DELETE</option>
						 <?php /*?> <option value="2">APPROVE</option><?php */?>
						  <option value="3">SEND NEWSLETTER</option>
						  </select>
                          <img src="/admin/img/btn_go.png" width="23" height="20" align="absmiddle" onclick="listActionRun()" style="cursor:pointer"></td>
                        </tr>
                      </table></td>
                    </tr>
                  </table></td>
                </tr>
              </table>
</form>
<SCRIPT language="JavaScript">
<!--
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
	if(document.getElementById("listAction").value=='1')
	{
		if (confirm('Detete this item(s) (Once it\'s done, there is no way back)?')) {
			document.getElementById("delItem").value='1';
			document.ff1.submit();
		} else {
			return false;
		}
	}
	else if(document.getElementById("listAction").value=='3')
	{
		document.ff1.action="/admin/newsLetter/index.php";
		document.ff1.submit();
	}
	else
	{
		document.ff1.submit();
	}
}

function deleteProduct()
{
	if (confirm('Detete this item(s) (Once it\'s done, there is no way back)?')) {
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
</SCRIPT>
<?
include $_SERVER['DOCUMENT_ROOT']."/admin/inc/footer.php";
?>