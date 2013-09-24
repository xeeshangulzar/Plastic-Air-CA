<?
$page_name="News";
$newsID=empty($_REQUEST['newsID'])?'':str_replace("'", "", $_REQUEST['newsID']);
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";?>
<table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2"><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold">News</td></tr>
<tr><td colspan="2" height="40"></td></tr>
<tr><td width="25"></td>
<td align="left"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/news.php" class="subnav_link_active">News</a></td></tr>
<tr><td>&nbsp;</td>
<td>&nbsp;</td></tr>
</table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table></td></tr>
<tr><td colspan="2" height="20"></td></tr>
<tr><td colspan="2">

<table border="0" cellspacing="1" cellpadding="1" width="670"><?
if(!empty($newsID)){
	$sSQL="SELECT newsID, newsDate, title, headline, description FROM news WHERE newsID='$newsID'";
	$result = mysql_query($sSQL) or die("err: " . mysql_error().$sSQL);
	if($row = mysql_fetch_assoc($result)){
	?><tr><td width="560" align="left"><a href="<?=seo_link("news", $row['newsID'], $row['title'])?>" class="arial_13_orange_bold"><?=$row['title']?></a></td>
	<td width="115" align="right" class="arial_11_grey_italic_bold" style="padding-right:10px;"><?=date("m/d/Y", strtotime($row['newsDate']))?></td></tr>
	<tr><td colspan="2" height="3"></td></tr>
	<tr><td width="560" align="left"><?= stripslashes($row['description'])?></td>
	<td width="115" align="right" class="arial_11_grey_italic_bold" style="padding-right:10px;"></td></tr>
	<tr><td colspan="2" align="right"><a href="/news.php" class="arial_14_green_bold">View All</a><br /><br /></td></tr><? }}
else{
$sSQL="SELECT newsID, newsDate, title, headline FROM news ORDER BY newsDate DESC";
$result = mysql_query($sSQL) or die("err: " . mysql_error().$sSQL);
$pageItems=5; $pageNum=empty($_REQUEST['pageNum'])?'1':$_REQUEST['pageNum'];
$total=mysql_num_rows($result);

if($pageItems*($pageNum-1)>$total)$pageNum=1;
if($total>0)$rr=mysql_data_seek($result, $pageItems*($pageNum-1));
?><tr align="left" class="arial_12_g"><td colspan="2" align="right"><?=pagingBlog($_SERVER['REQUEST_URI'])?></td></tr>
<tr><td colspan="2" style="border-top:#CCC 1px dashed;">&nbsp;</td></tr><?
$ii=0;
while($row = mysql_fetch_assoc($result)){$ii++;
	if($ii>$pageItems)break;
	?><tr><td width="560" align="left"><a href="<?=seo_link("news", $row['newsID'], $row['title'])?>" class="arial_13_orange_bold"><?=$row['title']?></a></td>
<td width="115" align="right" class="arial_11_grey_italic_bold" style="padding-right:10px;"><?=date("m/d/Y", strtotime($row['newsDate']))?></td></tr>
<tr><td colspan="2" height="3"></td></tr>
<tr><td width="560" align="left"><?= nl2br(stripslashes($row['headline']))?></td>
<td width="115" align="right" class="arial_11_grey_italic_bold" style="padding-right:10px;"></td></tr>
<tr><td colspan="2" height="20"></td></tr>
	<?
	}
?><tr align="left" class="arial_13_9"><td colspan="2" align="right"><?=pagingBlog($_SERVER['REQUEST_URI'])?></td></tr><? } ?></table></td></tr>
</table>
<? include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php"; ?>