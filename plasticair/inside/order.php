<?
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";

$total = 0;
$taxes = 0;
$taxStr = "$00.00";
$grandTotal = 0;
$sSQL="SELECT * FROM shoppingCart
	WHERE usersID='".$_SESSION["usersID"]."'";
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
if(mysql_num_rows($result) < 1){header("/inside/cart.php");exit();}
else
{
	while($row=mysql_fetch_array($result))
	{
		$originalPrice = $row["price"];
		$total += $originalPrice*$row["qty"]; 
	}
	
	$sSQL="SELECT stateCodes.taxRate FROM users LEFT JOIN stateCodes ON(stateCodes.stateCode = users.billingStateCode)
		WHERE usersID='".$_SESSION["usersID"]."'";
	$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
	if($row=mysql_fetch_array($result))
	{
		if(!empty($row['taxRate']) && !empty($total))
		{
			$taxes = number_format($total/100*$row['taxRate'],2);
			$taxStr = "$".$taxes."(".$row['taxRate']."%)";
		}
	}
	$grandTotal = $total+ $taxes;
}

$billingAddress=(!empty($_REQUEST["billingAddress"]))?addslashes($_REQUEST["billingAddress"]):"";
$billingCity=(!empty($_REQUEST["billingCity"]))?addslashes($_REQUEST["billingCity"]):"";
$billingStateCode=(!empty($_REQUEST["billingStateCode"]))?addslashes($_REQUEST["billingStateCode"]):"";
$billingCountry=(!empty($_REQUEST["billingCountry"]))?addslashes($_REQUEST["billingCountry"]):"";
$billingZip=(!empty($_REQUEST["billingZip"]))?addslashes($_REQUEST["billingZip"]):"";
$billingPhone=(!empty($_REQUEST["billingPhone"]))?addslashes($_REQUEST["billingPhone"]):"";
$billingPhone2=(!empty($_REQUEST["billingPhone2"]))?addslashes($_REQUEST["billingPhone2"]):"";
$billingContact=(!empty($_REQUEST["billingContact"]))?addslashes($_REQUEST["billingContact"]):"";

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
		$sSQL="SELECT taxRate FROM stateCodes WHERE stateCode='".$billingStateCode."'";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		if($row=mysql_fetch_array($result))
		{
			if(!empty($row['taxRate']) && !empty($total))
			{
				$taxes = number_format($total/100*$row['taxRate'],2);
				$taxStr = "$".$taxes."(".$row['taxRate']."%)";
			}
		}
		$grandTotal = $total+ $taxes;	
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
		//saving order
	}
} 
if(empty($process))
{
	$sSQL="SELECT * FROM users WHERE usersID = '".$_SESSION['usersID']."'";
	$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
	if ($row = mysql_fetch_array($result)){	foreach($row as $k=>$v){$$k = $v;}}
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
                    <td width="640" align="right"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/inside/" class="subnav_link">Users</a><span class="subnav_divider">/</span><a href="/inside/cart.php" class="subnav_link">Shopping Cart</a><span class="subnav_divider">/</span><a href="/inside/checkout.php" class="subnav_link_active">Checkout</a></td>
                    <td width="12"></td>
                  </tr>
                  <tr>
                    <td width="13"></td>
                    <td width="640" class="arial_20_black_bold">Checkout</td>
                    <td width="12"></td>
                  </tr>
                  <tr>
                    <td width="13"></td>
                    <td width="640" style="padding-top:4px; padding-bottom:10px;"><div style="background-color:#CCCCCC; height:1px; widows:640px;"></div></td>
                    <td width="12"></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="5"></td>
                  </tr>
				  <? if(!empty($err)){?>
				  <tr>
                    <td width="13"></td>
                    <td width="640" style="color:#F00; padding-left:50px;" align="left"><ul><?=$err?></ul></td>
                    <td width="12"></td>
				  <? }?>
				  <? if(!empty($mess)){?>
				  <tr>
                    <td width="13"></td>
                    <td width="640" style="color:#093;"><?=$mess?></td>
                    <td width="12"></td>
				  <? }?>
				  <tr>
                    <td colspan="3" height="5"></td>
                  </tr>
                  <tr>
                    <td width="13"></td>
                    <td width="640" align="left"><table width="640" border="0" cellspacing="0" cellpadding="0">
					<form name="checkoutForm" id="checkoutForm" method="post">
						<input type="hidden" name="process" id="process" value="1">
						<input type="hidden" name="processSafeForm" id="processSafeForm" value="<?=$_SESSION["processSafe"]?>" />
                        <tr>
                          <td colspan="3" style="padding-bottom:7px;" class="arial_12_light_grey_bold">*Indicates a reuired field</td>
                        </tr>
                          <tr>
                            <td colspan="3" class="arial_16_red_bold">Billing Information</td>
                          </tr>
                          <tr>
                            <td colspan="3" style="padding-top:2px; padding-bottom:7px;" ><div style="background-color:#666666; height:1px; widows:640px;"></div></td>
                          </tr>
                          <tr>
                            <td colspan="3" height="10"></td>
                          </tr>
                          <tr>
                            <td align="left" width="300" style="padding-left:4px;"><strong>Contact Name*</strong></td>
                            <td width="40"></td>
                            <td align="left" width="300" style="padding-left:4px;"><strong>Address*</strong></td>
                          </tr>
                          <tr>
                            <td align="left" width="300"><input name="billingContact" id="billingContact" value="<?=$billingContact?>" type="text" class="form"></td>
                            <td width="40"></td>
                            <td align="left" width="300"><input name="billingAddress" id="billingAddress" value="<?=$billingAddress?>" type="text" class="form"></td>
                          </tr>
                          <tr>
                            <td colspan="3" height="10"></td>
                          </tr>
                          <tr>
                            <td align="left" width="300" style="padding-left:4px;"><strong>City*</strong></td>
                            <td width="40"></td>
                            <td align="left" width="300" style="padding-left:4px;"><strong>Province/State*</strong></td>
                          </tr>
                          <tr>
                            <td align="left" width="300"><input name="billingCity" id="billingCity" value="<?=$billingCity?>" type="text" class="form"></td>
                            <td width="40"></td>
                            <td align="left" width="300">
							<select name="billingStateCode" id="billingStateCode" class="form">
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
							</td>
                          </tr>
                          <tr>
                            <td colspan="3" height="10"></td>
                          </tr>
                          <tr>
                            <td align="left" width="300" style="padding-left:4px;"><strong>Postal/Zip Code*</strong></td>
                            <td width="40"></td>
                            <td align="left" width="300" style="padding-left:4px;"><strong>Country*</strong></td>
                          </tr>
                          <tr>
                            <td align="left" width="300"><input name="billingZip" id="billingZip" value="<?=$billingZip?>" type="text" class="form"></td>
                            <td width="40"></td>
                            <td align="left" width="300">
							<select name="billingCountry" id="billingCountry" class="form">
							<option value="">Please Select</option>
							  <option value="CA" <?=("CA" == $billingCountry)?"selected='selected'":""?>>Canada</option>
							  <option value="US" <?=("US" == $billingCountry)?"selected='selected'":""?>>United States</option>
							</select>
							</td>
                          </tr>
                          <tr>
                            <td colspan="3" height="10"></td>
                          </tr>
                          <tr>
                            <td align="left" width="300" style="padding-left:4px;"><strong>Phone*</strong></td>
                            <td width="40"></td>
                            <td align="left" width="300" style="padding-left:4px;"><strong>Other Phone (optional)</strong></td>
                          </tr>
                          <tr>
                            <td align="left" width="300"><input name="billingPhone" id="billingPhone" value="<?=$billingPhone?>" type="text" class="form"></td>
                            <td width="40"></td>
                            <td align="left" width="300"><input name="billingPhone2" id="billingPhone2" value="<?=$billingPhone2?>" type="text" class="form"></td>
                          </tr>
                          <tr>
                            <td colspan="3" height="30"></td>
                          </tr>
                          <tr>
                            <td colspan="3" style="padding-bottom:7px;" class="arial_12_light_grey_bold"><table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td style="padding-left:5px;"><a href="#" onclick="copyInfo(); return false;">Copy Billing Information</a></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td colspan="3" height="15"></td>
                          </tr>
                          <tr>
                            <td colspan="3" class="arial_16_red_bold">Shipping Information</td>
                          </tr>
                          <tr>
                            <td colspan="3" style="padding-top:2px; padding-bottom:7px;" ><div style="background-color:#666666; height:1px; widows:640px;"></div></td>
                          </tr>
                          <tr>
                            <td colspan="3" height="10"></td>
                          </tr>
                          <tr>
                            <td align="left" width="300" style="padding-left:4px;"><strong>Contact Name*</strong></td>
                            <td width="40"></td>
                            <td align="left" width="300" style="padding-left:4px;"><strong>Address*</strong></td>
                          </tr>
                          <tr>
                            <td align="left" width="300"><input name="shippingContact" id="shippingContact" value="<?=$shippingContact?>" type="text" class="form"></td>
                            <td width="40"></td>
                            <td align="left" width="300"><input name="shippingAddress" id="shippingAddress" value="<?=$shippingAddress?>" type="text" class="form"></td>
                          </tr>
                          <tr>
                            <td colspan="3" height="10"></td>
                          </tr>
                          <tr>
                            <td align="left" width="300" style="padding-left:4px;"><strong>City*</strong></td>
                            <td width="40"></td>
                            <td align="left" width="300" style="padding-left:4px;"><strong>Province/State*</strong></td>
                          </tr>
                          <tr>
                            <td align="left" width="300"><input name="shippingCity" id="shippingCity" value="<?=$shippingCity?>" type="text" class="form"></td>
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
							</td>
                          </tr>
                          <tr>
                            <td colspan="3" height="10"></td>
                          </tr>
                          <tr>
                            <td align="left" width="300" style="padding-left:4px;"><strong>Postal/Zip Code*</strong></td>
                            <td width="40"></td>
                            <td align="left" width="300" style="padding-left:4px;"><strong>Country*</strong></td>
                          </tr>
                          <tr>
                            <td align="left" width="300"><input name="shippingZip" id="shippingZip" value="<?=$shippingZip?>" type="text" class="form"></td>
                            <td width="40"></td>
                            <td align="left" width="300">
							<select name="shippingCountry" id="shippingCountry" class="form">
							<option value="">Please Select</option>
							  <option value="CA" <?=("CA" == $shippingCountry)?"selected='selected'":""?>>Canada</option>
							  <option value="US" <?=("US" == $shippingCountry)?"selected='selected'":""?>>United States</option>
							</select>
							</td>
                          </tr>
                          <tr>
                            <td colspan="3" height="10"></td>
                          </tr>
                          <tr>
                            <td align="left" width="300" style="padding-left:4px;"><strong>Phone*</strong></td>
                            <td width="40"></td>
                            <td align="left" width="300" style="padding-left:4px;"><strong>Other Phone (optional)</strong></td>
                          </tr>
                          <tr>
                            <td align="left" width="300"><input name="shippingPhone" id="shippingPhone" value="<?=$shippingPhone?>" type="text" class="form"></td>
                            <td width="40"></td>
                            <td align="left" width="300"><input name="shippingPhone2" id="shippingPhone2" value="<?=$shippingPhone2?>" type="text" class="form"></td>
                          </tr>
                          <tr>
                            <td colspan="3" height="10"></td>
                          </tr>
						  <tr>
                            <td colspan="3" style="padding-top:2px; padding-bottom:7px;" ><div style="background-color:#666666; height:1px; widows:640px;"></div></td>
                          </tr>
						  <tr>
                            <td colspan="3" height="10"></td>
                          </tr>
						  <tr>
						  	<td colspan="3" align="center">
							<table cellpadding="3" cellspacing="0" border="0" class="arial_12_grey_bold">
							<tr>
								<td align="right" class="arial_13_red_bold">Sub total</td>
								<td align="left">$<?=number_format($total,2)?></td>
							</tr>
							<tr>
								<td align="right" class="arial_13_red_bold">Taxes</td>
								<td align="left"><?=$taxStr?></td>
							</tr>
							<tr>
								<td align="right" class="arial_13_red_bold">Total</td>
								<td align="left">$<?=number_format($grandTotal,2)?></td>
							</tr>
							</table>
							</td>
						  </tr>
						  <tr>
                            <td colspan="3" height="10"></td>
                          </tr>
                          <tr>
                            <td align="left" width="300">
							
							</td>
                            <td width="40"></td>
                            <td align="left" width="300"><table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td><img src="/img/black-btn-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
                                  <td><div class="black_btn">
                                      <ul>
                                        <li><a  href="#" onclick="document.checkoutForm.submit();" class="black_btn_text">ORDER</a></li>
                                      </ul>
                                    </div></td>
                                  <td><img src="/img/black-btn-right.jpg" hspace="0" vspace="0" border="0" alt=""></td>
                                </tr>
                              </table></td>
                          </tr>
                        </form>
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
