<? 
$page_name="Industries";
$show_light_box="1";
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";?>
<table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="4"><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold">Industries</td></tr>
<tr><td colspan="2" height="40"></td></tr>
<tr><td width="25"></td>
<td align="left"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/industries.php" class="subnav_link_active">Industries</a></td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr></table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table></td></tr>
<tr><td colspan="4" height="13"></td></tr>
<tr><td width="13"><img src="/img/product-tittle-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td width="400" align="left" style="background:url(/img/product-tittle-bg.jpg); background-repeat:repeat-x;" class="arial_14_grey_bold">Industries</td>
<td width="249" align="right" style="background:url(/img/product-tittle-bg.jpg); background-repeat:repeat-x;"></td>
<td width="13"><img src="/img/product-tittle-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
<tr><td colspan="4" align="center" style="padding-top:20px;"><?
$sSQL="SELECT * FROM industry ORDER BY position";
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
while($row=mysql_fetch_array($result)) { 



?><div style=" background-color:#FFFFFF; border:#DCDCDC solid 1px; width:635px; margin-bottom:20px; padding-left:10px;">
<table width="620" border="0" cellspacing="0" cellpadding="0" style="margin:7px 0;">
<tr><td colspan="3" align="left"><a href="<?=seo_link("industry", $row['industryID'], $row['title'])?>" class="arial_14_green_bold"><?=$row['title']?></a></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr valign="top"><?
	$sSQL="SELECT * FROM site_images WHERE tableName='industry' AND tableIDNum='".$row['industryID']."' ORDER BY main_img DESC";
	$result2=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
    if($row2=mysql_fetch_assoc($result2)){ 
		list($ww, $hh)=@getimagesize($_SERVER['DOCUMENT_ROOT']."/".$row2['filePath_s']);		
		if($ww>100){$hh=round($hh*100/$ww);$ww=100; }
		if($hh>80){$ww=round($ww*80/$hh);$hh=80; }	
		?><td width="118"><div style="position:relative;">
		<?php /*?><div style="position:absolute; bottom:0px; left:100px;"><img src="/img/btn-enlarge.png" onclick="displayHideBox('1', '<?=$row2['filePath']?>', <?=$hh?>);" style="cursor:pointer;" border="0" alt=""></div><?php */?>	
		<a href="<?=seo_link("industry", $row['industryID'], $row['title'])?>"><img src="<?=$row2['filePath_s']?>" height="<?=$hh?>" alt="" border="0" <?php /*?>onclick="displayHideBox('1', '<?=$row2['filePath']?>', <?=$hh?>);"<?php */?> style="cursor:pointer;"></a></div></td><td width="18"></td><? }
?>
<td align="left"><?=nl2br($row['headline'])?><br>
<div style=" text-align:right;"><a href="<?=seo_link("industry", $row['industryID'], $row['title'])?>"><img src="/img/btn-view-detail.jpg" hspace="0" vspace="0" border="0" alt=""></a></div></td></tr></table></div><? }
?></td></tr></table><? 
include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php"; ?>