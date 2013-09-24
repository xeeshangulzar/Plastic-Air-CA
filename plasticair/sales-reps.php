<? include $_SERVER['DOCUMENT_ROOT']."/inc/header.php"; ?>
<table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="4"><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold">Sales Reprs</td></tr>
<tr><td colspan="2" height="40"></td></tr>
<tr><td width="25"></td>
<td align="left"><a href="#" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="#" class="subnav_link_active">Sales Reprs</a></td></tr>
<tr><td>&nbsp;</td>
<td>&nbsp;</td></tr>
</table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table></td></tr>
<tr><td colspan="4" height="13"></td></tr>
<tr><td colspan="4"><? include $_SERVER['DOCUMENT_ROOT']."/inc/reps.php"; ?></td></tr>
<tr><td colspan="4" height="30"></td></tr><?

?><tr><td width="13"><img src="/img/product-tittle-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td width="649" align="left" style="background:url(/img/product-tittle-bg.jpg); background-repeat:repeat-x;" class="arial_14_grey_bold">Representors in <?= empty($s_countryName)?'':$s_countryName?> <?=empty($s_stateName)?'':("/ ".$s_stateName)?></td>
<td width="13"><img src="/img/product-tittle-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
<tr><td colspan="4" height="20"></td></tr>
<tr><td colspan="4" align="left"><table width="660" border="0" cellspacing="0" cellpadding="0">
<?php /*?><tr><td width="20" align="left"><img src="/img/bullet-6.png" hspace="0" vspace="0" border="0" alt=""></td>
<td width="640" align="left" class="arial_13_green_bold">HTS ENGINEERING </td></tr>
<tr><td width="20" align="left"></td>
<td width="640" align="left" style="border-bottom:#CCCCCC solid 1px;"></td></tr>
<tr><td colspan="2" height="10"></td></tr><?php */?>
<tr><td width="20" align="left"></td>
<td width="640" align="left"><table border="0" cellspacing="0" cellpadding="0"><tr><? 


$sSQL="SELECT * FROM representative WHERE countryCode='".$s_countryCode."' ";
if(!empty($s_stateCode)) $sSQL.=" AND stateCode='$s_stateCode'";
$sSQL.=" ORDER BY position";

$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
$nn=0;
while($row1=mysql_fetch_assoc($result1)){$stateCode=$row1['stateCode'];
	$nn++;
	?><td width="200" style="padding:10px 13px 20px 0;" valign="top"><b><?=$row1['title']?></b><br />
	<?=nl2br($row1['address'])?><br />
	<?=nl2br($row1['phones'])?><br />
	<?=empty($row1['emails'])?'':('Email: '.$row1['emails'])?>
	</td><?
	if($nn>=3){ ?></tr><tr><? $nn=0;}
	}

?></tr></table></td></tr><?

for ($i=0; $i <= 3; $i++ ) {/* ?>


<tr><td width="20" align="left"></td>
<td width="640" align="left">
<div style="width:220px; float:left;">

<table width="200" border="0" cellspacing="0" cellpadding="0" style="line-height:15px;">
<tr><td align="left"><strong>HTS Engineering 
Toronto - Head Office</strong><br>
115 Norfinch Drive<br>
Toronto, ON M3N 1W8 <br>
Telephone: 416-661-3400<br>
Fax: 416-661-0100<br>
Toll Free: 1-800-850-0567</td></tr>
</table>

</div>

<div style="width:220px; float:left;">
<table width="200" border="0" cellspacing="0" cellpadding="0" style="line-height:15px;">
<tr><td align="left"><strong>HTS Engineering 
Toronto - Head Office</strong><br>
115 Norfinch Drive<br>
Toronto, ON M3N 1W8 <br>
Telephone: 416-661-3400<br>
Fax: 416-661-0100<br>
Toll Free: 1-800-850-0567</td></tr>
</table>
</div>


<div style="width:200px; float:left;">
<table width="200" border="0" cellspacing="0" cellpadding="0" style="line-height:15px;">
<tr><td align="left"><strong>HTS Engineering 
Toronto - Head Office</strong><br>
115 Norfinch Drive<br>
Toronto, ON M3N 1W8 <br>
Telephone: 416-661-3400<br>
Fax: 416-661-0100<br>
Toll Free: 1-800-850-0567</td></tr>
</table>
</div>

</td></tr>

<tr><td colspan="2" height="30"></td></tr><? */} 
?></table></td></tr></table>
<? include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php"; ?>