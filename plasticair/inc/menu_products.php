<? 
$product_categoryID=empty($_REQUEST['product_categoryID'])?'':str_replace("'", "", $_REQUEST['product_categoryID']);
$n_parent_prod1=""; $n_parent_prod2=""; $n_parent_cat1="";
$services_categoryID=empty($services_categoryID)?'':$services_categoryID;

$productsID=empty($productsID)?'':str_replace("'", "", $productsID);

$sSQL="SELECT * FROM products ORDER BY product_categoryID, position ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
while($row1=mysql_fetch_array($result1)){
	if($productsID==$row1['productsID']){
		$product_categoryID=$row1['product_categoryID'];
		$arr_specifications[$row1['productsID']]=stripslashes($row1['specifications']);
	}
	$arr_products[$row1['product_categoryID']][$row1['productsID']]=$row1['title'];
	$arr_products_descr[$row1['productsID']]=stripslashes($row1['description']);
	$arr_products_title[$row1['productsID']]=stripslashes($row1['title']);
	$arr_headline[$row1['productsID']]=stripslashes($row1['headline']);
	$arr_price[$row1['productsID']]=stripslashes($row1['price']);}


$sSQL="SELECT * FROM product_category ORDER BY parentID, position ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
	
$arr_parents=array(); $arr_parentsID=array(); $arr_product_category=array();
$arr_parentsID[0]="";
while($row1=mysql_fetch_array($result1)){
	if($_SERVER['PHP_SELF']!="/index.php"){$product_categoryID=empty($product_categoryID)?$row1['product_categoryID']:$product_categoryID;}
	$arr_parents[$row1['parentID']][$row1['product_categoryID']]=$row1['title'];
	$arr_parentsID[$row1['product_categoryID']]=$row1['parentID'];
	
	if($row1['product_categoryID']==$product_categoryID && $arr_parentsID[$row1['parentID']]==0){
		$n_parent_prod1=$row1['parentID'];
		}
	elseif($row1['product_categoryID']==$product_categoryID && $arr_parentsID[$row1['parentID']]!=0) 
		{$n_parent_prod2=$row1['parentID']; 
		$n_parent_prod1=$arr_parentsID[$row1['parentID']];
		}
	$arr_product_category[$row1['product_categoryID']]=$row1['title'];
	$arr_prod_cat_descr[$row1['product_categoryID']]=$row1['description']; }
	
	
?><table width="240" border="0" cellspacing="0" cellpadding="0">
<tr><td width="15"><img src="/img/green-tittle-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td width="210" class="green_bg" align="left"><a href="/products.php" class="arial_16_white_bold">Products</a></td><td width="15"><img src="/img/green-tittle-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
<tr><td colspan="3" height="10"></td></tr><?
$n_zindex=100;
foreach($arr_parents[0] as $k=>$v){
		if(!empty($arr_products[$k])){ 
			if($k==$product_categoryID || $n_parent_prod1==$k){
				?><tr><td></td><td align="left"><a href="<?=seo_link("products", $k, $v)?>" class="<?=$product_categoryID==$k?'sidebar_link_hover':'sidebar_link'?>"><?=$v?></a></td><td></td></tr>
					<tr align="left"><td></td><td style="padding-top:10px; padding-bottom:10px;"><div class="pix_1" style="width:210px;"></div></td><td></td></tr><?
				foreach($arr_products[$k] as $kk=>$vv){
					?><tr align="left"><td></td><td><div style="position:relative; z-index:<?=$n_zindex?>; margin:0 0 7px 0;">
				<table border="0" cellspacing="0" cellpadding="0"><tr><td valign="middle"><img src="/img/bullet-5.png" align="left" border="0" style="margin:0 5px 0 0;" alt=""></td>
					<td><a href="<?=seo_link("product", $kk, $vv)?>" class="sidebar_sub_link"><?=$vv?></a></td></tr></table></div>
					</td></tr><?
				}
			}
			else{
				?><tr align="left"><td></td>
				<td onmouseover="showMenu('1', '_<?=$k?>__');" onmouseout="showMenu('0', '_<?=$k?>__');"><div style="position:relative; z-index:<?=$n_zindex?>"><a href="<?=seo_link("products", $k, $v)?>" class="<?=$product_categoryID==$k?'sidebar_link_hover':'sidebar_link'?>"><?=$v?></a>
				<div style="position:absolute; display:none; background-color:#f0f0f0; top:0px; left:130px;" id="menuA_<?=$k?>__">
				<table border="0" cellspacing="3" cellpadding="3" style="margin:3px; border:1px solid #CCC;"><?
                    
			foreach($arr_products[$k] as $kk=>$vv){
				?><tr align="left"><td style=" padding:0 10px;" nowrap="nowrap">&bull;&nbsp;<a href="<?=seo_link("product", $kk, $vv)?>" class="arial_12_grey"><?=$vv?></a></td></tr><?
				}?></table></div></div></td><td></td></tr>
			<tr><td></td><td style="padding-top:10px; padding-bottom:10px;"><div class="pix_1" style="width:210px;"></div></td><td></td></tr><?
			}
		}
		else{
		?><tr><td></td><td align="left"><a href="<?=seo_link("products", $k, $v)?>" class="<?=$product_categoryID==$k?'sidebar_link_hover':'sidebar_link'?>"><?=$v?></a></td><td></td></tr>
			<tr><td></td><td style="padding-top:10px; padding-bottom:10px;"><div class="pix_1" style="width:210px;"></div></td><td></td></tr><? }
	$n_zindex--;}
$not_swow_prod_btn="1";
?>