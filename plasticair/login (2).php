<?
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
if(!empty($_SESSION['usersID'])){header("Location: /inside/"); exit();}
$email=(!empty($_REQUEST["email"]))?addslashes($_REQUEST["email"]):"";
$password=(!empty($_REQUEST["password"]))?addslashes($_REQUEST["password"]):"";
$r = (!empty($_REQUEST["r"]))?str_replace("'","",$_REQUEST["r"]):"/inside/";
$err = "";

if(!empty($_REQUEST["processLogin"])) {
	 
	if(!empty($email) && !empty($password))
	{
		$sSQL="SELECT * 
				FROM users 
				WHERE email = '".$email."' 
				AND password = '".md5($password)."'";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
		if ($row = mysql_fetch_array($result))
		{	
			if(!empty($row['active']))
			{
				if(!empty($row['confirmed']))
				{
					$_SESSION['usersID'] = $row['usersID'];
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
}
?>

<!-- Second raw starts here-->

<tr>
  <td><table width="960" border="0" cellspacing="0" cellpadding="0" style="padding-top:10px;">
      <tr> 
        <!-- Left bar starts here-->
        <td width="270" valign="top">
		<? include $_SERVER['DOCUMENT_ROOT']."/inc/menuGeneral.php";?>
		</td>
        <!-- Left bar ends here-->
        
        <td width="25"></td>
        
        <!-- Right bar starts here-->
        <td width="665" valign="top"><table width="665" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><table width="665" border="0" cellspacing="0" cellpadding="0" style="border:#CBCBCB solid 1px;">
                  <tr>
                    <td colspan="3" height="13" bgcolor="#FFFFFF"></td>
                  </tr>
                  <tr>
                    <td width="13" bgcolor="#FFFFFF"></td>
                    <td width="640"><div style="position:relative;"> <img src="/img/inner-img.jpg" hspace="0" vspace="0" border="0" alt="">
                        <div style="position:absolute; top:-30px; left:-38px;"><img src="/img/inner-img-left.png" hspace="0" vspace="0" border="0" alt=""></div>
                        <div style="position:absolute; top:-30px; left:652px;"><img src="/img/inner-img-right.png" hspace="0" vspace="0" border="0" alt=""></div>
                      </div></td>
                    <td width="12" bgcolor="#FFFFFF"></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="13" bgcolor="#FFFFFF"></td>
                  </tr>
                </table></td>
            </tr>
            
            <!-- Content starts here-->
            <tr>
              <td><table width="665" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td colspan="3" height="10"></td>
                  </tr>
                  <tr>
                    <td width="13"></td>
                    <td width="640" align="right"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/login.php" class="subnav_link_active">Login</a></td>
                    <td width="12"></td>
                  </tr>
                  <tr>
                    <td width="13"></td>
                    <td width="640" class="arial_20_black_bold">Login</td>
                    <td width="12"></td>
                  </tr>
                  <tr>
                    <td width="13"></td>
                    <td width="640" style="padding-top:4px; padding-bottom:10px;"><div style="background-color:#CCCCCC; height:1px; widows:640px;"></div></td>
                    <td width="12"></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="25"></td>
                  </tr>
                  <tr>
                    <td width="13"></td>
                    <td width="640" align="left"><table width="640" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="300" align="left" valign="top"><table width="300" border="0" cellspacing="0" cellpadding="0">
						  		<form name="loginForm" id="loginForm" method="post" action="">
							  <input type="hidden" name="processLogin" id="processLogin" value="1" />
							  <?
							  if(!empty($err)){
							  ?>
							  <tr>
							  	<td style="color:#F00;"><?=$err?></td>
							  </tr>
							  <? }?>
                              <tr>
                                <td><strong>Email</strong></td>
                              </tr>
                              
                                <tr>
                                  <td style="padding-top:3px;"><input type="text" name="email" id="email" value="<?=$email?>" class="form"></td>
                                </tr>
                                <tr>
                                  <td height="10"></td>
                                </tr>
                                <tr>
                                  <td><strong>Password</strong></td>
                                </tr>
                                <tr>
                                  <td style="padding-top:3px;"><input type="password" name="password" id="password" value="" class="form"></td>
                                </tr>
                                <tr>
                                  <td height="10"></td>
                                </tr>
                                <tr>
                                  <td><a href="/forgotPassword.php" class="arial_12_light_grey_bold">Forgot Password?</a></td>
                                </tr>
                                <tr>
                                  <td height="10"></td>
                                </tr>
                                <tr>
                                  <td align="left"><table border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td><img src="/img/black-btn-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
                                        <td><div class="black_btn">
                                            <ul>
                                              <li><a  href="#" onclick="document.loginForm.submit(); return false;" class="black_btn_text">LOGIN</a></li>
                                            </ul>
                                          </div></td>
                                        <td><img src="/img/black-btn-right.jpg" hspace="0" vspace="0" border="0" alt=""></td>
                                      </tr>
                                    </table></td>
                                </tr>
                              </form>
                            </table></td>
                          <td width="20"></td>
                          <td width="1" class="pix-3"></td>
                          <td width="20"></td>
                          <td width="204" class="arial_12_light_grey_bold" align="center" valign="top" style="padding-top:30px;">Not a member yet?<br>
                            <br>
                            <table border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td><img src="/img/black-btn-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
                                <td><div class="black_btn">
                                    <ul>
                                      <li><a  href="#" class="black_btn_text">REGISTER</a></li>
                                    </ul>
                                  </div></td>
                                <td><img src="/img/black-btn-right.jpg" hspace="0" vspace="0" border="0" alt=""></td>
                              </tr>
                            </table></td>
                        </tr>
                      </table></td>
                    <td width="12"></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="15"></td>
                  </tr>
                </table></td>
            </tr>
            <!-- Content ends here-->
          </table></td>
        <!-- Right bar ends here--> 
      </tr>
    </table></td>
</tr>
<!-- Second raw ends here-->

<?
include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php";
?>
