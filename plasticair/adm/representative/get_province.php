<?
include_once $_SERVER['DOCUMENT_ROOT']."/inc/dbconnect.php";
include_once $_SERVER['DOCUMENT_ROOT']."/inc/functions.php";
$s_country=empty($_REQUEST['countryCode'])?'':str_replace("'", "", $_REQUEST['countryCode']);
$s_representativeID=empty($_REQUEST['representativeID'])?'':str_replace("'", "", $_REQUEST['representativeID']);
$stateCode="";
if(!empty($s_representativeID)){
	$sSQL="SELECT * FROM representative WHERE representativeID='".$s_representativeID."'";
	$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
	if($row1=mysql_fetch_assoc($result1)){$stateCode=$row1['stateCode'];}
	
	}

if($s_country=="CA" || $s_country=="US") {?><select name="stateCode" id="stateCode" class="inputField" style="width:200px;"><option value="">Select Province/State</option><?
$sSQL="SELECT stateCode, stateName FROM stateCodes WHERE countryCode='".$s_country."' ORDER BY stateType DESC, stateName ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
while($row1=mysql_fetch_assoc($result1)){
		?><option value="<?=$row1['stateCode']?>" <?=$stateCode==$row1['stateCode']?"selected='selected'":""?> ><?=$row1['stateName']?></option><?
		}?></select><? } 
elseif(!empty($s_country)){ ?><input name="stateCode" value="<?=$stateCode?>" class="inputField" style="width:200px;" /><? }
else{ ?><? }


?>