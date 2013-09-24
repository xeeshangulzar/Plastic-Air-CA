<?
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
$shipping_info="";
$sSQL="SELECT * FROM content WHERE contentID=7";
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
if($row=mysql_fetch_array($result)){ $shipping_info=stripslashes($row['description']); }
$total = 0;
$taxes = 0;
$taxStr = array();
$gst=0;  $pst=0; $hst=0; $tax=0;
$grandTotal = 0;

$sSQL="SELECT * FROM shoppingCart WHERE usersID='".$_SESSION["usersID"]."'";
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
if(mysql_num_rows($result) < 1){header("/inside/cart.php");exit();}
else
{
	while($row=mysql_fetch_array($result))
	{
		$originalPrice = $row["price"];
		$total += $originalPrice*$row["qty"]; 
	}

	$sSQL="SELECT s.gst, s.pst, s.hst, s.tax FROM users LEFT JOIN stateCodes s ON (s.stateCode = users.billingStateCode) WHERE usersID='".$_SESSION["usersID"]."'";
	$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
	if($row=mysql_fetch_array($result)){
		if(!empty($total)){
			$taxStr = array();
			if(!empty($row['gst'])){
				$gst = round($row['gst']*$total/100,2);
				$taxStr["GST "."(".$row['gst']."%)"]= "$".number_format($gst, 2);
				}
			if(!empty($row['pst'])){
				$pst = round($row['pst']*$total/100,2);
				$taxStr["PST (".$row['pst']."%)"]= "$".number_format($pst, 2);
				}
			if(!empty($row['hst'])){
				$hst = round($row['hst']*$total/100,2);
				$taxStr["HST (".$row['hst']."%)"]= "$".number_format($hst, 2);
				}
			if(!empty($row['tax'])){
				$tax = round($row['tax']*$total/100,2);
				$taxStr["TAX (".$row['tax']."%)"]= "$".number_format($tax, 2);
				}

				$tax_gst = $row['gst'];
				$tax_pst = $row['pst'];
				$tax_hst = $row['hst'];
				$tax_tax = $row['tax'];
		}
	}
	$grandTotal = $total+ $gst+$pst+$hst+$tax;
}

$billingAddress=(!empty($_REQUEST["billingAddress"]))?addslashes($_REQUEST["billingAddress"]):"";
$billingCity=(!empty($_REQUEST["billingCity"]))?addslashes($_REQUEST["billingCity"]):"";
$billingStateCode=(!empty($_REQUEST["billingStateCode"]))?addslashes($_REQUEST["billingStateCode"]):"";
$billingCountry=(!empty($_REQUEST["billingCountry"]))?addslashes($_REQUEST["billingCountry"]):"";
$billingZip=(!empty($_REQUEST["billingZip"]))?addslashes($_REQUEST["billingZip"]):"";
$billingPhone=(!empty($_REQUEST["billingPhone"]))?addslashes($_REQUEST["billingPhone"]):"";
$billingPhone2=(!empty($_REQUEST["billingPhone2"]))?addslashes($_REQUEST["billingPhone2"]):"";
$billingContact=(!empty($_REQUEST["billingContact"]))?addslashes($_REQUEST["billingContact"]):"";

$step=(!empty($_REQUEST["step"]))?addslashes($_REQUEST["step"]):1;

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
	$pSQL = "";
	$_SESSION["processSafe"]++;
	//checks
	
	if(empty($billingAddress)){	$err .= "<li>Please fill Billing Address</li>";}
	if(empty($billingCity)){	$err .= "<li>Please fill Billing City</li>";}
	if(empty($billingStateCode)){	$err .= "<li>Please select Billing State/Province</li>";}
	else
	{
		$sSQL="SELECT gst, pst, hst, tax FROM stateCodes WHERE stateCode='".$billingStateCode."'";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		if($row=mysql_fetch_array($result))
		{
			if(!empty($total))
			{
				$taxStr = array();
				if(!empty($row['gst'])){
					$gst = round($row['gst']*$total/100,2);
					$taxStr["GST "."(".$row['gst']."%)"]= "$".number_format($gst, 2);
					}
				if(!empty($row['pst'])){
					$pst = round($row['pst']*$total/100,2);
					$taxStr["PST (".$row['pst']."%)"]= "$".number_format($pst, 2);
					}
				if(!empty($row['hst'])){
					$hst = round($row['hst']*$total/100,2);
					$taxStr["HST (".$row['hst']."%)"]= "$".number_format($hst, 2);
					}
				if(!empty($row['tax'])){
					$tax = round($row['tax']*$total/100,2);
					$taxStr["TAX (".$row['tax']."%)"]= "$".number_format($tax, 2);
					}

				$tax_gst = $row['gst'];
				$tax_pst = $row['pst'];
				$tax_hst = $row['hst'];
				$tax_tax = $row['tax'];
			}
		}
		$grandTotal = $total+ $gst+$pst+$hst+$tax;	
	}
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
		if($step == 1)
		{
			$step = 2;	
		}
		elseif($step == 2)
		{
			$sSQL="SELECT fname, lname, email FROM users WHERE usersID='".$_SESSION["usersID"]."'";
			$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
			if($row=mysql_fetch_array($result))
			{
				foreach($row as $k=>$v){$$k=$v;}
			}
			$sSQL="INSERT INTO orders (dateCreated) VALUES(NOW())";
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
			$ordersID = mysql_insert_id();
			$sSQL="UPDATE orders 
					SET 
					email = '".$email."',
					fname = '".$fname."',
					lname = '".$lname."',
					billingAddress = '".$billingAddress."',
					billingCity = '".$billingCity."',
					billingStateCode = '".$billingStateCode."',
					billingCountry = '".$billingCountry."',
					billingZip = '".$billingZip."',
					billingPhone = '".$billingPhone."',
					billingPhone = '".$billingPhone."',
					billingPhone2 = '".$billingPhone2."',
					billingContact = '".$billingContact."',
					shippingAddress = '".$shippingAddress."',
					shippingCity = '".$shippingCity."',
					shippingStateCode = '".$shippingStateCode."',
					shippingCountry = '".$shippingCountry."',
					shippingZip = '".$shippingZip."',
					shippingPhone = '".$shippingPhone."',
					shippingPhone = '".$shippingPhone."',
					shippingPhone2 = '".$shippingPhone2."',
					shippingContact = '".$shippingContact."',
					shipping_info = '".($shipping_info)."',
					subTotal = '".$total."', 
					taxRate = '',
					tax_gst = '$tax_gst',
					tax_pst = '$tax_pst',
					tax_hst = '$tax_hst',
					tax_tax = '$tax_tax',
					gst = '$gst',
					pst = '$pst',
					hst = '$hst',
					tax = '$tax',
					taxAmount = '".$taxes."', 
					total = '".$grandTotal."',
					status = 'New',
					usersID = '".$_SESSION['usersID']."'
					WHERE ordersID = '".$ordersID."'";
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
			$productsHTML = '<table cellpadding="0" cellspacing="0">';
			$productsHTML .= "<tr><td><b>Product</b></td><td><b>Price</b></td><td><b>Quantity</b></td></tr>";
			
			$sSQL="SELECT * FROM shoppingCart WHERE usersID='".$_SESSION["usersID"]."'";
			$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
			while($row=mysql_fetch_array($result))
			{
				$sSQL="INSERT INTO orderRow (dateCreated,usersID,productsID,productNumber,title,price,qty,ordersID) 
									VALUES(NOW(),'".$_SESSION['usersID']."','".$row['productsID']."','','".$row['title']."','".$row['price']."','".$row['qty']."','".$ordersID."')";
				mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
				
				$sSQL="DELETE FROM shoppingCart
				WHERE shoppingCartID='".$row['shoppingCartID']."'";
				mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
				
				$productsHTML .= "<tr><td>".$row['productNumber']." - ".$row['title']."</td><td>$".number_format($row['price'],2)."</td><td>".$row['qty']."</td></tr>";	
			}
			$productsHTML .= "</table>";
			//emailing order to
			$mailbody = "";
			$mailbody .= "New Order Submitted<br/><br/>";
			$mailbody .= "On: ".date("F j,Y h:ia")."<br/>";
			$mailbody .= "By: ".$fname." ".$lname."<br/><br/>";
			$mailbody .= $productsHTML;
			$mailbody .= "<br/>";
			$mailbody .= "Sub Total: $".number_format($total,2)."<br/>";
			$mailbody .= "Taxes: ".$taxStr."<br/>";
			$mailbody .= "Total: $".number_format($grandTotal,2)."<br/>";
			$headers  = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";
			$headers .= "From: Plasticair <info@".$_SERVER['HTTP_HOST']."> \n"; 
			$subject = "New Order Submitted!";
			mail("vpankratov@hotmail.comn", $subject, $mailbody, $headers);
			
			$mess="Thank you. Your order has been created. ";
			$step =3;
		}
	}
} 
if(empty($process))
{
	$sSQL="SELECT * FROM users WHERE usersID = '".$_SESSION['usersID']."'";
	$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
	if ($row = mysql_fetch_array($result)){	foreach($row as $k=>$v){$$k = $v;}}
}
?><table width="665" border="0" cellspacing="0" cellpadding="0">
<tr><td><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold">Shopping Cart</td></tr>
<tr><td colspan="2" height="40"></td></tr>
<tr><td width="25"></td>
<td align="left"><div style="position:relative;"><div style="position:absolute; white-space:nowrap;"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/inside/" class="subnav_link">Users</a><span class="subnav_divider">/</span><a href="/inside/cart.php" class="subnav_link">Shopping Cart</a><span class="subnav_divider">/</span><a href="/inside/checkout.php" class="subnav_link_active">Checkout</a></div></div></td></tr>
<tr><td>&nbsp;</td>
<td>&nbsp;</td></tr>
</table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table></td></tr>

<!-- Content starts here-->
<tr><td><?
$page_inside="Shopping Cart";
include "inc/menu.php";
?><table width="665" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="3" height="10"></td></tr>

<tr><td width="13"></td>
<td width="640" class="arial_20_black_bold">Checkout Step <?=$step?> of 2</td>
<td width="12"></td></tr>
<tr><td width="13"></td>
<td width="640" style="padding-top:4px; padding-bottom:10px;"><div style="background-color:#CCCCCC; height:1px; widows:640px;"></div></td>
<td width="12"></td></tr>
<tr><td colspan="3" height="5"></td></tr>
<? if(!empty($err)){?>
<tr><td width="13"></td>
<td width="640" style="color:#F00; padding-left:50px;" align="left"><ul><?=$err?></ul></td>
<td width="12"></td>
<? }?>
<tr><td colspan="3" height="5"></td></tr>
<tr><td width="13"></td>
<td width="640" align="left">
<? if($step != 3) {?>
<table width="640" border="0" cellspacing="0" cellpadding="0">
<form name="checkoutForm" id="checkoutForm" method="post">
<input type="hidden" name="process" id="process" value="1">
<input type="hidden" name="step" id="step" value="<?=$step?>">
<input type="hidden" name="processSafeForm" id="processSafeForm" value="<?=$_SESSION["processSafe"]?>" />
<tr><td colspan="3" style="padding-bottom:7px;" class="arial_12_grey_bold">*Indicates a reuired field</td></tr>
<tr><td colspan="3" class="arial_16_green_bold">Billing Information <? if($step == 2){?><a href="/inside/checkout.php" class="arial_11_grey_bold">change</a><? }?></td></tr>

<tr><td colspan="3" style="padding-top:2px; padding-bottom:7px;" ><div style="background-color:#666666; height:1px; widows:640px;"></div></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<? if($step == 1) {?>
<tr><td align="left" width="300" style="padding-left:4px;">Contact Name*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Address*</td></tr>
<tr><td align="left" width="300"><input name="billingContact" id="billingContact" value="<?=$billingContact?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300"><input name="billingAddress" id="billingAddress" value="<?=$billingAddress?>" type="text" class="form"></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">City*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Province/State*</td></tr>
<tr><td align="left" width="300"><input name="billingCity" id="billingCity" value="<?=$billingCity?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300">
<select name="billingStateCode" id="billingStateCode" class="form1">
<option value="">Please Select</option>
<option value="" disabled="disabled">===========Canada===========</option>
<?
$sc = 1;
$sql="SELECT * FROM stateCodes ORDER BY stateType DESC,stateName";
$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
while($row=mysql_fetch_assoc($result)){
if($sc != $row['stateType']){$sc = $row['stateType']; ?><option value="" disabled="disabled">============USA============</option><? }
?>
<option value="<?=$row['stateCode']?>" <?=($row['stateCode'] == $billingStateCode)?"selected='selected'":""?>><?=$row['stateName']?></option>
<? }?>
</select>
</td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">Postal/Zip Code*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Country*</td></tr>
<tr><td align="left" width="300"><input name="billingZip" id="billingZip" value="<?=$billingZip?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300">
<select name="billingCountry" id="billingCountry" class="form1">
<option value="">Please Select</option><?
$sSQL="SELECT DISTINCT countryCode, countryName FROM countryCodes ORDER BY countryType, countryName ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
while($row1=mysql_fetch_array($result1)){
	?><option value="<?=$row1['countryCode']?>"<?=$billingCountry==$row1['countryCode']?' selected="selected"':'' ?>><?=$row1['countryName']?></option><? }
?></select>
</td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">Phone*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Other Phone (optional)</td></tr>
<tr><td align="left" width="300"><input name="billingPhone" id="billingPhone" value="<?=$billingPhone?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300"><input name="billingPhone2" id="billingPhone2" value="<?=$billingPhone2?>" type="text" class="form"></td></tr>
<tr><td colspan="3" height="30"></td></tr>
<tr><td colspan="3" style="padding-bottom:7px;" class="arial_12_grey_bold"><table border="0" cellspacing="0" cellpadding="0">
<tr><td style="padding-left:5px;"><a href="#" onclick="copyInfo(); return false;">Copy Billing Information</a></td></tr>
</table></td></tr>
<? } else {?>
<input type="hidden" name="billingAddress" id="billingAddress" value="<?=$billingAddress?>" />
<input type="hidden" name="billingCity" id="billingCity" value="<?=$billingCity?>" />
<input type="hidden" name="billingStateCode" id="billingStateCode" value="<?=$billingStateCode?>" />
<input type="hidden" name="billingCountry" id="billingCountry" value="<?=$billingCountry?>" />
<input type="hidden" name="billingZip" id="billingZip" value="<?=$billingZip?>" />
<input type="hidden" name="billingPhone" id="billingPhone" value="<?=$billingPhone?>" />
<input type="hidden" name="billingPhone2" id="billingPhone2" value="<?=$billingPhone2?>" />
<input type="hidden" name="billingContact" id="billingContact" value="<?=$billingContact?>" />
<input type="hidden" name="shippingAddress" id="shippingAddress" value="<?=$shippingAddress?>" />
<input type="hidden" name="shippingCity" id="shippingCity" value="<?=$shippingCity?>" />
<input type="hidden" name="shippingStateCode" id="shippingStateCode" value="<?=$shippingStateCode?>" />
<input type="hidden" name="shippingCountry" id="shippingCountry" value="<?=$shippingCountry?>" />
<input type="hidden" name="shippingZip" id="shippingZip" value="<?=$shippingZip?>" />
<input type="hidden" name="shippingPhone" id="shippingPhone" value="<?=$shippingPhone?>" />
<input type="hidden" name="shippingPhone2" id="shippingPhone2" value="<?=$shippingPhone2?>" />
<input type="hidden" name="shippingContact" id="shippingContact" value="<?=$shippingContact?>" />
<tr><td colspan="3" class="arial_12_grey_bold">
<?=$billingContact?>, <?=$billingPhone?>, <?=$billingPhone2?><br />
<?=$billingAddress?><br />
<?=$billingCity?>, <?=$billingStateCode?>, <?=$billingCountry?>, <?=$billingZip?>
</td></tr>
<? }?>
<tr><td colspan="3" height="15"></td></tr>
<tr><td colspan="3" class="arial_16_green_bold">Shipping Information <? if($step == 2){?><a href="/inside/checkout.php" class="arial_11_grey_bold">change</a><? }?></td></tr>
<tr><td colspan="3" style="padding-top:2px; padding-bottom:7px;" ><div style="background-color:#666666; height:1px; widows:640px;"></div></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<? if($step == 1) {?>
<tr><td align="left" width="300" style="padding-left:4px;">Contact Name*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Address*</td></tr>
<tr><td align="left" width="300"><input name="shippingContact" id="shippingContact" value="<?=$shippingContact?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300"><input name="shippingAddress" id="shippingAddress" value="<?=$shippingAddress?>" type="text" class="form"></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">City*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Province/State*</td></tr>
<tr><td align="left" width="300"><input name="shippingCity" id="shippingCity" value="<?=$shippingCity?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300">
<select name="shippingStateCode" id="shippingStateCode" class="form">
<option value="">Please Select</option>
<option value="" disabled="disabled">===========Canada===========</option>
<?
$sc = 1;
$sql="SELECT * FROM stateCodes ORDER BY stateType DESC,stateName";
$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
while($row=mysql_fetch_assoc($result)){
if($sc != $row['stateType']){$sc = $row['stateType']; ?><option value="" disabled="disabled">============USA============</option><? }
?>
<option value="<?=$row['stateCode']?>" <?=($row['stateCode'] == $shippingStateCode)?"selected='selected'":""?>><?=$row['stateName']?></option>
<? }?>
</select>
</td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">Postal/Zip Code*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Country*</td></tr>
<tr><td align="left" width="300"><input name="shippingZip" id="shippingZip" value="<?=$shippingZip?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300">
<select name="shippingCountry" id="shippingCountry" class="form">
<option value="">Please Select</option><?
$sSQL="SELECT DISTINCT countryCode, countryName FROM countryCodes ORDER BY countryType, countryName ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
while($row1=mysql_fetch_array($result1)){
	?><option value="<?=$row1['countryCode']?>"<?=$shippingCountry==$row1['countryCode']?' selected="selected"':'' ?>><?=$row1['countryName']?></option><? }
?></select>
</td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td align="left" width="300" style="padding-left:4px;">Phone*</td>
<td width="40"></td>
<td align="left" width="300" style="padding-left:4px;">Other Phone (optional)</td></tr>
<tr><td align="left" width="300"><input name="shippingPhone" id="shippingPhone" value="<?=$shippingPhone?>" type="text" class="form"></td>
<td width="40"></td>
<td align="left" width="300"><input name="shippingPhone2" id="shippingPhone2" value="<?=$shippingPhone2?>" type="text" class="form"></td></tr>
<tr><td><br />Shipping Info:</td></tr>
<tr><td><?=stripslashes($shipping_info)?></td></tr>
<? } else {?>
<tr><td colspan="3" class="arial_12_grey_bold">
<?=$shippingContact?>, <?=$shippingPhone?>, <?=$shippingPhone2?><br />
<?=$shippingAddress?><br />
<?=$shippingCity?>, <?=$shippingStateCode?>, <?=$shippingCountry?>, <?=$shippingZip?>
</td></tr>
<? }?>
<tr><td colspan="3" height="15"></td></tr>
<? if($step == 2) {?>
<tr><td colspan="3" class="arial_16_green_bold">Order Information</td></tr>
<tr><td colspan="3" style="padding-top:2px; padding-bottom:7px;" ><div style="background-color:#666666; height:1px; widows:640px;"></div></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td colspan="3" align="left">
<table cellpadding="3" cellspacing="0" border="0" class="arial_12_grey_bold">
<tr><td align="right" class="arial_13_red_bold">Sub total:</td>
<td align="right">$<?=number_format($total,2)?></td></tr><?
if(!empty($taxStr)){
	foreach($taxStr as $k=>$v){ ?><tr><td align="right" class="arial_13_red_bold"><?=$k?>:</td><td align="right" ><?=$v?></td></tr><? } }
?><tr><td align="right" class="arial_13_red_bold">Total:</td>
<td align="right">$<?=number_format($grandTotal,2)?></td></tr>
</table>
</td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td colspan="3" class="arial_13_red_bold">Ship Info:</td></tr>
<tr><td colspan="3" height="10"><?=($shipping_info)?></td></tr>
<? }?>
<tr><td align="left" width="300">

</td>
<td width="40"></td>
<td align="left" width="300"><table border="0" cellspacing="0" cellpadding="0"><tr>
<td><a href="#" onclick="document.checkoutForm.submit(); return false;"><?=($step == 2)?'<img src="/img/btn/order.png" width="110" height="25" border="0" />':'<img src="/img/btn/proceed.png" width="110" height="25" border="0" />'?></a></td></tr>
</table></td></tr>
</form>
</table>
<? } else {?>
<center><span style="color:#093;"><?=$mess?></span></center>
<? }?>
</td>
<td width="12"></td></tr>
<tr><td colspan="3" height="15"></td></tr>
</table></td></tr></table>
<script type="text/javascript" language="javascript">
function copyInfo()
{
	document.getElementById('shippingContact').value = document.getElementById('billingContact').value;	
	document.getElementById('shippingAddress').value = document.getElementById('billingAddress').value;	
	document.getElementById('shippingCity').value = document.getElementById('billingCity').value;	
	document.getElementById('shippingStateCode').value = document.getElementById('billingStateCode').value;	
	document.getElementById('shippingCountry').value = document.getElementById('billingCountry').value;	
	document.getElementById('shippingZip').value = document.getElementById('billingZip').value;	
	document.getElementById('shippingPhone').value = document.getElementById('billingPhone').value;	
	document.getElementById('shippingPhone2').value = document.getElementById('billingPhone2').value;	
}

</script>
<?
include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php";
?>
