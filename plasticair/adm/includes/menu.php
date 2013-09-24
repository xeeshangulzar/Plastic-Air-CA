
<table width="100%" border="0" cellspacing="0" cellpadding="0">  
<tr><td class="menuItem<?=(($currentFolder!="product_category")?"":" activeItem")?>" onClick="showHideCategories('product_category','/adm/product_category/')" id="tdproduct_category"><a href="/adm/product_category/" class="<?=(($currentFolder!="product_category")?"navLink":"navLinkActive")?>">&nbsp;Product Categories</a></td></tr>

<tr><td class="menuItem<?=(($currentFolder!="products")?"":" activeItem")?>" onClick="showHideCategories('products','/adm/products/')" id="tdproducts"><a href="/adm/products/" class="<?=(($currentFolder!="products")?"navLink":"navLinkActive")?>">&nbsp;Products</a></td></tr>

<tr><td class="menuItem<?=(($currentFolder!="contacts")?"":" activeItem")?>" onClick="showHideCategories('contacts','/adm/contacts/')" id="tdcontacts"><a href="/adm/contacts/" class="<?=(($currentFolder!="contacts")?"navLink":"navLinkActive")?>">&nbsp;Contacts</a></td></tr>

<tr><td class="menuItem<?=(($currentFolder!="news")?"":" activeItem")?>" onClick="showHideCategories('news','/adm/news/')" id="tdnews"><a href="/adm/news/" class="<?=(($currentFolder!="news")?"navLink":"navLinkActive")?>">&nbsp;News</a></td></tr>

<tr><td class="menuItem<?=(($currentFolder!="industry")?"":" activeItem")?>" onClick="showHideCategories('industry','/adm/industry/')" id="tdindustry"><a href="/adm/industry/" class="<?=(($currentFolder!="industry")?"navLink":"navLinkActive")?>">&nbsp;Industry</a></td></tr>

<tr><td class="menuItem<?=(($currentFolder!="representative")?"":" activeItem")?>" onClick="showHideCategories('representative','/adm/representative/')" id="tdrepresentative"><a href="/adm/representative/" class="<?=(($currentFolder!="representative")?"navLink":"navLinkActive")?>">&nbsp;Representative</a></td></tr>

<tr><td class="menuItem<?=(($currentFolder!="front_images")?"":" activeItem")?>" onClick="showHideCategories('front_images','/adm/front_images/')" id="tdfront_images"><a href="/adm/front_images/" class="<?=(($currentFolder!="front_images")?"navLink":"navLinkActive")?>">&nbsp;Front Images</a></td></tr>

<tr><td class="menuItem<?=(($currentFolder!="orders")?"":" activeItem")?>" onClick="showHideCategories('orders','/adm/orders/')" id="tdorders"><a href="/adm/orders/" class="<?=(($currentFolder!="orders")?"navLink":"navLinkActive")?>">&nbsp;Orders</a></td></tr>

<tr><td class="menuItem<?=(($currentFolder!="stateCodes")?"":" activeItem")?>" onClick="showHideCategories('stateCodes','/adm/stateCodes/')" id="tdstateCodes"><a href="/adm/stateCodes/" class="<?=(($currentFolder!="stateCodes")?"navLink":"navLinkActive")?>">&nbsp;Taxes</a></td></tr>

<tr><td class="menuItem<?=(($currentFolder!="users")?"":" activeItem")?>" onClick="showHideCategories('users','/adm/users/')" id="tdusers"><a href="/adm/users/" class="<?=(($currentFolder!="users")?"navLink":"navLinkActive")?>">&nbsp;Users</a></td></tr>

<?php /*?><tr><td class="menuItem<?=(($currentFolder!="customers")?"":" activeItem")?>" onClick="showHideCategories('blog','/adm/customers/newsLetter.php')" id="tdcustomers"><a href="/adm/customers/newsLetter.php" class="<?=(($currentFolder!="customers")?"navLink":"navLinkActive")?>">&nbsp;Newsletters</a></td></tr><?php */?>

<tr><td class="menuItem<?=(($currentFolder!="settings")?"":" activeItem")?>" onClick="showHideCategories('settings','')" id="tdsettings"><span class="navLink">&nbsp;Settings</span></td>
</tr>
<tr>
<td style="padding-left:12px;"><div id="divsettings" style="display:<?=(($currentFolder!="settings")?"none":"block")?>; background-image:url(/adm/img/menu_inner_bg.jpg); background-repeat:repeat-y;">
<table width="100%" border="0" cellspacing="0" cellpadding="3" align="center">
<tr>
    <td width="3px" style="padding-left:10px;"><img src="/adm/img/menu_arrowBlackRight.png" width="3" height="5"></td>
  <td><a href="/adm/settings/adminPassword/" class="navLink">Admin Settings</a></td>
</tr>
<tr>
    <td width="3px" style="padding-left:10px;"><img src="/adm/img/menu_arrowBlackRight.png" width="3" height="5"></td>
  <td><a href="/adm/settings/content" class="navLink">Content Pages</a></td>
</tr>
</table>
</div></td>
</tr>
</table>