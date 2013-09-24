<?
ini_set('session.cache_limiter', 'private');
session_start();
header('Cache-Control: private'); 
header("Expires: ".date("D, M j Y G:i:s T", strtotime("+1 day"))." ");
header("Content-type: text/html; charset=utf-8");
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')!==false){
		header('Cache-Control: no-store, no-cache, must-revalidate');   
		header('Cache-Control: post-check=0, pre-check=0', false);   
		header('Pragma: no-cache');}
else {
		header('Cache-Control: cache');     
		header('Pragma: cache');
		}
@session_start();
ob_start();
$page_name=empty($page_name)?"home":$page_name;
$arr_title=array("1"=>"menu", "2"=>"menu", "3"=>"menu", "4"=>"menu", "5"=>"menu", "6"=>"menu");
if($page_name=="Industries")$arr_title[4]="menu_active";
elseif($page_name=="About Us")$arr_title[2]="menu_active";
elseif($page_name=="Products")$arr_title[3]="menu_active";
elseif($page_name=="Contact Us")$arr_title[5]="menu_active";
elseif($page_name=="Home")$arr_title[1]="menu_active";
elseif($page_name=="Express Ship")$arr_title[6]="menu_active";

include_once $_SERVER['DOCUMENT_ROOT']."/inc/dbconnect.php";
include_once $_SERVER['DOCUMENT_ROOT']."/inc/functions.php";
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<title><?=empty($page_name)?'':($page_name." - ")?>Plasticair</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="MSSmartTagsPreventParsing" content="TRUE">
<meta name="ROBOTS" CONTENT="NOARCHIVE">
<meta name="description" content="">
<script language="javascript" src="/inc/ajax.js"></script>
<script language="javascript" src="/inc/validations.js"></script>
<script language="javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script language="javascript" type="text/javascript">
function showMenu(sss, aaa){ 
	try{
		if(sss=='1'){document.getElementById('menuA'+aaa).style.display='inline';}
		else if(sss=='0') { document.getElementById('menuA'+aaa).style.display='none';}//setTimeout("hideMenu('"+aaa+"')", 500);
	}
	catch(e){}
	}
function hideMenu(aaa){
	document.getElementById('menuA'+aaa).style.display='none';
	}
</script><script type="text/javascript" language="javascript">
/* Superior Web Systems */ 
var  tmrIM; var n_heigh=10; var big_img_heigh=0;
function displayHideBox(boxNumber, src_img, big_img_heigh_php) 
{ 	big_img_heigh=big_img_heigh_php;
	document.getElementById("left_go_box").style.display="none";
	document.getElementById("right_go_box").style.display="none";
    if(document.getElementById("LightBox"+boxNumber).style.display=="none") { n_heigh=10;
		document.getElementById("img_big").style.height=n_heigh+"px";
		document.getElementById("img_big").src=src_img; 
        document.getElementById("LightBox"+boxNumber).style.display="inline-block"; 
        document.getElementById("LightBox2").style.display="inline-block"; 
        document.getElementById("grayBG").style.display="block"; 
		tmrIM=setTimeout("increaseIMG()", 10);
    } else { 
        document.getElementById("LightBox"+boxNumber).style.display="none"; 
        document.getElementById("LightBox2").style.display="none"; 
        document.getElementById("grayBG").style.display="none"; 
    } 
}
function increaseIMG(){
	n_heigh+=20;
	document.getElementById("img_big").style.height=n_heigh+"px";
	if(1*big_img_heigh<=1*n_heigh){document.getElementById("img_big").style.height=big_img_heigh+"px"; return;}
	tmrIM=setTimeout("increaseIMG()", 10);
	}
</script><style> 
.grayBox{ position: fixed; top: 0%; left: 0%; width: 100%; height: 100%; background-color: black; z-index:1001; -moz-opacity: 0.8; opacity:.80; filter: alpha(opacity=80); } 
.box_content {position: relative; text-align:center; margin:20px auto; padding: 16px; z-index:1002;} 
</style>
<meta name="keywords" content="">
<link href="/inc/styles.css" rel="stylesheet" type="text/css"></head>

<body><?
if(!empty($show_light_box)){ ?>
<div id="grayBG" class="grayBox" style="display:none;"></div>
<div style="position: absolute; z-index:1002; width:100%; height:100%; text-align:center; vertical-align:middle; padding:auto;display: none;" id="LightBox2"><div id="LightBox1" class="box_content" style="display: none;">
<table border="0" cellspacing="0" cellpadding="0">
<tr><td align="right"><img src="/img/gallery/arrow_left.png"  style="cursor:pointer; display:none; margin:0 30px 0 0;" onclick="change_imgs('-1');" alt="" id="left_go_box"></td>
<td align="left"><img src="/img/gallery/arrow_right.png" style="cursor:pointer; display:none; margin:0 0 0 30px;" onclick="change_imgs('1');" alt="" id="right_go_box"></td></tr>
<tr><td align="right" colspan="2">&nbsp;<a href="#" onclick="displayHideBox('1', ''); return false;" style="font: bold 12px Arial, sans-serif; color: #FFF;">close x</a><br />
<img src="" id="img_big" onclick="displayHideBox('1', ''); return false;" style="cursor:pointer; border:4px solid #FFF;" /></td></tr></table>
</div></div><? }

?><div align="center">
<table width="960" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="container">
<tr><td style="background:url(/img/top-bar.jpg); background-repeat:no-repeat;" width="960"  valign="top"><table width="960" border="0" cellspacing="0" cellpadding="0">
<tr><td width="525" align="left" valign="top" style="padding-left:20px; padding-top:9px;"><a href="/"><img src="/img/logo.png" hspace="0" vspace="0" border="0" alt=""></a></td>
<td width="435" align="right" valign="top" style="padding-top:13px; padding-right:12px;"><table border="0" cellspacing="0" cellpadding="0">
<tr><td><a href="/news.php" class="top_menu">News</a><span style="color:#CCCCCC; font-weight:bold; font-size:11px; padding:0 13px;">|</span> <a href="/what-is-frp.php" class="top_menu">What is FRP?</a><span style="color:#CCCCCC; font-weight:bold; font-size:11px; padding:0 13px;">|</span> <a href="/sales-reps.php" class="top_menu">Sales Reps</a><span style="color:#CCCCCC; font-weight:bold; font-size:11px; padding:0 13px;">|</span> <a href="/about-us/2/Company-Profile" class="top_menu">Company Profile</a></td></tr>
<tr><td height="35" align="right"></td></tr>
<tr><td align="right">
<table border="0" cellspacing="0" cellpadding="0">
<tr><td><? 
if(empty($_SESSION['usersID'])){?><a href="/login.php" class="arial_12_grey_bold">Login</a><? } 
else {?><a href="/logout.php" class="arial_12_grey_bold">Logout</a><? }
?></td>
<td class="arial_12_grey_bold">&nbsp;&nbsp;|&nbsp;&nbsp;</td>
<td nowrap><? 
if(empty($_SESSION['usersID'])){?><a href="/registration.php" class="arial_12_grey_bold">Register</a><? } 
else {?><a href="/inside/" class="arial_12_grey_bold">My Account</a><? }
?></td><? 
if(!empty($_SESSION['usersID'])){?><td class="arial_12_grey_bold">&nbsp;&nbsp;|&nbsp;&nbsp;</td>
	<td><a href="/inside/cart.php" class="arial_12_grey_bold">Shopping Cart</a><span class="arial_11_orange_bold" style="padding-left:10px;" id="sp_cart"><?
    $sSQL2="SELECT SUM(qty) qq FROM shoppingCart WHERE usersID='".$_SESSION["usersID"]."' GROUP BY usersID ";
    $result2=mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br />".$sSQL2);
    if($row2=mysql_fetch_array($result2))
    { ?><?=$row2['qq']?> items<? }
    else{ ?>Empty<? } ?></span></td><? } ?></tr>
</table>

</td></tr>
</table></td></tr>
</table></td></tr>

<tr><td align="center" valign="top" ><table width="960" border="0" cellspacing="0" cellpadding="0" style="background:url(/img/menu-bg.jpg); background-repeat:repeat-x;">
<tr><td style="padding-top:0px;" align="left"><table  border="0" cellspacing="0" cellpadding="0"  >
<tr><td><a href="/" class="<?=$arr_title[1]?>">HOME</a></td>
<td width="2" valign="top"><img src="/img/menu-divider.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td><a href="/about-us.php" class="<?=$arr_title[2]?>">ABOUT US</a></td>
<td width="2" valign="top"><img src="/img/menu-divider.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td><a href="/products.php" class="<?=$arr_title[3]?>">PRODUCTS</a></td>
<td width="2" valign="top"><img src="/img/menu-divider.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td><a href="/products/18/Express-Ship" class="<?=$arr_title[6]?>">EXPRESS SHIP</a></td>
<td width="2" valign="top"><img src="/img/menu-divider.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td><a href="/industries.php" class="<?=$arr_title[4]?>">INDUSTRIES</a></td>
<td width="2" valign="top"><img src="/img/menu-divider.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td><a href="/contact-us.php" class="<?=$arr_title[5]?>">CONTACT US</a></td></tr>
</table></td><?
$_SERVER['HTTP_USER_AGENT']=empty($_SERVER['HTTP_USER_AGENT'])?'':$_SERVER['HTTP_USER_AGENT'];?>
<td align="right" style="padding-right:10px; padding-top:6px;" valign="top"><table border="0" cellspacing="0" cellpadding="0">
<form name="ff1_search" method="post" action="/search.php">
<tr><td><img src="/img/search-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td><input type="text" class="search_form" value="Search" name="search_site"<?
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')!==false ||  strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')!==false){ ?>onclick="this.select();"<? }
else{ ?>onfocus="this.select();"<? } ?>></td>
<td align="right" style="padding-top:0px;"><input type="image" src="/img/search-right.jpg" width="27" height="28" style="cursor:pointer;"></td></tr></form>
</table></td></tr></table></td></tr>
<tr><td align="center" style="padding-top:0px;">

<table width="940" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" valign="top"><?
if($_SERVER['PHP_SELF']=="/index.php"){}
elseif($_SERVER['PHP_SELF']=="/service.php" || $_SERVER['PHP_SELF']=="/about-us.php" || $_SERVER['PHP_SELF']=="/news.php"){
	include $_SERVER['DOCUMENT_ROOT']."/inc/menuAboutUs.php";}
elseif($_SERVER['PHP_SELF']=="/contact-us.php"){	include $_SERVER['DOCUMENT_ROOT']."/inc/menuContactUs.php";}
elseif($_SERVER['PHP_SELF']=="/shopping-cart.php"){	include $_SERVER['DOCUMENT_ROOT']."/inc/menuShoppingCart.php";}
elseif($page_name=="Industries"){	include $_SERVER['DOCUMENT_ROOT']."/inc/menu_industry.php";}
else{ include $_SERVER['DOCUMENT_ROOT']."/inc/menuGeneral.php";}
?></td>
<td width="25"></td><td width="675" valign="top">