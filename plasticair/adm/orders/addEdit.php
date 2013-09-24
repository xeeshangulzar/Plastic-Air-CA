<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";

$tableName="orders";
$tableID="ordersID";
$folderName="orders";
$pageTitle="Orders";
$filesPath="/dat/orders/";

$$tableID=(!empty($_REQUEST[$tableID]))?str_replace("'","",$_REQUEST[$tableID]):"0";
$status=(!empty($_REQUEST["status"]))?addslashes($_REQUEST["status"]):"";
$shipping_info=(!empty($_REQUEST["shipping_info"]))?addslashes($_REQUEST["shipping_info"]):"";


$_SESSION["processSafe"]=(empty($_SESSION["processSafe"]))?"1":$_SESSION["processSafe"];
$processSafeForm=(!empty($_REQUEST["processSafeForm"]))?$_REQUEST["processSafeForm"]:"";

$err="";
$mess="";

if(!empty($_REQUEST["process"]) && !empty($processSafeForm) && $_SESSION["processSafe"]==$processSafeForm) {

	$_SESSION["processSafe"]++;

	//mandatory fields
	if($ordersID==""){$$tableID="0";}
	if($status==""){$err.="<li>Status</il> ";}
	

	//So message will be written to DB
	if($err=="")
	{
		$sSQL="UPDATE ".$tableName." SET
						status='".$status."',
						shipping_info='".$shipping_info."'
					    WHERE  ".$tableID." = '".$$tableID."'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		
		$mess="Information Saved.";
	}
	if (!empty($err)) {
		$err="<u>The following is required</u>:<ul>".$err."</ul>";
	}
}
if (!empty($$tableID)) {
	$sql="SELECT * FROM ".$tableName." WHERE  ".$tableID." = '".$$tableID."'";
	$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
	if($row=mysql_fetch_assoc($result)){
		foreach ($row as $k=>$v) $$k=$v;
		$taxStr="";
		if(!empty($row['gst'])){
			$taxStr .= "GST: $".number_format($row['tax_gst'], 2)."(".$row['gst']."%)<br />";
			}
		if(!empty($row['pst'])){
			$taxStr .= "PST: $".number_format($row['tax_pst'], 2)."(".$row['pst']."%)<br />";
			}
		if(!empty($row['hst'])){
			$taxStr .= "HST: $".number_format($row['tax_hst'], 2)."(".$row['hst']."%)<br />";
			}
		if(!empty($row['tax'])){
			$taxStr .= "TAX: $".number_format($row['tax_tax'], 2)."(".$row['tax']."%)<br />";
			}
		}
}
else
{
	header("Location: /adm/orders/");	
	exit();
}
?>
<FORM id="ff1" name="ff1" action="" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="process" id="process" value="1">
        <input type="hidden" name="deleteFile" id="deleteFile" value="">
		<input type="hidden" name="processSafeForm" id="processSafeForm" value="<?=$_SESSION["processSafe"]?>" />
		<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" class="content">
              <table border="0" cellspacing="0" cellpadding="0" class="text" width="100%">
				  <tr>
<td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pageTabs">
                    <tr>
                      <td width="2" background="/adm/img/tabs_bottom_bg_left.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                      <td width="150" class="arial20_Grey" align="center"><?=$pageTitle?> <img src="/adm/img/arrowGreyRightBig.jpg" width="5" height="9" align="absmiddle" /></td>
                      <td align="left"><table border="0" cellspacing="0" cellpadding="0" height="35">
                        <tr>
                          <td width="2" background="/adm/img/tabs_bg_left.jpg"></td>
                          <td background="/adm/img/tabs_bg.jpg"><a href="/adm/<?=$folderName?>/?searchFromSession=1" class="tabLink"><b>View All</b></a></td>
                          <td width="1" background="/adm/img/tabs_bg_right.jpg"></td><?php /*?>
                          <td width="2" background="/adm/img/tabs_active_bg_left.jpg"></td>
                          <td background="/adm/img/tabs_active_bg.jpg"><a href="/adm/<?=$folderName?>/addEdit.php" class="tabLink">Add New</a></td>
                          <td width="4" background="/adm/img/tabs_active_bg_right_tr.jpg"></td><?php */?>
                        </tr>
                      </table></td>
                      <td width="2" background="/adm/img/tabs_bottom_bg_right.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                    </tr>
                  </table></td></tr>
<tr><td class="contentCell"><table border="0" cellspacing="0" cellpadding="3" class="text" width="100%"><tr><td>&nbsp;</td><td>&nbsp;</td></tr><?
if (!empty($err)) { ?><tr><td colspan="2" class="err"><?=$err?></td></tr><? } 
if (!empty($mess)) { ?><tr><td colspan="2" class="message"><?=$mess?></td></tr><? }
?>
					<tr>
						<td align="right">Status: </td> 
						<td>
						<select name="status" id="status" class="inputField" style="width:200px;">
						<option value="New">New</option>
						<option value="Processed" <?=($status=="Processed")?"selected='selected'":""?>>Processed</option>
						<option value="Complete" <?=($status=="Complete")?"selected='selected'":""?>>Complete</option>
						</select>
						</td>
					</tr>
					<tr>
						<td colspan="4" ><hr /></td>
					</tr>
					<tr><td></td><td align="left"><strong>Order Information</strong></td></tr>
					<tr>
						<td align="right">Customer: </td>
						<td><?=$fname?> <?=$lname?>, <?=$email?></td>
					</tr>
					<tr>
						<td align="right" valign="top">Products: </td>
						<td>
						<table cellpadding="3" cellspacing="0" border="1" class="text">
						<tr>
							<td><b>Product</b></td>
							<td><b>Price</b></td>
							<td><b>Quantity</b></td>
						</tr>
						<?
						$sql="SELECT * FROM orderRow WHERE  ".$tableID." = '".$$tableID."'";
						$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
						while($row=mysql_fetch_assoc($result))
						{
						?>
						<tr>
							<td><?=$row['productNumber']?> - <?=$row['title']?></td>
							<td>$<?=number_format($row['price'],2)?></td>
							<td align="center"><?=$row['qty']?></td>
						</tr>
						<? }?>
						</table>
						</td>
					</tr>
					<tr>
						<td align="right">Sub total: </td>
						<td>$<?=number_format($subTotal,2)?></td>
					</tr>
					<tr>
						<td align="right">Taxes: </td>
						<td><?=$taxStr?></td>
					</tr>
					<tr>
						<td align="right">Total: </td>
						<td>$<?=number_format($total,2)?></td>
					</tr>
					<tr>
						<td colspan="4" ><hr /></td>
					</tr>
					<tr><td></td><td align="left"><strong>Billing Information</strong></td></tr>
					<tr>
						<td align="right">Contact Name: </td>
						<td><input type="text" name="billingContact" id="billingContact" readonly="readonly" value="<?=$billingContact?>" class="inputField" style="width:200px;" /></td>
						<td align="right">Address: </td>
						<td><input type="text" name="billingAddress" id="billingAddress" readonly="readonly" value="<?=$billingAddress?>" class="inputField" style="width:200px;" /></td>
					</tr>
					<tr>
						<td align="right">City: </td>
						<td><input type="text" name="billingCity" id="billingCity" readonly="readonly" value="<?=$billingCity?>" class="inputField" style="width:200px;" /></td>
						<td align="right">State/Province: </td>
						<td><select name="billingStateCode" id="billingStateCode" disabled="disabled" class="inputField" style="width:200px;">
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
							</select></td>
					</tr>
					<tr>
						<td align="right">Zip/Postal Code: </td>
						<td><input type="text" name="billingZip" readonly="readonly" id="billingZip" value="<?=$billingZip?>" class="inputField" style="width:200px;" /></td>
						<td align="right">Country: </td>
						<td><select name="billingCountry" disabled="disabled" id="billingCountry" class="inputField" style="width:200px;">
<option value="">Please Select</option><?
$sSQL="SELECT DISTINCT countryCode, countryName FROM countryCodes ORDER BY countryType, countryName ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
while($row1=mysql_fetch_array($result1)){
	?><option value="<?=$row1['countryCode']?>"<?=$billingCountry==$row1['countryCode']?' selected="selected"':'' ?>><?=$row1['countryName']?></option><? }
?></select></td>
					</tr>
					<tr>
						<td align="right">Phone 1: </td>
						<td><input type="text" name="billingPhone" readonly="readonly" id="billingPhone" value="<?=$billingPhone?>" class="inputField" style="width:200px;" /></td>
						<td align="right">Phone 2: </td>
						<td><input type="text" name="billingPhone2" readonly="readonly" id="billingPhone2" value="<?=$billingPhone2?>" class="inputField" style="width:200px;" /></td>
					</tr>
					<tr>
						<td colspan="4" ><hr /></td>
					</tr>
					<tr><td></td><td align="left"><strong>Shipping Information</strong></td></tr>
					<tr>
						<td align="right">Contact Name: </td>
						<td><input type="text" name="shippingContact" readonly="readonly" id="shippingContact" value="<?=$shippingContact?>" class="inputField" style="width:200px;" /></td>
						<td align="right">Address: </td>
						<td><input type="text" name="shippingAddress" readonly="readonly" id="shippingAddress" value="<?=$shippingAddress?>" class="inputField" style="width:200px;" /></td>
					</tr>
					<tr>
						<td align="right">City: </td>
						<td><input type="text" name="shippingCity" readonly="readonly" id="shippingCity" value="<?=$shippingCity?>" class="inputField" style="width:200px;" /></td>
						<td align="right">State/Province: </td>
						<td><select name="shippingStateCode" disabled="disabled" id="shippingStateCode" class="inputField" style="width:200px;">
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
							</select></td>
					</tr>
					<tr>
						<td align="right">Zip/Postal Code: </td>
						<td><input type="text" name="shippingZip" readonly="readonly" id="shippingZip" value="<?=$shippingZip?>" class="inputField" style="width:200px;" /></td>
						<td align="right">Country: </td>
						<td><select name="shippingCountry" id="shippingCountry" disabled="disabled" class="inputField" style="width:200px;">
<option value="">Please Select</option><?
$sSQL="SELECT DISTINCT countryCode, countryName FROM countryCodes ORDER BY countryType, countryName ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
while($row1=mysql_fetch_array($result1)){
	?><option value="<?=$row1['countryCode']?>"<?=$shippingCountry==$row1['countryCode']?' selected="selected"':'' ?>><?=$row1['countryName']?></option><? }
?></select></td>
					</tr>
					<tr>
						<td align="right">Phone 1: </td>
						<td><input type="text" name="shippingPhone" readonly="readonly" id="shippingPhone" value="<?=$shippingPhone?>" class="inputField" style="width:200px;" /></td>
						<td align="right">Phone 2: </td>
						<td><input type="text" name="shippingPhone2" readonly="readonly" id="shippingPhone2" value="<?=$shippingPhone2?>" class="inputField" style="width:200px;" /></td>
					</tr>
<tr valign="top"><td align="right">Shipping Info:</td><td colspan="3"><textarea name="shipping_info" class="inputField" style="height:50px; width:400px;"><?=stripslashes($shipping_info)?></textarea></td></tr>

<tr><td height="40" colspan="2" align="center" valign="middle"><input type="image" src="/adm/img/btn_save.png" accesskey="s" />
<input type="hidden" name="<?=$tableID?>" id="<?=$tableID?>" value="<?=$$tableID?>" />&nbsp;</td></tr>
</table></td></tr></table></td></tr></table></FORM>
<script type="text/javascript" language="javascript">
function deleteFiles(id)
{
	if(confirm(" File will be deleted permanenty please confirm. "))
	{
		document.getElementById('deleteFile').value = id; 
		document.getElementById('ff1').submit();
	}
}
</script><?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/footer.php";?>