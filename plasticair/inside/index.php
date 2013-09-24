<?
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
if(empty($_SESSION['usersID'])){header("Location: /login.php"); exit();}
?><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold">My Account</td></tr>
<tr><td colspan="2" height="40"></td></tr>
<tr><td width="25"></td>
<td align="left"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/inside/" class="subnav_link_active">My Account</a></td></tr>
<tr><td>&nbsp;</td>
<td>&nbsp;</td></tr>
</table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table><?
$page_inside="Home";
include "inc/menu.php";

$sSQL="SELECT * FROM users WHERE usersID = '".$_SESSION['usersID']."'";
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
if ($row = mysql_fetch_array($result)){	foreach($row as $k=>$v){$$k = $v;}}	
		
?><div align="left">
<table border="0" cellspacing="0" cellpadding="0">
<tr><td><table border="0" cellspacing="0" cellpadding="0" width="640" style="margin:25px 0 11px 0; border-bottom:#666666 1px solid;">
<tr><td class="arial_16_g" align="left">Personal Information</td>
<td align="right"><a href="/inside/account.php"><img src="/img/btn/edit.png" width="74" height="21" border="0" style="margin:0 0 3px 0;" /></a></td></tr></table></td></tr>
<tr><td><table border="0" cellspacing="0" cellpadding="0" class="arial_13_0" align="left">
<tr><td width="120px" height="18px">Name:&nbsp;</td><td class="arial_12_9"><?=$fname?> <?=$lname?></td></tr>
<tr><td height="18px">Email:&nbsp;</td><td class="arial_12_9"><?=$email?></td></tr></table></td></tr>
<tr><td><table border="0" cellspacing="0" cellpadding="0" width="640" style="margin:25px 0 11px 0;border-bottom:#666666 1px solid;">
<tr><td class="arial_16_g" align="left">Billing / Shipping Information</td>
<td align="right"><a href="/inside/account.php"><img src="/img/btn/edit.png" width="74" height="21" border="0" style="margin:0 0 3px 0;" /></a></td></tr></table></td></tr>
<tr><td><table border="0" cellspacing="0" cellpadding="0" class="arial_13_0" align="left">
<tr valign="top" height="22px"><td width="120px">Name:&nbsp;</td><td class="arial_12_9"><?=$billingContact ?></td></tr>
<tr valign="top"><td>Address:&nbsp;</td><td class="arial_12_9"><?=$billingAddress?>, <?=$billingCity?>,<br />
<?=$billingStateCode ?>, <?=$billingZip ?></td></tr>
<tr height="22px"><td>Phone:&nbsp;</td><td class="arial_12_9"><?=$billingPhone?></td></tr>
</table></td></tr></table></div><?
include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php";?>