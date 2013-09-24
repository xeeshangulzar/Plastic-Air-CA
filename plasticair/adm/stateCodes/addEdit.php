<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";

$tableName="stateCodes";
$tableID="stateCode";
$folderName="stateCodes";
$pageTitle="Taxes";
$filesPath="/dat/news/";

$$tableID=(!empty($_REQUEST[$tableID]))?str_replace("'","",$_REQUEST[$tableID]):"0";
$stateCode=(!empty($_REQUEST["stateCode"]))?str_replace("'", "`", $_REQUEST["stateCode"]):"";
$title=(!empty($_REQUEST["title"]))?str_replace("'", "`", $_REQUEST["title"]):"";
$headline=(!empty($_REQUEST["headline"]))?str_replace("'", "`", $_REQUEST["headline"]):"";
$gst =(!empty($_REQUEST["gst"]))?str_replace("'", "`", $_REQUEST["gst"]):"";
$pst =(!empty($_REQUEST["pst"]))?str_replace("'", "`", $_REQUEST["pst"]):"";
$hst =(!empty($_REQUEST["hst"]))?str_replace("'", "`", $_REQUEST["hst"]):"";
$tax =(!empty($_REQUEST["tax"]))?str_replace("'", "`", $_REQUEST["tax"]):"";
$processSafeForm=(!empty($_REQUEST["processSafeForm"]))?$_REQUEST["processSafeForm"]:"";
$date = (!empty($_REQUEST['date']))?str_replace("'","",$_REQUEST['date']):"";
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
	if($stateCode==""){$$tableID="0";}
	//if($title==""){$err.="<li>Title</il> ";}
	
	//So message will be written to DB
	if($err=="")
	{			
		if($$tableID=="0")
		{
			//$sSQL="INSERT INTO ".$tableName."(dateCreated)
//								VALUES(NOW())";
//			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
//			$$tableID=mysql_insert_id();
		}
		
		$addImageSQL=""; 
		/*$pictpath=uplPictures($_FILES["File"],
								"/dat/news/".$$tableID.".jpg",
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
		
		$sSQL="UPDATE settings SET last_update=NOW() WHERE settingsID='1'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		$sSQL="UPDATE ".$tableName." SET 
						tax='".$tax."',
						date='".date("Y-m-d", strtotime($date))."',
						gst='".$gst."',
						pst='".$pst."',
						hst='".$hst."'
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
	//echo $sql;
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
                        </tr>
                      </table></td>
                      <td width="2" background="/adm/img/tabs_bottom_bg_right.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                    </tr>
                  </table></td></tr>
<tr><td class="contentCell"><table border="0" cellspacing="0" cellpadding="3" class="text" width="100%"><tr><td>&nbsp;</td><td>&nbsp;</td></tr><?
if (!empty($err)) { ?><tr><td colspan="2" class="err"><?=$err?></td></tr><? } 
if (!empty($mess)) { ?><tr><td colspan="2" class="message"><?=$mess?></td></tr><? }
?><tr><td align="right">Province: </td><td><b><?=$stateName ?></b></td></tr>
<tr><td align="right">GST: </td><td><input type="text" name="gst" id="gst" value="<?=$gst?>" class="inputField" size="3" /></td></tr>
<tr><td align="right">PST: </td><td><input type="text" name="pst" id="pst" value="<?=$pst?>" class="inputField" size="3" /></td></tr>
<tr><td align="right">HST: </td><td><input type="text" name="hst" id="hst" value="<?=$hst?>" class="inputField" size="3" /></td></tr>
<tr><td align="right">Tax: </td><td><input type="text" name="tax" id="tax" value="<?=$tax?>" class="inputField" size="3" /></td></tr>
<?php /*?><tr><td align="right">Date : </td><td><input type="text" name="headline" id="headline" value="<?=$headline?>" class="inputField" style="width:200px;" /></td></tr><?php */?>
<tr><td align="right">Date:&nbsp;</td>
<td><i><span id="newsDateDisplay"><?=(!empty($newsDate) && $newsDate!="0000-00-00")?date("F d, Y", strtotime($newsDate)):date("F d, Y")?></span></i>&nbsp;	
    <input type="hidden" name="newsDate" id="newsDate" value="<?=(!empty($newsDate) && $newsDate!="0000-00-00")?date("Y-m-d",strtotime($newsDate)):date("Y-m-d")?>" />
    <input type="hidden" name="newsDateaa" id="newsDateaa" value="newsDateIsHere" />
<a href="#" id="calfrom"><img src="/adm/img/calendar.gif" border="0" align="absmiddle" /></a>
<script type="text/javascript">
Calendar.setup({
    checkField     :    "newsDateaa",     // id of the input field
    inputField     :    "newsDate",     // id of the input field
    displayArea    :    "newsDateDisplay",
    ifFormat       :    "%Y-%m-%d",  // format of the input field
    daFormat       :    "%B %d, %Y",
    timeFormat     :    "24",     
    showsTime      :    false,       // will display a time selector
    button         :    "calfrom",   // trigger for the calendar (button ID)
    singleClick    :    true            // double-click mode
});
</script></td></tr>
<tr><td height="40" colspan="2" align="center" valign="middle"><input type="image" src="/adm/img/btn_save.png" accesskey="s" />
<input type="hidden" name="<?=$tableID?>" id="<?=$tableID?>" value="<?=$$tableID?>" />&nbsp;</td></tr>
</table></td></tr></table></td></tr></table></FORM>
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
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/footer.php";?>