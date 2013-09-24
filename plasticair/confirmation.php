<?
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
$usersID = (!empty($_REQUEST['i']))?str_replace("'","",$_REQUEST['i']):"";
$confirmationCode = (!empty($_REQUEST['c']))?str_replace("'","",$_REQUEST['c']):"";
$err = "There was an error with confirmation process.";
if(is_numeric($usersID) && is_numeric($confirmationCode))
{
	$sSQL="SELECT usersID,confirmed FROM users WHERE usersID='".$usersID."' AND confirmationCode = '".$confirmationCode."'";
	$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
	if($row=mysql_fetch_array($result)){	
		if(empty($row['confirmed']))
		{ 
			$sSQL="UPDATE users SET confirmed = 1 WHERE usersID='".$usersID."' AND confirmationCode = '".$confirmationCode."'";
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
			$err = "";
		}
		else{	$err = "Your account has already been confirmed.";	}
	}
	else {$err = "There was an error with confirmation process.";}
}
?><table width="665" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2"><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold">Email Confirmation</td></tr>
<tr><td colspan="2" height="40"></td></tr>
<tr><td width="25"></td>
<td align="left"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/confirmation.php" class="subnav_link_active">Email Confirmation</a></td></tr>
<tr><td>&nbsp;</td>
<td>&nbsp;</td></tr>
</table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table></td></tr>

<tr><td><table width="665" border="0" cellspacing="0" cellpadding="0">
<tr><td width="13"></td><td width="640" style="color:#093; padding:35px 0;"> 
<? if(!empty($err)) {?><span style="color:#F00;"><?=$err?></span><? }
else{?>Welcome to Plasticair,<br /><br />
Your account has been successfully confirmed and activated. You can login using your email and password.<? }?></td>
<td width="12"></td></tr>
<tr><td colspan="3" height="30"></td></tr>
<tr><td width="13"></td>
<td width="640"></td>
<td width="12"></td></tr>
</table></td></tr>
<!-- Content ends here-->
</table><?
include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php";?>