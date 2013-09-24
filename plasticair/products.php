<? 
$page_name="Products";
$show_light_box="1";
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";?>
<table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="4"><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold">Products</td></tr>
<tr><td colspan="2" height="40"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top"><div style="position:relative;"><div style="position:absolute; white-space: nowrap;"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/products.php" class="subnav_link_active">Products</a><?
if(!empty($arr_product_category[$product_categoryID])){ ?><span class="subnav_divider">/</span><a href="<?=seo_link("products", $product_categoryID, $arr_product_category[$product_categoryID])?>" class="subnav_link_active"><?=$arr_product_category[$product_categoryID]?></a><? }
?></div></div></td></tr>
<tr><td>&nbsp;</td>
<td>&nbsp;</td></tr>
</table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table></td></tr>
<tr><td colspan="4" height="13"></td></tr>
<tr><td width="13"><img src="/img/product-tittle-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td width="400" align="left" style="background:url(/img/product-tittle-bg.jpg); background-repeat:repeat-x;" class="arial_14_grey_bold"><?
if(!empty($arr_product_category[$product_categoryID])){ ?><?=$arr_product_category[$product_categoryID]?><? }
?></td>
<td width="249" align="right" style="background:url(/img/product-tittle-bg.jpg); background-repeat:repeat-x;"></td>
<td width="13"><img src="/img/product-tittle-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
<tr><td colspan="4" align="center" style="padding-top:20px;"><? 
//arr_products_descr
if(!empty($arr_products[$product_categoryID])){
	foreach($arr_products[$product_categoryID] as $k=>$v){
		?><div style=" background-color:#FFFFFF; border:#DCDCDC solid 1px; width:635px; margin-bottom:20px; padding:10px;">
<table width="620" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="3" align="left"><a href="<?=seo_link("product", $k, $v)?>" class="arial_14_green_bold"><?=$v?></a></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><?
	$sSQL="SELECT * FROM site_images WHERE tableName='products' AND tableIDNum='".$k."' ORDER BY main_img DESC";
	$result2=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
    if($row2=mysql_fetch_assoc($result2)){ 
		list($ww, $hh)=@getimagesize($_SERVER['DOCUMENT_ROOT']."/".$row2['filePath']);
		?><td width="118"><div style="border:#DCDCDC solid 1px; position:relative;">
		<div style="position:absolute; bottom:0px; left:100px;"><img src="/img/btn-enlarge.png" onclick="displayHideBox('1', '<?=$row2['filePath']?>', <?=$hh?>);" border="0" alt="" style="cursor:pointer;"></div>	
		<img src="<?=$row2['filePath_s']?>" alt="" hspace="0" onclick="displayHideBox('1', '<?=$row2['filePath']?>', <?=$hh?>);" style="cursor:pointer;"></div></td><td width="18"></td><? }
?><td align="left"><?=stripslashes($arr_headline[$k])?><br><br>
<div style="text-align:right;"><a href="<?=seo_link("product", $k, $v)?>"><img src="/img/btn-view-detail.jpg" hspace="0" vspace="0" border="0" alt=""></a></div></td></tr></table></div><?		
		}	
	}
/*for ($i = 0; $i <= 4; $i++) {    ?>
<div style=" background-color:#FFFFFF; border:#DCDCDC solid 1px; width:635px; margin-bottom:20px; padding-left:10px;">
<table width="620" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="3" height="10"></td></tr>
<tr><td colspan="3" align="left"><a href="#" class="arial_14_green_bold">BCMPA Series (Low to Medium Volume FRP Fans)</a></td></tr>
<tr><td colspan="3" height="10"></td></tr>
<tr><td width="118"><div style="border:#DCDCDC solid 1px; position:relative;">
<div  style="position:absolute; top:75px; left:100px;"><a href="#"><img src="/img/btn-enlarge.png" hspace="0" vspace="0" border="0" alt=""></a></div>
<img src="/img/product-1.jpg" hspace="0" vspace="0" border="0" alt=""></div></td>
<td width="18"></td>
<td align="left">Aenean viverra est vel ante feugiat eu elementum metus consequat. Suspendisse placerat iaculis porta. Pellentesque sagittis egestas lectus, porta porta odio pretium nec.<br>
<br>
<a href="#"><img src="/img/btn-view-detail.jpg" hspace="0" vspace="0" border="0" alt=""></a></td></tr>
<tr><td colspan="3" height="10"></td></tr>
</table>
</div>
<?php } */  
?></td></tr></table>
<? include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php"; ?>