<?
//function toSQL($sss){return trim(str_replace("'", "", $sss));}
function seo_link($f, $id, $tit){	return "/".$f."/".$id."/".urlencode(str_replace('"', "", str_replace("/", "-", str_replace(" ", "-", $tit))));}
function pagingBlog($s_link){
	global $total, $pageItems, $pageNum;
	$arr_link=explode("?", $s_link);
	$s_link=$arr_link[0];
	?><table border="0" cellpadding="1" cellspacing="1"<?php /*?> style="margin:0 25px;"<?php */?>>
	<tr><td><?		
	if($pageNum > 1){?><a href="<?=$s_link?>?pageNum=<?=$pageNum-1?>" class="paging">previous</a><? }
	 ?></td><? 
	$totalPages=ceil($total/$pageItems);	
	if($totalPages > 5){
		if($pageNum<5){
			?><td align="center"><?
			for($i=1; $i<7; $i++){	?><td><a href="<?=$s_link?>?pageNum=<?=$i?>" class="<?=($pageNum==$i)?"paging_sel":"paging"?>"><?=$i?></a></td><? }
			?><td>&nbsp;...&nbsp;</td>
		<td><a href="<?=$s_link?>?pageNum=<?=$totalPages?>" class="paging"><?=$totalPages?></a></td><?
		}
		elseif($pageNum>=5 && $pageNum<=($totalPages-5)){
		?><td><a href="<?=$s_link?>?pageNum=0" class="paging">1</a></td>
			<td>&nbsp;...&nbsp;</td>
		<td><a href="<?=$s_link?>?pageNum=<?=$pageNum-1?>" class="paging"><?=$pageNum-1?></a></td>
		<td><a href="<?=$s_link?>?pageNum=<?=$pageNum?>" class="paging"><?=$pageNum?></a></td>
		<td><a href="<?=$s_link?>?pageNum=<?=$pageNum?>" class="paging_sel"><?=$pageNum+1?></a></td>
		<td><a href="<?=$s_link?>?pageNum=<?=$pageNum+1?>" class="paging"><?=$pageNum+2?></a></td>
		<td><a href="<?=$s_link?>?pageNum=<?=$pageNum+2?>" class="paging"><?=$pageNum+3?></a></td>
			<td>&nbsp;...&nbsp;</td>
		<td><a href="<?=$s_link?>?pageNum=<?=$totalPages?>" class="paging"><?=$totalPages?></a></td><?
		}
		else{
			?><td><a href="<?=$s_link?>?pageNum=1" class="paging">1</a></td>
			<td>&nbsp;...&nbsp;</td><?
			for($i=$totalPages-5; $i<=$totalPages; $i++){
			?><td><a href="<?=$s_link?>?pageNum=<?=$i?>" class="<?=($pageNum==$i)?"paging_sel":"paging"?>"><?=$i?></a></td><? }
			}
	}
	else{
		for($i=1; $i<7; $i++){
			if($i==$totalPages+1)break;
			?><td><a href="<?=$s_link?>?pageNum=<?=$i?>" class="<?=($pageNum==$i)?"paging_sel":"paging"?>"><?=$i?></a></td><? }
		 }
		?><td><?
	if($total > ($pageNum) * $pageItems){?><a href="<?=$s_link?>?pageNum=<?=$pageNum+1?>" class="paging">next</a><? }
	?></td></tr></table><?
}
function randomkeys1($length)
{
  $pattern = "23456789abcdefghijkmnpqrstuvwxyz";
  for($i=0;$i<$length;$i++)
  {
    if(isset($key))
      $key .= $pattern{rand(0,31)};
    else
      $key = $pattern{rand(0,31)};
  }
  return $key;
}
function count_cart(){ //count and change qty in cart on header	
	$sSQL2="SELECT SUM(qty) qq FROM shoppingCart WHERE usersID='".$_SESSION["usersID"]."' GROUP BY usersID ";
    $result2=mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br />".$sSQL2);
	?><script language="javascript"><?
    if($row2=mysql_fetch_array($result2)) { ?>document.getElementById('sp_cart').innerHTML='<?=$row2['qq']?> items';<? }
    else{ ?>document.getElementById('sp_cart').innerHTML='Empty';<? } 
	?></script><?
	}
?>