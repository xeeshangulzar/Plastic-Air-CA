<?
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
if(empty($_SESSION['usersID'])){header("Location: /login.php?r=/inside/cart.php"); exit();}
$err="";
$msg="";

/* Add item cart */
$_REQUEST['shipping_info']=empty($_REQUEST['shipping_info'])?'':str_replace("'", "", $_REQUEST['shipping_info']);
$processSafeForm=(!empty($_REQUEST["processSafeForm"]))?$_REQUEST["processSafeForm"]:"";
$_SESSION["processSafe"]=(empty($_SESSION["processSafe"]))?"1":$_SESSION["processSafe"];
if(!empty($_REQUEST["addProduct"]))
{
	$_SESSION["processSafe"]++;
	$productsID=(!empty($_REQUEST["productsID"]))?str_replace("'","`",$_REQUEST["productsID"]):"";
	$quantity=(!empty($_REQUEST["quantity"]))?str_replace("'","`",$_REQUEST["quantity"]):"";
	
	/* If product ID not empty process with adding product to cart */
	
	if($productsID!=""){
	
		/* If quantity of products more then 0 processe with adding product to cart */
	
		if($quantity>0){
		
			/* Check if product ID we are adding is existing product, and retrieving product details */
			
			$sSQL="SELECT * FROM products WHERE productsID = '".$productsID."'";
			$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
			if($row=mysql_fetch_array($result))
			{
				$price=$row["price"];
				$productNumber="";
				
				/* Check if products we are trying to add already in a cart.
					If yes then just add quantity, if no insert new row */
							
				$sSQL2="SELECT * FROM shoppingCart WHERE usersID='".$_SESSION["usersID"]."' AND productsID='".$row["productsID"]."' ";
				$result2=mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br />".$sSQL2);
				if($row2=mysql_fetch_array($result2))
				{
					/* Updating product quantity in cart */
					$sSQL3="UPDATE shoppingCart SET qty=qty+".$quantity." WHERE usersID='".$_SESSION["usersID"]."' AND productsID='".$row["productsID"]."' ";
					mysql_query($sSQL3) or die ("MySQL err: ".mysql_error()."<br />".$sSQL3);
				}
				else
				{
					/* Adding new row to a cart */
					$sSQL3="INSERT INTO shoppingCart (dateCreated,
														usersID,
														productsID,
														productNumber,
														title,
														price,
														qty, shipping_info)
											VALUES(NOW(),
													'".$_SESSION["usersID"]."',
													'".$row["productsID"]."',
													'".$productNumber."',
													'".addslashes($row["title"])."',
													'".$price."',
													'".$quantity."', '".addslashes($_REQUEST['shipping_info'])."')";
					mysql_query($sSQL3) or die ("MySQL err: ".mysql_error()."<br />".$sSQL3);
				}
			}
			/* Display message that products was successfully added to shopping cart */
			count_cart();
			
			
			
			$msg=$quantity." product(s) added to your shopping cart";
		}
		else
		{
			/* Display error if quantity less or equal to zero */
			$err="Invalid quantity. <a href='/products/details.php?productsID=".$productsID."'>Click here</a> to get back to product details.";
		}
	}
	else
	{
		/* If product ID empty then display error message. Should never be visible,
			but if someone will try to hack the system, proper message will be shown */
		$err="Can not add product to the cart. Please try again.";
	}
}
/* Delete item from cart */
$shoppingCartIDDel=(!empty($_REQUEST["shoppingCartIDDel"]))?str_replace("'","`",$_REQUEST["shoppingCartIDDel"]):"";
$processSafeFormCart=(!empty($_REQUEST["processSafeFormCart"]))?$_REQUEST["processSafeFormCart"]:"";
if($shoppingCartIDDel!="" && !empty($processSafeFormCart) && $_SESSION["processSafe"]==$processSafeFormCart){
	$_SESSION["processSafe"]++;
	$sSQL="DELETE FROM shoppingCart WHERE usersID='".$_SESSION["usersID"]."' AND shoppingCartID='".$shoppingCartIDDel."'";
	mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
	
    count_cart();
	
	$msg="Product deleted from you shopping cart";
}

/* Recalculate shopping cart values */

if(!empty($_REQUEST["recalculate"]) && $_REQUEST["recalculate"]=="1"
	&& !empty($processSafeFormCart) && $_SESSION["processSafe"]==$processSafeFormCart)
{
	$_SESSION["processSafe"]++;
	$sSQL="SELECT * FROM shoppingCart WHERE usersID='".$_SESSION["usersID"]."'";
	$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
	$totalPrice=0;
	while($row=mysql_fetch_array($result))
	{
		$code = '';
		$newQTYvarName="qty_".$row["shoppingCartID"];
		$$newQTYvarName=(!empty($_REQUEST[$newQTYvarName]))?str_replace("'","`",$_REQUEST[$newQTYvarName]):"0";
		
		if($$newQTYvarName>0 && empty($_REQUEST["del_".$row["shoppingCartID"]]))
		{
			$sSQL2="UPDATE shoppingCart SET qty = '".$$newQTYvarName."', shipping_info='".addslashes($_REQUEST['shipping_info'])."' WHERE usersID='".$_SESSION["usersID"]."'
					AND shoppingCartID='".$row["shoppingCartID"]."'";
			mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br />".$sSQL2);
		}
		else
		{
			$sSQL2="DELETE FROM shoppingCart
				WHERE usersID='".$_SESSION["usersID"]."'
					AND shoppingCartID='".$row["shoppingCartID"]."'";
			mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br />".$sSQL2);
		}
	}
	count_cart();
	$msg.="Shopping cart updated";
}
?><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold">Shopping Cart</td></tr>
<tr><td colspan="2" height="40"></td></tr>
<tr><td width="25"></td>
<td align="left"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/inside/" class="subnav_link_active">Users</a><span class="subnav_divider">/</span><a href="/inside/cart.php" class="subnav_link_active">Shopping Cart</a></td></tr>
<tr><td>&nbsp;</td>
<td>&nbsp;</td></tr>
</table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table><?
$page_inside="Shopping Cart";
include "inc/menu.php";
?>
<table width="665" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="3" height="10"></td></tr>
<tr><td width="13"></td>
<td width="640" align="left"><table width="640" border="0" cellspacing="0" cellpadding="0" style="line-height:15px;">
<form name="cart" id="cart" action="/inside/cart.php" method="post">
<input type="hidden" name="shoppingCartIDDel" id="shoppingCartIDDel" value="" />
<input type="hidden" name="processSafeFormCart" id="processSafeFormCart" value="<?=$_SESSION["processSafe"]?>" />
<input type="hidden" name="recalculate" id="recalculate" value="" />
<tr class="arial_13_green_bold"><td width="39" align="left">No.</td>
<td width="134" align="left">Product Code</td>
<td width="207" align="left">Product Tittle</td>
<td width="70" align="left">Quantity</td>
<td width="100" align="left">Price</td>
<td width="70" align="left">Delete</td>
<td width="20" align="left"></td></tr>
<tr><td colspan="7" height="10"></td></tr>
<?
$sSQL="SELECT * FROM shoppingCart
WHERE usersID='".$_SESSION["usersID"]."'";
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
$totalPriceOriginal=0;
$jsItemsArr="";
$count=0;
$zpt="";
$relatedStr = "";
while($row=mysql_fetch_array($result)){
		$originalPrice = $row["price"];
		$totalPriceOriginal += $originalPrice*$row["qty"]; 
		$jsItemsArr.=$zpt.'"qty_'.$row["shoppingCartID"].'"';
		$count++;
		$relatedStr .= $zpt.$row['productsID'];
		$zpt=",";
		?><tr class="arial_12_grey_bold">
		<td width="39" align="left" ><?=$count?>.</td>
		<td width="134" align="left"><?=$row['productNumber']?></td>
		<td width="187" align="left" style="padding-right:20px;" ><?=$row['title']?></td>
		<td width="70" align="left" ><input type="text" name="qty_<?=$row["shoppingCartID"]?>" id="qty_<?=$row["shoppingCartID"]?>" class="form" value="<?=$row['qty']?>" style=" width:30px; padding-left:15px; height:20px;"></td>
		<td width="100" align="left" >$<?=number_format($row['price'],2)?></td>
		<td width="55" align="left" style="padding-left:13px;"><input name="del_<?=$row["shoppingCartID"]?>" id="del_<?=$row["shoppingCartID"]?>" type="checkbox" value="1"></td>
		<td width="20" align="left"><a href="<?=seo_link("products", $row['productsID'], $row['title'])?>" class="arial_11_grey_bold">View</a></td></tr>
		<tr><td height="5px" colspan="7"><hr /></td></tr><? }
//taxes
$taxes = 0;
$taxStr = array();
$gst=0;  $pst=0; $hst=0; $tax=0;
$sSQL="SELECT s.gst, s.pst, s.hst, s.tax FROM users LEFT JOIN stateCodes s ON (s.stateCode = users.billingStateCode)
WHERE usersID='".$_SESSION["usersID"]."'";
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
if($row=mysql_fetch_array($result)){
	if(!empty($totalPriceOriginal)){
		if(!empty($row['gst'])){
			$gst = round($row['gst']*$totalPriceOriginal/100,2);
			$taxStr["GST "."(".$row['gst']."%)"]= "$".number_format($gst, 2);
			}
		if(!empty($row['pst'])){
			$pst = round($row['pst']*$totalPriceOriginal/100,2);
			$taxStr["PST (".$row['pst']."%)"]= "$".number_format($pst, 2);
			}
		if(!empty($row['hst'])){
			$hst = round($row['hst']*$totalPriceOriginal/100,2);
			$taxStr["HST (".$row['hst']."%)"]= "$".number_format($hst, 2);
			}
		if(!empty($row['tax'])){
			$tax = round($row['tax']*$totalPriceOriginal/100,2);
			$taxStr["TAX (".$row['tax']."%)"]= "$".number_format($tax, 2);
			}
	}
}
$grandTotal = $totalPriceOriginal+ $gst+$pst+$hst+$tax;
?>
<tr><td colspan="7" height="30"></td></tr>
<tr class="arial_12_grey_bold" valign="top">
<td width="39" align="left" ></td>
<td width="134" align="left" valign="top"></td>
<td width="187" align="left" style="padding-right:20px;"></td>
<td width="70" align="right" class="arial_13_green_bold">Sub Total:</td>
<td width="100" align="right" >$<?=number_format($totalPriceOriginal,2)?></td>
<td width="55" align="left" style="padding-left:13px; padding-top:4px;"></td>
<td width="20" align="left"></td></tr>
<tr><td colspan="7" height="10"></td></tr><?
if(!empty($taxStr)){
	foreach($taxStr as $k=>$v){ ?><tr class="arial_12_grey_bold"><td colspan="3"></td>
	<td width="70" align="right" class="arial_13_green_bold"><?=$k?>:</td><td align="right" ><?=$v?></td></tr><? } }
?>
<tr><td colspan="7" height="10"></td></tr>
<tr class="arial_12_grey_bold" valign="top">
<td width="39" align="left" ></td>
<td width="134" align="left" valign="top"></td>
<td width="187" align="left" style="padding-right:20px;"></td>
<td width="70" align="right" class="arial_13_green_bold">Total:</td>
<td width="100" align="right" >$<?=number_format($grandTotal,2)?></td>
<td width="55" align="left" style="padding-left:13px; padding-top:4px;"></td>
<td width="20" align="left"></td></tr>
<tr><td colspan="7" height="20"></td></tr>
<tr><td colspan="7" height="2" bgcolor="#CCCCCC"></td></tr>
<tr><td colspan="11"><table border="0" cellspacing="0" cellpadding="0" style="margin:10px 0;">
<tr><td align="left" colspan="2"><div class="arial_14_green_bold">Shipping Info:</div><?
$sSQL="SELECT * FROM content WHERE contentID=7";
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
if($row=mysql_fetch_array($result)){ ?><?=stripslashes($row['description'])?><input type="hidden" name="shipping_info" value="<?=stripslashes($row['description'])?>" /><? }
?></td><td></td></tr></table>
</td></tr></form>
<tr><td colspan="7" height="10" align="right"><table border="0" cellspacing="0" cellpadding="0">
<tr><td><a href="#" onclick="recalculateCart(); return false;" class="arial_14_green_bold" style="width:110px;"><img src="/img/btn/recalculate.png" width="110" height="25" border="0" /></a></td>
<td style="padding-left:20px;"><a href="/products.php"><img src="/img/btn-continue-shopping.jpg"  border="0" alt=""></a></td><? 
if(!empty($count)){
	?><td style="padding-left:20px;"><a href="/inside/checkout.php" class="black_btn_text"><img src="/img/btn-checkout.jpg" border="0" alt="" /></a></td><? 
}?></tr>
</table></td></tr>
</table></td>
<td width="12"></td></tr>
<tr><td colspan="3" height="15"></td></tr>
</table>
<script type="text/javascript" language="javascript">

function recalculateCart()
{
	document.getElementById("recalculate").value="1";
	document.cart.submit();
}
</script><?
include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php";?>