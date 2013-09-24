<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";
//print_r($_REQUEST);
if(!empty($_SESSION['adminlevel']) && !stristr($adminLevelstring[$_SESSION['adminlevel']],"productsCategory=1"))
{
	header("Location: /adm/home.php");
	exit(); 
}
//if(!checkAdmin(1,'sContentPages')){header("Location: /adm/home.php"); exit();}

$err="";
$mess="";
$pagetitle="Product Category ";
$tableName="product_category";
$tableID="product_categoryID";
$folderName="product_category";
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
<tr valign="top"> <td  class="categoryCell"><?

function reorderCats() //function removes gaps in position.
{ global $tableID, $tableName;
	$x = 1;
   $lastcat = 0;
	
	$sSQL="SELECT $tableID, parentID FROM $tableName ORDER BY parentID, position";
	//echo $sSQL;
   $result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
   while ($row = mysql_fetch_array($result)){
	   //reseting position for each parent
	   if($lastcat != $row['parentID'])
	   {$x = 1; $lastcat = $row['parentID'];}
	   
	   
	   $sSQL2="UPDATE $tableName SET position = '".$x."'
				WHERE $tableID ='".$row[$tableID]."'";
	   mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br>".$sSQL2);
	   
	   $x++;
   }
}

$editeditID=(!empty($_REQUEST["editeditID"]))?str_replace("'","`",$_REQUEST["editeditID"]):"0";
$addCategoryParentID=(!empty($_REQUEST["addCategoryParentID"]))?str_replace("'","`",$_REQUEST["addCategoryParentID"]):"0";
$_SESSION["processSafe"]=(empty($_SESSION["processSafe"]))?"1":$_SESSION["processSafe"];
if(!empty($_REQUEST["process"]) && $_REQUEST["process"]=="1"
	&& !empty($_REQUEST["processSafeForm"]) && $_REQUEST["processSafeForm"]==$_SESSION["processSafe"])
{
	$_SESSION["processSafe"]++;
	if($editeditID!=0)
	{
		$EditNewtitle=(!empty($_REQUEST["categorytitle_".$editeditID]))?$_REQUEST["categorytitle_".$editeditID]:"";
		if($EditNewtitle!="")
		{
			$sSQL="SELECT $tableID, title, parentID
					FROM $tableName 
					WHERE $tableID ='".$editeditID."'";
			$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
			// display each child 
			if($row = mysql_fetch_array($result))
			{
				$sSQL2="UPDATE $tableName SET title='".addslashes($EditNewtitle)."' 
							WHERE $tableID ='".$row[$tableID]."'";
				mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br>".$sSQL2);
				$mess="Category <u>".$EditNewtitle."</u> updated.";
			}
			else
			{
				$err="You can not edit this category.";
			}
		}
		else
		{
			$err="Title can not be empty.";
		}
	}
	elseif($addCategoryParentID!="0")
	{
		$AddNewtitle=(!empty($_REQUEST["addCategorytitle_".$addCategoryParentID]))?$_REQUEST["addCategorytitle_".$addCategoryParentID]:"";
		
		$sSQL="SELECT *
					FROM $tableName 
					WHERE title ='".$AddNewtitle."' AND parentID = '".$addCategoryParentID."'";
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
			$sSQL="SELECT $tableID, title, parentID
					FROM $tableName 
					WHERE $tableID ='".$addCategoryParentID."'";
			$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
			// display each child 
			if($row = mysql_fetch_array($result))
			{$parentID = $row[$tableID];}
			else{$parentID = 0;}
			
			// finding position
			$lastPosition = 1;
			$sSQL="SELECT MAX(position) AS pos
					FROM $tableName 
					WHERE parentID ='".$parentID."'";
			$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
			if($row = mysql_fetch_array($result))
			{$lastPosition = $row["pos"]+1;}
			//
			$sSQL2="INSERT INTO $tableName (dateCreated, title, parentID, position)
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
		$sSQLcat="SELECT 1 FROM $tableName WHERE parentID = '".$catIdTodel."'";
		$resultcat=mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
		if (mysql_num_rows($resultcat)==0)
		{
			$sSQLdel="DELETE FROM $tableName WHERE $tableID = '".$catIdTodel."'";
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
	else{$err="Unknown System Error.";}
}
?>
<style>
.categoryLink{font-family:Tahoma;font-size: 12px;color: #1144AA;text-decoration: none;}
.categoryLink:hover{font-size: 12px;color: #3366CC;text-decoration: underline;background-color:#EFEFEF;}
</style>
<table border="0" cellpadding="4" cellspacing="4" width="100%"><FORM name="cats" action="" method="post"><?
if($err!="")
{
?><tr><td align="center"><b style="color:#FF0000;"><?=$err?></b></td></tr><?
}

if($mess!=""){?><tr><td align="center"><b style="color:#009900;"><?=$mess?></b></td></tr><? }
?><tr><td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="optionsTable">
<tr valign="middle">
	<td valign="middle" align="center">
		<IMG src="/adm/img/btn_add.png" border="0"> <b>Add Content Page</b>
		&nbsp;&nbsp;&nbsp;
		<IMG src="/adm/img/btn_rename.png" border="0"> <b>Modify Content Page</b>
		&nbsp;&nbsp;&nbsp;
		<IMG src="/adm/img/btn_delete.png" border="0"> <b>Delete Content Page <a href="#" onclick="alert('You can delete pages, which do not have any  subpages.');">?</a></b>
	</td></tr></table></td></tr>
<?php /*?><tr>
  <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pagingTable" style="height:32px;">
  
    <tr><td align="left"><A href="#addCategorytitle_None" onclick="addCategory('None');"><IMG src="/adm/img/btn_add.png" border="0"></A></td><td align="right"></td></tr>
  </table></td>
</tr><?php */?>
<tr><td>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr><td width="25">&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" width="100%">
<? display_children(0,0); ?>							
</table></td></tr></table></td></tr><?php 
// $parent is the parent of the children we want to see 
// $padding is increased when we go deeper into the tree, 
//        used to display a nice indented tree

function display_children($parent, $padding) {  
   // retrieve all children of $parent 
   global $categoriesTree, $bgColor, $tableName, $tableID, $folderName;
   $sSQL="SELECT $tableID, title, parentID, position
			FROM $tableName 
			WHERE parentID ='".$parent."'
			ORDER BY position, $tableID";
   $result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
   // display each child 
   while ($row = mysql_fetch_array($result)) { 
       // indent and display the title of this child
	?><tr><td style="padding-left:<?=$padding?>px;"><table width="100%" cellpadding="0" cellspacing="2" border="0">
	<tr bgcolor="<?=$bgColor?>"><td style="padding-left:5px;" align="left" nowrap="nowrap"><span id="displayCategory_<?=$row[$tableID]?>"><A href="/adm/<?=$folderName?>/addEdit.php?<?=$tableID?>=<?=$row[$tableID]?>" class="categoryLink"><?=$row["title"]?></A><?
	$bgColor=($bgColor=="FFFFFF")?"E6E6E6":"FFFFFF";
	if($row["parentID"]==0){
	?>&nbsp;<A href="#" title="ADD" onclick="addCategory('<?=$row[$tableID]?>'); return false;"><IMG src="/adm/img/btn_add.png" border="0"></A><? }
	
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
	?>&nbsp;<A href="/adm/<?=$folderName?>/addEdit.php?<?=$tableID?>=<?=$row[$tableID]?>"><IMG src="/adm/img/btn_rename.png" border="0"></A><?
	
		$sSQLcat="SELECT 1 FROM products WHERE product_categoryID = '".$row[$tableID]."'";
		$resultcat=mysql_query($sSQLcat) or die ("MySQL err: ".mysql_error()."<br>".$sSQLcat);
		if (mysql_num_rows($resultcat)==0 && $row[$tableID]!=18){//
		?>&nbsp;<img src="/adm/img/btn_delete.png" border="0" style="cursor:pointer;" onclick="deleteCategory('<?=$row[$tableID]?>')"><? }
		if ($row["parentID"]!=0){}
		?>&nbsp;<img src="/adm/img/up.jpg" border="0" style="cursor:pointer;" onclick="moveContent(<?=$row[$tableID]?>,1);">
        <img src="/adm/img/down.jpg" border="0" style="cursor:pointer;" onclick="moveContent(<?=$row[$tableID]?>,2);"><? 
?></span><span id="editCategory_<?=$row[$tableID]?>" style="display:none;"><input type="text" name="categorytitle_<?=$row[$tableID]?>" id="categorytitle_<?=$row[$tableID]?>" value="<?=$row["title"]?>" class="inputField" style="width:200px;" />&nbsp;<input type="button" name="Save" id="Save" value="Save" class="editbox" style="width:50px;" onclick="editCategorySubmit('<?=$row[$tableID]?>')" /></span>
	</td></tr>
	<tr><td align="left"><div class="scroll" id="addCategory_<?=$row[$tableID]?>" style="display:none">
    <input type="text" name="addCategorytitle_<?=$row[$tableID]?>" id="addCategorytitle_<?=$row[$tableID]?>" value="" class="inputField" style="width:200px;" />&nbsp;<input type="button" name="Add" id="Add" value="Add" class="editbox" style="width:50px;" onclick="addCategorySubmit('<?=$row[$tableID]?>')" /></div></td></tr>
</table></td></tr><?
       // call this function again to display this 
       // child's children 
       display_children($row[$tableID], $padding+25);  
   } 
} 
?>  
<script type="text/javascript" language="javascript">
function editCategory(editID,categoryName,timesToChange,tmpStr)
{
	document.getElementById("displayCategory_"+editID).style.display="none";
	document.getElementById("editCategory_"+editID).style.display="block";
} 
function editCategorySubmit(catID)
{
	document.getElementById("editeditID").value=catID
	document.cats.submit();
}

function addCategory(parentCatID)
{
	document.getElementById("addCategory_"+parentCatID).style.display="block";
}
function addCategorySubmit(parentCatID)
{
	document.getElementById("addCategoryParentID").value=parentCatID;
	document.cats.submit();
}
function saveActive()
{
	document.getElementById("saveActiveCats").value=1;
	document.cats.submit();
}
function deleteCategory(catIdTodel)
{
	if (confirm('Deteting this category page (Once it\'s done, there is no way back)?')) {
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
<tr><td class="pagingTable">
  <table border="0" cellspacing="0" cellpadding="0" style="height:32px;" class="pagingTable">
  <tr><td align="left" width="20px" height="30px">
      <A href="#" title="ADD" onclick="addCategory('None'); return false;"><IMG src="/adm/img/btn_add.png" border="0"></A></td>
<td>Add new</td><td><div class="scroll" id="addCategory_None" style="display:none; float:left;"><table border="0" cellspacing="0" cellpadding="0">
<tr><td><input type="text" name="addCategorytitle_None" id="addCategorytitle_None" value="" class="inputField" style="width:200px;" /></td>
<td><input type="button" name="Add" id="Add" value="Add" class="editbox" style="width:50px;" onclick="addCategorySubmit('None')" /></td></tr></table></div></td></tr></table></td></tr> 
<input type="hidden" name="process" id="process" value="1" />
<input type="hidden" name="move_ID" id="move_ID" value="" />
<input type="hidden" name="move_Way" id="move_Way" value="" />
<input type="hidden" name="catIdTodel" id="catIdTodel" value="" />
<input type="hidden" name="editeditID" id="editeditID" value="" />
<input type="hidden" name="addCategoryParentID" id="addCategoryParentID" value="" />
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