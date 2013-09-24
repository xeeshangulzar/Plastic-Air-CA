<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";

$email=empty($_POST['email'])?"":str_replace("'","`",$_POST['email']);
$mess="";
$errMess="";
$mailbody="";
if (!empty($_POST['process'])) {
	if (!empty($email)) {
		$sql="SELECT adminUsername, adminPass, adminEmail  
				FROM admins 
				WHERE adminEmail='".$email."'";
				//echo $sql;
		$result=mysql_query($sql) or die ("<p>SQL Err:".mysql_query()."<br>".$sql);
		if ($row=mysql_fetch_assoc($result)) {
			if (!empty($row['adminEmail'])) {
				//send email here
				$mailbody .= "<table><tr><td>Hello</td></tr>";
				$mailbody .= "<tr><td>Please use following login information:</td></tr>";
				$mailbody .= "<tr><td>Username: ".$row["adminUsername"]."</td></tr>";
				$mailbody .= "<tr><td>Password : ".$row["adminPass"]."</td></tr>";
				$mailbody .= "<tr><td><br />
				Thank you,<br />
				SWS<br />
				info@superiorwebsys.com<br />
				905-532-9642<br />
				</td></tr>";
				$mailbody .="</table>";
				  
				$headers  = "MIME-Version: 1.0\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\n";
				$headers .= "From: info@superiorwebsys.com <info@superiorwebsys.com> \n"; 
				
				$subject = "Password request";
				$recepient = $row["adminEmail"]; 
				mail($recepient, $subject, $mailbody, $headers);
				$mess="Email with login information has been sent";
			}
		}
		else
		{
			$errMess="Invalid email";
		}
	}
	else
	{
		$errMess="Please enter email";
	}
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="3">               
			<tr>
			  <td class="arial18_Grey" style=" padding-left:60px; padding-top:30px;">Forgot Password?</td>
			</tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="center" valign="top">
				<form name="ff1" id="ff1" action="" method="post">
				<table width="315" height="174" border="0" cellpadding="3" cellspacing="0" background="/adm/img/login_bg.jpg" class="arial13_blue">
                  <tr>
                    <td colspan="2" height="50"></td>
                  </tr>
                  <?
                  if(!empty($errMess))
				  {
				  ?>
                  <tr><td colspan="2" align="center" style="color:#990000;"><b><?=$errMess?></b></td></tr>
                  <?
                  }
				  ?>
                  <?
                  if(!empty($mess))
				  {
				  ?>
                  <tr><td colspan="2" align="center"><b><?=$mess?></b><br /><a href="/adm/index.php" class="arial12_Grey">login</a></td></tr>
                  <?
                  }
				  else
				  {
				  ?>
                  <tr>
                    <td width="77" height="35" align="right">Email:</td>
                    <td width="226"><input type="text" name="email" id="email" value="<?=$email?>" class="inputField" style="width:200px;"></td>
                  </tr>
                  <tr>
                    <td colspan="2" align="center" valign="middle">
					<input type="image" src="/adm/img/btn_login.jpg" width="89" height="30">
					<input type="hidden" name="process" id="process" value="1">					</td>
                  </tr>
                  <?
                  }
				  ?>
                </table>
				</form></td>
              </tr>
            </table>
<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/footer.php";
?>