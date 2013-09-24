<?
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
$_REQUEST['search_site']=str_replace("Search", "", $_REQUEST['search_site']);
?>

<table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2"><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold">Search</td></tr>
<tr><td colspan="2" height="40"></td></tr>
<tr><td width="25"></td>
<td align="left"><a href="#" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/search.php" class="subnav_link_active">Search</a></td></tr>
<tr><td>&nbsp;</td>
<td>&nbsp;</td></tr>
</table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table></td></tr>
<tr><td colspan="2" height="13"></td></tr>
<tr valign="top"><td width="35"></td><td align="left">
<div style="margin:15px;" class="arial_18_blue_bold">Search Results For: <?=$_REQUEST['search_site']?></div></td></tr><?
$sSQL="SELECT contentID, title, description FROM content  
	WHERE (title LIKE '%".toSQL($_REQUEST['search_site'])."%' OR description LIKE '%".toSQL($_REQUEST['search_site'])."%') AND parentID='2' ";
$result = mysql_query($sSQL) or die("err: " . mysql_error().$sSQL);
if(mysql_num_rows($result)>0){
	?><tr valign="top"><td width="35"></td><td align="left" style="padding:10px 0;">
    <div class="arial_14_c" style="margin:0 15px 5px 15px ;"><b>Website Content: <?=mysql_num_rows($result)?> match(es) found</b></div><?
while($row = mysql_fetch_assoc($result)){
	?><div style="margin:0 15px;"><a href="/about-us/<?=$row['contentID']?>/<?=str_replace(" ", "-", trim($row['title']))?>" class="arial_13_c"><?=$row['title']?></a></div><?
	}
	?></td></tr><?
}
$sSQL="SELECT contentID, title, description FROM content  
	WHERE (title LIKE '%".toSQL($_REQUEST['search_site'])."%' OR description LIKE '%".toSQL($_REQUEST['search_site'])."%') AND contentID ='5' ";
$result = mysql_query($sSQL) or die("err: " . mysql_error().$sSQL);
if(mysql_num_rows($result)>0){
	?><tr valign="top"><td width="35"></td><td align="left" style="padding:10px 0;">
    <div class="arial_14_c" style="margin:0 15px 5px 15px ;"><b>What is frp?: <?=mysql_num_rows($result)?> match(es) found</b></div><?
while($row = mysql_fetch_assoc($result)){
	?><div style="margin:0 15px;"><a href="/what-is-frp.php" class="arial_13_c"><?=$row['title']?></a></div><?
	}
	?></td></tr><?
}

$sSQL="SELECT title, newsDate, headline, newsID, description FROM news 
	WHERE (title LIKE '%".toSQL($_REQUEST['search_site'])."%' OR description LIKE '%".toSQL($_REQUEST['search_site'])."%' OR headline LIKE '%".toSQL($_REQUEST['search_site'])."%')";
$result = mysql_query($sSQL) or die("err: " . mysql_error().$sSQL);
if(mysql_num_rows($result)>0){
	?><tr valign="top"><td width="35"></td><td align="left" style="padding:10px 0;"><div class="arial_14_c" style="margin:15px 15px 5px 15px;"><b>News: <?=mysql_num_rows($result)?> match(es) found</b></div><?
while($row = mysql_fetch_assoc($result)){
	?><div style="margin:0 15px;"><a href="<?=seo_link("news", $row['newsID'], $row['title'])?>" class="arial_13_c"><?=$row['title']?></a></div><?
	}
	?></td></tr><?
}
$sSQL="SELECT productsID, title, description  FROM products   
	WHERE (title LIKE '%".toSQL($_REQUEST['search_site'])."%' OR description LIKE '%".toSQL($_REQUEST['search_site'])."%')";
$result = mysql_query($sSQL) or die("err: " . mysql_error().$sSQL);
if(mysql_num_rows($result)>0){
	?><tr valign="top"><td width="35"></td><td align="left" style="padding:10px 0;">
    <div class="arial_14_c" style="margin:0 15px 5px 15px ;"><b>Products: <?=mysql_num_rows($result)?> match(es) found</b></div><?
while($row = mysql_fetch_assoc($result)){
	?><div style="margin:0 15px;"><a href="<?=seo_link("product", $row['productsID'], $row['title'])?>" class="arial_13_c"><?=$row['title']?></a></div><?
	}
	?></td></tr><?
}
$sSQL="SELECT industryID, title, description  FROM industry   
	WHERE (title LIKE '%".toSQL($_REQUEST['search_site'])."%' OR description LIKE '%".toSQL($_REQUEST['search_site'])."%')";
$result = mysql_query($sSQL) or die("err: " . mysql_error().$sSQL);
if(mysql_num_rows($result)>0){
	?><tr valign="top"><td width="35"></td><td align="left" style="padding:10px 0;">
    <div class="arial_14_c" style="margin:0 15px 5px 15px ;"><b>Industry: <?=mysql_num_rows($result)?> match(es) found</b></div><?
while($row = mysql_fetch_assoc($result)){
	?><div style="margin:0 15px;"><a href="<?=seo_link("industry ", $row['industryID'], $row['title'])?>" class="arial_13_c"><?=$row['title']?></a></div><?
	}
	?></td></tr><?
}
$sSQL="SELECT product_categoryID, title, description  FROM product_category    
	WHERE (title LIKE '%".toSQL($_REQUEST['search_site'])."%' OR description LIKE '%".toSQL($_REQUEST['search_site'])."%')";
$result = mysql_query($sSQL) or die("err: " . mysql_error().$sSQL);
if(mysql_num_rows($result)>0){
	?><tr valign="top"><td width="35"></td><td align="left" style="padding:10px 0;">
    <div class="arial_14_c" style="margin:0 15px 5px 15px ;"><b>Categories: <?=mysql_num_rows($result)?> match(es) found</b></div><?
while($row = mysql_fetch_assoc($result)){
	?><div style="margin:0 15px;"><a href="<?=seo_link("products", $row['product_categoryID'], $row['title'])?>" class="arial_13_c"><?=$row['title']?></a></div><?
	}
	?></td></tr><?
}
?></table><?
include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php"; 
?>