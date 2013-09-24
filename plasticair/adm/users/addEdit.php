<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";

$tableName="users";
$tableID="usersID";
$folderName="users";
$pageTitle="Users";
$filesPath="/dat/users/";

$$tableID=(!empty($_REQUEST[$tableID]))?str_replace("'","",$_REQUEST[$tableID]):"0";
$usersID=(!empty($_REQUEST["usersID"]))?addslashes($_REQUEST["usersID"]):"";
$active=(!empty($_REQUEST["active"]))?1:0;
$confirmed=(!empty($_REQUEST["confirmed"]))?1:0;
$fname=(!empty($_REQUEST["fname"]))?addslashes($_REQUEST["fname"]):"";
$lname = (!empty($_REQUEST['lname']))?str_replace("'","",$_REQUEST['lname']):"";
$email = (!empty($_REQUEST['email']))?str_replace("'","",$_REQUEST['email']):"";

$billingAddress=(!empty($_REQUEST["billingAddress"]))?addslashes($_REQUEST["billingAddress"]):"";
$billingCity=(!empty($_REQUEST["billingCity"]))?addslashes($_REQUEST["billingCity"]):"";
$billingStateCode=(!empty($_REQUEST["billingStateCode"]))?addslashes($_REQUEST["billingStateCode"]):"";
$billingCountry=(!empty($_REQUEST["billingCountry"]))?addslashes($_REQUEST["billingCountry"]):"";
$billingZip=(!empty($_REQUEST["billingZip"]))?addslashes($_REQUEST["billingZip"]):"";
$billingPhone=(!empty($_REQUEST["billingPhone"]))?addslashes($_REQUEST["billingPhone"]):"";
$billingPhone2=(!empty($_REQUEST["billingPhone2"]))?addslashes($_REQUEST["billingPhone2"]):"";
$billingContact=(!empty($_REQUEST["billingContact"]))?addslashes($_REQUEST["billingContact"]):"";

$shippingAddress=(!empty($_REQUEST["shippingAddress"]))?addslashes($_REQUEST["shippingAddress"]):"";
$shippingCity=(!empty($_REQUEST["shippingCity"]))?addslashes($_REQUEST["shippingCity"]):"";
$shippingStateCode=(!empty($_REQUEST["shippingStateCode"]))?addslashes($_REQUEST["shippingStateCode"]):"";
$shippingCountry=(!empty($_REQUEST["shippingCountry"]))?addslashes($_REQUEST["shippingCountry"]):"";
$shippingZip=(!empty($_REQUEST["shippingZip"]))?addslashes($_REQUEST["shippingZip"]):"";
$shippingPhone=(!empty($_REQUEST["shippingPhone"]))?addslashes($_REQUEST["shippingPhone"]):"";
$shippingPhone2=(!empty($_REQUEST["shippingPhone2"]))?addslashes($_REQUEST["shippingPhone2"]):"";
$shippingContact=(!empty($_REQUEST["shippingContact"]))?addslashes($_REQUEST["shippingContact"]):"";

$_SESSION["processSafe"]=(empty($_SESSION["processSafe"]))?"1":$_SESSION["processSafe"];
$processSafeForm=(!empty($_REQUEST["processSafeForm"]))?$_REQUEST["processSafeForm"]:"";

$err="";
$mess="";

if(!empty($_REQUEST["process"]) && !empty($processSafeForm) && $_SESSION["processSafe"]==$processSafeForm) {

	$_SESSION["processSafe"]++;

	//mandatory fields
	if($usersID==""){$$tableID="0";}
	if($fname==""){$err.="<li>First Name</il> ";}
	if($lname==""){$err.="<li>Last Name</il> ";}
	if($email==""){$err.="<li>Email</il> ";}
	
	if($billingContact==""){$err.="<li>Billing Contact</il> ";}
	if($billingAddress==""){$err.="<li>Billing Address</il> ";}
	if($billingCity==""){$err.="<li>Billing City</il> ";}
	if($billingStateCode==""){$err.="<li>Billing State/Province</il> ";}
	if($billingCountry==""){$err.="<li>Billing Country</il> ";}
	if($billingZip==""){$err.="<li>Billing Zip/Postal Code</il> ";}
	if($billingPhone==""){$err.="<li>Billing Phone</il> ";}
	
	if($shippingContact==""){$err.="<li>Shipping Contact</il> ";}
	if($shippingAddress==""){$err.="<li>Shipping Address</il> ";}
	if($shippingCity==""){$err.="<li>Shipping City</il> ";}
	if($shippingStateCode==""){$err.="<li>Shipping State/Province</il> ";}
	if($shippingCountry==""){$err.="<li>Shipping Country</il> ";}
	if($shippingZip==""){$err.="<li>Shipping Zip/Postal Code</il> ";}
	if($shippingPhone==""){$err.="<li>Shipping Phone</il> ";}

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
		
		$sSQL="UPDATE ".$tableName." SET
						fname='".$fname."',
						lname='".$lname."',
						active='".$active."',
						confirmed='".$confirmed."',
						billingAddress='".$billingAddress."',
						billingCity='".$billingCity."',
						billingStateCode='".$billingStateCode."',
						billingCountry='".$billingCountry."',
						billingZip='".$billingZip."',
						billingPhone='".$billingPhone."',
						billingPhone2='".$billingPhone2."',
						billingContact='".$billingContact."',
						shippingAddress='".$shippingAddress."',
						shippingCity='".$shippingCity."',
						shippingStateCode='".$shippingStateCode."',
						shippingCountry='".$shippingCountry."',
						shippingZip='".$shippingZip."',
						shippingPhone='".$shippingPhone."',
						shippingPhone2='".$shippingPhone2."',
						shippingContact='".$shippingContact."',
						email='".$email."'
					    WHERE  ".$tableID." = '".$$tableID."'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		
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
                        </tr>
                      </table></td>
                      <td width="2" background="/adm/img/tabs_bottom_bg_right.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                    </tr>
                  </table></td></tr>
<tr><td class="contentCell"><table border="0" cellspacing="0" cellpadding="3" class="text" width="100%"><tr><td>&nbsp;</td><td>&nbsp;</td></tr><?
if (!empty($err)) { ?><tr><td colspan="2" class="err"><?=$err?></td></tr><? } 
if (!empty($mess)) { ?><tr><td colspan="2" class="message"><?=$mess?></td></tr><? }
?>
					<tr>
						<td align="right">Active : </td> 
						<td><input type="checkbox" value="1" name="active" id="active" <?=(!empty($active))?"checked='checked'":""?> /></td>
						<td align="right">Confirmed : </td>
						<td><input type="checkbox" value="1" name="confirmed" id="confirmed" <?=(!empty($confirmed))?"checked='checked'":""?> /></td>
					</tr>
					<tr>
						<td align="right">First Name : </td>
						<td><input type="text" name="fname" id="fname" value="<?=$fname?>" class="inputField" style="width:200px;" /></td>
						<td align="right">Last Name : </td>
						<td><input type="text" name="lname" id="lname" value="<?=$lname?>" class="inputField" style="width:200px;" /></td>
					</tr>
					<tr>
						<td align="right">Email : </td>
						<td><input type="text" name="email" id="email" value="<?=$email?>" class="inputField" style="width:200px;" /></td>
					</tr>
					<tr>
						<td colspan="4" ><hr /></td>
					</tr>
					<tr><td></td><td align="left"><strong>Billing Information</strong></td></tr>
					<tr>
						<td align="right">Contact Name : </td>
						<td><input type="text" name="billingContact" id="billingContact" value="<?=$billingContact?>" class="inputField" style="width:200px;" /></td>
						<td align="right">Address : </td>
						<td><input type="text" name="billingAddress" id="billingAddress" value="<?=$billingAddress?>" class="inputField" style="width:200px;" /></td>
					</tr>
					<tr>
						<td align="right">City : </td>
						<td><input type="text" name="billingCity" id="billingCity" value="<?=$billingCity?>" class="inputField" style="width:200px;" /></td>
						<td align="right">State/Province : </td>
						<td><select name="billingStateCode" id="billingStateCode" class="inputField" style="width:200px;">
							<option value="">Please Select</option>
							<option value="" disabled="disabled">===========Canada===========</option>
							  <?
							  $sc = 1;
							  $sql="SELECT * FROM stateCodes ORDER BY stateType DESC,stateName";
								$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
								while($row=mysql_fetch_assoc($result)){
									if($sc != $row['stateType']){$sc = $row['stateType']; ?><option value="" disabled="disabled">============USA============</option><? }
							  ?>
							  <option value="<?=$row['stateCode']?>" <?=($row['stateCode'] == $billingStateCode)?"selected='selected'":""?>><?=$row['stateName']?></option>
							  <? }?>
							</select></td>
					</tr>
					<tr>
						<td align="right">Zip/Postal Code : </td>
						<td><input type="text" name="billingZip" id="billingZip" value="<?=$billingZip?>" class="inputField" style="width:200px;" /></td>
						<td align="right">Country : </td>
						<td><select name="billingCountry" id="billingCountry" class="inputField" style="width:200px;">
<option value="">Please Select</option><?
$sSQL="SELECT DISTINCT countryCode, countryName FROM countryCodes ORDER BY countryType, countryName ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
while($row1=mysql_fetch_array($result1)){
	?><option value="<?=$row1['countryCode']?>"<?=$billingCountry==$row1['countryCode']?' selected="selected"':'' ?>><?=$row1['countryName']?></option><? }
?></select></td>
					</tr>
					<tr>
						<td align="right">Phone 1 : </td>
						<td><input type="text" name="billingPhone" id="billingPhone" value="<?=$billingPhone?>" class="inputField" style="width:200px;" /></td>
						<td align="right">Phone 2 : </td>
						<td><input type="text" name="billingPhone2" id="billingPhone2" value="<?=$billingPhone2?>" class="inputField" style="width:200px;" /></td>
					</tr>
					<tr>
						<td colspan="4" ><hr /></td>
					</tr>
					<tr><td></td><td align="left"><strong>Shipping Information</strong></td></tr>
					<tr>
						<td align="right">Contact Name : </td>
						<td><input type="text" name="shippingContact" id="shippingContact" value="<?=$shippingContact?>" class="inputField" style="width:200px;" /></td>
						<td align="right">Address : </td>
						<td><input type="text" name="shippingAddress" id="shippingAddress" value="<?=$shippingAddress?>" class="inputField" style="width:200px;" /></td>
					</tr>
					<tr>
						<td align="right">City : </td>
						<td><input type="text" name="shippingCity" id="shippingCity" value="<?=$shippingCity?>" class="inputField" style="width:200px;" /></td>
						<td align="right">State/Province : </td>
						<td><select name="shippingStateCode" id="shippingStateCode" class="inputField" style="width:200px;">
							<option value="">Please Select</option>
							<option value="" disabled="disabled">===========Canada===========</option>
							  <?
							  $sc = 1;
							  $sql="SELECT * FROM stateCodes ORDER BY stateType DESC,stateName";
								$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
								while($row=mysql_fetch_assoc($result)){
									if($sc != $row['stateType']){$sc = $row['stateType']; ?><option value="" disabled="disabled">============USA============</option><? }
							  ?>
							  <option value="<?=$row['stateCode']?>" <?=($row['stateCode'] == $shippingStateCode)?"selected='selected'":""?>><?=$row['stateName']?></option>
							  <? }?>
							</select></td>
					</tr>
					<tr>
						<td align="right">Zip/Postal Code : </td>
						<td><input type="text" name="shippingZip" id="shippingZip" value="<?=$shippingZip?>" class="inputField" style="width:200px;" /></td>
						<td align="right">Country : </td>
						<td><select name="shippingCountry" id="shippingCountry" class="inputField" style="width:200px;">
<option value="">Please Select</option><?
$sSQL="SELECT DISTINCT countryCode, countryName FROM countryCodes ORDER BY countryType, countryName ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
while($row1=mysql_fetch_array($result1)){
	?><option value="<?=$row1['countryCode']?>"<?=$shippingCountry==$row1['countryCode']?' selected="selected"':'' ?>><?=$row1['countryName']?></option><? }
?></select></td>
					</tr>
					<tr>
						<td align="right">Phone 1 : </td>
						<td><input type="text" name="shippingPhone" id="shippingPhone" value="<?=$shippingPhone?>" class="inputField" style="width:200px;" /></td>
						<td align="right">Phone 2 : </td>
						<td><input type="text" name="shippingPhone2" id="shippingPhone2" value="<?=$shippingPhone2?>" class="inputField" style="width:200px;" /></td>
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
<script type="text/javascript" language="javascript">
function deleteFiles(id)
{
	if(confirm(" File will be deleted permanenty please confirm. "))
	{
		document.getElementById('deleteFile').value = id; 
		document.getElementById('ff1').submit();
	}
}
</script>
<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/footer.php";
?>