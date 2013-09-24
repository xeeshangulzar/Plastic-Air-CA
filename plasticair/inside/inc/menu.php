<?
$arr_menu_inside=array("menu_acc", "menu_acc", "menu_acc", "menu_acc");
if($page_inside=="Home") $arr_menu_inside[0]="menu_acc_active";
elseif($page_inside=="My Profile") $arr_menu_inside[0]="menu_acc_active";
elseif($page_inside=="Shopping Cart") $arr_menu_inside[1]="menu_acc_active";
elseif($page_inside=="Purchase History") $arr_menu_inside[2]="menu_acc_active";
elseif($page_inside=="Privacy Policy") $arr_menu_inside[3]="menu_acc_active";

?><table border="0" cellspacing="0" cellpadding="0" width="665" style="border-bottom:#666666 1px solid; margin:15px 0">
  <tr>
    <td class="arial_17_g" align="left">Welcome, <span class="arial_17_6"><?=$_SESSION['users_name_sess']?></span></td>
    <td align="right"><a href="/logout.php" class="arial_12_green_bold" style="font-size:12px;">Log Out</a></td>
  </tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" width="665">
  <tr>
    <td class="<?=$arr_menu_inside[0]?> arial_13_w" onclick="self.location.href='/inside/account.php'">My Profile</td>
    <td width="5px">&nbsp;</td>
    <td class="<?=$arr_menu_inside[1]?> arial_13_w" onclick="self.location.href='/inside/cart.php'">Shopping Cart</td>
    <td width="5px">&nbsp;</td>
    <td class="<?=$arr_menu_inside[2]?> arial_13_w" onclick="self.location.href='/inside/orderHistory.php'">Purchase History</td>
    <td width="5px">&nbsp;</td>
    <td class="<?=$arr_menu_inside[3]?> arial_13_w" onclick="self.location.href='/inside/orderHistory.php'">Privacy Policy</td>
    <td>&nbsp;</td>
    <td align="right"><a href="/products.php"><img src="/img/btn/continue_shopping.jpg" width="137" height="25" border="0" alt="" /></a></td>
  </tr>
</table>