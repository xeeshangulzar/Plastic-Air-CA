<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";

$tableName="admins";
$tableID="adminsId";
$folderName="settings/adminPassword";
$pageTitle="Admin Login";

$$tableID=(!empty($_REQUEST[$tableID]))?str_replace("'","",$_REQUEST[$tableID]):"1";
$oldPassword=(!empty($_REQUEST["oldPassword"]))?addslashes($_REQUEST["oldPassword"]):"";
$newPassword=(!empty($_REQUEST["newPassword"]))?addslashes($_REQUEST["newPassword"]):"";
$newPassword1=(!empty($_REQUEST["newPassword1"]))?addslashes($_REQUEST["newPassword1"]):"";
$adminUsername=(!empty($_REQUEST["adminUsername"]))?addslashes($_REQUEST["adminUsername"]):"";
$adminEmail=(!empty($_REQUEST["adminEmail"]))?addslashes($_REQUEST["adminEmail"]):"";


$processSafeForm=(!empty($_REQUEST["processSafeForm"]))?$_REQUEST["processSafeForm"]:"";
$_SESSION["processSafe"]=(empty($_SESSION["processSafe"]))?"1":$_SESSION["processSafe"];

$err="";
$mess="";

if(!empty($_REQUEST["process"]) && !empty($processSafeForm) && $_SESSION["processSafe"]==$processSafeForm) 
{
	$_SESSION["processSafe"]++;
	
	$sql="SELECT * FROM ".$tableName." WHERE ".$tableID." = 1";
	$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
	if($row=mysql_fetch_assoc($result)){}
	
	if($oldPassword == $row['adminPass'] && $newPassword == $newPassword1 && $newPassword != '' && $adminUsername != "")
	{
		$sSQL="UPDATE ".$tableName." SET
						adminPass='".$newPassword."',
						adminUsername='".$adminUsername."'
						WHERE  ".$tableID." = 1";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		$mess="New Password and Username Saved.";
	}
	elseif($adminUsername != $row['adminUsername'] && $adminUsername != "")
	{
		$sSQL="UPDATE ".$tableName." SET
						adminUsername='".$adminUsername."',
						adminEmail='".$adminEmail."'
						WHERE  ".$tableID." = 1";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		$mess="New Username Saved. (password remains the same)";
	}
	else
	{
		if($oldPassword != $row['adminPass'])
		{
			$err .= "Old password does not match <br />";
		}
		if($newPassword != $newPassword1)
		{
			$err .= "New passwords do not match <br />";
		}
		if($newPassword == '')
		{	
			$err .= "New Password cannot be empty <br />";
		}
		if($adminUsername == '')
		{	
			$err .= "Username cannot be empty <br />";
		}
	}
}

	$sql="SELECT * FROM ".$tableName." WHERE ".$tableID." = 1";
	$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
	$row=mysql_fetch_assoc($result);
	foreach ($row as $k=>$v) $$k=$v;
?>
<FORM id="ff1" name="ff1" action="" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="process" id="process" value="1">
		<input type="hidden" name="processSafeForm" id="processSafeForm" value="<?=$_SESSION["processSafe"]?>" />
		<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" class="content">
              <table border="0" cellspacing="0" cellpadding="0" class="text" width="100%">
              <? if ($err) {?>
            <tr>
              	<td colspan="4">
				<table cellpadding="0" cellspacing="0" border="0">
				<tr class="err"><td style="border:solid #FF0000 1px; padding-left:10px; padding-right:10px"><?=$err?></td></tr>
                </table>
                </td>
            </tr>
            <? } if ($mess) {?>
            <tr>
              	<td colspan="4">
				<table cellpadding="0" cellspacing="0" border="0">
				<tr class="message"><td style="border:solid #00FF00 1px; padding-left:10px; padding-right:10px"><?=$mess?></td></tr>
                </table>
                </td>
            </tr>
            <? }?>
            <tr><td height="5px;"></td></tr>
				  <tr>
					<td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pageTabs">
                    <tr>
                      <td width="2" background="/adm/img/tabs_bottom_bg_left.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                      <td width="160" class="arial20_Grey" align="center"><?=$pageTitle?> <img src="/adm/img/arrowGreyRightBig.jpg" width="5" height="9" align="absmiddle" /></td>
                      <td align="left">&nbsp;</td>
                      <td width="2" background="/adm/img/tabs_bottom_bg_right.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                    </tr>
                    <tr><td height="7px"></td></tr>
                  </table></td>
				  </tr>
                <tr>
                  <td class="contentCell"><table border="0" cellspacing="0" cellpadding="3" class="text" width="100%">			  
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					</tr>
                    <?php /*?><tr>
                      <td width="150px">Email: </td>
					  <td><input type="text" name="adminEmail" id="adminEmail" value="<?=$adminEmail?>" class="inputField" style="width:140px;" /></td>
				    </tr><?php */?>
                    <tr>
                      <td width="150px">Username: </td>
					  <td><input type="text" name="adminUsername" id="adminUsername" value="<?=$adminUsername?>" class="inputField" style="width:140px;" /></td>
				    </tr>					
					<tr>
                      <td width="150px">Old Password: </td>
					  <td><input type="password" name="oldPassword" id="oldPassword" value="" class="inputField" style="width:140px;" /></td>
				    </tr>
                    <tr>
                      <td>New Password: </td>
					  <td><input type="password" name="newPassword" id="newPassword" value="" class="inputField" style="width:140px;" /></td>
				    </tr>
                    <tr>
                      <td>Confirm New Password: </td>
					  <td><input type="password" name="newPassword1" id="newPassword1" value="" class="inputField" style="width:140px;" /></td>
				    </tr>
					<tr>
					  <td height="40" colspan="2" align="center" valign="middle"><input type="image" src="/adm/img/btn_save.png" accesskey="s" />
					  <input type="hidden" name="<?=$tableID?>" id="<?=$tableID?>" value="<?=$$tableID?>" />&nbsp;</td>
					</tr>
				  </table></td>
                </tr>
              </table></td>
		  </tr>
		</table>
</FORM>
<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/footer.php";
?>