<?
$sSQL="SELECT * FROM content ORDER BY parentID, position ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
	
while($row1=mysql_fetch_array($result1)){
	$arr_parents_cont[$row1['parentID']][$row1['contentID']]=$row1['title'];
	$arr_parentsID[$row1['contentID']]=$row1['contentID'];
	$arr_descr[$row1['contentID']]=stripslashes($row1['description']);
	$arr_title_cont[$row1['contentID']]=stripslashes($row1['title']);
	$arr_headline[$row1['contentID']]=stripslashes($row1['headline']);
	}
?><table width="240" border="0" cellspacing="0" cellpadding="0">
<tr><td width="15"><img src="/img/green-tittle-left.jpg" hspace="0" vspace="0" border="0" alt=""></td>
<td width="210" class="green_bg" align="left"><a href="/about-us.php" class="arial_16_white_bold">About Us</a></td>
<td width="15"><img src="/img/green-tittle-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
<tr><td colspan="3" height="10"></td></tr><?
if(!empty($arr_parents_cont[1])){
 foreach($arr_parents_cont[1] as $k=>$v) { 
	?><tr><td></td><td align="left"><a href="<?=seo_link("about-us", $k, $v)?>" class="<?=$contentID==$k?'sidebar_link_hover':'sidebar_link'?>"><?=$v?></a></td><td></td></tr>
	<tr><td></td><td style="padding-top:10px; padding-bottom:10px;"><div class="pix_1" style="width:210px;"></div></td><td></td></tr><? 
}}

include $_SERVER['DOCUMENT_ROOT']."/inc/menuBottom.php"; ?> 
</table>
