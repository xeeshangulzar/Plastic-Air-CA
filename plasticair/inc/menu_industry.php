<? 
$industryID=empty($_REQUEST['industryID'])?'':str_replace("'", "", $_REQUEST['industryID']);

$sSQL="SELECT * FROM industry ORDER BY position ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
while($row1=mysql_fetch_array($result1)){
	$arr_industry_descr[$row1['industryID']]=stripslashes($row1['description']);
	$arr_industry_title[$row1['industryID']]=stripslashes($row1['title']);}

?><table width="240" border="0" cellspacing="0" cellpadding="0">
<tr><td width="15"><img src="/img/green-tittle-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td width="210" class="green_bg" align="left"><a href="/industries.php" class="arial_16_white_bold">Industries</a></td><td width="15"><img src="/img/green-tittle-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
<tr><td colspan="3" height="10"></td></tr><?
$n_zindex=100;
foreach($arr_industry_title as $k=>$v){
		?><tr><td></td><td align="left"><a href="<?=seo_link("industry", $k, $v)?>" class="<?=$industryID==$k?'sidebar_link_hover':'sidebar_link'?>"><?=$v?></a></td><td></td></tr>
			<tr><td></td><td style="padding-top:10px; padding-bottom:10px;"><div class="pix_1" style="width:210px;"></div></td><td></td></tr><? 
	$n_zindex--;}

include $_SERVER['DOCUMENT_ROOT']."/inc/menuBottom.php"; ?></table>