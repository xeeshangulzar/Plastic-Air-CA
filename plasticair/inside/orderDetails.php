<?
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
$ordersID=(!empty($_REQUEST['ordersID']))?str_replace("'","",$_REQUEST['ordersID']):"";

if(empty($_SESSION['usersID'])){header("Location: /login.php?r=/inside/orderDetails.php?ordersID=".$ordersID); exit();}
if(empty($ordersID)){header("Location: /inside/orderHistory.php"); exit();}
else
{
	$sql="SELECT * FROM orders WHERE  ordersID = '".$ordersID."'";
	$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
	if($row=mysql_fetch_assoc($result))
	{
		foreach ($row as $k=>$v) $$k=$v;
		$taxStr="";
		if(!empty($row['gst'])){
			$taxStr .= "GST $".number_format($row['tax_gst'], 2)."(".$row['gst']."%)<br />";
			}
		if(!empty($row['pst'])){
			$taxStr .= "PST $".number_format($row['tax_pst'], 2)."(".$row['pst']."%)<br />";
			}
		if(!empty($row['hst'])){
			$taxStr .= "HST $".number_format($row['tax_hst'], 2)."(".$row['hst']."%)<br />";
			}
		if(!empty($row['tax'])){
			$taxStr .= "TAX $".number_format($row['tax_tax'], 2)."(".$row['tax']."%)<br />";
			}
	}
}
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
<tr><td width="13"></td>
<td width="640" align="left">
<table width="100%" border="0" cellspacing="0" cellpadding="3" style="line-height:15px;">
<tr><td align="right"><strong>Date:</strong></td><td><?=date("F j, Y h:ia",strtotime($dateCreated))?></td></tr>
<tr><td height="5px"></td></tr>
<tr><td align="right"><strong>Status:</strong></td><td><?=$status?></td></tr>
<tr><td height="5px"></td></tr>
<tr><td align="right" valign="top"><strong>Products:</strong></td>
<td>
<table cellpadding="3" cellspacing="0" border="1" class="text">
<tr><td><b>Product</b></td>
<td><b>Price</b></td>
<td><b>Quantity</b></td></tr><?
$sql="SELECT * FROM orderRow WHERE  ordersID = '".$ordersID."'";
$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
while($row=mysql_fetch_assoc($result))
{
?><tr><td><?=$row['productNumber']?> - <?=$row['title']?></td>
<td>$<?=number_format($row['price'],2)?></td>
<td align="center"><?=$row['qty']?></td></tr><? }?></table>
</td></tr>
<tr><td height="5px"></td></tr>
<tr><td align="right"><strong>Sub total:</strong></td>
<td>$<?=number_format($subTotal,2)?></td></tr>
<tr><td height="5px"></td></tr>
<tr valign="top"><td align="right"><strong>Taxes:</strong></td>
<td><??><?=$taxStr?></td></tr>
<tr><td height="5px"></td></tr>
<tr><td align="right"><strong>Total:</strong></td>
<td>$<?=number_format($total,2)?></td></tr>
<tr><td height="5px"></td></tr>
<tr><td align="right" valign="top"><strong>Billing Information:</strong></td>
<td>
<?=$billingContact?><br />
<?=$billingAddress?><br />
<?=$billingCity?>, <?=$billingStateCode?>, <?=$billingCountry?>, <?=$billingZip?><br />
<?=$billingPhone?>, <?=$billingPhone2?>
</td></tr>
<tr><td height="5px"></td></tr>
<tr><td align="right" valign="top"><strong>Shipping Information:</strong></td>
<td><?=$shippingContact?><br />
<?=$shippingAddress?><br />
<?=$shippingCity?>, <?=$shippingStateCode?>, <?=$shippingCountry?>, <?=$shippingZip?><br />
<?=$shippingPhone?>, <?=$shippingPhone2?></td></tr>
<tr><td align="right"><b>Shipping Info</b>:</td><td><?=nl2br(stripslashes($shipping_info))?></td></tr>
</table>
</td>
<td width="12"></td></tr>
<tr><td colspan="3" height="15"></td></tr>
</table></td></tr></table><?
include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php";?>