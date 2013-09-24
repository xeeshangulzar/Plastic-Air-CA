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

if($s_country=="CA" || $s_country=="US") {
$sSQL="SELECT DISTINCT s.stateCode, s.stateName FROM stateCodes s JOIN representative r USING(stateCode) WHERE s.countryCode='".$s_country."' ORDER BY s.stateType DESC, s.stateName ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);

//echo $sSQL;
?><select name="stateCode" id="stateCode" class="drop_down"><option value="">Select Province/State</option><?
while($row1=mysql_fetch_assoc($result1)){
		?><option value="<?=$row1['stateCode']?>" <?=$stateCode==$row1['stateCode']?"selected='selected'":""?> ><?=$row1['stateName']?></option><?
		}?></select><? } 
elseif(!empty($s_country)){ /*?><input name="stateCode" value="<?=$stateCode?>" class="drop_down" /><?*/ }
else{ ?><? }

?>