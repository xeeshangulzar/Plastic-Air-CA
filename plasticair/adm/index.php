<? 
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";
if (!empty($_SESSION['adminLoggedin']))
{
	header ("Location: /adm/home.php");
	exit();
}
$msg=(!empty($_REQUEST["msg"]))?$_REQUEST["msg"]:"";
$username=!empty($_POST['username'])?str_replace("'","",$_POST['username']):""; 
if (!empty($_POST['username']) && $_POST['password'] && $_POST['process']) {
	$sql="SELECT adminsID, adminActive FROM admins WHERE adminUsername='".addslashes(str_replace("'","",$_POST['username']))."' AND adminPass='".addslashes(str_replace("'","",$_POST['password']))."'";
	$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br>".$sql);
	if (mysql_num_rows($result)) {
		$row=mysql_fetch_array($result);
		if ($row['adminActive']) {
			$_SESSION['adminLoggedin']=$row['adminsID'].":@:".str_replace("'","",$_POST['password']);
			$_SESSION['adminsID']=$row['adminsID'];
			$_SESSION['adminPass']=str_replace("'","",$_POST['password']);
			$sSQL="UPDATE admins SET adminLast_login=NOW() WHERE adminsID='".$row['adminsID']."'";
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
			if(empty($_REQUEST['rd']))
			{
				//echo "LALA";
				header("Location: /adm/home.php");	
			}
			else{
				$rd=$_REQUEST['rd'];
				$rd=urldecode($rd);
				//echo $rd;
				header("Location: $rd");
	
			}
			//header("Location: /adm/home.php");
			exit;
		} else {
			$msg="Your account is not active<br>";
		}
	} else {
		$msg="Invalid login";
	}
}
	
 ?>
<table width="100%" border="0" cellspacing="0" cellpadding="3">             
			<tr>
			  <td class="arial18_Grey" style=" padding-left:60px; padding-top:30px;">Welcome</td>
			</tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="center" valign="top">
                  <?
                  if(!empty($msg))
				  {
				  ?>
                  <b><?=$msg?></b>
                  <?
                  }
				  ?>
				<form name="ff1" id="ff1" action="" method="post">
				<table width="315" height="174" border="0" cellpadding="3" cellspacing="0" background="/adm/img/login_bg.jpg">
                  <tr>
                    <td colspan="2" height="20"></td>
                  </tr>
                  <tr>
                    <td height="35" align="right" class="arial13_blue">Username:</td>
                    <td><input type="text" name="username" id="username" value="<?=$username?>" class="inputField"></td>
                  </tr>
                  <tr>
                    <td height="35" align="right" class="arial13_blue">Password:</td>
                    <td><input type="password" name="password" id="password" value="" class="inputField"></td>
                  </tr>
                  <tr>
                    <td height="15"></td>
                    <td valign="bottom"><a href="/adm/forgot_password.php" class="arial10_grey">Forgot your password?</a> </td>
                  </tr>
                  <tr>
                    <td colspan="2" align="center" valign="middle">
					<input type="image" src="/adm/img/btn_login.jpg" width="89" height="30">
					<input type="hidden" name="process" id="process" value="1">
					</td>
                  </tr>
                </table>
				</form></td>
              </tr>
            </table>
<? 
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/footer.php"; 	
?>
<script language="javascript" type="text/javascript">
<!--
document.onload=document.getElementById('username').focus();
//-->
</script>