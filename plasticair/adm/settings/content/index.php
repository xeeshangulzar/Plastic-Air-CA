<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";
//print_r($_REQUEST);
if(!empty($_SESSION['adminlevel']) && !stristr($adminLevelstring[$_SESSION['adminlevel']],"productscontent=1"))
{
	header("Location: /adm/home.php");
	exit(); 
}
//if(!checkAdmin(1,'sContentPages')){header("Location: /adm/home.php"); exit();}

$err="";
$mess="";
$pagetitle="Content";
$folderName="settings/content";
$tableName="content";
$tableID="contentID";
$bgColor="E6E6E6";
?>
<table width="100%" cellpadding="0" cellspacing="0"><tr><td style="padding-left:50px; padding-top:30px; padding-right:50px;">
<table border="0" cellspacing="0" cellpadding="0" class="text" width="100%">
<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pageTabs">
<tr><td width="2" background="/adm/img/tabs_bottom_bg_left.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
<td width="180" class="arial20_Grey" align="center"><?=$pagetitle?> <img src="/adm/img/arrowGreyRightBig.jpg" width="5" height="9" align="absmiddle" /></td>
<td align="left"><table border="0" cellspacing="0" cellpadding="0" height="35">
<tr> <td width="2" background="/adm/img/tabs_active_bg_left.jpg"></td>
  <td background="/adm/img/tabs_active_bg.jpg"><a href="/adm/<?=$folderName?>/" class="tabLink"><b>Content Tree</b></a></td>
 <td width="2" background="/adm/img/tabs_active_bg_right.jpg"></td></tr>
</table></td>
<td width="2" background="/adm/img/tabs_bottom_bg_right.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td></tr></table></td></tr>
<tr valign="top"> <td  class="contentCell"><?

function reorderCats() //function removes gaps in position.
{
	$x = 1;
   $lastcat = 0;
	
	$sSQL="SELECT contentID, parentID FROM content ORDER BY parentID, position";
	//echo $sSQL;
   $result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
   while ($row = mysql_fetch_array($result)){
	   //reseting position for each parent
	   if($lastcat != $row['parentID'])
	   {$x = 1; $lastcat = $row['parentID'];}
	   
	   
	   $sSQL2="UPDATE content SET position = '".$x."'
				WHERE contentID ='".$row['contentID']."'";
	   mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br>".$sSQL2);
	   
	   $x++;
   }
}

$editcontentID=(!empty($_REQUEST["editcontentID"]))?str_replace("'","`",$_REQUEST["editcontentID"]):"0";
$addcontentParentID=(!empty($_REQUEST["addcontentParentID"]))?str_replace("'","`",$_REQUEST["addcontentParentID"]):"0";
$_SESSION["processSafe"]=(empty($_SESSION["processSafe"]))?"1":$_SESSION["processSafe"];
if(!empty($_REQUEST["process"]) && $_REQUEST["process"]=="1"
	&& !empty($_REQUEST["processSafeForm"]) && $_REQUEST["processSafeForm"]==$_SESSION["processSafe"])
{
	$_SESSION["processSafe"]++;
	if($editcontentID!=0)
	{
		$EditNewtitle=(!empty($_REQUEST["contenttitle_".$editcontentID]))?$_REQUEST["contenttitle_".$editcontentID]:"";
		if($EditNewtitle!="")
		{
			$sSQL="SELECT contentID, title, parentID
					FROM content 
					WHERE contentID ='".$editcontentID."'";
			$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
			// display each child 
			if($row = mysql_fetch_array($result))
			{
				$sSQL="UPDATE settings SET last_update=NOW() WHERE settingsID='1'";
				mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
				$sSQL2="UPDATE content SET title='".addslashes($EditNewtitle)."' 
							WHERE contentID ='".$row["contentID"]."'";
				mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br>".$sSQL2);
				$mess="content <u>".$EditNewtitle."</u> updated.";
			}
			else
			{
				$err="You can not edit this content.";
			}
		}
		else
		{
			$err="Title can not be empty.";
		}
	}
	elseif($addcontentParentID!="0")
	{
		$AddNewtitle=(!empty($_REQUEST["addcontenttitle_".$addcontentParentID]))?$_REQUEST["addcontenttitle_".$addcontentParentID]:"";
		
		$sSQL="SELECT *
					FROM content 
					WHERE title ='".$AddNewtitle."' AND parentID = '".$addcontentParentID."'";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
		// display each child 
		if($row = mysql_fetch_array($result))
		{
			$err .= "This page title already exists.";	
		}
		$err .=(empty($AddNewtitle))?"Page title cannot be empty":"";
		$err .=($AddNewtitle == "Home")?"This page title is reserved.":"";
		$err .=($AddNewtitle == "Contact Us")?"This page title is reserved.":"";
		$err .=($AddNewtitle == "front" || $AddNewtitle == "Front" || $AddNewtitle == "contactUs" || $AddNewtitle == "ContactUs")?"This page title is reserved.":"";
		//
		if($AddNewtitle!="" && empty($err))
		{
			$sSQL="SELECT contentID, title, parentID
					FROM content 
					WHERE contentID ='".$addcontentParentID."'";
			$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
			// display each child 
			if($row = mysql_fetch_array($result))
			{$parentID = $row["contentID"];}
			else{$parentID = 0;}
			
			// finding position
			$lastPosition = 1;
			$sSQL="SELECT MAX(position) AS pos
					FROM content 
					WHERE parentID ='".$parentID."'";
			$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
			if($row = mysql_fetch_array($result))
			{$lastPosition = $row["pos"]+1;}
			//
			$sSQL="UPDATE settings SET last_update=NOW() WHERE settingsID='1'";
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
			$sSQL2="INSERT INTO content (dateCreated, title, parentID, position)
									VALUES(NOW(),
											'".addslashes($AddNewtitle)."',
											'".$parentID."',
											'".$lastPosition."'
											)";
			mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br>".$sSQL2);
			$mess="Page <u>".$AddNewtitle."</u> added.";			
		}
	}
	elseif(!empty($_REQUEST["catIdTodel"]))
	{
		$catIdTodel=(!empty($_REQUEST["catIdTodel"]))?str_replace("'","`",$_REQUEST["catIdTodel"]):"";
		$sSQLcat="SELECT 1 FROM content WHERE parentID = '".$catIdTodel."'";
		$resultcat=mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
		if (mysql_num_rows($resultcat)==0)
		{
			$sSQL="UPDATE settings SET last_update=NOW() WHERE settingsID='1'";
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
			$sSQLdel="DELETE FROM content WHERE contentID = '".$catIdTodel."'";
			mysql_query($sSQLdel) or die ("MySQL err: ".mysql_error()."<br>".$sSQLdel);
			$mess="Content Page Deleted.";
			reorderCats();
		}
		else
		{
			$err="Unable to delete page - there are sub pages under this page.";
		}
	}
	elseif(!empty($_REQUEST["move_ID"])){
		$move_ID=(!empty($_REQUEST["move_ID"]))?str_replace("'","`",$_REQUEST["move_ID"]):"";
		$move_Way=(!empty($_REQUEST["move_Way"]))?str_replace("'","`",$_REQUEST["move_Way"]):"";
		
		$sSQLcat="SELECT * FROM $tableName WHERE $tableID = '".$move_ID."'";
		$resultcat=mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
		if ($row = mysql_fetch_array($resultcat)){
			$oldID = $row['position'];
			$parentID = $row['parentID'];
			if($move_Way == 1){ //up
				$sSQLcat="SELECT position, $tableID FROM $tableName WHERE parentID = '".$parentID."' AND position<'$oldID' ORDER BY position DESC";}
			else{ //down 
				$sSQLcat="SELECT position, $tableID FROM $tableName WHERE parentID = '".$parentID."' AND position>'$oldID' ORDER BY position ASC";}
				$resultcat=mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
				if($row = mysql_fetch_array($resultcat)){
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
	else{$err="Unknown System Error.";}
}
?>
<style>
.contentLink
{
	font-family:Tahoma;
	font-size: 12px;
	color: #1144AA;
	text-decoration: none;
}
.contentLink:hover
{
	font-size: 12px;
	color: #3366CC;
	text-decoration: underline;
	background-color:#EFEFEF;
}
</style>
<table border="0" cellpadding="4" cellspacing="4" width="100%"><FORM name="cats" action="" method="post">
<?
if($err!=""){?><tr><td align="center"><b style="color:#FF0000;"><?=$err?></b></td></tr><? }

if($mess!=""){?><tr><td align="center"><b style="color:#009900;"><?=$mess?></b></td></tr><?} ?>
<tr>
<td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="optionsTable">
<tr valign="middle">
	<td valign="middle" align="center">
		<IMG src="/adm/img/btn_add.png" border="0"> <b>Add Content Page</b>
		&nbsp;&nbsp;&nbsp;
		<IMG src="/adm/img/btn_rename.png" border="0"> <b>Modify Content Page</b>
		&nbsp;&nbsp;&nbsp;
		<IMG src="/adm/img/btn_delete.png" border="0"> <b>Delete Content Page <a href="#" onclick="alert('You can delete pages, which do not have any  subpages.');">?</a></b>
	</td></tr>
</table>
</td></tr>
<?php /*?><tr>
  <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pagingTable" style="height:32px;">
  
    <tr><td align="left"><A href="#addcontenttitle_None" onclick="addcontent('None');"><IMG src="/adm/img/btn_add.png" border="0"></A></td><td align="right"></td></tr>
  </table></td>
</tr><?php */?>
<tr><td>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr><td width="25">&nbsp;</td>
	<td>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<? display_children(0,0); ?>							
</table>
			</td> 
			</tr>
	</table>
   </td></tr>
 <?php 
// $parent is the parent of the children we want to see 
// $padding is increased when we go deeper into the tree, 
//        used to display a nice indented tree

function display_children($parent, $padding) {  
   // retrieve all children of $parent 
   global $categoriesTree,$bgColor;
   $sSQL="SELECT contentID, title, parentID, position
			FROM content 
			WHERE parentID ='".$parent."'
			ORDER BY position, contentID";
   $result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
   // display each child 
   while ($row = mysql_fetch_array($result)) { 
       // indent and display the title of this child
	?>
	<tr><td style="padding-left:<?=$padding?>px;"><table width="100%" cellpadding="0" cellspacing="2" border="0">
	<tr bgcolor="<?=$bgColor?>"><td style="padding-left:5px;" align="left" nowrap="nowrap"><span id="displaycontent_<?=$row["contentID"]?>"><A href="/adm/settings/content/addEdit.php?contentID=<?=$row["contentID"]?>" class="contentLink"><?=$row["title"]?></A><?
	$bgColor=($bgColor=="FFFFFF")?"E6E6E6":"FFFFFF";
	if($row["parentID"]==0){
	?>&nbsp;<A href="#" title="ADD" onclick="addcontent('<?=$row["contentID"]?>'); return false;"><IMG src="/adm/img/btn_add.png" border="0"></A><?
	}
	$delimetr="[~:DoubleQuote:~]";
		$loopTill=0;
		do
		{
			if(substr_count($row["title"],$delimetr)!=0)
			{
				$delimetr=str_replace("~","~~",$delimetr);
			}
			else
			{
				$loopTill=1;
			}
		}while($loopTill<1);
	 
		$titleTmp=addslashes(str_replace('"',$delimetr,$row["title"]));
	?>&nbsp;<A href="/adm/settings/content/addEdit.php?contentID=<?=$row["contentID"]?>"><IMG src="/adm/img/btn_rename.png" border="0"></A><?
		$sSQLcat="SELECT 1 FROM content WHERE parentID = '".$row["contentID"]."'";
		$resultcat=mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
		if (mysql_num_rows($resultcat)==0 && $row["parentID"]!=0 && $row["contentID"]!=2)
		{?>&nbsp;<img src="/adm/img/btn_delete.png" border="0" style="cursor:pointer;" onclick="deletecontent('<?=$row["contentID"]?>')"><? }
		if ($row["parentID"]!=0)
		{?>&nbsp;<img src="/adm/img/up.jpg" border="0" style="cursor:pointer;" onclick="moveContent(<?=$row["contentID"]?>,1);">
        <img src="/adm/img/down.jpg" border="0" style="cursor:pointer;" onclick="moveContent(<?=$row["contentID"]?>,2);"><? }
?></span><span id="editcontent_<?=$row["contentID"]?>" style="display:none;"><input type="text" name="contenttitle_<?=$row["contentID"]?>" id="contenttitle_<?=$row["contentID"]?>" value="<?=$row["title"]?>" class="inputField" style="width:200px;" />&nbsp;<input type="button" name="Save" id="Save" value="Save" class="editbox" style="width:50px;" onclick="editcontentSubmit('<?=$row["contentID"]?>')" /></span>
	</td></tr>
	<tr><td align="left"><div class="scroll" id="addcontent_<?=$row["contentID"]?>" style="display:none">
    <input type="text" name="addcontenttitle_<?=$row["contentID"]?>" id="addcontenttitle_<?=$row["contentID"]?>" value="" class="inputField" style="width:200px;" />&nbsp;<input type="button" name="Add" id="Add" value="Add" class="editbox" style="width:50px;" onclick="addcontentSubmit('<?=$row["contentID"]?>')" /></div></td></tr>
</table></td></tr>
	<?
       // call this function again to display this 
       // child's children 
       display_children($row["contentID"], $padding+25);  
   } 
} 
?>  
<script type="text/javascript" language="javascript">
function editcontent(contentID,contentName,timesToChange,tmpStr)
{
	document.getElementById("displaycontent_"+contentID).style.display="none";
	document.getElementById("editcontent_"+contentID).style.display="block";
} 
function editcontentSubmit(catID)
{
	document.getElementById("editcontentID").value=catID
	document.cats.submit();
}

function addcontent(parentCatID)
{
	document.getElementById("addcontent_"+parentCatID).style.display="block";
}
function addcontentSubmit(parentCatID)
{
	document.getElementById("addcontentParentID").value=parentCatID;
	document.cats.submit();
}
function saveActive()
{
	document.getElementById("saveActiveCats").value=1;
	document.cats.submit();
}
function deletecontent(catIdTodel)
{
	if (confirm('Deteting this content page (Once it\'s done, there is no way back)?')) {
		document.getElementById("catIdTodel").value=catIdTodel;
		document.cats.submit();
	} else {
		return false;
	}
}
function moveContent(id,way)
{
	document.getElementById('move_ID').value = id;
	document.getElementById('move_Way').value = way;
	document.cats.submit();
}
</script> 
<tr>
  <td>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="pagingTable" style="height:32px;">
  <tr>
      <?php /*?><td align="left" width="20px" height="30px">
      <A href="#" title="ADD" onclick="addcontent('None'); return false;"><IMG src="/adm/img/btn_add.png" border="0"></A></td><?php */?>
      <td><div class="scroll" id="addcontent_None" style="display:none; float:left;">
    <input type="text" name="addcontenttitle_None" id="addcontenttitle_None" value="" class="inputField" style="width:200px;" />&nbsp;<input type="button" name="Add" id="Add" value="Add" class="editbox" style="width:50px;" onclick="addcontentSubmit('None')" /></div>
    </td>
      <td align="right"></td>
    </tr>
  </table></td>
</tr> 
<input type="hidden" name="process" id="process" value="1" />
<input type="hidden" name="move_ID" id="move_ID" value="" />
<input type="hidden" name="move_Way" id="move_Way" value="" />
<input type="hidden" name="catIdTodel" id="catIdTodel" value="" />
<input type="hidden" name="editcontentID" id="editcontentID" value="" />
<input type="hidden" name="addcontentParentID" id="addcontentParentID" value="" />
<input type="hidden" name="saveActiveCats" id="saveActiveCats" value="" />
<input type="hidden" name="processSafeForm" id="processSafeForm" value="<?=$_SESSION["processSafe"]?>" />
</form></table>
</td>
</tr>
</table>
</td></tr></table>
<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/footer.php";
?>