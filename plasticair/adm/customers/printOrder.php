<?
error_reporting(E_ALL);
session_start();
include $_SERVER['DOCUMENT_ROOT']."/inc/dbconnect.php";
if((empty($_SESSION['storeID']) || empty($_SESSION['storeAdminsID'])) && $_SERVER['SCRIPT_NAME']!="/admin/index.php")
{
	header("Location: /admin/index.php");
	exit();
}


$statusArr["1"]="New";
$statusArr["2"]="In Process";
$statusArr["3"]="Shipped";
$statusArr["4"]="Paid";

$storeOrderID=(!empty($_REQUEST["storeOrderID"]))?str_replace("'","",$_REQUEST["storeOrderID"]):"0";
$sSQL="SELECT *
		FROM storeOrder
		WHERE storeID='".$_SESSION['storeID']."' AND storeOrderID = '".$storeOrderID."'";
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
if ($row=mysql_fetch_array($result)) {
	foreach ($row as $k=>$v) $$k=$v;
}

$sSQL="SELECT customerCompany, email, phone
		FROM storeCustomers
		WHERE storeID='".$_SESSION['storeID']."' AND storeCustomersID = '".$storeCustomersID."'";
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
if ($row=mysql_fetch_array($result)) {
	foreach ($row as $k=>$v) $$k=$v;
}
?>
<link rel="stylesheet" type="text/css" href="../inc/styles.css">
<table cellpadding="0" cellspacing="0" width="100%">
<tr><td align="center">

<table width="97%">

<tr valign="top"><td width="100%" valign="top"  align="center">
<form name='ff1' class='form' method='POST' action='' enctype="multipart/form-data" >
<input type='hidden' name='show' value='admin/table_edit' class='editbox'><input type='hidden' name='Submit' value='1' class='editbox'>  
		<table width="97%" class="text" cellpadding="2" cellspacing="2">

 
   
           

		<tr>
		<td>
			<table width="100%" class="text">


<tr><td colspan="2" align="center">
<table width="95%">
<tr>
<td align="left"></td>
<td align="right"><a href="#" onClick="print();">Print</a></td>
</tr>
</table>
</td></tr>
           
            <tr> 
              <td colspan="2" align="center"> 
                <table border="0" cellpadding="0" cellspacing="0" width="95%">
				<tr><td><table border="0" cellpadding="0" cellspacing="0" class="text">
                  <tr><td><b>Order #:</b>&nbsp;</td><td> <?=$storeOrderID?></td></tr>
					<tr><td><b>Date:</b>&nbsp;</td><td> <?=date("M, d Y",strtotime($submitted))?></td></tr>
					<tr><td><b>Total:</b>&nbsp;</td><td> $<?=number_format($total,2)?></td></tr></table></td></tr>
                  
				  </table>
              </td>
            </tr>
			
			
           <tr> 
              <td colspan="2" align="center">
			  
			  <br />
			  
			  <table class="text" width="95%">
<tr bgcolor="#CCCCCC">
	<td width="15"></td>
	<td><b>SKU</b></td>
	<td><b>Title</b></td>
	<td><b>Unit Price</b></td>
	<td><b>QTY</b></td>
	<td><b>Total</b></td>
</tr>
<?
$sSQL="SELECT * FROM storeOrderRow 
	WHERE storeID='".$_SESSION['storeID']."' AND storeCustomersID='".$storeCustomersID."' AND storeOrderID = '".$storeOrderID."' ORDER BY sku";
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
$totalPrice=0;
$jsItemsArr="";
$count=0;
$zpt="";
while($row=mysql_fetch_array($result))
{
	$totalPrice+=$row["unitPrice"]*$row["qty"];
	$jsItemsArr.=$zpt;
	
	if(!empty($row["storeCartRowID"])){
	$jsItemsArr.='"qty_'.$row["storeCartRowID"].'"';
	}
	$count++;
	$zpt=",";
?>
<tr>
<td><b><?=$count?></b></td>
<td><?=$row["sku"]?></td>
<td><?=$row["title"]?></td>
<td><?=number_format($row["unitPrice"], 2)?></td>
<td><?=$row["qty"]?></td>
<td align="right"><?=number_format($row["unitPrice"]*$row["qty"],2)?></td>
</tr>
<?
}
?><tr>
<td colspan="5" align="right"><b>Total:</b>&nbsp;</td>
<td align="right"><b><?=number_format($totalPrice,2)?></b></td>
</tr>
			  </table>
			  
			  
			  
              </td>
            </tr>
			
			
			
			
		               
			
			
			
			
			
			
			
             <tr> 
              <td colspan="2" align="center"><BR>
				<table width="95%" cellspacing="1" cellpadding="2" class="text">
	
	<tr>
		<td colspan="4" height="25" bgcolor="#CCCCCC"><table width="100%" cellspacing="0" cellpadding="0" class="text">
				<tr>
					<td><span class="text">Customer Information</span></td>
					<td align="right" width="*"></td>
				</tr>
		</table></td>

</tr>	
<tr>
		<td colspan="4" height="25"	>
		<table border="0" cellpadding="0" cellspacing="0" class="text">
			<tr><td><b>Company:</b>&nbsp;</td><td> <?=$customerCompany?></td></tr>
			<tr><td><b>Phone:</b>&nbsp;</td><td> <?=$phone?></td></tr>
			<tr><td><b>Email:</b>&nbsp;</td><td> <?=$email?></td></tr>
		</table>
</tr>
	<tr>
		<td colspan="4" height="25" bgcolor="#CCCCCC"><table width="100%" cellspacing="0" cellpadding="0" class="text">
				<tr>
					<td><span class="text">Billing Address</span></td>
					<td align="right" width="*"></td>
				</tr>
		</table></td>

</tr>

<tr>
		<td class="text" width="20%">First Name:</td>
		<td class="text" width="30%"><?=$bFName?></td>
		<td class="text" width="20%">Last Name:</td>
		<td class="text" width="30%"><?=$bLName?></td>
</tr>

<tr>
		<td class="text">Address:</td>
		<td class="text" colspan="3"><?=$bAddress?></td>
		
</tr>

<tr>
		<td class="text" width="20%">City:</td>
		<td class="text" width="30%"><?=$bCity?></td>
		<td class="text" width="20%">Postal Code / Zip:</td>
		<td class="text" width="30%"><?=$bzip?></td>
</tr>

<tr>
		<td width="20%" height="29" class="text">Province / State:</td>
		<td class="text" width="30%"><?
		$sSQL="SELECT stateName
				FROM stateCodes
					WHERE stateCode='".$bstateCode."'";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
		if($row = mysql_fetch_array($result)){
			echo $row["stateName"];
		}
		else
		{
			echo "N/A";
		} ?></td>
		<td class="text" width="20%">Country:</td>
		<td class="text" width="30%"><?
		if($bcountryCode=='CA'){ echo "CANADA";}elseif($bcountryCode=='US'){echo "UNITED STATES";}else{ echo "N/A"; }?></td>
</tr>

<tr>
		<td colspan="4" height="25" bgcolor="#CCCCCC"><table width="100%" cellspacing="0" cellpadding="0" class="text">
				<tr>
					<td><span class="text">Shipping Address</span></td>
				</tr>
		</table></td>

</tr>
<tr>
		<td class="text" width="20%">First Name:</td>
		<td class="text" width="30%"><?=$sFName?></td>
		<td class="text" width="20%">Last Name:</td>
		<td class="text" width="30%"><?=$sLName?></td>
</tr>


<tr>
		<td class="text">Address</td>
		<td class="text" colspan="3"><?=$sAddress?></td>
		
</tr>

<tr>
		<td class="text" width="20%">City:</td>
		<td class="text" width="30%"><?=$sCity?></td>
		<td class="text" width="20%">Postal Code / Zip:</td>
		<td class="text" width="30%"><?=$szip?></td>
</tr>

<tr>
		<td width="20%" height="29" class="text">Province / State:</td>
		<td class="text" width="30%"><?
		$sSQL="SELECT stateName
				FROM stateCodes
					WHERE stateCode='".$sstateCode."'";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
		if($row = mysql_fetch_array($result)){
			echo $row["stateName"];
		}
		else
		{
			echo "N/A";
		} ?></td>
		<td class="text" width="20%">Country:</td>
		<td class="text" width="30%"><?
		if($scountryCode=='CA'){ echo "CANADA";}elseif($scountryCode=='US'){echo "UNITED STATES";}else{ echo "N/A"; }?></td>
</tr>

 

</table>
              </td>
            </tr>
			<tr> 
              <td colspan="2" align="center">
			  <table width="95%" class="text">
			  <tr> 
                    <td><b>Comment:</b><br><table border="0" cellpadding="0" cellspacing="0" width="100%">
                              <tr> 
                                <td width="100%"><?=$comment?></td>
                              </tr>
                            </table></td>
                  </tr>
			  </table>
			  
			  
			  
			  
			  
               
              </td>
            </tr>
			
			
			
			
			
			
			
			<tr> 
              <td colspan="2" align="center" >
			  <table width="95%" class="text">
			  <tr> 
                    <td><b>Status</b> <BR>
						<?=$statusArr[$status]?></td>
                  </tr>
			  </table>
              </td>
            </tr>
			
			
			
			
			
			
			
            
   

                </table>
		  </td>
		</tr>
		
		
         
		
</table>

</form>
</td>
</tr>

</table>



</td></tr>

</table>