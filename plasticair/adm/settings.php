<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";

$tableName="admins";
$tableID="adminsID";
$folderName="admins";
$pageTitle="Admins";
$$tableID=1;
$adminPass=(!empty($_REQUEST["adminPass"]))?str_replace("'","",$_REQUEST["adminPass"]):"";
$passwordConf=(!empty($_REQUEST["passwordConf"]))?str_replace("'","",$_REQUEST["passwordConf"]):"";
$err="";
$mess="";
$processSafeForm=(!empty($_REQUEST["processSafeForm"]))?$_REQUEST["processSafeForm"]:"";
$_SESSION["processSafe"]=(empty($_SESSION["processSafe"]))?"1":$_SESSION["processSafe"];
if(!empty($_REQUEST["process"]) && $_REQUEST["process"]=="1"
	&& !empty($processSafeForm) && $_SESSION["processSafe"]==$processSafeForm)
{
	$_SESSION["processSafe"]++;
	if($adminPass!=$passwordConf){$err.="Correct Password; ";}
	
	//So message will be written to DB
	if($err=="")
	{
		$min_order = isset($_POST['min_order']) ? $_POST['min_order'] : 0;
		$sSQL="UPDATE ".$tableName." SET
		                adminPass='".$adminPass."'
				WHERE  ".$tableID." = '".$$tableID."'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		//echo $sSQL;
		$mess="Information Saved.";
	}
	else
	{
		$err="Please Enter: ".$err;
	}

}
?>
<table width="100%" cellpadding="0" cellspacing="0">
<tr><td class="pageTitle">Change Password<br /></td></tr>
<tr><td height="7" background="/img/bg_dots.jpg" style="background-position:center; background-repeat:repeat-x;"></td></tr>
<tr>
<td style="padding-top:10px; padding-left:10px;">
<form name='ff1' class='form' method='POST' action='' enctype="multipart/form-data" >
<table cellpadding="0" cellspacing="0">
<? if($err!=""){ ?>
<tr><td align="center"><b style="color:#FF0000;"><?=$err?></b></td></tr>
<? } ?>
<? if($mess!=""){ ?>
<tr><td align="center"><b style="color:#009900;"><?=$mess?></b></td></tr>
<? } ?>
<tr> 
<td align="right" width="278">Password:&nbsp;&nbsp;</td>
<td><input type='password' name='adminPass' id="adminPass" value='' class='inputF' style="width:255px;"></td>
</tr>
<tr> 
<td align="right" width="278">Confirm Password:&nbsp;&nbsp;</td>
<td><input type='password' name='passwordConf' id="passwordConf" value='' class='inputF' style="width:255px;"></td>
</tr>
<tr> 
<td></td>
<td><input type="submit" name="Save" id="Save" value="Save" class='inputF' /></td>
	</tr>
</table>
<input type="hidden" name="process" id="process" value="1" /><input type="hidden" name="processSafeForm" id="processSafeForm" value="<?=$_SESSION["processSafe"]?>" />
<input type="hidden" name="<?=$tableID?>" id="<?=$tableID?>" value="<?=$$tableID?>" />
</form>
</td>
</tr>
</table>
<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/footer.php";
?>