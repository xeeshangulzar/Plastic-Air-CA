<? 
$page_name="Industries";
$industryID=empty($_REQUEST['industryID'])?'':str_replace("'", "", $_REQUEST['industryID']);
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
<td align="left"><div style="position:relative;"><div style="position:absolute; white-space: nowrap;"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/industries.php" class="subnav_link_active">Industries</a><?
if(!empty($arr_industry_title[$industryID])){ ?><span class="subnav_divider">/</span><a href="<?=seo_link("industry", $industryID, $arr_industry_title[$industryID])?>" class="subnav_link_active"><?=$arr_industry_title[$industryID]?></a><? }
?></div></div></td></tr>
<tr><td>&nbsp;</td>
<td>&nbsp;</td></tr>
</table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table></td></tr>
<tr><td colspan="4" height="13"></td></tr>
<tr><td width="13"><img src="/img/product-tittle-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td width="400" align="left" style="background:url(/img/product-tittle-bg.jpg); background-repeat:repeat-x;" class="arial_14_grey_bold">Industries</td>
<td width="249" align="right" style="background:url(/img/product-tittle-bg.jpg); background-repeat:repeat-x;"><a href="/industries.php" class="arial_12_green_bold" style="text-decoration:underline;">Back to Industries</a></td>
<td width="13"><img src="/img/product-tittle-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
<tr><td colspan="4" align="center"><table width="650" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="3" height="20"></td></tr>
<tr><td width="12"><img src="/img/subtittle-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td width="626" style="background:url(/img/subtittle-bg.jpg); background-repeat:repeat-x;" align="left" class="arial_14_green_bold"><?
if(!empty($arr_industry_title[$industryID])){ ?><?=$arr_industry_title[$industryID]?><? }
?></td>
<td width="12"><img src="/img/subtittle-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr></table>
<table width="650" border="0" cellspacing="0" cellpadding="0"><tr><td colspan="3" height="15"></td></tr>
<tr><td colspan="3" align="left"><?
	$sSQL="SELECT * FROM site_images WHERE tableName='industry' AND tableIDNum='".$industryID."' ORDER BY main_img DESC";
	$result2=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
    if($row2=mysql_fetch_assoc($result2)){ 
		list($ww, $hh)=@getimagesize($_SERVER['DOCUMENT_ROOT']."/".$row2['filePath']);
		?><div style="border:#DCDCDC solid 1px; position:relative; float:left; margin:0 8px 0 0;">
		<div style="position:absolute; bottom:0px; right:0px;"><img src="/img/btn-enlarge.png" onclick="displayHideBox('1', '<?=$row2['filePath']?>', <?=$hh?>);" style="cursor:pointer;" border="0" alt=""></div>	
		<img src="<?=$row2['filePath_s']?>" alt="" hspace="0" onclick="displayHideBox('1', '<?=$row2['filePath']?>', <?=$hh?>);" style="cursor:pointer;"></div><? }
?><?=$arr_industry_descr[$industryID]?><?

$sSQL="SELECT i.title, i.productsID FROM products i JOIN products_industry p ON (p.productsID=i.productsID AND p.industryID='".$industryID."') ORDER BY i.title";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
if(mysql_num_rows($result1)>0){
?></td></tr>
<tr><td colspan="3" align="left"><div align="left" style="clear:both;margin:15px 0;"><b>Best designed products for this Industry:</b></div>
<table border="0" cellspacing="2" cellpadding="2" class="text"><?
while($row1=mysql_fetch_assoc($result1)) {
	?><tr><td valign="middle"><img src="/img/bullet-5.png" align="left" border="0" style="margin:0 5px 0 0;" alt=""></td>
	<td><a href="<?=seo_link("product", $row1['productsID'], $row1['title'])?>" class="sidebar_sub_link"><?=$row1['title']?></a></td></tr><?
	}
?></table><? }

?></td></tr>
<tr><td colspan="3" height="20"></td></tr>
<tr><td colspan="3"><?

?><table width="650" border="0" cellspacing="0" cellpadding="0">
<tr><td width="445"></td>
<td width="40"></td>
<td width="165" valign="top"></td></tr>
</table></td></tr>
</table></td></tr></table>
<? include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php"; ?>