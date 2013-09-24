<?
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
if(empty($_SESSION['usersID'])){header("Location: /login.php?r=/inside/orderHistory.php"); exit();}
$pageItems=(!empty($_REQUEST["pageItems"]))?str_replace("'","",$_REQUEST["pageItems"]):"8";
$pageNum=(!empty($_REQUEST["pageNum"]))?str_replace("'","",$_REQUEST["pageNum"]):"1";
?><table width="665" border="0" cellspacing="0" cellpadding="0">
<tr><td><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold">Order History</td></tr>
<tr><td colspan="2" height="40"></td></tr>
<tr><td width="25"></td>
<td align="left"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/inside/orderHistory.php" class="subnav_link_active">Order History</a></td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr></table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr></table></td></tr>

<tr><td><?
$page_inside="Purchase History";
include "inc/menu.php";
?><table width="665" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="3" height="10"></td></tr>

<tr><td colspan="3" height="10"></td></tr>
<tr><td width="13"></td>
<td width="640" align="left"><?
$sql="SELECT * FROM orders
WHERE usersID='".$_SESSION["usersID"]."'";
$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
$fromRecord=($pageNum - 1)*$pageItems;
$total=mysql_num_rows($result);
$sSQLLimit=" LIMIT ".$fromRecord." , ".$pageItems;
$sql.= $sSQLLimit;
$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
$link = "inside/orderHistory.php?a=1";	
?><table width="100%" border="0" cellspacing="0" cellpadding="0" style="line-height:15px;">
<tr><td align="right" colspan="7"><? pagingBlog($link);?></td></tr>
<tr><td colspan="7" height="10"></td></tr>
<tr class="arial_13_green_bold"><td align="left">Number</td>
<td align="left">Date</td>
<td align="left">Sub Total</td>
<td align="left">Taxes</td>
<td align="left">Total</td>
<td align="left">Status</td>
<td align="left">Details</td></tr>
<tr><td colspan="7" height="10"></td></tr><?

while($row=mysql_fetch_array($result)){?>
<tr class="arial_12_grey_bold">
<td align="left" ><?=$row['ordersID']?></td>
<td align="left"><?=date("F j, Y h:ia",strtotime($row['dateCreated']))?></td>
<td align="left" style="padding-right:20px;" >$<?=number_format($row['subTotal'],2)?></td>
<td align="left" style="padding-right:20px;" ><?=number_format($row['taxAmount'],2)?> (<?=$row['taxRate']?>%)</td>
<td align="left" style="padding-right:20px;" >$<?=number_format($row['total'],2)?></td>
<td align="left" ><?=$row['status']?></td>
<td align="left" ><a href="/inside/orderDetails.php?ordersID=<?=$row['ordersID']?>" class="arial_11_grey_bold">Details</a></td>
</tr>
<tr><td height="5px" colspan="7"><hr /></td></tr><? }
?><tr><td colspan="7" height="10"></td></tr>
<tr><td align="right" colspan="7"><? pagingBlog($link);?></td></tr>
</table></td><td width="12"></td></tr>
<tr><td colspan="3" height="15"></td></tr></table></td></tr></table><?
include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php";?>
