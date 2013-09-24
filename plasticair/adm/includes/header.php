<? 
ob_start();
// Report all PHP errors (bitwise 63 may be used in PHP 3)
error_reporting(E_ALL);
session_start();
ini_set('session.cache_limiter', 'private');
header('Cache-Control: private'); 
include $_SERVER['DOCUMENT_ROOT']."/inc/dbconnect.php";
include_once $_SERVER['DOCUMENT_ROOT']."/inc/arrays.php";
include $_SERVER['DOCUMENT_ROOT']."/inc/fckeditor/fckeditor_php5.php";
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/functions.php";

if((empty($_SESSION['adminLoggedin']) || empty($_SESSION['adminsID']) || empty($_SESSION['adminPass'])) && $_SERVER['SCRIPT_NAME']!="/adm/index.php" && $_SERVER['SCRIPT_NAME']!="/adm/forgot_password.php")
{
	$rd=$_SERVER['REQUEST_URI'];
	header ("Location: /adm/index.php?rd=$rd");
	exit();
}

##bread crumps
$urlArr=explode("/",$_SERVER['REQUEST_URI']);
if (!empty($urlArr[2])) {
	$currentFolder=($urlArr[2]=="index.php")?"":$urlArr[2];
}
if (!empty($urlArr[3])) {
	$tmp=explode("?",$urlArr[3]);
	$currentFile=($tmp[0]=="index.php")?"":$tmp[0];	
}
$folderNames=array(
	"home.php"=>array(
		"default"=>"Welcome"
		), 
	"contacts"=>array(
		"default"=>"Contacts",
		"addEdit.php"=>"Add/Edit Contacts"
		),
	"front_images"=>array(
		"default"=>"Front Images",
		"addEdit.php"=>"Add/Edit Front Images"
		),
	"orders"=>array(
		"default"=>"Orders",
		"addEdit.php"=>"Add/Edit Orders"
		),
	"faqs"=>array(
		"default"=>"FAQs",
		"addEdit.php"=>"Add/Edit FAQs"
		),
	"industry"=>array(
		"default"=>"Industry",
		"addEdit.php"=>"Add/Edit Industry"
		),
	"news"=>array(
		"default"=>"News",
		"addEdit.php"=>"Add/Edit News"
		),
	"products"=>array(
		"default"=>"Products",
		"addEdit.php"=>"Add/Edit Products"
		),
	"product_category"=>array(
		"default"=>"Product Category",
		"addEdit.php"=>"Add/Edit Product Category"
		),
	"stateCodes"=>array(
		"default"=>"Taxes",
		"addEdit.php"=>"Add/Edit Taxes"
		),
	"representative"=>array(
		"default"=>"Representative",
		"addEdit.php"=>"Add/Edit Representative"
		),
	"settings"=>array(
		"content"=>"Website Content",
		"website"=>"Website settings",
		"adminPassword"=>"Admin settings"
		),
	"users"=>array(
		"default"=>"Users",
		"addEdit.php"=>"Add/Edit Users"
		)
);

?><html><head><script src="/inc/ajax.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Plasticair</title>
<link rel="stylesheet" type="text/css" href="/adm/includes/styles.css">
</head>
<body>
<table  cellpadding="0" cellspacing="0" width="100%" height="100%">
  <tr>
    <td  height="101" background="/adm/img/headerBG.jpg" style="background-repeat:repeat-x"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><table width="100%" height="60" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><table border="0" cellspacing="0" cellpadding="0" height="37" style="margin-left:15px;">
                <tr>
                  <td width="10" background="/adm/img/nameLeft.png"></td>
                  <td align="center" background="/adm/img/nameBG.jpg" class="arial20_Grey">Plasticair</td>
                  <td width="10" background="/adm/img/nameRight.png"></td>
                </tr>
              </table></td>
              <td align="right" valign="top"><table border="0" cellspacing="0" cellpadding="0">
                 <tr>
                  <td align="center" valign="top"><img src="/adm/img/arrowBlueDown.png" width="10" height="6"></td>
                  <td align="center" valign="top"><img src="/adm/img/arrowBlackDown.png" width="10" height="6"></td>
                  <td align="center" valign="top"><img src="/adm/img/arrowBlackDown.png" width="10" height="6"></td><?
				 if (!empty($_SESSION['adminLoggedin'])) { ?>
                  <td align="center" valign="top"><img src="/adm/img/arrowBlackDown.png" width="10" height="6"></td><?
				  } ?>
                  <td width="20" align="center" valign="top"></td>
                </tr>
                <tr>
                  <td align="center" class="tdpadding"><a href="/adm/" class="arial12_lighBlue">Main</a></td>
                  <td align="center" class="tdpadding"><a href="http://superiorwebsys.com/mail" class="arial12_lightGrey">E-mail</a></td>
                  <td align="center" class="tdpadding"><a href="http://superiorwebsys.com/customers/" target="_blank" class="arial12_lightGrey">Support</a></td><?
				 if (!empty($_SESSION['adminLoggedin'])) { ?>
                  <td align="center" class="tdpadding"><a href="/adm/logout.php" class="arial12_lightGrey">Logout</a></td><?
				  } ?>
                  <td align="center">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td valign="bottom"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="200">&nbsp;</td>
              <td valign="bottom"><table width="100%" height="33" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="2" background="/adm/img/top_menu_bg_left.jpg"></td>
                  <td background="/adm/img/top_menu_bg.jpg" style="padding:5px;"> 
				  <a href="/adm/home.php" class="arial12_Grey">Home</a><?
				  if (!empty($currentFolder) && !empty($folderNames[$currentFolder]["default"]))  { ?>
				   &raquo; <a href="/adm/<?=$currentFolder?>" class="arial12_Grey"><?=$folderNames[$currentFolder]["default"]?></a><?
				  } 
				 if (!empty($currentFile) && !empty($folderNames[$currentFolder][$currentFile]))  { ?>
				  &raquo; <span class="arial12_Grey"><?=$folderNames[$currentFolder][$currentFile]?></span><?
				  } ?></td>
                  <td width="2" background="/adm/img/top_menu_bg_right.jpg"></td>
                </tr>
              </table></td>
              <td width="14">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
      </table></td>
  </tr>
  <tr height="100%">
    <td valign="top"><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="200" valign="top"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center" valign="top" background="/adm/img/menu_bg.jpg" class="arial18_Grey" style="background-repeat:repeat-x; padding-top:15px;">
			&nbsp; 
			Site Admin<br>
              <br><? if (!empty($_SESSION['adminsID'])) { ?><? include $_SERVER['DOCUMENT_ROOT']."/adm/includes/menu.php"; ?><?
				} ?></td>
            <td width="10" background="/adm/img/manu_bg_right.jpg" style="background-repeat:no-repeat;">&nbsp;</td>
          </tr>
        </table></td>
        <td valign="top"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="66" valign="top">

