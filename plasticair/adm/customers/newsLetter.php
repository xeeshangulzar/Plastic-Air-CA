<?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/header.php";
if(!empty($_SESSION['adminlevel']) && !stristr($adminLevelstring[$_SESSION['adminlevel']],"newsletter=1"))
{
	header("Location: /adm/home.php");
	exit();
}
$pageTitle="Create Newsletter";
$folderName="customers";

$recipients=(!empty($_REQUEST["recipients"]))?str_replace("'","`",$_REQUEST["recipients"]):"";
$subject=(!empty($_REQUEST["subject"]))?str_replace("'","`",$_REQUEST["subject"]):"";
$body=(!empty($_REQUEST["body"]))?str_replace("'","`",$_REQUEST["body"]):"";
$sendToIds=(!empty($_REQUEST["sendToIds"]))?str_replace("'","`",$_REQUEST["sendToIds"]):"";

$err="";
$mess="";
$processSafeForm=(!empty($_REQUEST["processSafeForm"]))?$_REQUEST["processSafeForm"]:"";
$_SESSION["processSafe"]=(empty($_SESSION["processSafe"]))?"1":$_SESSION["processSafe"];
if(!empty($_REQUEST["process"]) && $_REQUEST["process"]=="1"
	&& !empty($processSafeForm) && $_SESSION["processSafe"]==$processSafeForm)
{
	$_SESSION["processSafe"]++;
	
	//if($recipients=="" && $sendToIds==""){	$err.="Please select recipients<br/>";}
	if($subject==""){	$err.="Please enter email subject<br/>";}
	if($body==""){	$err.="Please enter email body<br/>";}
	
	if($err=="")
	{			
		$sSQLwhere="";
		if($recipients!="")
		{
			switch ($recipients) {
				case "1":
					//All Subscribed Customers
					$sSQLwhere=" AND subscribe='1'";
					break;
				case "2":
					//All Active and Approved Customers
					$sSQLwhere=" AND active='1' AND approved='1'";
					break;
				case 3:
					//All Customers
					$sSQLwhere="";
					break;
			}
		}
		else
		{
			$sSQLwhere=" AND storeCustomersID IN (".str_replace("'","",$sendToIds).")";
		}
		$sSQL="SELECT email FROM news_letters_email ";//WHERE storeID = '".$_SESSION['storeID']."'".$sSQLwhere;
		$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
		$lettersSent=0;$recipients="";
		while($row=mysql_fetch_array($result))
		{
			foreach ($row as $k=>$v) $$k=$v;
			$bodyToSend=$body;
			$bodyToSend=str_replace("[::name::]",empty($name)?'':$name,$bodyToSend);
			$bodyToSend=str_replace("[::lastName::]",empty($lastName)?'':$lastName,$bodyToSend);
			$bodyToSend=str_replace("[::customerCompany::]",empty($customerCompany)?'':$customerCompany,$bodyToSend);
			$bodyToSend=str_replace("[::storeName::]",empty($storeName)?'':$storeName,$bodyToSend);
			$bodyToSend=str_replace("[::storePhone::]",empty($storePhone)?'':$storePhone,$bodyToSend);
			$bodyToSend=str_replace("[::storeFax::]",empty($storeFax)?'':$storeFax, $bodyToSend);
			$bodyToSend=str_replace("[::storeMainEmail::]",empty($storeMainEmail)?'':$storeMainEmail, $bodyToSend);
			$bodyToSend=str_replace("[::web::]",empty($webLink)?'':$webLink, $bodyToSend);
			
			//echo "==".$bodyToSend."==<br /> <br /> <br />";
			if($email!="")
			{
				$headers  = "MIME-Version: 1.0\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\n";
				$headers .= "From: KimTech <info@kimtech.ca> \n";
				mail($email,$subject,$bodyToSend,$headers);
				$recipients.=$email.";";
				$lettersSent++;
			}
		}
		$sSQL="INSERT INTO news_letters_sent (created, recipients, subject, body, lettersSent)
					VALUES(NOW(),
							'".toSQL($recipients)."',
							'".toSQL($subject)."',
							'".addslashes($body)."',
							'".$lettersSent."')";
		//echo $sSQL;
		mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);

		$mess=$lettersSent." emails sent.";
	}
	//Display HTML properly
	
	
}
$chkItem=(!empty($_REQUEST["chkItem"]))?$_REQUEST["chkItem"]:"";
if(!empty($chkItem))
{
	//Delete Customer(s)
	
	$chkItemString=implode(",",$chkItem);
	if(empty($chkItemString))
	{
		$chkItemString=$chkItem;
	}
	$sSQL="SELECT * FROM news_letters_email ";
	$result=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
	$del="";
	$emailSendTo="";
	while($row = mysql_fetch_array($result))
	{
		$emailSendTo.=$del.$row["name"]." ".$row["lastName"];
		$del="; ";
	}
}


?><table border="0" cellspacing="0" cellpadding="0" class="text" width="100%"><form name='ff1' class='form' method='POST' action='' >
<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="pageTabs">
<tr><td width="2" background="/adm/img/tabs_bottom_bg_left.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td>
<td width="200px" class="arial20_Grey" align="center"><?=$pageTitle?> <img src="/adm/img/arrowGreyRightBig.jpg" width="5" height="9" align="absmiddle" /></td>
<td align="left"><table border="0" cellspacing="0" cellpadding="0" height="35"><tr>
<td width="1" background="/adm/img/tabs_bg_right.jpg"></td>
<td background="/adm/img/tabs_active_bg.jpg"><a href="/adm/<?=$folderName?>/newsLetter.php" class="tabLink"><b>Newsletter</b></a></td>
<td width="4" background="/adm/img/tabs_active_bg_right_tr.jpg"></td>
<td width="1" background="/adm/img/tabs_bg_right.jpg"></td>
<td background="/adm/img/tabs_bg.jpg"><a href="/adm/<?=$folderName?>/emails.php" class="tabLink">Emails</a></td>
<td width="1" background="/adm/img/tabs_bg_right.jpg"></td>
<td width="1" background="/adm/img/tabs_bg_right.jpg"></td>
<td background="/adm/img/tabs_bg.jpg"><a href="/adm/<?=$folderName?>/emails_sent.php" class="tabLink">Emails Sent</a></td>
<td width="1" background="/adm/img/tabs_bg_right.jpg"></td>
<td width="1"></td></tr>
</table></td>
<td width="2" background="/adm/img/tabs_bottom_bg_right.jpg" style="background-position:bottom; background-repeat:no-repeat;"></td></tr>
</table></td></tr>
<tr valign="top"><td  class="contentCell">
<table cellpadding="0" cellspacing="0" width="100%">
<tr><td align="center"><table width="97%"><? 
if($err!=""){ ?><tr><td align="center"><b style="color:#FF0000;"><?=$err?></b></td></tr><? } 
if($mess!=""){ ?><tr><td align="center"><b style="color:#009900;"><?=$mess?></b></td></tr><? } 
?><tr valign="top"><td width="100%" valign="top"  align="center">


<br /><input type='hidden' name='show' value='admin/table_edit' class='editbox'><input type='hidden' name='Submit' value='1' class='editbox'>  
		<table width="97%" class="tblList" cellpadding="2" cellspacing="2">
<tr><td></td></tr>
<tr valign="top"> <td ><br /><table width="100%" class="text">


<tr><td valign="top"> 
<table border="0" cellpadding="0" cellspacing="5" width="100%">
<?php /*?><tr><td width="60%" ><? if(empty($emailSendTo)){ ?><span class="text">Email options</span> <br />
							  
						
<select name="recipients" class="inputField" style="width: 95%">
<option value="0">Select</option>
<option value="1" <?=($recipients=="1")?'selected="selected"':''?>>All Subscribed Customers</option>
<option value="2" <?=($recipients=="2")?'selected="selected"':''?>>All Active and Approved Customers</option>
<option value="3" <?=($recipients=="3")?'selected="selected"':''?>>All Customers</option>
</select>
            <? }else{ ?><span class="text">Email will be sent to:</span><br /><?=$emailSendTo?>
            <input type="hidden" name="sendToIds" id="sendToIds" value="<?=$chkItemString?>" />
            <?
            }
			?></td><td></td></tr><?php */?>
<tr><td width="60%" ><span class="text">Email Subject</span> <br />
<INPUT type="text" name="subject" value='<?=$subject?>' class="inputField" style="width: 95%"></td>
<td></td></tr>

<tr><td width="60%" ><span class="text">Email Details</span> <br /><?
$oFCKeditor = new FCKeditor('body') ;
$oFCKeditor->BasePath = '/inc/fckeditor/' ;
$oFCKeditor->Width = '800' ;
$oFCKeditor->Height = '400' ;
$oFCKeditor->ToolbarSet = 'Default' ;
$oFCKeditor->Value = stripslashes($body);
$oFCKeditor->Create() ;
?></td>
<td><span class="text">Variables - Drug and Drop into needed place</span><br />
<input type="text" id="textBoxToSelect" name="textBoxToSelect" value="[::name::]" style="cursor:pointer; width:140px; font-size:14px; vertical-align:top;" onMouseOver="this.select();" onClick="this.select();" onMouseOut="this.value='[::name::]'" class="inputField" /><br />
<input type="text" id="textBoxToSelect" name="textBoxToSelect" value="[::lastName::]" style="cursor:pointer; width:140px; font-size:14px; vertical-align:top;" onMouseOver="this.select();" onClick="this.select();" onMouseOut="this.value='[::lastName::]'" class="inputField" /><br />
<input type="text" id="textBoxToSelect" name="textBoxToSelect" value="[::customerCompany::]" style="cursor:pointer; width:140px; font-size:14px; vertical-align:top;" onMouseOver="this.select();" onClick="this.select();" onMouseOut="this.value='[::customerCompany::]'" class="inputField" /><br />
<input type="text" id="textBoxToSelect" name="textBoxToSelect" value="[::storeName::]" style="cursor:pointer; width:140px; font-size:14px; vertical-align:top;" onMouseOver="this.select();" onClick="this.select();" onMouseOut="this.value='[::storeName::]'" class="inputField" /><br />
<input type="text" id="textBoxToSelect" name="textBoxToSelect" value="[::storePhone::]" style="cursor:pointer; width:140px; font-size:14px; vertical-align:top;" onMouseOver="this.select();" onClick="this.select();" onMouseOut="this.value='[::storePhone::]'" class="inputField" /><br />
<input type="text" id="textBoxToSelect" name="textBoxToSelect" value="[::storeFax::]" style="cursor:pointer; width:140px; font-size:14px; vertical-align:top;" onMouseOver="this.select();" onClick="this.select();" onMouseOut="this.value='[::storeFax::]'" class="inputField" /><br />
<input type="text" id="textBoxToSelect" name="textBoxToSelect" value="[::storeMainEmail::]" style="cursor:pointer; width:140px; font-size:14px; vertical-align:top;" onMouseOver="this.select();" onClick="this.select();" onMouseOut="this.value='[::storeMainEmail::]'" class="inputField" /><br />
<input type="text" id="textBoxToSelect" name="textBoxToSelect" value="[::web::]" style="cursor:pointer; width:140px; font-size:14px; vertical-align:top;" onMouseOver="this.select();" onClick="this.select();" onMouseOut="this.value='[::web::]'" class="inputField" /></td></tr></table></td></tr></table></td></tr>
<tr><td><br /></td></tr>
		
<tr><td ><HR noshade color="#C0C0C0" size="1"></td></tr></table>

<input type="hidden" name="processSafeForm" id="processSafeForm" value="<?=$_SESSION["processSafe"]?>" />
<input type="hidden" name="storeCustomersID" id="storeCustomersID" value="<?=$storeCustomersID?>" />
<input type="hidden" name="process" id="process" value="1" />
</td></tr></table></td></tr>
<tr><td align="center"><table width="94%">
<tr><td align="right"><input type="submit" value="Send email" /></td></tr>
</table></td></tr></table></td></tr></form></table><?
include $_SERVER['DOCUMENT_ROOT']."/adm/includes/footer.php";
?>