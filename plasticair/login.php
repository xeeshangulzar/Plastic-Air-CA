<? include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
if(!empty($_SESSION['usersID'])){header("Location: /inside/"); exit();}
$email=(!empty($_REQUEST["email"]))?addslashes($_REQUEST["email"]):"";
$password=(!empty($_REQUEST["password"]))?addslashes($_REQUEST["password"]):"";
$r = (!empty($_REQUEST["r"]))?str_replace("'","",$_REQUEST["r"]):"/inside/";
$err = "";

if(!empty($_REQUEST["processLogin"])) {
	 
	if(!empty($email) && !empty($password))
	{
		$sSQL="SELECT * FROM users WHERE email = '".$email."' AND password = '".md5($password)."'";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
		if ($row = mysql_fetch_array($result))
		{	
			if(!empty($row['active']))
			{
				if(!empty($row['confirmed']))
				{
					$_SESSION['usersID'] = $row['usersID'];
					$_SESSION['users_name_sess'] = $row['fname']." ".$row['lname'];
					header("Location: ".$r);	
					exit();
				}
				else {$err = "Your account needs to be confirmed. Please check your email for instructions.";}
			}
			else {$err = "Your account has been deactivated by administration. Please contact us for more information.";}
		}
		else {$err = "email address and/or password is incorrect";}
	}
	else {$err = "email address and/or password is incorrect";}
}?>
<table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2"><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold">Login</td></tr>
<tr><td colspan="2" height="40"></td></tr>
<tr><td width="25"></td>
<td align="left"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/login.php" class="subnav_link_active">Login</a></td></tr>
<tr><td>&nbsp;</td>
<td>&nbsp;</td></tr>
</table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table></td></tr>
<tr><td colspan="2" height="13"></td></tr>
<tr><td width="665" align="center"><table width="450" border="0" cellspacing="0" cellpadding="0">
<tr><td width="242" align="left"><?=!empty($err)?$err:''?>
<table width="220" border="0" cellspacing="0" cellpadding="0">
<tr><td><b>Email</b></td></tr>
<form name="loginForm" id="loginForm" method="post" action=""><input type="hidden" name="processLogin" id="processLogin" value="1" />
<tr><td style="padding-top:3px;"><input type="text" class="form" style="width:200px;" name="email" id="email" value="<?=$email?>"></td></tr>
<tr><td height="10"></td></tr>
<tr><td><b>Password</b></td></tr>
<tr><td style="padding-top:3px;"><input type="password" class="form" style="width:200px;" name="password" id="password" value=""></td></tr>
<tr><td height="10"></td></tr>
<tr><td><a href="/forgotPassword.php" class="arial_12_grey_bold">Forgot Password?</a></td></tr>
<tr><td height="10"></td></tr>
<tr><td align="left"><a href="#" onclick="document.loginForm.submit(); return false;"><img src="/img/btn-login.jpg" hspace="0" vspace="0" border="0" alt=""></a></td></tr>
</form>
</table></td>
<td width="8"></td>
<td width="1" bgcolor="#E5E5E5"></td>
<td width="20"></td>
<td width="179" align="left" style="padding-left:10px;"><table border="0" cellspacing="0" cellpadding="0">
<tr><td class=" arial_13_grey_bold">Not a member yet?</td></tr>
<tr><td height="20"></td></tr>
<tr><td><a href="/registration.php"><img src="/img/btn-register.jpg" hspace="0" vspace="0" border="0" alt=""></a></td></tr>
</table></td></tr>
</table></td>
<td width="10"></td></tr>
</table>
<? include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php"; ?>