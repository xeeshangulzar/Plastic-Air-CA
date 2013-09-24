<?
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";

$email=(!empty($_REQUEST["email"]))?str_replace("'","",$_REQUEST["email"]):"";

$err = "";
$mess = "";
$processSafeForm=(!empty($_REQUEST["processSafeForm"]))?$_REQUEST["processSafeForm"]:"";
$_SESSION["processSafe"]=(empty($_SESSION["processSafe"]))?"1":$_SESSION["processSafe"];

if(!empty($_REQUEST["process"]) && !empty($processSafeForm) && $_SESSION["processSafe"]==$processSafeForm) {

	$_SESSION["processSafe"]++;
	
	if(!empty($email))
	{
		$sSQL="SELECT usersID,email,fname,lname FROM users WHERE email = '".$email."'";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
		if ($row = mysql_fetch_array($result))
		{
			
			$pwd=""; // to store generated password

			for($i=0;$i<12;$i++)
			{
			  $num=rand(48,122);
			  if(($num > 97 && $num < 122))
			  {
				  $pwd.=chr($num);
			  }
			  else if(($num > 65 && $num < 90))
			  {
				  $pwd.=chr($num);
			  }
			  else if(($num >48 && $num < 57))
			  {
				  $pwd.=chr($num);
			  }
			  else if($num==95)
			  {
				  $pwd.=chr($num);
			  }
			  else
			  {
				  $i--;
			  }
			} 
			
			$sSQL="UPDATE users SET password = '".addslashes(md5($pwd))."' WHERE usersID = '".$row['usersID']."'";
			$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
			
			$mailbody = "Dear ".$row['fname']." ".$row['lname']."<br><br>";
			$mailbody .= "On ".date("F j, Y")." you have completed 'Forgot Password' form.<br>";
			$mailbody .= "Our system generated new password for you: ".$pwd."<br>";
			
			$headers  = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";
			$headers .= "From: Plasticair <info@".$_SERVER['HTTP_HOST']."> \n"; 
			$subject = "Plasticair Forgot Password";
			mail($row["email"], $subject, $mailbody, $headers);
			$mess="Your new password has been sent to ".$email;
		}
		else{$err = "Account not found";}
	}
	else {$err = "Account not found";}
}
?><table width="665" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2"><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold">Forgot Password</td></tr>
<tr><td colspan="2" height="40"></td></tr>
<tr><td width="25"></td>
<td align="left"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/forgotPassword.php" class="subnav_link_active">Registration</a></td></tr>
<tr><td>&nbsp;</td>
<td>&nbsp;</td></tr>
</table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table></td></tr>

<tr><td><table width="665" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="3" height="10"></td></tr>

<tr><td width="13"></td>
<td width="640" class="arial_16_green_bold" align="left">Request New Password</td>
<td width="12"></td></tr>
<tr><td width="13"></td>
<td width="640" style="padding-top:4px; padding-bottom:10px;"><div style="background-color:#CCCCCC; height:1px; widows:640px;"></div></td>
<td width="12"></td></tr>
<tr><td colspan="3" height="25"></td></tr>
<tr><td width="13"></td>
<td width="640" align="left"><table width="640" border="0" cellspacing="0" cellpadding="0">
<tr><td width="300" align="left" valign="top">
<? if(empty($mess)) {?>
<table width="300" border="0" cellspacing="0" cellpadding="0">
<form name="loginForm" id="loginForm" method="post" action="">
<input type="hidden" name="process" id="process" value="1">
<input type="hidden" name="processSafeForm" id="processSafeForm" value="<?=$_SESSION["processSafe"]?>" /><?
if(!empty($err)){ ?><tr><td style="color:#F00;"><?=$err?></td></tr><?
 }?><tr><td><strong>Email</strong></td></tr>

<tr><td style="padding-top:3px;"><input type="text" name="email" id="email" value="<?=$email?>" class="form"></td></tr>
<tr><td height="10"></td></tr>
<tr><td align="left"><table border="0" cellspacing="0" cellpadding="0">
<tr><td><img src="/img/btn-submit.jpg" border="0" alt="" onclick="document.loginForm.submit();" style="cursor:pointer;"></td></tr>
</table></td></tr>
</form>
</table>
<? } else {?>
<span style="color:#093;"><?=$mess?></span>
<? }?>
</td>
<td width="20"></td>
<td width="1" class="pix-3"></td>
<td width="20"></td>
<td width="204" class="arial_12_light_grey_bold" align="center" valign="top" style="padding-top:30px;">Not a member yet?<br>
<a href="/registration.php" class="black_btn_text"><img src="/img/btn/register.png" border="0" style="margin:7px 0;" alt=""></a></td></tr>
</table></td>
<td width="12"></td></tr>
<tr><td colspan="3" height="15"></td></tr>
</table></td></tr></table><?
include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php";?>