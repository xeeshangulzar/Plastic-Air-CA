<?
include $_SERVER['DOCUMENT_ROOT']."/admin/inc/header.php";

$folderName="customers";
$pageTitle="Orders";

$statusArr["1"]="New";
$statusArr["2"]="In Process";
$statusArr["3"]="Shipped";
$statusArr["4"]="Paid";

$comment=(!empty($_REQUEST["comment"]))?addslashes($_REQUEST["comment"]):"";


$sFName=(!empty($_REQUEST["sFName"]))?str_replace("'","`",$_REQUEST["sFName"]):"";
$bFName=(!empty($_REQUEST["bFName"]))?str_replace("'","`",$_REQUEST["bFName"]):"";
$sLName=(!empty($_REQUEST["sLName"]))?str_replace("'","`",$_REQUEST["sLName"]):"";
$bLName=(!empty($_REQUEST["bLName"]))?str_replace("'","`",$_REQUEST["bLName"]):"";
$sAddress=(!empty($_REQUEST["sAddress"]))?str_replace("'","`",$_REQUEST["sAddress"]):"";
$bAddress=(!empty($_REQUEST["bAddress"]))?str_replace("'","`",$_REQUEST["bAddress"]):"";
$sCity=(!empty($_REQUEST["sCity"]))?str_replace("'","`",$_REQUEST["sCity"]):"";
$bCity=(!empty($_REQUEST["bCity"]))?str_replace("'","`",$_REQUEST["bCity"]):"";
$szip=(!empty($_REQUEST["szip"]))?str_replace("'","`",$_REQUEST["szip"]):"";
$bzip=(!empty($_REQUEST["bzip"]))?str_replace("'","`",$_REQUEST["bzip"]):"";
$sstateCode=(!empty($_REQUEST["sstateCode"]))?str_replace("'","`",$_REQUEST["sstateCode"]):"";
$bstateCode=(!empty($_REQUEST["bstateCode"]))?str_replace("'","`",$_REQUEST["bstateCode"]):"";
$scountryCode=(!empty($_REQUEST["scountryCode"]))?str_replace("'","`",$_REQUEST["scountryCode"]):"";
$bcountryCode=(!empty($_REQUEST["bcountryCode"]))?str_replace("'","`",$_REQUEST["bcountryCode"]):"";
$status=(!empty($_REQUEST["status"]))?str_replace("'","`",$_REQUEST["status"]):"";
$paid=(!empty($_REQUEST["paid"]))?1:0; 
//productImage





$storeOrderID=(!empty($_REQUEST["storeOrderID"]))?str_replace("'","",$_REQUEST["storeOrderID"]):"0";
$err="";
$mess="";
$processSafeForm=(!empty($_REQUEST["processSafeForm"]))?$_REQUEST["processSafeForm"]:"";
$_SESSION["processSafe"]=(empty($_SESSION["processSafe"]))?"1":$_SESSION["processSafe"];
if(!empty($_REQUEST["process"]) && $_REQUEST["process"]=="1"
	&& !empty($processSafeForm) && $_SESSION["processSafe"]==$processSafeForm)
{
	$_SESSION["processSafe"]++;
	//if($title==""){$err.="Titile; ";}
	//if($details==""){$err.="Details; ";}
	//if($storeCategoryID==""){$err.="Category; ";}

	if($err=="")
	{	
		if($storeOrderID=="0")
		{
			$sSQL="INSERT INTO storeCustomers(created, storeID)
								VALUES(NOW(), '".$_SESSION['storeID']."')";
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
			$storeOrderID=mysql_insert_id();
		}
		

		
		
		$sSQL="UPDATE storeOrder SET
						bFName='".$bFName."',
						bLName='".$bLName."',
						bAddress='".$bAddress."',
						bCity='".$bCity."',
						bzip='".$bzip."',
						bstateCode='".$bstateCode."',
						bcountryCode='".$bcountryCode."',
						sFName='".$sFName."',
						sLName='".$sLName."',
						sAddress='".$sAddress."',
						sCity='".$sCity."',
						szip='".$szip."',
						sstateCode='".$sstateCode."',
						scountryCode='".$scountryCode."',
						status='".$status."',
						paid='".$paid."', 
						comment='".$comment."'
				WHERE storeID = '".$_SESSION['storeID']."' AND storeOrderID = '".$storeOrderID."'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		$mess="Information Saved.";
	}
	else
	{
		$err="Please Enter: ".$err;
	}
	//Display HTML properly
	
	
}

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
<FORM id="ff1" name="ff1" action="" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="process" id="process" value="1">
		<input type="hidden" name="processSafeForm" id="processSafeForm" value="<?=$_SESSION["processSafe"]?>" />
		<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" class="content">
              <table border="0" cellspacing="0" cellpadding="0" class="text" width="100%">
				  <tr>
					<td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pageTabs">
                    <tr>
                      <td width="2" background="/admin/img/tabs_bottom_bg_left.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                      <td width="140" class="arial20_Grey" align="center"><?=$pageTitle?> <img src="/admin/img/arrowGreyRightBig.jpg" width="5" height="9" align="absmiddle" /></td>
                      <td align="left"><table border="0" cellspacing="0" cellpadding="0" height="35">
                        <tr>
                          <td width="1" background="/admin/img/tabs_bg_right.jpg"></td>
                          <td background="/admin/img/tabs_bg.jpg"><a href="/admin/<?=$folderName?>/orderList.php" class="tabLink">View All</a></td>
                          <td width="1" background="/admin/img/tabs_bg_right.jpg"></td>
                        </tr>
                      </table></td>
                      <td width="2" background="/admin/img/tabs_bottom_bg_right.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                    </tr>
                  </table></td>
				  </tr>
                <tr>
                  <td class="contentCell"><table border="0" cellspacing="0" cellpadding="3" class="text" width="100%">			  
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					</tr><?
					if (!empty($err)) { ?>	  
					<tr>
					  <td colspan="2" class="err"><?=$err?></td>
					</tr><?
					} 
					if (!empty($mess)) { ?>			  
					<tr>
					  <td colspan="2" class="message"><?=$mess?></td>
					</tr><?
					}
					?>
					<tr><td><br />
			<TABLE width="100%">         
            <TR> 
              <TD colspan="2"> 
                <TABLE border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
				<tr><td colspan="3" align="right"><input type="button" value="Print Order" onclick="printer();" /></td></tr>
                  <TR> 
                    <TD width="33%" ><SPAN class="text">Order #</SPAN> 
                      <BR>
					  	<INPUT type='text' name='storeOrderID' value='<?=$storeOrderID?>' class="inputField" style="width:200px;" readonly>                    </TD>
                    <TD width="34%"><SPAN class="text">Date</SPAN> <BR>
					 <INPUT type='text' name='submitted' value='<?=date("M, d Y",strtotime($submitted))?>' class="inputField" style="width:200px;" readonly>                    </TD>
                    <TD width="33%"><SPAN class="text">Total</SPAN> 
                      <BR>
					  <INPUT type='text' name='total' value='<?=$total?>' class="inputField" style="width:200px;" readonly>
                    </TD>
                  </TR>
                  <TR> 
                    <TD >
                      <SPAN class="text">Company Name</SPAN> <BR>
					  <INPUT type='hidden' name='customers.idCustomer' value='11350' class="inputField" style="width:200px;">					  <INPUT type='text' name='customers.customerCompany' value='<?=$customerCompany?>' class="inputField" style="width:200px;" readonly>                    </TD>
                    <TD><SPAN class="text">Email</SPAN> <BR>
					<INPUT type='text' name='customers.email' value='<?=$email?>' class="inputField" style="width:200px;" readonly>                  
                    </TD>
                    <TD><SPAN class="text">Business Phone</SPAN> <BR>
                      <INPUT type='text' name='customers.phone' value='<?=$phone?>' class="inputField" style="width:200px;" readonly>                    </TD>
                  </TR>
                </TABLE>
              </TD>
            </TR>
			
			
           <TR> 
              <TD colspan="2" align="center">
			  
			  <br />
			  
			  <table class="text" width="95%">
<tr bgcolor="#CCCCCC">
	<td width="15"></td>
	<td><b>SKU</b></td>
	<td><b>UPC</b></td>
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
<td><?=$row["upc"]?></td>
<td><?=$row["title"]?></td>
<td><?=number_format($row["unitPrice"], 2)?></td>
<td><?=$row["qty"]?></td>
<td align="right"><?=number_format($row["unitPrice"]*$row["qty"],2)?></td>
</tr>
<?
}
?><tr>
<td colspan="6" align="right"><b>Total:</b>&nbsp;</td>
<td align="right"><b><?=number_format($totalPrice,2)?></b></td>
</tr>
			  </table>
			  
			  
			  
              </TD>
            </TR>
			
			
             <TR> 
              <TD colspan="2" align="center"><BR>
				<TABLE width="95%" cellspacing="1" cellpadding="2" class="text">
	
	<tr>
		<td colspan="4" height="25" bgcolor="#CCCCCC"><TABLE width="100%" cellspacing="0" cellpadding="0" class="arial_black11">
				<tr>
					<td class="text"><b>Billing Address</b></td>
					<td align="right" width="*"></td>
				</tr>
		</TABLE></td>

</tr>

<tr>
		<td class="ccol1" width="20%">First Name</td>
		<td class="ccol2" width="30%"><INPUT type='text' name='bFName' id="bFName" value='<?=$bFName?>' class="inputField" style="width:200px;"></td>
		<td class="ccol1" width="20%">Last Name</td>
		<td class="ccol2" width="30%"><INPUT type='text' name='bLName' id="bLName" value='<?=$bLName?>' class="inputField" style="width:200px;"></td>
</tr>

<tr>
		<td class="ccol1">Address</td>
		<td class="ccol2" colspan="3"><INPUT type='text' name='bAddress' id="bAddress" value='<?=$bAddress?>' class="inputField" style="width:200px;"></td>
		
</tr>

<tr>
		<td class="ccol1" width="20%">City</td>
		<td class="ccol2" width="30%"><INPUT type='text' name='bCity' id="bCity" value='<?=$bCity?>' class="inputField" style="width:200px;"></td>
		<td class="ccol1" width="20%">Postal Code / Zip</td>
		<td class="ccol2" width="30%"><INPUT type='text' name='bzip' id="bzip" value='<?=$bzip?>' class="inputField" style="width:200px;"></td>
</tr>

<tr>
		<td width="20%" height="29" class="ccol1">Province / State</td>
		<td class="ccol2" width="30%"><select name='bstateCode' id="bstateCode" class="inputField" style="width:200px;">
		<option value=''>Select Province / State</option>
		<?
		$sSQL="SELECT stateCode, stateName, stateType FROM stateCodes ORDER BY stateType DESC, stateName";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
		$stateType=0;
		while($row = mysql_fetch_array($result)){?>
		<?
		if($stateType!=$row["stateType"])
		{
			?><option>========================</option>
			<?
		}
		$stateType=$row["stateType"];
		?>
		<option value="<?=$row["stateCode"]?>" <?=($row["stateCode"]==$bstateCode)?' selected="selected"':""?>><?=$row["stateName"]?></option>
		<? } ?>
	  </select></td>
		<td class="ccol1" width="20%">Country</td>
		<td class="ccol2" width="30%"><select name='bcountryCode' id="bcountryCode" class="inputField" style="width:200px;">
		<option value=''>Select Country</option>
		<option value='CA' <?=($bcountryCode=='CA')?' selected="selected"':""?>>CANADA</option>
		<option value='US' <?=($bcountryCode=='US')?' selected="selected"':""?>>UNITED STATES</option></select></td>
</tr>

<tr>
		<td colspan="4" height="25" bgcolor="#CCCCCC"><TABLE width="100%" cellspacing="0" cellpadding="0" class="arial_black11">
				<tr>
					<td class="text"><b>Shipping Address</b></td>
				</tr>
		</TABLE></td>

</tr>
<tr>
		<td class="ccol1" width="20%">First Name</td>
		<td class="ccol2" width="30%"><INPUT type='text' name='sFName' id="sFName" value='<?=$sFName?>' class="inputField" style="width:200px;"></td>
		<td class="ccol1" width="20%">Last Name</td>
		<td class="ccol2" width="30%"><INPUT type='text' name='sLName' id="sLName" value='<?=$sLName?>' class="inputField" style="width:200px;"></td>
</tr>


<tr>
		<td class="ccol1">Address</td>
		<td class="ccol2" colspan="3"><INPUT type='text' name='sAddress' id="sAddress" value='<?=$sAddress?>' class="inputField" style="width:200px;"></td>
		
</tr>

<tr>
		<td class="ccol1" width="20%">City</td>
		<td class="ccol2" width="30%"><INPUT type='text' name='sCity' id="sCity" value='<?=$sCity?>' class="inputField" style="width:200px;"></td>
		<td class="ccol1" width="20%">Postal Code / Zip</td>
		<td class="ccol2" width="30%"><INPUT type='text' name='szip' id="szip" value='<?=$szip?>' class="inputField" style="width:200px;"></td>
</tr>

<tr>
		<td width="20%" height="29" class="ccol1">Province / State</td>
		<td class="ccol2" width="30%"><select name='sstateCode' id="sstateCode" class="inputField" style="width:200px;">
		<option value=''>Select Province / State</option>
		<?
		$sSQL="SELECT stateCode, stateName, stateType FROM stateCodes ORDER BY stateType DESC, stateName";
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
		$stateType=0;
		while($row = mysql_fetch_array($result)){?>
		<?
		if($stateType!=$row["stateType"])
		{
			?><option>========================</option>
			<?
		}
		$stateType=$row["stateType"];
		?>
		<option value="<?=$row["stateCode"]?>" <?=($row["stateCode"]==$sstateCode)?' selected="selected"':""?>><?=$row["stateName"]?></option>
		<? } ?>
	  </select></td>
		<td class="ccol1" width="20%">Country</td>
		<td class="ccol2" width="30%"><select name='scountryCode' id="scountryCode" class="inputField" style="width:200px;">
		<option value=''>Select Country</option>
		<option value='CA' <?=($scountryCode=='CA')?' selected="selected"':""?>>CANADA</option>
		<option value='US' <?=($scountryCode=='US')?' selected="selected"':""?>>UNITED STATES</option></select></td>
</tr>

 


</TABLE>
              </TD>
            </TR>
			
			             <TR> 
              <TD colspan="2"><SPAN class="text">Comment</SPAN> 
                <TABLE border="1" cellpadding="5" cellspacing="0" width="97%" bordercolor="#FFFFFF" style="border: thin">
                  <TR> 
                    <TD><TABLE border="0" cellpadding="0" cellspacing="0" width="100%">
                              <TR> 
                                <TD width="100%"><TEXTAREA  id='comment' name='comment' style="height:150px; width:100%;"><?=$comment?></TEXTAREA></TD>
                              </TR>
                            </TABLE></TD>
                  </TR>
                </TABLE>
              </TD>
            </TR>
            <TR> 
              <TD width="50%"><SPAN class="text">Status</SPAN> <BR>
						<SELECT name='status' class="inputField" style="width:200px;">
						<? foreach($statusArr as $statusID => $statusName){ ?>
						<option value='<?=$statusID?>' <?=($status==$statusID)?' selected="selected"':""?>><?=$statusName?></option>
						<? } ?>
						</SELECT></TD>
              <TD width="50%"  ><SPAN class="text">Paid</SPAN> <BR>
						<input type="checkbox" name="paid" id="paid" value="1" <?=(!empty($paid))?"checked='checked'":""?> /> </TD>
            </TR>
   			<tr><td><input type="image" src="/admin/img/btn_save.png" />&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>

                </TABLE>
		  </td></tr>
				  </table></td>
                </tr>
              </table></td>
		  </tr>
		</table>
</FORM>
<script language="JavaScript1.2" type="text/JavaScript">
<!-- 
function form_product_submit() {
	document.forms["ff1"].submit();
}
function form_product_reset() {
	document.forms["ff1"].reset();
}
-->
</script>
<script type="text/javascript" language="javascript">
function deleteProduct()
{
	if (confirm('Detete this item(s) (Once it\'s done, there is no way back)?')) {
		document.location.href="http://cms.superiorwebsys.com/admin/storeCustomers/index.php?listAction=1&delItem=1&chkItem=<?=$storeCustomersID?>&searchFromSession=1";
	} else {
		return false;
	}
}
function printer()
{
	window.open("printOrder.php?storeOrderID=<?=$storeOrderID?>");
}
</script>
<?
include $_SERVER['DOCUMENT_ROOT']."/admin/inc/footer.php";
?>