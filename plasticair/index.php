<? 

$page_name="Home";
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
$sSQL="SELECT front_imagesID, filePath, position, image_title, filePath_s, links, image_descr FROM front_images ORDER BY position";
$result = mysql_query($sSQL) or die(mysql_error()."<br>$sSQL");
$ii=0;
$jsImage=" var arrImage=Array(); var arrImage_s=Array(); var arrImage_link=Array(); var arr_caption=Array(); var arr_descr=Array(); var arr_links=Array(); ";
$n_lenth=0;
while($row = mysql_fetch_assoc($result)){
	if(is_file($_SERVER['DOCUMENT_ROOT']."/".$row['filePath'])){
		//echo $row['filePath'];
		$arr_links[$ii]= $row['links'];
		$imgSource[$ii]= $row['filePath'];
		list($ww1, $hh1)=@getimagesize($_SERVER['DOCUMENT_ROOT']."/".$row['filePath_s']);
		if(!empty($hh1))$imgSource2[$ii]= $row['filePath_s'];
		//if(!empty($hh1))$ww1=(int)($ww1*64/$hh1);
		//if(!empty($ww1))$hh1=(int)($hh1*85/$ww1);
		$n_lenth+=$hh1+2;
		$imgCaption[$ii]=$row['image_title'];
		$imgDescr[$ii]=$row['image_descr'];
		list($ww, $hh)=@getimagesize($_SERVER['DOCUMENT_ROOT']."/".$row['filePath']);
		?><img src="<?=$row['filePath']?>" style="display:none;" /><?
		//470 350
		//if($ww>463){$hh=(int)($hh*463/$ww);$ww=463; }
		//if($hh>350){$ww=(int)($ww*350/$hh);$hh=350; }
		if(empty($ww)){$img_h[$ii]='<img src="/img/p/no_img_b.png" width="463" height="350" />';}
		else{$img_h[$ii]='<img width='.$ww.' height='.$hh.' src="'. $imgSource[$ii].'"  />';}
		$s_link_img="";
		if(!empty($row['links']))$s_link_img="<a href=\"".$row['links']."\">";
		$jsImage.="arrImage[$ii]='".$s_link_img."<img width=".$ww." height=".$hh." src=\"". $imgSource[$ii]."\" id=\"img_main\" border=0 /></a>'; ";
		$jsImage.="arrImage_link[$ii]='". $imgSource[$ii]."'; ";
		//$jsImage.="arr_links[$ii]='". (strpos($row['links'], "http")===false?("http://".$row['links']):($row['links']))."'; ";
		$jsImage.="arr_links[$ii]='".$row['links']."'; ";
		
		$jsImage.="arrImage_s[$ii]='<img src=\"". $row['filePath_s']."\" onClick=\" show_arrow($ii);\" align=\"center\" title=\"". $imgCaption[$ii]."\" alt=\"".$imgCaption[$ii]."\" />'; ";
		$jsImage.="arr_caption[$ii]='". $imgCaption[$ii]."'; ";
		$jsImage.="arr_descr[$ii]='". preg_replace("/(\r\n|\r|\n)/", " ", $imgDescr[$ii])."'; ";
	$ii++;
	}
}
$nn1="0";
if(!empty($imgSource))$nn1=count($imgSource);?>
</td></tr>
<tr><td colspan="3">

<table width="940" border="0" cellspacing="0" cellpadding="0" style="background:url(/img/home-img-bg.jpg) repeat-x;" height="287">
<tr><td width="16"><img src="/img/home-img-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td width="224" align="center" valign="top"><table width="224" border="0" cellspacing="0" cellpadding="0" height="272">
<tr><td align="center" valign="top" style="padding-top:45px;"><img src="/img/home-tittle.png" hspace="0" vspace="0" border="0" alt=""></td></tr>
<tr><td height="60"></td></tr>
<tr><td align="right" class="home_img_text " id="td_img_text"><?=empty($arr_links[0])?'':('<a href="'.$arr_links[0].'">')?><?=empty($imgDescr[0])?'':$imgDescr[0]?></a></td></tr>
<tr><td height="20"></td></tr>
<tr><td align="right" valign="bottom"><?
$s_style_first="/img/home-img-bullet-hover.gif";
foreach($imgCaption as $k=>$v){
	?><a href="#" class="<?=$s_style_first?>" onclick="show_arrow(<?=$k?>); return false;" style="padding-left:3px;"><img src="<?=$s_style_first?>" border="0" id="a_numder_<?=$k?>" alt=""></a><?
	$s_style_first="/img/home-img-bullet.gif";
	}

?></td></tr>
</table></td>
<td width="25"></td>
<td valign="top" style="padding-top:12px;">


<div style="position:relative; height:262px; width:660px; z-index:44;"><div style="position:absolute; top:0px; left:0px; height:262px; background-position:left top;  background-repeat:no-repeat; z-index:11; overflow:hidden;" id="toPrint1"><?=empty($arr_links[0])?'':('<a href="'.$arr_links[0].'">')?><img src="<?=!empty($imgSource[0])?$imgSource[0]:'/img/home-main-img.jpg'?>" border="0" id="img_main" /></a></div></div></td>
<td width="11"><img src="/img/home-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
<script language="javascript" type="text/javascript">
var n_img=0; var tmr2; var time_out=5000; 
<?=$jsImage?>
function ch_op(vvvv){
	document.getElementById('img_main').style.opacity = (vvvv / 300);
    document.getElementById('img_main').style.MozOpacity = (vvvv / 300);
    document.getElementById('img_main').style.KhtmlOpacity = (vvvv / 300);
    document.getElementById('img_main').style.filter = "alpha(opacity=" + vvvv/3 + ")";}
function hide_pop(){ aaa=1*aaa + 4; 
	if(aaa>=300) {aaa=300; 
		ch_op(aaa); 
		document.getElementById("toPrint1").style.backgroundImage="";
		tmr3=setTimeout("show_arrow1(1)", time_out);	
		time_out=5000;
		return;}
	ch_op(aaa);
	tmr2=setTimeout("hide_pop()", 10);
}
var n_img_previous=0; var kk=0; var aaa=0;
function show_arrow(nn){ kk=nn;
	clearTimeout(tmr2);
	clearTimeout(tmr3);
	document.getElementById("toPrint1").style.backgroundImage="url('"+arrImage_link[n_img_previous]+"')";	
	document.getElementById('img_main').style.opacity = 0;
    document.getElementById('img_main').style.MozOpacity = 0;
    document.getElementById('img_main').style.KhtmlOpacity = 0;
    document.getElementById('img_main').style.filter = "alpha(opacity=" + 0 + ")";
	document.getElementById("toPrint1").innerHTML=arrImage[nn]; 
	document.getElementById("a_numder_"+n_img_previous).src="/img/home-img-bullet.gif"; 
	document.getElementById("a_numder_"+nn).src="/img/home-img-bullet-hover.gif";
	document.getElementById("td_img_text").innerHTML='<a href="'+arr_links[nn]+'">'+arr_descr[nn]+'</a>';
	//document.getElementById("td_img_title").innerHTML=arr_caption[nn];
	//document.getElementById("a_img_link").innerHTML="<a"+(arr_links[nn]!=''?(' href="'+arr_links[nn]+'"'):'')+" class='home_img_text'>&raquo;</a>";
	aaa=0;
	
	clearTimeout(tmr2);
	clearTimeout(tmr3);
	hide_pop();
	n_img_previous=1*nn; //document.getElementById("div_caption").innerHTML=arr_caption[kk];
	}
function show_arrow1(n_dir){kk+=1*n_dir;

	if(kk>=arrImage.length)kk=0;
	else if(1*kk<0)kk=1*arrImage.length-1;
	show_arrow(kk)
	}
var tmr3=setTimeout("show_arrow1(1)", 3000);
</script>


<tr><td colspan="5" height="15"></td></tr>
<div class="display_none"><img src="/img/home-product-bg-hover.jpg" hspace="0" vspace="0" border="0" alt=""></div>

<tr><td colspan="5"><?
$sSQL="SELECT * FROM product_category WHERE show_home='1' ORDER BY parentID, position LIMIT 0, 5 ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
while($row1=mysql_fetch_array($result1)){ 
	?><div style="width:188px; height:90px; float:left;"><a href="<?=seo_link("products", $row1['product_categoryID'], $row1['title'])?>" class="home_product_menu"><table width="180" border="0" cellspacing="0" cellpadding="0">
<tr><td style="padding-left:10px;" class="arial_13_grey_b" align="left" onclick="self.location.href='<?=seo_link("products", $row1['product_categoryID'], $row1['title'])?>';"><?=$row1['title']?></td>
<td width="82"  height="90px" onclick="self.location.href='<?=seo_link("products", $row1['product_categoryID'], $row1['title'])?>';"><? if(!empty($row1['picture'])){ ?><img src="<?=$row1['picture']?>?mmm=<?=mktime()?>" border="0" style="border:#CCC 1px solid;" alt=""><? } ?></td></tr></table></a></div><?

}

?>
<?php /*?><div  style="width:190px; height:90px; float:left;"> <a href="#" onmouseover="this.className='home_product_menu_hover'" onMouseOut="this.className='home_product_menu'" class="home_product_menu">
<table width="180" border="0" cellspacing="0" cellpadding="0">
<tr><td style="padding-left:10px;" class="arial_13_grey_b"> Centrifugal </td>
<td width="82"><img src="/img/home-product-2.png" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table>
</a> </div>
<div  style="width:190px; height:90px; float:left;"> <a href="#" onmouseover="this.className='home_product_menu_hover'" onMouseOut="this.className='home_product_menu'" class="home_product_menu">
<table width="180" border="0" cellspacing="0" cellpadding="0">
<tr><td style="padding-left:10px;" class="arial_13_grey_b">FRP Centrifugal Fans</td>
<td width="82"><img src="/img/home-product-3.png" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table>
</a> </div>
<div  style="width:190px; height:90px; float:left;"> <a href="#" onmouseover="this.className='home_product_menu_hover'" onMouseOut="this.className='home_product_menu'" class="home_product_menu">
<table width="180" border="0" cellspacing="0" cellpadding="0">
<tr><td style="padding-left:10px;" class="arial_13_grey_b">FRP Centrifugal Fans</td>
<td width="82"><img src="/img/home-product-4.png" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table>
</a> </div>
<div  style="width:180px; height:90px; float:left;"> <a href="#" onmouseover="this.className='home_product_menu_hover'" onMouseOut="this.className='home_product_menu'" class="home_product_menu">
<table width="180" border="0" cellspacing="0" cellpadding="0">
<tr><td style="padding-left:10px;" class="arial_13_grey_b">FRP Centrifugal Fans</td>
<td width="82"><img src="/img/home-product-5.png" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table>
</a> </div><?php */?></td></tr>
</table>

<table width="940" border="0" cellspacing="0" cellpadding="0" style="margin:10px 0 0 0;">
<tr><td width="240" valign="top"><?
include $_SERVER['DOCUMENT_ROOT']."/inc/menu_products.php";

?><table width="240" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="3" height="20"></td></tr>
<tr><td colspan="3"><table width="240" border="0" cellspacing="0" cellpadding="0" bgcolor="#F2F2F2">
<tr><td colspan="3" height="10"></td></tr>
<tr><td width="15"></td><td width="210" class="arial_16_green_bold" align="left"><a href="/news.php" class="arial_16_green_bold">News</a></td><td width="15"></td></tr><?

$sSQL="SELECT newsID, title, newsDate FROM news ORDER BY newsDate DESC LIMIT 0, 2";
$result = mysql_query($sSQL) or die(mysql_error()."<br>$sSQL");
while($row = mysql_fetch_assoc($result)){ ?><tr><td colspan="3" height="10"></td></tr>
<tr><td width="15"></td><td width="210" align="left" style="line-height:14px;"><a href="<?=seo_link("news", $row['newsID'], $row['title'])?>" class="arial_13_orange_bold"><?=$row['title']?></a><br>
<span class="arial_11_grey_italic_bold"><?=date("m/d/Y", strtotime($row['newsDate']))?></span></td><td width="15"></td></tr><? }
?><tr><td colspan="3" height="10"></td></tr></table></td></tr></table></td>

<td width="25"></td>

<td width="675" valign="top" align="left"><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="425" valign="top"><table width="425" border="0" cellspacing="0" cellpadding="0">
<tr><td width="15"><img src="/img/green-tittle-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td width="395" class="green_bg" align="left"><a href="/industries.php" class="arial_16_white_bold">Industries Served</a></td>
<td width="15"><img src="/img/green-tittle-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>

<tr><td colspan="3"><table width="425" border="0" cellspacing="0" cellpadding="0" style="margin:10px 0;"><tr><?

$sSQL="SELECT * FROM industry ORDER BY position";
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
$nn=0;
while($row=mysql_fetch_array($result)) {
	$nn++;
	?><td width="20" align="left"><img src="/img/bullet-1.png" hspace="0" vspace="0" border="0" alt=""></td>
<td><a href="<?=seo_link("industry", $row['industryID'], $row['title'])?>" class="arial_14_grey"><?=$row['title']?></a></td><? 
	if($nn>=2){ ?></tr><tr><? $nn=0;}}

?></tr></table></td></tr>



<?php /*?><tr><td colspan="3"><table width="425" border="0" cellspacing="0" cellpadding="0">
<tr><td width="200"><table width="200" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="10"></td></tr>
<?

for ($i = 0; $i <= 5; $i++) {    ?>
<tr><td width="20" align="left"><img src="/img/bullet-1.png" hspace="0" vspace="0" border="0" alt=""></td>
<td><a href="#" class="arial_14_grey">Mining</a></td></tr>
<tr><td colspan="2" height="10"></td></tr>
<?php }    ?>
</table></td>
<td width="25"></td>
<td width="200" valign="top"><table width="200" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="10"></td></tr>
<?php for ($i = 0; $i <= 5; $i++) {    ?>
<tr><td width="20" align="left"><img src="/img/bullet-1.png" hspace="0" vspace="0" border="0" alt=""></td>
<td><a href="#" class="arial_14_grey">Mining</a></td></tr>
<tr><td colspan="2" height="10"></td></tr>
<?php }    ?>
</table></td></tr>
</table></td></tr><?php */?>
</table></td>
<td width="20"></td><?
$sSQL="SELECT * FROM content WHERE contentID=6";
$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
$customLink="http://www.amca.org";
if($row=mysql_fetch_array($result)) {$customLink=(strpos(strtolower($row['customLink']), "http")===false?'http://':'').$row['customLink'];}
?>
<td width="230" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td width="15"><img src="/img/green-tittle-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td width="200" class="green_bg" align="left"><a href="<?=$customLink?>" target="_blank" class="arial_16_white_bold">AMCA Member</a></td>
<td width="15"><img src="/img/green-tittle-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
<tr><td></td><td align="center" style="padding-top:15px;"><a href="<?=$customLink?>" target="_blank"><img src="/img/amca-logo.gif" hspace="0" vspace="0" border="0" alt=""></a></td>
<td></td></tr>
<tr><td colspan="3" align="right" style="padding-top:15px;"><?php /*?><a href="#" class="arial_11_green_italic_bold" style="text-decoration:underline;">Read More</a><?php */?></td></tr>
</table></td></tr>
<tr><td colspan="3" height="20"></td></tr>
<tr><td colspan="3"><? include $_SERVER['DOCUMENT_ROOT']."/inc/reps.php"; ?></td></tr>
</table></td></tr>
</table>
<? include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php"; ?>