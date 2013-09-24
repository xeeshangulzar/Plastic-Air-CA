<?
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";


$username=(!empty($_REQUEST["username"]))?str_replace("'","",$_REQUEST["username"]):"";
$email=(!empty($_REQUEST["email"]))?str_replace("'","",$_REQUEST["email"]):"";
$email2=(!empty($_REQUEST["email2"]))?str_replace("'","",$_REQUEST["email2"]):"";
$fname=(!empty($_REQUEST["fname"]))?addslashes($_REQUEST["fname"]):"";
$lname=(!empty($_REQUEST["lname"]))?addslashes($_REQUEST["lname"]):"";
$password=(!empty($_REQUEST["password"]))?str_replace("'","",$_REQUEST["password"]):"";
$password2=(!empty($_REQUEST["password2"]))?str_replace("'","",$_REQUEST["password2"]):"";

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

$process=(!empty($_REQUEST["process"]))?1:0;
$err = "";
$processSafeForm=(!empty($_REQUEST["processSafeForm"]))?$_REQUEST["processSafeForm"]:"";
$_SESSION["processSafe"]=(empty($_SESSION["processSafe"]))?"1":$_SESSION["processSafe"];

if(!empty($_REQUEST["process"]) && !empty($processSafeForm) && $_SESSION["processSafe"]==$processSafeForm) {
	$pSQL = "";
	$_SESSION["processSafe"]++;
	//checks
	if(!preg_match("/^[A-z0-9_\-\.]+[@][A-z0-9_\-\.]+([.][A-z0-9_\-\.]+)+$/", $email)){	$err .= "<li>Email is invalid</li>";}
	elseif($email != $email2)
	{
		$err .= "<li>Emails do not match</li>";	
	}
	else
	{
		$sSQL="SELECT usersID FROM users WHERE email = '".$email."' AND usersID <> '".$_SESSION['usersID']."'";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
		if ($row = mysql_fetch_array($result)){	$err .= "<li>Email already registered.</li>";}	
	}
	if($email!=$email2){	$err .= "<li>Emails do not match</li>";}
	if(!empty($password))
	{
		if(strlen($password) > 12 || strlen($password) < 4){	$err .= "<li>Password must be between 4 and 12 characters long</li>";}
		elseif($password!=$password2){	$err .= "<li>Passwords do not match</li>";}
		else
		{
			$pSQL = " password = '".addslashes(md5($password))."' ";	
		}
	}
	if(empty($fname)){	$err .= "<li>Please fill First Name</li>";}
	if(empty($lname)){	$err .= "<li>Please fill Last Name</li>";}
	
	if(empty($billingAddress)){	$err .= "<li>Please fill Billing Address</li>";}
	if(empty($billingCity)){	$err .= "<li>Please fill Billing City</li>";}
	if(empty($billingStateCode)){	$err .= "<li>Please select Billing State/Province</li>";}
	if(empty($billingCountry)){	$err .= "<li>Please fill Last Name</li>";}
	if(empty($billingZip)){	$err .= "<li>Please fill Billing Zip/Postal Code</li>";}
	if(empty($billingPhone)){	$err .= "<li>Please fill  Billing Phone Number</li>";}
	if(empty($billingContact)){	$err .= "<li>Please fill Billing Contact</li>";}
	
	if(empty($shippingAddress)){	$err .= "<li>Please fill Shipping Address</li>";}
	if(empty($shippingCity)){	$err .= "<li>Please fill Shipping City</li>";}
	if(empty($shippingStateCode)){	$err .= "<li>Please select Shipping State/Province</li>";}
	if(empty($shippingCountry)){	$err .= "<li>Please fill Last Name</li>";}
	if(empty($shippingZip)){	$err .= "<li>Please fill Shipping Zip/Postal Code</li>";}
	if(empty($shippingPhone)){	$err .= "<li>Please fill  Shipping Phone Number</li>";}
	if(empty($shippingContact)){	$err .= "<li>Please fill Shipping Contact</li>";}

	if(empty($err))
	{
		$sSQL="UPDATE users 
				SET ".$pSQL."
				email = '".$email."',
				fname = '".$fname."',
				lname = '".$lname."',
				billingAddress = '".$billingAddress."',
				billingCity = '".$billingCity."',
				billingStateCode = '".$billingStateCode."',
				billingCountry = '".$billingCountry."',
				billingZip = '".$billingZip."',
				billingPhone = '".$billingPhone."',
				billingPhone = '".$billingPhone."',
				billingPhone2 = '".$billingPhone2."',
				billingContact = '".$billingContact."',
				shippingAddress = '".$shippingAddress."',
				shippingCity = '".$shippingCity."',
				shippingStateCode = '".$shippingStateCode."',
				shippingCountry = '".$shippingCountry."',
				shippingZip = '".$shippingZip."',
				shippingPhone = '".$shippingPhone."',
				shippingPhone = '".$shippingPhone."',
				shippingPhone2 = '".$shippingPhone2."',
				shippingContact = '".$shippingContact."'
				WHERE usersID = '".$_SESSION['usersID']."'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
		$mess="Information Saved";
	}
}
$sSQL="SELECT * FROM users WHERE usersID = '".$_SESSION['usersID']."'";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
		if ($row = mysql_fetch_array($result)){	foreach($row as $k=>$v){$$k = $v;}}	
?><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold">Account Information</td></tr>
<tr><td colspan="2" height="40"></td></tr>
<tr><td width="25"></td>
<td align="left"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/inside/" class="subnav_link_active">Users</a><span class="subnav_divider">/</span><a href="/inside/account.php" class="subnav_link_active">Account</a></td></tr>
<tr><td>&nbsp;</td>
<td>&nbsp;</td></tr>
</table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table><?
$page_inside="My Profile";
include "inc/menu.php";
?><table width="665" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="3" height="5"></td></tr>
<? if(!empty($err)){?>
<tr><td width="13"></td>
<td width="640" style="color:#F00; padding-left:50px;" align="left"><ul><?=$err?></ul></td>
<td width="12"></td>
<? }?>
<? if(!empty($mess)){?>
<tr><td width="13"></td>
<td width="640" style="color:#093;"><?=$mess?></td>
<td width="12"></td>
<? }?>
<tr><td colspan="3" height="5"></td></tr>
<tr><td colspan="2" align="left"><table width="640" border="0" cellspacing="0" cellpadding="0" class="arial_13_grey_bold">
<tr><td colspan="3" class="arial_16_green_bold">Personal Information</td></tr>
<tr><td colspan="3" style="padding-top:2px; padding-bottom:7px;" ><div style="background-color:#666666; height:1px; widows:640px;"></div></td></tr>
<tr><td colspan="3" style="padding-bottom:7px;" class="arial_12_grey_bold">*Indicates a reuired field</td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">First Name*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Last Name*</td></tr>
<form name="registrationForm" id="registrationForm">
<input type="hidden" name="process" id="process" value="1">
<input type="hidden" name="processSafeForm" id="processSafeForm" value="<?=$_SESSION["processSafe"]?>" />
<tr><td align="left" width="300"><input name="fname" id="fname" value="<?=$fname?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300"><input name="lname" id="lname" value="<?=$lname?>" type="text" class="form"></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">Email*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Confirm Email*</td></tr>
<tr><td align="left" width="300"><input name="email" id="email" value="<?=$email?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300"><input name="email2" id="email2" value="<?=(empty($email2))?$email:$email2?>" type="text" class="form"></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">Password*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Confirm Passoword*</td></tr>
<tr><td align="left" width="300"><input name="password" id="password" type="password" class="form"></td>
<td width="40"></td>
<td align="left" width="300"><input name="password2" id="password2" type="password" class="form"></td></tr>
<tr><td colspan="3" height="30"></td></tr>
<tr><td colspan="3" class="arial_16_green_bold">Billing Information</td></tr>
<tr><td colspan="3" style="padding-top:2px; padding-bottom:7px;" ><div style="background-color:#666666; height:1px; widows:640px;"></div></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">Contact Name*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Address*</td></tr>
<tr><td align="left" width="300"><input name="billingContact" id="billingContact" value="<?=$billingContact?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300"><input name="billingAddress" id="billingAddress" value="<?=$billingAddress?>" type="text" class="form"></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">City*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Province/State*</td></tr>
<tr><td align="left" width="300"><input name="billingCity" id="billingCity" value="<?=$billingCity?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300">
<select name="billingStateCode" id="billingStateCode" class="form1">
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
</select>
</td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">Postal/Zip Code*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Country*</td></tr>
<tr><td align="left" width="300"><input name="billingZip" id="billingZip" value="<?=$billingZip?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300">
<select name="billingCountry" id="billingCountry" class="form1">
<option value="">Please Select</option><?
$sSQL="SELECT DISTINCT countryCode, countryName FROM countryCodes ORDER BY countryType, countryName ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
while($row1=mysql_fetch_array($result1)){
	?><option value="<?=$row1['countryCode']?>"<?=$billingCountry==$row1['countryCode']?' selected="selected"':'' ?>><?=$row1['countryName']?></option><? }
?></select>
</td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">Phone*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Other Phone (optional)</td></tr>
<tr><td align="left" width="300"><input name="billingPhone" id="billingPhone" value="<?=$billingPhone?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300"><input name="billingPhone2" id="billingPhone2" value="<?=$billingPhone2?>" type="text" class="form"></td></tr>
<tr><td colspan="3" height="30"></td></tr>
<tr><td colspan="3" style="padding-bottom:7px;" class="arial_12_grey_bold"><table border="0" cellspacing="0" cellpadding="0">
<tr><td style="padding-left:5px;"><a href="#" onclick="copyInfo(); return false;">Copy Billing Information</a></td></tr>
</table></td></tr>
<tr><td colspan="3" height="15"></td></tr>
<tr><td colspan="3" class="arial_16_green_bold">Shipping Information</td></tr>
<tr><td colspan="3" style="padding-top:2px; padding-bottom:7px;" ><div style="background-color:#666666; height:1px; widows:640px;"></div></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">Contact Name*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Address*</td></tr>
<tr><td align="left" width="300"><input name="shippingContact" id="shippingContact" value="<?=$shippingContact?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300"><input name="shippingAddress" id="shippingAddress" value="<?=$shippingAddress?>" type="text" class="form"></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">City*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Province/State*</td></tr>
<tr><td align="left" width="300"><input name="shippingCity" id="shippingCity" value="<?=$shippingCity?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300">
<select name="shippingStateCode" id="shippingStateCode" class="form1">
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
</select>
</td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">Postal/Zip Code*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Country*</td></tr>
<tr><td align="left" width="300"><input name="shippingZip" id="shippingZip" value="<?=$shippingZip?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300">
<select name="shippingCountry" id="shippingCountry" class="form1">
<option value="">Please Select</option><?
$sSQL="SELECT DISTINCT countryCode, countryName FROM countryCodes ORDER BY countryType, countryName ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
while($row1=mysql_fetch_array($result1)){
	?><option value="<?=$row1['countryCode']?>"<?=$shippingCountry==$row1['countryCode']?' selected="selected"':'' ?>><?=$row1['countryName']?></option><? }
?></select>
</td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">Phone*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Other Phone (optional)</td></tr>
<tr><td align="left" width="300"><input name="shippingPhone" id="shippingPhone" value="<?=$shippingPhone?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300"><input name="shippingPhone2" id="shippingPhone2" value="<?=$shippingPhone2?>" type="text" class="form"></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300">

</td>
<td width="40"></td>
<td align="left" width="300"><img src="/img/btn-submit.jpg" border="0" alt="" onclick="document.registrationForm.submit();" style="cursor:pointer;">
</td></tr>
</form>
</table></td>
<td width="12"></td></tr>
<tr><td colspan="3" height="15"></td></tr>
</table>
<script type="text/javascript" language="javascript">
function copyInfo()
{
	document.getElementById('shippingContact').value = document.getElementById('billingContact').value;	
	document.getElementById('shippingAddress').value = document.getElementById('billingAddress').value;	
	document.getElementById('shippingCity').value = document.getElementById('billingCity').value;	
	document.getElementById('shippingStateCode').value = document.getElementById('billingStateCode').value;	
	document.getElementById('shippingCountry').value = document.getElementById('billingCountry').value;	
	document.getElementById('shippingZip').value = document.getElementById('billingZip').value;	
	document.getElementById('shippingPhone').value = document.getElementById('billingPhone').value;	
	document.getElementById('shippingPhone2').value = document.getElementById('billingPhone2').value;	
}

</script>
<?
include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php";
?>
