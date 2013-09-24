<? 
$page_name="Registration";
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";

$username=(!empty($_REQUEST["username"]))?str_replace("'","",$_REQUEST["username"]):"";
$email=(!empty($_REQUEST["email"]))?str_replace("'","",$_REQUEST["email"]):"";
$email2=(!empty($_REQUEST["email2"]))?str_replace("'","",$_REQUEST["email2"]):"";
$fname=(!empty($_REQUEST["fname"]))?addslashes($_REQUEST["fname"]):"";
$lname=(!empty($_REQUEST["lname"]))?addslashes($_REQUEST["lname"]):"";
$pass=(!empty($_REQUEST["pass"]))?str_replace("'","",$_REQUEST["pass"]):"";
$pass2=(!empty($_REQUEST["pass2"]))?str_replace("'","",$_REQUEST["pass2"]):"";

$billingAddress=(!empty($_REQUEST["billingAddress"]))?addslashes($_REQUEST["billingAddress"]):"";
$billingCity=(!empty($_REQUEST["billingCity"]))?addslashes($_REQUEST["billingCity"]):"";
$billingStateCode=(!empty($_REQUEST["billingStateCode"]))?addslashes($_REQUEST["billingStateCode"]):"";
$billingCountry=(!empty($_REQUEST["billingCountry"]))?addslashes($_REQUEST["billingCountry"]):"";
$billingZip=(!empty($_REQUEST["billingZip"]))?addslashes($_REQUEST["billingZip"]):"";
$billingPhone=(!empty($_REQUEST["billingPhone"]))?addslashes($_REQUEST["billingPhone"]):"";
$billingPhone2=(!empty($_REQUEST["billingPhone2"]))?addslashes($_REQUEST["billingPhone2"]):"";
$billingContact=(!empty($_REQUEST["billingContact"]))?addslashes($_REQUEST["billingContact"]):"";

$shipping_same=(!empty($_REQUEST["shipping_same"]))?addslashes($_REQUEST["shipping_same"]):"";

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

	$_SESSION["processSafe"]++;
	//checks
	if(md5($_REQUEST["secretNumber"])!=$_SESSION["secureNumber"]){$err .= "<li>Secure number does not match</li>";}
	if(!preg_match("/^[A-z0-9_\-\.]+[@][A-z0-9_\-\.]+([.][A-z0-9_\-\.]+)+$/", $email)){	$err .= "<li>Email is invalid</li>";}
	elseif($email != $email2)
	{
		$err .= "<li>Emails do not match</li>";	
	}
	else
	{
		$sSQL="SELECT usersID FROM users WHERE email = '".$email."'";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
		if ($row = mysql_fetch_array($result)){	$err .= "<li>Email already registered.</li>";}	
	}
	if($email!=$email2){	$err .= "<li>Emails do not match</li>";}
	if(strlen($pass) > 12 || strlen($pass) < 4){	$err .= "<li>Password must be between 4 and 12 characters long</li>";}
	if($pass!=$pass2){	$err .= "<li>Passwords do not match</li>";}
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
		$code = rand(1000000000,9999999999);
		$sSQL="INSERT INTO users (email, dateCreated, confirmationCode, password, fname, lname, active, confirmed, billingAddress, billingCity, 
			billingStateCode, billingCountry, billingZip, billingPhone, billingPhone2, billingContact, shippingAddress, shippingCity, shippingStateCode, 
			shippingCountry, shippingZip, shippingPhone, shippingPhone2, shippingContact)
			VALUES('".$email."',NOW(),'".$code."','".str_replace("'","",md5($pass))."','".$fname."','".$lname."',1,0,'".$billingAddress."','".$billingCity."','".$billingStateCode."','".$billingCountry."','".$billingZip."','".$billingPhone."','".$billingPhone2."','".$billingContact."','".$shippingAddress."','".$shippingCity."','".$shippingStateCode."','".$shippingCountry."','".$shippingZip."','".$shippingPhone."','".$shippingPhone2."','".$shippingContact."')";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
		$usersID = mysql_insert_id();
		// sending apropriate emails
		$mailbody = "";
		$mailbody .= "Dear ".$fname." ".$lname."<br/><br/>";
		$mailbody .= "Welcome to Plasticair <br/><br/>";
		$mailbody .= "Please <a href='http://".$_SERVER['HTTP_HOST']."/confirmation.php?i=".$usersID."&c=".$code."'>click here</a> to confirm your email.<br/><br/>Or copy following address into your browser: ".$_SERVER['HTTP_HOST']."/confirmation.php?i=".$usersID."&c=".$code."<br><br>
					  <i>".$_SERVER['HTTP_HOST']."</i>
					  ";
		$headers  = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		$headers .= "From: Plasticair <info@".$_SERVER['HTTP_HOST']."> \n"; 
		$subject = "Confirm your Plasticair account, ".$fname." ".$lname."!";
		mail($email, $subject, $mailbody, $headers);
		$mess="Thank you for registering with us. Your account has been created and an activation link has been sent to  ".$email;
		$mess.="<br><br>"; 
		$mess.="Note that you must activate the account by clicking on the activation link when you get the email before you can login";

$username="";
$email="";
$email2="";
$fname="";
$lname="";

$billingAddress="";
$billingCity="";
$billingStateCode="";
$billingCountry="";
$billingZip="";
$billingPhone="";
$billingPhone2="";
$billingContact="";

$shipping_same="";

$shippingAddress="";
$shippingCity="";
$shippingStateCode="";
$shippingCountry="";
$shippingZip="";
$shippingPhone="";
$shippingPhone2="";
$shippingContact="";
	}
}

?>
<table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2"><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold">Registration</td></tr>
<tr><td colspan="2" height="40"></td></tr>
<tr><td width="25"></td>
<td align="left"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/registration.php" class="subnav_link_active">Registration</a></td></tr>
<tr><td>&nbsp;</td>
<td>&nbsp;</td></tr>
</table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table></td></tr>
<tr><td colspan="2" height="13"></td></tr>
<tr><td width="665" align="left"><?=!empty($err)?("<ul>".$err."</ul>"):""?>
<table width="640" border="0" cellspacing="0" cellpadding="0" align="left"><?
if(!empty($mess)){?><tr><td colspan="3" style="color:#093; padding:1opx 25px;"><?=$mess?></td></tr><? }
else { ?>
<tr><td colspan="3" class="arial_16_green_bold">Personal Information</td></tr>
<tr><td colspan="3" style="padding-top:2px; padding-bottom:7px;" ><div style="background-color:#666666; height:1px; widows:640px;"></div></td></tr>
<tr>
  <td colspan="3" style="padding-bottom:7px;" class="arial_12_grey_bold">*Indicates a required field</td></tr>
<tr><td align="left" width="217" style="padding-left:4px;" class="arial_13_grey_bold">First Name*</td>
<td width="31"></td>
<td align="left" width="392" style="padding-left:4px;" class="arial_13_grey_bold">Last Name*</td></tr>

<form name="registrationForm" id="registrationForm">
<input type="hidden" name="process" id="process" value="1">
<input type="hidden" name="processSafeForm" id="processSafeForm" value="<?=$_SESSION["processSafe"]?>" />
<tr><td align="left" width="217"><input name="fname" id="fname" value="<?=$fname?>" type="text" class="form"></td>
<td width="31"></td>
<td align="left" width="392"><input name="lname" id="lname" value="<?=$lname?>" type="text" class="form"></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="217" class="arial_13_grey_bold" style="padding-left:4px;">Email*</td>
<td width="31"></td>
<td align="left" width="392" style="padding-left:4px;" class="arial_13_grey_bold">Confirm Email*</td></tr>
<tr><td align="left" width="217"><input name="email" id="email" value="<?=$email?>" type="text" class="form"></td>
<td width="31"></td>
<td align="left" width="392"><input name="email2" id="email2" value="<?=$email2?>" type="text" class="form"></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="217" class="arial_13_grey_bold" style="padding-left:4px;">Password*</td>
<td width="31"></td>
<td align="left" width="392" class="arial_13_grey_bold" style="padding-left:4px;">Re-type Password*</td></tr>
<tr><td align="left" width="217"><input name="pass" id="pass" type="password" class="form"></td>
<td width="31"></td>
<td align="left" width="392"><input name="pass2" id="pass2" type="password" class="form"></td></tr>
<tr><td colspan="3" height="30"></td></tr>
<tr><td colspan="3" class="arial_16_green_bold">Billing Information</td></tr>
<tr><td colspan="3" style="padding-top:2px; padding-bottom:7px;" ><div style="background-color:#666666; height:1px; widows:640px;"></div></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="217" style="padding-left:4px;" class="arial_13_grey_bold">Contact Name*</td>
<td width="31"></td>
<td align="left" width="392" style="padding-left:4px;" class="arial_13_grey_bold">Address*</td></tr>
<tr><td align="left" width="217"><input name="billingContact" id="billingContact" value="<?=$billingContact?>" type="text" class="form"></td>
<td width="31"></td>
<td align="left" width="392"><input name="billingAddress" id="billingAddress" value="<?=$billingAddress?>" type="text" class="form"></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="217" class="arial_13_grey_bold" style="padding-left:4px;">City</td>
<td width="31"></td>
<td align="left" width="392" class="arial_13_grey_bold" style="padding-left:4px;">Province</td></tr>
<tr><td align="left" width="217"><input name="billingCity" id="billingCity" value="<?=$billingCity?>" type="text" class="form"></td>
<td width="31"></td>
<td align="left" width="392">
<select name="billingStateCode" id="billingStateCode" class="form1">
<option value="">Please Select</option>
<option value="" disabled="disabled">===========Canada===========</option><?
  $sc = 1;
  $sql="SELECT * FROM stateCodes ORDER BY stateType DESC,stateName";
    $result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
    while($row=mysql_fetch_assoc($result)){
        if($sc != $row['stateType']){$sc = $row['stateType']; ?><option value="" disabled="disabled">============USA============</option><? }
  ?><option value="<?=$row['stateCode']?>" <?=($row['stateCode'] == $billingStateCode)?"selected='selected'":""?>><?=$row['stateName']?></option><? }
?></select></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="217" class="arial_13_grey_bold" style="padding-left:4px;">Postal Code</td>
<td width="31"></td>
<td align="left" width="392" class="arial_13_grey_bold" style="padding-left:4px;">Country</td></tr>
<tr><td align="left" width="217"><input name="billingZip" id="billingZip" value="<?=$billingZip?>" type="text" class="form"></td>
<td width="31"></td>
<td align="left" width="392"><select name="billingCountry" id="billingCountry" class="form1"><?
$sSQL="SELECT DISTINCT countryCode, countryName FROM countryCodes ORDER BY countryType, countryName ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
while($row1=mysql_fetch_array($result1)){
	?><option value="<?=$row1['countryCode']?>"<?=$billingCountry==$row1['countryCode']?' selected="selected"':'' ?>><?=$row1['countryName']?></option><? }
?></select></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="217" class="arial_13_grey_bold" style="padding-left:4px;">Phone</td>
<td width="31"></td>
<td align="left" width="392" class="arial_13_grey_bold" style="padding-left:4px;">Other Phone (optional)</td></tr>
<tr><td align="left" width="217"><input name="billingPhone" id="billingPhone" value="<?=$billingPhone?>" type="text" class="form"></td>
<td width="31"></td>
<td align="left" width="392"><input name="billingPhone2" id="billingPhone2" value="<?=$billingPhone2?>" type="text" class="form"></td></tr>
<tr><td colspan="3" height="30"></td></tr>
<tr><td colspan="3" style="padding-bottom:7px;" class="arial_12_light_grey_bold"><table border="0" cellspacing="0" cellpadding="0">
<tr><td><input name="shipping_same" type="checkbox" value="1" <?=!empty($shipping_same)?' checked="checked"':''?> onclick="copyInfo(this);"></td>
<td style="padding-left:5px;">Same as Billing Address</td></tr>
</table></td></tr>
<tr><td colspan="3" height="15"></td></tr>
<tr><td colspan="3" class="arial_16_green_bold" align="left">Shipping Information</td></tr>
<tr><td colspan="3" style="padding-top:2px; padding-bottom:7px;" ><div style="background-color:#666666; height:1px; widows:640px;"></div></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="217" class="arial_13_grey_bold" style="padding-left:4px;">Contact Name</td>
<td width="31"></td>
<td align="left" width="392" class="arial_13_grey_bold" style="padding-left:4px;">Address</td></tr>
<tr><td align="left" width="217"><input name="shippingContact" id="shippingContact" value="<?=$shippingContact?>" type="text" class="form"></td>
<td width="31"></td>
<td align="left" width="392"><input name="shippingAddress" id="shippingAddress" value="<?=$shippingAddress?>" type="text" class="form"></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="217" class="arial_13_grey_bold" style="padding-left:4px;">City</td>
<td width="31"></td>
<td align="left" width="392" class="arial_13_grey_bold" style="padding-left:4px;">Province</td></tr>
<tr><td align="left" width="217"><input name="shippingCity" id="shippingCity" value="<?=$shippingCity?>" type="text" class="form"></td>
<td width="31"></td>
<td align="left" width="392"><select name="shippingStateCode" id="shippingStateCode" class="form1">
<option value="">Please Select</option>
<option value="" disabled="disabled">===========Canada===========</option><?
$sc = 1;
$sql="SELECT * FROM stateCodes ORDER BY stateType DESC,stateName";
$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
while($row=mysql_fetch_assoc($result)){
if($sc != $row['stateType']){$sc = $row['stateType']; ?><option value="" disabled="disabled">============USA============</option><? }
?><option value="<?=$row['stateCode']?>" <?=($row['stateCode'] == $shippingStateCode)?"selected='selected'":""?>><?=$row['stateName']?></option><? 
}?></select></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="217" class="arial_13_grey_bold" style="padding-left:4px;">Postal Code</td>
<td width="31"></td>
<td align="left" width="392" class="arial_13_grey_bold" style="padding-left:4px;">Country</td></tr>
<tr><td align="left" width="217"><input name="shippingZip" id="shippingZip" value="<?=$shippingZip?>" type="text" class="form"></td>
<td width="31"></td>
<td align="left" width="392"><select name="shippingCountry" id="shippingCountry" class="form1"><?

$sSQL="SELECT DISTINCT countryCode, countryName FROM countryCodes ORDER BY countryType, countryName ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
while($row1=mysql_fetch_array($result1)){
	?><option value="<?=$row1['countryCode']?>"<?=$shippingCountry==$row1['countryCode']?' selected="selected"':'' ?>><?=$row1['countryName']?></option><? }
?></select></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="217" class="arial_13_grey_bold" style="padding-left:4px;">Phone</td>
<td width="31"></td>
<td align="left" width="392" class="arial_13_grey_bold" style="padding-left:4px;">Other Phone (optional)</td></tr>
<tr><td align="left" width="217"><input name="shippingPhone" id="shippingPhone" value="<?=$shippingPhone?>" type="text" class="form"></td>
<td width="31"></td>
<td align="left" width="392"><input name="shippingPhone2" id="shippingPhone2" value="<?=$shippingPhone2?>" type="text" class="form"></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="217" class="arial_13_grey_bold" style="padding-left:4px;">Secure Number</td>
<td width="31"></td>
<td align="left" width="392" style="padding-left:4px;"></td></tr>
<tr><td align="left" width="217">
<table border="0" cellspacing="0" cellpadding="0">
<tr><td><input type="text" class="form" style="width:100px;" name="secretNumber" value="" maxlength="6" ></td>
<td><img src="/inc/secureImage.php" border="0" style="border:#CCC 1px solid; height:22px; margin:0 0 0 10px;" align="absmiddle" /></td></tr>
</table></td>
<td width="31"></td>
<td align="left" width="392"><img src="/img/btn-submit.jpg" border="0" alt="" onclick="return chkFields();" style="cursor:pointer;"></td></tr>
</form><? }
?></table></td><td width="10"></td></tr></table>
<? include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php"; ?><script language="JavaScript" type="text/javascript">
var reLogin = /^([a-zA-Z0-9]{4,33})+$/;
var reField = /^([a-zA-Z0-9\'\-\.\,\s]{1,41})+$/;
var rePhone = /^([0-9\)\(\-\.\+\s]{4,41})+$/;

function chSize2(sss, aaa, bbb, ccc){ 
	var rePhone = /^[0-9]+$/;
	if(!rePhone.test(Trim(sss.value)) && Trim(sss.value)!=''){ alert("wrong_phone"); sss.value=""; }
	else if(sss.value.length >= 1*bbb){ document.ff1[aaa].select();}
	}
	
function chkFields(){
		var ret; var mess;
		var reg1 = /(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/; // not valid
		var reg2 = /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/; // valid	
		var rePhoneNumber = new RegExp(/^(\d{1})?\+?\s?\(?[1-9]\d{2}\)?\s?\d{3}\-?\s?\d{4}$/);	
		ret=true;
		mess="";
		sForm=document.registrationForm;
		if(Trim(sForm.fname.value)==""){		ret=false; 	mess += "\n - Name";}
		if(Trim(sForm.lname.value)=="" ){		ret=false; 	mess += "\n - Last name";}
	  	var str = sForm.email.value; // email string
	  	if (reg1.test(str) || !reg2.test(str) || str!=sForm.email2.value) { 	ret=false;	mess += "\n - Email wrong";  }
		
		if(Trim(sForm.pass.value)=="" || Trim(sForm.pass.value)!=Trim(sForm.pass2.value)){		ret=false; 	mess += "\n - Password/Confirm Password";}
		if(Trim(sForm.billingContact.value)==""){		ret=false; 	mess += "\n - Billing Contact Name";}
		if(Trim(sForm.billingAddress.value)==""){		ret=false; 	mess += "\n - Billing Address";}
		if(Trim(sForm.billingCity.value)==""){ret=false; 	mess += "\n - Billing City";}
		if(Trim(sForm.billingStateCode.value)=="" && (Trim(sForm.billingCountry.value)=="CA" || Trim(sForm.billingCountry.value)=="US")){	ret=false; 	mess += "\n - Billing Province/State";}
		if(Trim(sForm.billingCountry.value)==""){	ret=false; 	mess += "\n - Billing Country";}		
		//if(!rePhoneNumber.test(sForm.billingPhone.value)){		ret=false; 	mess += "\n - Wrong Billing Phone";}
		if(Trim(sForm.billingPhone.value)==""){		ret=false; 	mess += "\n - Billing Phone";}
				
		
		if(Trim(sForm.shippingContact.value)==""){		ret=false; 	mess += "\n - Shipping Contact Name";}
		if(Trim(sForm.shippingAddress.value)==""){		ret=false; 	mess += "\n - Shipping Address";}
		if(Trim(sForm.shippingCity.value)==""){ret=false; 	mess += "\n - Shipping City";}
		if(Trim(sForm.shippingStateCode.value)=="" && (Trim(sForm.shippingCountry.value)=="CA" || Trim(sForm.shippingCountry.value)=="US")){	ret=false; 	mess += "\n - Shipping Province/State";}
		if(Trim(sForm.shippingCountry.value)==""){	ret=false; 	mess += "\n - Shipping Country";}		
		if(Trim(sForm.shippingPhone.value)==""){		ret=false; 	mess += "\n - Shipping Phone";}
		
		if(Trim(sForm.secretNumber.value)==""){		ret=false; 	mess += "\n - Enter the number";}
		else if(!reField.test(Trim(sForm.secretNumber.value))){mess+="\n - Enter the number has invalid characters ";	ref=false;}
		
		if(!ret) {alert("Next Field Required:" + mess); return ret;}
		else{ sForm.process.value='submited'; sForm.submit(); }
		}
function copyInfo(o_obj)
{
	if(o_obj.checked){
	document.getElementById('shippingContact').value = document.getElementById('billingContact').value;	
	document.getElementById('shippingAddress').value = document.getElementById('billingAddress').value;	
	document.getElementById('shippingCity').value = document.getElementById('billingCity').value;	
	document.getElementById('shippingStateCode').value = document.getElementById('billingStateCode').value;	
	document.getElementById('shippingCountry').value = document.getElementById('billingCountry').value;	
	document.getElementById('shippingZip').value = document.getElementById('billingZip').value;	
	document.getElementById('shippingPhone').value = document.getElementById('billingPhone').value;	
	document.getElementById('shippingPhone2').value = document.getElementById('billingPhone2').value;}	
}
<?
if(!empty($shipping_same)){?>copyInfo(document.registrationForm.shipping_same)<? }
?>
</script>