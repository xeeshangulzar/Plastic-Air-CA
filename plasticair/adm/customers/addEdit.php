<?
include $_SERVER['DOCUMENT_ROOT']."/admin/inc/header.php";
if(!empty($_SESSION['adminlevel']) && !stristr($adminLevelstring[$_SESSION['adminlevel']],"customersEdit=1"))
{
	header("Location: /admin/home.php");
	exit();
}
$tableName="storeCustomers";
$tableID="storeCustomersID";
$folderName="customers";
$pageTitle="Customers";

$$tableID=(!empty($_REQUEST[$tableID]))?str_replace("'","",$_REQUEST[$tableID]):"0";
$storeCustomersID=(!empty($_REQUEST["storeCustomersID"]))?addslashes($_REQUEST["storeCustomersID"]):"";
$customerCompany=(!empty($_REQUEST["customerCompany"]))?str_replace("'","`",$_REQUEST["customerCompany"]):"";
$name=(!empty($_REQUEST["name"]))?str_replace("'","`",$_REQUEST["name"]):"";
$lastName=(!empty($_REQUEST["lastName"]))?str_replace("'","`",$_REQUEST["lastName"]):"";
$email=(!empty($_REQUEST["email"]))?str_replace("'","`",$_REQUEST["email"]):"";
$password=(!empty($_REQUEST["password"]))?str_replace("'","`",$_REQUEST["password"]):"";
$phone=(!empty($_REQUEST["phone"]))?str_replace("'","`",$_REQUEST["phone"]):"";
$fax=(!empty($_REQUEST["fax"]))?str_replace("'","`",$_REQUEST["fax"]):"";
$address=(!empty($_REQUEST["address"]))?str_replace("'","`",$_REQUEST["address"]):"";
$city=(!empty($_REQUEST["city"]))?str_replace("'","`",$_REQUEST["city"]):"";
$zip=(!empty($_REQUEST["zip"]))?str_replace("'","`",$_REQUEST["zip"]):"";
$stateCode=(!empty($_REQUEST["stateCode"]))?str_replace("'","`",$_REQUEST["stateCode"]):"";
$countryCode=(!empty($_REQUEST["countryCode"]))?str_replace("'","`",$_REQUEST["countryCode"]):"";
$minOrder=(!empty($_REQUEST["minOrder"]))?str_replace("'","`",$_REQUEST["minOrder"]):"";
$level=(!empty($_REQUEST["level"]))?str_replace("'","`",$_REQUEST["level"]):"0";
$subscribe=(!empty($_REQUEST["subscribe"]))?"1":"0";
$approved=(!empty($_REQUEST["approved"]))?"1":"0";
$active=(!empty($_REQUEST["active"]))?"1":"0";

$storeCustomersID=(!empty($_REQUEST["storeCustomersID"]))?str_replace("'","",$_REQUEST["storeCustomersID"]):"0";
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
		if($storeCustomersID=="0")
		{
			$sSQL="INSERT INTO storeCustomers(created, storeID)
								VALUES(NOW(), '".$_SESSION['storeID']."')";
			mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
			$storeCustomersID=mysql_insert_id();
		}
		


		$sSQL="UPDATE storeCustomers SET ".$sSQLAproveDate."
						customerCompany='".$customerCompany."',
						name='".$name."',
						lastName='".$lastName."',
						email='".$email."',
						password='".$password."',
						phone='".$phone."',
						fax='".$fax."',
						address='".$address."',
						city='".$city."',
						zip='".$zip."',
						stateCode='".$stateCode."',
						countryCode='".$countryCode."',
						minOrder='".$minOrder."',
						level='".$level."',
						subscribe='".$subscribe."',
						approved='".$approved."',
						active='".$active."'
				WHERE storeID = '".$_SESSION['storeID']."' AND storeCustomersID = '".$storeCustomersID."'";
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		$mess="Information Saved.";
	}
	else
	{
		$err="Please Enter: ".$err;
	}
	//Display HTML properly
	
	
}
$sSQL="SELECT categoriesTree 
		FROM store WHERE storeID ='".$_SESSION['storeID']."'";
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
if($row = mysql_fetch_array($result))
{ 
	$categoriesTree=explode(",",$row["categoriesTree"]);
}
$sSQL="SELECT *
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
<table border="0" cellspacing="0" cellpadding="0" class="text" width="100%">
				  <tr>
					<td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pageTabs">
                    <tr>
                      <td width="2" background="/admin/img/tabs_bottom_bg_left.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
                      <td width="140" class="arial20_Grey" align="center"><?=$pageTitle?> <img src="/admin/img/arrowGreyRightBig.jpg" width="5" height="9" align="absmiddle" /></td>
                      <td align="left"><table border="0" cellspacing="0" cellpadding="0" height="35">
                        <tr>
                          <td width="1" background="/admin/img/tabs_bg_right.jpg"></td>
                          <td background="/admin/img/tabs_bg.jpg"><a href="/admin/<?=$folderName?>/" class="tabLink">View All</a></td>
                          <td width="1" background="/admin/img/tabs_bg_right.jpg"></td>
                          <td width="1"></td>
                          <td width="2" background="/admin/img/tabs_active_bg_left.jpg"></td>
                          <td background="/admin/img/tabs_active_bg.jpg"><a href="/admin/<?=$folderName?>/addEdit.php" class="tabLink"><b>Add / Edit </b></a></td>
                          <td width="2" background="/admin/img/tabs_active_bg_right.jpg"></td>
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
					<tr>
                      <td align="right">Institution: </td>
					  <td align="left"><input type='text' name='customerCompany' value='<?=$customerCompany?>' class="inputField" style="width:200px;" /></td>
				    </tr>
					<tr>
                      <td align="right">Institution type: </td>
					  <td align="left"><input type='text' name='customerCompanyType' value='<?=$customerCompanyType?>' class="inputField" style="width:200px;" /></td>
				    </tr>
                    
                    
					<tr>
                      <td align="right">First Name: </td>
					  <td align="left"><input type='text' name='name' value='<?=$name?>' class="inputField" style="width:200px;" /></td>
				    </tr>
					<tr>
                      <td align="right">Last Name: </td>
					  <td align="left"><input type='text' name='lastName' value='<?=$lastName?>' class="inputField" style="width:200px;" /></td>
				    </tr>
					<tr>
                      <td align="right">Email: </td>
					  <td align="left"><input type='text' name='email' value='<?=$email?>' class="inputField" style="width:200px;" /></td>
				    </tr>
					<tr>
                      <td align="right">Password: </td>
					  <td align="left"><input type='text' name='password' value='<?=$password?>' class="inputField" style="width:200px;" /></td>
				    </tr>
					<tr>
                      <td align="right">Business Phone: </td>
					  <td align="left"><input type='text' name='phone' value='<?=$phone?>' class="inputField" style="width:200px;" /></td>
				    </tr>
					<tr>
                      <td align="right">Fax: </td>
					  <td align="left"><input type='text' name='fax' value='<?=$fax?>' class="inputField" style="width:200px;" /></td>
				    </tr>
					<tr>
                      <td align="right">Store Address: </td>
					  <td align="left"><input type='text' name='address' value='<?=$address?>' class="inputField" style="width:200px;" /></td>
				    </tr>
					<tr>
                      <td align="right">City: </td>
					  <td align="left"><input type='text' name='city' value='<?=$city?>' class="inputField" style="width:200px;" /></td>
				    </tr>
					<tr>
                      <td align="right">Zip: </td>
					  <td align="left"><input type='text' name='zip' value='<?=$zip?>' class="inputField" style="width:200px;" /></td>
				    </tr>
					<tr>
                      <td align="right">Province / State: </td>
					  <td align="left"><select name='stateCode' id="stateCode" class="inputField" style="width:200px;">
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
											<option value="<?=$row["stateCode"]?>" <?=($row["stateCode"]==$stateCode)?' selected="selected"':""?>>
											<?=$row["stateName"]?></option>
											<? } ?>
									  </select>
					  </td>
				    </tr>	
					<tr>
                      <td align="right">Country: </td>
					  <td align="left"><select name='countryCode' id="countryCode" class="inputField" style="width:200px;">
										<option value=''>Select Country</option>
										<option value='CA' <?=($countryCode=='CA')?' selected="selected"':""?>>CANADA</option>
										<option value='US' <?=($countryCode=='US')?' selected="selected"':""?>>UNITED STATES</option>
										</select>
					  </td>
				    </tr>
					
					<tr>
                      <td align="right">Subscribe Status: </td>
					  <td align="left"><input type='checkbox' id='subscribe' name='subscribe' value='1' <?=($subscribe=="1")?'checked="checked"':""?> /></td>
				    </tr>
					<tr>
                      <td align="right">Approved: </td>
					  <td align="left"><input type='checkbox' id='approved' name='approved' value='1' <?=($approved=="1")?'checked="checked"':""?> /></td>
				    </tr>
					<tr>
                      <td align="right">Active: </td>
					  <td align="left"><input type='checkbox' id='active' name='active' value='1' <?=($active=="1")?'checked="checked"':""?> /></td>
				    </tr>	
					<tr>
					  <td height="40" align="right" valign="middle"></td>
					  <td align="left" valign="middle"><input type="image" src="/admin/img/btn_save.png" /></td>
					</tr>
				  </table></td>
                </tr>
              </table><input type="hidden" name="<?=$tableID?>" id="<?=$tableID?>" value="<?=$$tableID?>" />
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
</script>
<?
include $_SERVER['DOCUMENT_ROOT']."/admin/inc/footer.php";
?>