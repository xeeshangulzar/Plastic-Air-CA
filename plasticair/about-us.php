<? 
$page_name="About Us";
$contentID=empty($_REQUEST['contentID'])?'2':str_replace("'", "", $_REQUEST['contentID']);
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";?>
<table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2"><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold"><?=empty($arr_title_cont[$contentID])?'About Us':$arr_title_cont[$contentID]?></td></tr>

<tr><td align="left" style="padding:40px 0 40px 25px;" colspan="2"><div style="position:relative;"><div style="position:absolute; white-space:nowrap;"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/about-us.php" class="subnav_link_active">About Us</a><?
if(!empty($arr_title_cont[$contentID]) && $contentID!="1"){ ?><span class="subnav_divider">/</span><a href="<?=seo_link("about-us", $contentID, $arr_title_cont[$contentID])?>" class="subnav_link_active"><?=empty($arr_title_cont[$contentID])?'About Us':$arr_title_cont[$contentID]?></a><? }
?></div></div></td></tr>
</table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table></td></tr>
<tr><td colspan="2" height="13"></td></tr>
<tr><td width="665" align="left"><?=empty($arr_descr[$contentID])?'':$arr_descr[$contentID]?></td>
<td width="10"></td></tr>
</table>
<? include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php"; ?>
