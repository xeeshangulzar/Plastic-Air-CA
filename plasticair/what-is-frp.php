<? 
$page_name="What Is FRP";
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";?>
<table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2"><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold">What is FRP?</td></tr>
<tr><td colspan="2" height="40"></td></tr>
<tr><td width="25"></td>
<td align="left"><a href="#" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="#" class="subnav_link_active">What is FRP?</a></td></tr>
<tr><td>&nbsp;</td>
<td>&nbsp;</td></tr>
</table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table></td></tr>
<tr><td colspan="2" height="13"></td></tr>
<tr><td width="665" align="left"><?
$sSQL="SELECT * FROM content WHERE contentID=5";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
while($row1=mysql_fetch_array($result1)){ ?><?=stripslashes($row1['description'])?><? }
?></td>
<td width="10"></td></tr>
<tr><td colspan="2" height="20"></td></tr>
<?php /*?><tr><td colspan="2"><table width="665" border="0" cellspacing="0" cellpadding="0">
<tr><td width="20" align="left"><img src="/img/bullet-6.png" hspace="0" vspace="0" border="0" alt=""></td>
<td align="left"><a href="#" class=" arial_13_green_bold">Hand lay-up:</a></td></tr>
<tr><td></td>
<td align="left">A process where each layer of the laminate is individually fabricated one at a time on a contact mold. Allows precise control of the glass content and ensures that the glass is thoroughly wetted out with resin. This fabrication method is the most labor intensive.</td></tr>
<tr><td colspan="2" height="20"></td></tr>
<?php for ($i = 0; $i <= 6; $i++) {    ?>
<tr><td width="20" align="left"><img src="/img/bullet-6.png" hspace="0" vspace="0" border="0" alt=""></td>
<td align="left"><a href="#" class=" arial_13_green_bold">Hand lay-up:</a></td></tr>
<tr><td colspan="2" height="10"></td></tr>
<?php }    ?>
</table></td></tr><?php */?>
</table>
<? include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php"; ?>