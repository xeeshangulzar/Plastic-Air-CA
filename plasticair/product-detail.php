<? 
$page_name="Products";
$productsID=empty($_REQUEST['productsID'])?'':str_replace("'", "", $_REQUEST['productsID']);
$show_light_box="1";
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";?>
<table width="675" border="0" cellspacing="0" cellpadding="0"><tr><td colspan="4"><table width="675" border="0" cellspacing="0" cellpadding="0"><tr><td width="240" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td width="25"></td>
<td align="left"><div style="position:relative;"><div style="position:absolute; white-space: nowrap;"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/products.php" class="subnav_link_active">Products</a><?
if(!empty($arr_product_category[$product_categoryID])){ ?><span class="subnav_divider">/</span><a href="<?=seo_link("products", $product_categoryID, $arr_product_category[$product_categoryID])?>" class="subnav_link_active"><?=$arr_product_category[$product_categoryID]?></a><? }
?></div></div></td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr></table></td><td width="435" valign="top"></td></tr></table></td></tr>


<tr><td colspan="4" height="13"></td></tr>
<tr><td width="13"><img src="/img/product-tittle-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td width="400" align="left" style="background:url(/img/product-tittle-bg.jpg); background-repeat:repeat-x;" class="arial_14_grey_bold"><?
if(!empty($arr_product_category[$product_categoryID])){ ?><?=$arr_product_category[$product_categoryID]?><? }
?></td>
<td width="249" align="right" style="background:url(/img/product-tittle-bg.jpg); background-repeat:repeat-x;"><a href="<?=seo_link("products", (empty($product_categoryID)?'':$product_categoryID), (empty($arr_product_category[$product_categoryID])?'':$arr_product_category[$product_categoryID]))?>" class="arial_12_green_bold" style="text-decoration:underline;">Back to Products</a></td>
<td width="13"><img src="/img/product-tittle-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
<tr><td colspan="4" align="center"><table width="650" border="0" cellspacing="0" cellpadding="0"><tr><td colspan="3" height="20"></td></tr>
<tr><td width="12"><img src="/img/subtittle-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td width="626" style="background:url(/img/subtittle-bg.jpg); background-repeat:repeat-x;" align="left" class="arial_14_green_bold"><?
if(!empty($arr_products_title[$productsID])){ ?><?=$arr_products_title[$productsID]?><? }
?></td>
<td width="12"><img src="/img/subtittle-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
<tr><td colspan="3" height="15"></td></tr>
<tr><td colspan="3">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="240px" height="180px"><?
$s_first_img=""; $s_first_pict_title="";
$sSQL="SELECT * FROM site_images WHERE tableName='products' AND tableIDNum='".$productsID."' ORDER BY main_img DESC LIMIT 0, 1";
	$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
	if($row1=mysql_fetch_array($result1)){ 
	if(!empty($row1['filePath_m']))$s_first_img=$row1['filePath_m'];
	else $s_first_img=$row1['filePath'];
	
		list($ww, $hh)=@getimagesize($_SERVER['DOCUMENT_ROOT'].$s_first_img);
		
		if($ww>230){$hh=round($hh*230/$ww);$ww=230; }
		if($hh>180){$ww=round($ww*180/$hh);$hh=180; }//width="<? =$ww? >"
		?><center><img src="<?=$s_first_img ?>"  height="<?=$hh?>" id="div_img_main" onclick="show_big()" /></center><? 
	 }
?></td>
<td><table width="165" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border:#DCDCDC solid 1px;"><?
if(!empty($product_categoryID) && $product_categoryID==18){
?><tr><td colspan="3" height="10"></td></tr>
<tr><td width="28" align="right"><img src="/img/bullet-3.png" hspace="0" vspace="0" border="0" alt=""></td>
<td width="8"></td>
<td align="left"><a href="<?=empty($_SESSION['usersID'])?"/login.php?r=".seo_link("product", $productsID, $arr_products_title[$productsID]):"/inside/cart.php?addProduct=1&productsID=".$productsID."&quantity=1"?>" class="arial_12_grey_bold" style="text-decoration:underline;">Add to Cart</a></td></tr><? }
?><tr><td colspan="3" height="10"></td></tr>
<tr><td width="28" align="right"><img src="/img/bullet-3.png" hspace="0" vspace="0" border="0" alt=""></td>
<td width="8"></td>
<td align="left"><a href="/request-quote.php" class="arial_12_grey_bold" style="text-decoration:underline;">Request a Quote</a></td></tr><?
$sql="SELECT * FROM files WHERE tableName = 'products' AND tableIDNum = '".$productsID."'";
$result=mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);
while($row=mysql_fetch_assoc($result)){ ?><tr><td colspan="3" height="10"></td></tr>
<tr><td width="28" align="right"><img src="/img/bullet-3.png" hspace="0" vspace="0" border="0" alt=""></td>
<td width="8"></td><td align="left"><a href="<?=$row['filePath']?>" class="arial_12_grey_bold" style="text-decoration:underline;" target="_blank"><?=empty($row['fileTitle'])?'View Broshure':$row['fileTitle']?></a></td></tr><? }

?><tr><td colspan="3" height="10">&nbsp;</td></tr>
</table>
<?

	$sSQL="SELECT * FROM site_images WHERE tableName='products' AND tableIDNum='".$productsID."' ORDER BY main_img DESC";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
$full_width=0;
$slide_width=330;
$nn=0;
$s_javascript="var arr_imgs=new Array(); var arr_imgs_caption=new Array();  var arr_imgs_height=new Array(); ";
if(mysql_num_rows($result1)>0){
?><div style="border:#DCDCDC solid 1px; height:52px; padding:8px 0; width:<?=$slide_width+64?>px; margin:15px 0;"><table border="0" cellspacing="0" cellpadding="0" width="<?=$slide_width+64?>px">
<tr><td valign="top" width="26px"><img src="/img/arrow-left.png" name="img_left_fea" border="0" id="img_left_fea" style="margin:10px 3px 5px 5px; cursor:pointer;" onclick="change_plans('1');" /></td>
<td width="<?=$slide_width ?>px"><div style="position:relative; overflow:hidden; height:100px; width:<?=$slide_width ?>px;">
<div style="position:absolute; left:0px; top:0;" id="div_feateres"><table border="0" cellspacing="0" cellpadding="0"><tr valign="top" align="center"><?
//$sSQL="SELECT * FROM product_images WHERE tableIDNum='".toSQL($contentID)."' AND tableName='content' AND tableID='contentID' ";
//$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);

$iii=1;
while($row1=mysql_fetch_assoc($result1)){$iii++;
//for($iii=1; $iii<5; $iii++){}
$s_javascript.="arr_imgs['".$nn."']='".$row1['filePath']."'; arr_imgs_caption['".$nn."']='".nl2br($row1['pict_title'])."'; ";
list($ww, $hh)=@getimagesize($_SERVER['DOCUMENT_ROOT']."/".$row1['filePath_s']);
if(empty($ww)){list($ww, $hh)=@getimagesize($_SERVER['DOCUMENT_ROOT']."/".$row1['filePath']);
$s_javascript.="arr_imgs_height['".$nn."']='".$hh."'; ";}
$ww=(int)$ww*50/$hh;
$full_width+=$ww+18;
list($ww1, $hh1)=@getimagesize($_SERVER['DOCUMENT_ROOT']."/".$row1['filePath']);
?><img src="<?=$row1['filePath']?>" style="display:none;" /><?
$s_javascript.="arr_imgs_height['".$nn."']='".$hh1."'; ";

	if(!empty($row1['filePath_m']))$s_first_img=$row1['filePath_m'];
	else $s_first_img=$row1['filePath'];
	list($ww1, $hh1)=@getimagesize($_SERVER['DOCUMENT_ROOT'].$s_first_img);
	
		if($ww1>230){$hh1=round($hh1*230/$ww1);$ww1=230; }
		if($hh1>180){$ww1=round($ww1*180/$hh1);$hh1=180; } 	
//document.getElementById('img_main_opt').src='< ?=$row1['filePath']? >'
?><td align="center"><?php /*?><div style="position:relative; height:90px;"><?php */?><img src="<?=empty($row1['filePath_s'])?$row1['filePath']:$row1['filePath_s']?>" height="50" border="0" align="left" style="margin:0 7px; border:#cccccc 1px solid; cursor:pointer;" onclick="<?php /*?>show_big('<?=$nn?>');<?php */?> show_img('<?=$s_first_img?>', '<?=$hh1?>', '<?=$nn?>')" /><?php /*?><div style="position:absolute; bottom:0px; right:7px;"><img src="/img/btn-enlarge.png" hspace="0" vspace="0" border="0" alt=""></div></div><?php */?></td><?
$nn++;
}
//$full_width-=314;
?></tr>
</table></div></div></td>
<td valign="top" width="26px"><img src="/img/arrow-right.png" name="img_right_fea" border="0" id="img_right_fea" style="margin:10px 3px 5px 5px; cursor:pointer;" onclick="change_plans('-1')" /></td></tr></table></div><?
}

?></td></tr></table></td></tr>
<tr><td colspan="3" height="20"></td></tr>
<tr><td colspan="3"><table width="650" border="0" cellspacing="0" cellpadding="0"><tr><td width="445" align="left"><?
if(!empty($product_categoryID) && $product_categoryID!=18){
$sSQL="SELECT i.title, i.industryID, p.productsID FROM industry i JOIN products_industry p ON (p.industryID=i.industryID AND p.productsID='".$productsID."') ORDER BY i.title";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL);
if(mysql_num_rows($result1)>0){
?><div align="left">This product is best for the following Industries:</div>
<table border="0" cellspacing="0" cellpadding="0" class="text" style="margin:7px 0 15px 0;"><tr><?
$nnn=0;
while($row1=mysql_fetch_assoc($result1)) {
	$nnn++;
	?><td width="20" align="left"><img src="/img/bullet-1.png" hspace="0" vspace="0" border="0" alt=""></td>
<td><a href="<?=seo_link("industry", $row1['industryID'], $row1['title'])?>" class="arial_12_g"><?=$row1['title']?></a>&nbsp;&nbsp;</td><td>&nbsp;</td><?
	if($nnn>=2){ ?></tr><tr><? $nnn=0;}}
?></tr></table><? }}

?></td>
<td width="40"></td>
<td width="165" valign="top"></td></tr>
<tr><td colspan="3" align="left" style="padding:0 0 10px 0;">

<table width="505" border="0" cellspacing="0" cellpadding="0"><tr>
<td align="left" width="169"><div class="green_btn"><ul><li><a href="#" class="green_btn_text_active" onclick="show_tab('Features'); return false;" id="a_Features">Features</a></li></ul></div></td>
<td width="10"></td>
<td align="left" width="206"><div class="green_btn"><ul><li><a href="#" class="green_btn_text" onclick="show_tab('Specifications'); return false;" id="a_Specifications">Specifications</a></li></ul></div></td>
<td class="arial_12_r"><?
if(!empty($product_categoryID) && $product_categoryID==18){
?><b>Price: <?=empty($arr_price[$productsID])?'':("$".number_format($arr_price[$productsID], 2))?></b><? }?></td></tr>
<tr><td colspan="4" style="padding-top:5px; padding-bottom:10px;"><div class="pix_1" style="width:505px;"></div></td></tr></table>


<div id="div_Features" style=" max-height:300px; overflow:auto;"><?=empty($arr_products_descr[$productsID])?'':$arr_products_descr[$productsID]?></div>
<div id="div_Specifications" style="display:none; max-height:300px; overflow:auto;"><?=empty($arr_specifications[$productsID])?'':$arr_specifications[$productsID]?></div></td></tr>
</table></td></tr>
</table></td></tr></table>
<? include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php"; ?><script language="javascript">
function show_tab(s_tab){
	document.getElementById('a_Features').className="green_btn_text";
	document.getElementById('a_Specifications').className="green_btn_text";
	document.getElementById('a_'+s_tab).className="green_btn_text_active";	
	
	document.getElementById('div_Features').style.display="none";
	document.getElementById('div_Specifications').style.display="none";
	document.getElementById('div_'+s_tab).style.display="block";
	}
</script><script language="javascript">
<?=$s_javascript?>
var cur_img=0;

function change_imgs1(n_dir){
	cur_img=1*cur_img+1*n_dir;
	if(n_dir=='1' && cur_img>=<?=$nn?>){cur_img=0;}
	if(n_dir=='-1' && cur_img<0){cur_img=<?=$nn-1?>;}
	change_img1(cur_img);
	}

function change_img1(nn){cur_img=nn;
	//document.getElementById("img_big").src=arr_imgs[nn];
	document.getElementById('div_img_main').style.background='url('+arr_imgs[nn]+') center no-repeat';
	document.getElementById('div_img_text').innerHTML=arr_imgs_caption[nn];
	}

function show_big(){//s_nn
	//alert(arr_imgs[cur_img]+"=="+arr_imgs_height[cur_img])
	//cur_img=s_nn;
 	displayHideBox('1', arr_imgs[cur_img], arr_imgs_height[cur_img]);
	document.getElementById("left_go_box").style.display="block";
	document.getElementById("right_go_box").style.display="block";
}
function change_img(nn){cur_img=nn;
	document.getElementById("img_big").src=arr_imgs[nn];
	//document.getElementById('div_img_main').style.background='url('+arr_imgs[nn]+') center no-repeat';
	//document.getElementById('div_img_text').innerHTML=arr_imgs_caption[nn];
	boxNumber="1";
	big_img_heigh=1*arr_imgs_height[cur_img];
	n_heigh=10;
	document.getElementById("img_big").style.height=n_heigh+"px";
	document.getElementById("img_big").src=arr_imgs[cur_img]; 
	document.getElementById("LightBox"+boxNumber).style.display="inline-block"; 
	document.getElementById("LightBox2").style.display="inline-block"; 
	document.getElementById("grayBG").style.display="block"; 
	tmrIM=setTimeout("increaseIMG()", 10);
	}
function change_imgs(n_dir){
	cur_img=1*cur_img+1*n_dir;
	if(n_dir=='1' && 1*cur_img>=<?=$nn?>){cur_img=0;}
	if(n_dir=='-1' && 1*cur_img<0){cur_img=<?=$nn-1?>;}
	//alert(n_dir+"=="+cur_img+"==<?=$nn?>");
	change_img(cur_img);
	}
	
function show_img(s_src, hh, nn){
	cur_img=nn;
	document.getElementById('div_img_main').src=s_src;
	document.getElementById('div_img_main').style.height=hh+"px";//.style.background='url('+s_src+') center no-repeat';
	
	//document.getElementById('div_img_text').innerHTML=s_txt;
	}
var feateres_move=document.getElementById('div_feateres');
var n_pos=1; var tmr3=setTimeout("", 10);
var banLeft1_b=0; var n_step=0; var banLeft2=0;
function change_plans(s_n){
	clearTimeout(tmr3);
	//if(<?= $full_width//(($iii-2)*314) ?> < 314) return;
	n_pos=1*n_pos+1*s_n;
	hideBanner11_b(s_n);
	}
function hideBanner11_b(s_n){ //alert(banLeft2+"=px=" + banLeft1_b+"=px=" + s_n+"=px=<?=$full_width// (($iii-1)*314) ?>"); 
		if( 1*banLeft2>= 0 && s_n==1){ banLeft1_b=0; n_step=banLeft2; 
			document.getElementById('img_right_fea').src='/img/arrow-right.png';
			document.getElementById('img_left_fea').src='/img/arrow-left_grey.png'; return;}
		else if(banLeft2<=-(<?= $full_width-$slide_width//(($iii-2)*314) ?>) && s_n==-1){
			//alert(banLeft2+"=px=" + banLeft1_b+"=px=" + s_n+"=px=<?=$full_width// (($iii-1)*314) ?>");
			document.getElementById('img_left_fea').src='/img/arrow-left.png';
			document.getElementById('img_right_fea').src='/img/arrow-right_grey.png';
			banLeft1_b=0; n_step=banLeft2; return;}//
		if(1*banLeft1_b > <?=$slide_width-20 ?>){banLeft1_b = banLeft1_b + 1;}
		else if(1*banLeft1_b > <?=$slide_width-60 ?>){banLeft1_b = banLeft1_b + 5;}
		//else if(1*banLeft1_b > 244){banLeft1_b = banLeft1_b + 10;}
		else{banLeft1_b = banLeft1_b + 10;}
		banLeft2= 1*n_step + 1*banLeft1_b*s_n;
		feateres_move.style.left = banLeft2+"px";
		if (banLeft1_b >= <?=$slide_width ?>){
			//alert(banLeft2+"=px=" + banLeft1_b+"=px=" + s_n+"=px=<?=$full_width// (($iii-1)*314) ?>");
			banLeft1_b=0; n_step=banLeft2; 
			document.getElementById('img_right_fea').src='/img/arrow-right.png';
			document.getElementById('img_left_fea').src='/img/arrow-left.png';
			if(banLeft2>=0 && s_n==1){document.getElementById('img_left_fea').src='/img/arrow-left_grey.png'; return;}
			else if(banLeft2<=-(<?= $full_width-$slide_width//(($iii-2)*314) ?>) && s_n==-1){document.getElementById('img_right_fea').src='/img/arrow-right_grey.png';return;}
			return;}
		//alert(banLeft1_b);
		tmr3=setTimeout("hideBanner11_b('"+s_n+"')", 10);
	}
//alert(<?=$full_width?>+"=="+<?=$slide_width?>);
 </script>