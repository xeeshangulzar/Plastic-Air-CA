<table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="225" height="225" valign="top" style=" background:url(/img/home-map-left.jpg) no-repeat;" align="right"><table width="225" border="0" cellspacing="0" cellpadding="0" >
<tr><td align="right" style="padding-top:15px;" class="arial_20_white_bold">Find Representative</td></tr>
<tr><td height="35"></td></tr>
<form action="/sales-reps.php" method="post">
<tr><td align="right"><select name="countryCode" class="drop_down" onchange="fill_province(this)">
<option value="">Select Country</option><?
$s_countryCode=empty($_REQUEST['countryCode'])?'':str_replace("'", "", $_REQUEST['countryCode']);
$s_stateCode=empty($_REQUEST['stateCode'])?'':str_replace("'", "", $_REQUEST['stateCode']);
$s_countryName=""; $s_stateName="";

$sSQL="SELECT DISTINCT c.countryCode, c.countryName FROM countryCodes c JOIN representative r USING(countryCode) ORDER BY countryType, countryName ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
while($row1=mysql_fetch_array($result1)){
	if($s_countryCode==$row1['countryCode'])$s_countryName=$row1['countryName'];
	?><option value="<?=$row1['countryCode']?>"<?=$s_countryCode==$row1['countryCode']?' selected="selected"':'' ?>><?=$row1['countryName']?></option><? }


?></select></td></tr>
<tr><td height="22"></td></tr>
<tr><td align="right"><div id="div_stateCode"><?
if($s_countryCode=="CA" || $s_countryCode=="US") {
	?><select name="stateCode" id="stateCode" class="drop_down"><option value="">Select Province/State</option><?
	$sSQL="SELECT DISTINCT s.stateCode, s.stateName FROM stateCodes s JOIN representative r USING(stateCode) WHERE s.countryCode='".$s_countryCode."' ORDER BY s.stateType DESC, s.stateName ";
	$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
	while($row1=mysql_fetch_assoc($result1)){
	if($s_stateCode==$row1['stateCode'])$s_stateName=$row1['stateName'];
		?><option value="<?=$row1['stateCode']?>" <?=$s_stateCode==$row1['stateCode']?"selected='selected'":""?> ><?=$row1['stateName']?></option><?
		}?></select><? } 
else{ ?><? }?></div></td></tr>
<tr><td height="20"></td></tr>
<tr><td align="right"><input type="image" src="/img/btn-view.jpg" /></td></tr></form>
</table></td>
<td width="450" valign="top" style="background:url(/img/map-bg.jpg) repeat-x;"><table width="450" border="0" cellspacing="0" cellpadding="0">
<tr><td align="center" style="padding-top:20px;"><a href="#"><img src="/img/map.png" hspace="0" vspace="0" border="0" alt=""></a></td></tr>
<tr><td align="right" style="padding-top:10px;"><table border="0" cellspacing="0" cellpadding="0">
<tr><td class="arial_12_white_bold">Click on Map to find Representative in your region.</td>
<td style="padding-left:5px; padding-right:5px;"><img src="/img/bullet-2.png" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table></td></tr>
</table></td></tr></table>
<script language="javascript" type="text/javascript">
function fill_province(o_obj){ 
	makeRequest("/inc/get_province.php?countryCode="+o_obj.value+"&ttt="+getJsTime(), fill_province1, o_obj);
	}
function fill_province1(content, arg){ 
	document.getElementById("div_stateCode").innerHTML=content;
}
</script>