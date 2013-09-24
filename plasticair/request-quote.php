<?
$page_name="Request a Quote";
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
$sSQL="SELECT * FROM contacts";
//echo $sSQL;
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br>".$sSQL);
$_address=""; $_phones=""; $_fax=""; $_email=""; $description=""; $arr_emails="";
if($row1=mysql_fetch_array($result1)){ $_address=nl2br($row1["address"]);
	//$address_map=$row1["address_map"];
	$address_map=str_replace("\n", "",  str_replace("\r", "", $row1["address_map"]));
	$_phones=$row1["phones"];
	$_fax=$row1["fax"];
	$_email=$row1["email"];
	$description=stripslashes($row1["description"]);
	$arr_emails=explode(";", str_replace("/n", "", str_replace("/r", "", str_replace(" ", "", $row1['contact_emails']))));} 
	
if(!empty($_POST["formSubmit"]) && $_POST["formSubmit"]=="submited")
{
	if(!empty($_POST["secretNumber"]) && $_SESSION["secureNumber"]==md5($_POST["secretNumber"])){
		if($_POST["formSubmit"]=="submited")
		{
			$mailbody="<table style='font-family:Arial, Helvetica, sans-serif; font-size:12px;'>";
			$mailbody.="<tr><td><b>Name:</b> </td><td> ".stripslashes($_POST['fname'])."</td></tr>";
			//$mailbody.="<tr><td><b>Last Name</b> </td><td> ".stripslashes($_POST['lname'])."</td></tr>";
			//$mailbody.="<tr><td><b>Title:</b> </td><td> ".stripslashes($_POST['salutaition'])."</td></tr>";
			$mailbody.="<tr><td><b>Company:</b> </td><td> ".stripslashes($_POST['Company'])."</td></tr>";
			$mailbody.="<tr><td><b>Product Interested:</b> </td><td> ".stripslashes($_POST['Product_Interested'])."</td></tr>";
			//$mailbody.="<tr><td><b>City:</b> </td><td> ".stripslashes($_POST['City'])."</td></tr>";
			//$mailbody.="<tr><td><b>Province:</b> </td><td> ".stripslashes($_POST['Province'])."</td></tr>";
			//$mailbody.="<tr><td><b>Zip:</b> </td><td> ".stripslashes($_POST['Zip'])."</td></tr>";
			$mailbody.="<tr><td><b>Phone:</b> </td><td> ".stripslashes($_POST['Phone'])."</td></tr>";
			//$mailbody.="<tr><td><b>Fax:</b> </td><td> ".stripslashes($_POST['Fax'])."</td></tr>";
			$mailbody.="<tr><td><b>Email:</b> </td><td> ".stripslashes($_POST['email'])."</td></tr>";
			$mailbody.="<tr><td valign=top><b>Message:</b> </td><td> ".stripslashes(nl2br($_POST['message']))."</td></tr>";
			$mailbody.="</table>";

			$sSubject="Contact request from ".$_SERVER['HTTP_HOST'];
			$headers  = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";
			$headers .= "From: website@".$_SERVER['HTTP_HOST']."\n";

			if(!empty($arr_emails)){
				foreach($arr_emails as $v) mail($v, $sSubject, $mailbody, $headers);}
			
			$_POST['lname']="";
			$_POST['fname']="";
			$_POST['Company']="";
			$_POST['salutaition']="";
			$_POST['Address']="";
			$_POST['City']="";
			$_POST['Province']="";
			$_POST['Zip']="";
			$_POST['Phone']="";
			$_POST['Fax']="";
			$_POST['message']="";
			$_POST['email']="";
			$_POST['Country']="";
			$_POST['Product_Interested']="";
			//print $mailbody."<br />".$headers;
			//exit();
/*			?><div id='inCorr' style="position:absolute; border:#CC0000 1px solid; background-color:#CCFFCC; font-family:Verdana, Arial, Helvetica, sans-serif; color:#00CC00; width:100%; font-weight:900; text-align:right; z-index:101;"><br />
<center>Email has been sent successfully</center><br /><a href='#' onClick="document.getElementById('inCorr').style.display='none'" style=" font-family:Verdana, Arial, Helvetica, sans-serif; color:#00CC00; font-size:12px;">close&nbsp;x</a>&nbsp;&nbsp;
</div><?*/
		
		$mess="<div style='margin:20px; text-align:left; font:bold 16px arial; color:#008800'><b>Email has been sent successfully</b></div>";
		}
		$_SESSION["secureNumber"]=mktime();
	}
		else{$mess="<div style='margin:20px; text-align:left; font:bold 16px arial; color:#ff0000'><b>Wrong number</b></div>";}
}
$_SERVER['HTTP_USER_AGENT']=empty($_SERVER['HTTP_USER_AGENT'])?'':$_SERVER['HTTP_USER_AGENT'];?>
<table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2"><table width="675" border="0" cellspacing="0" cellpadding="0">
<tr><td width="240" style="background:url(/img/inner-img-left.jpg) no-repeat;" valign="top"><table width="230" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="2" height="62"></td></tr>
<tr><td width="25"></td>
<td align="left" valign="top" class="arial_17_white_italic_bold">Request a Quote</td></tr>
<tr><td colspan="2" height="40"></td></tr>
<tr><td width="25"></td>
<td align="left"><a href="/" class="subnav_link">Home</a><span class="subnav_divider">/</span><a href="/request-quote.php" class="subnav_link_active">Request a Quote</a></td></tr>
<tr><td>&nbsp;</td>
<td>&nbsp;</td></tr>
</table></td>
<td width="435" valign="top"><img src="/img/inner-img-right.jpg" hspace="0" vspace="0" border="0" alt=""></td></tr>
</table></td></tr>
<tr><td colspan="2" height="13"></td></tr>
<tr><td width="665" align="left"><?=empty($mess)?'':$mess?><table width="550" border="0" cellspacing="0" cellpadding="0" align="left">
<tr><td colspan="3" class="arial_13_green_bold">Please fill out below form.</td></tr>
<tr><td colspan="3" style="padding-top:2px; padding-bottom:7px;" ><div style="background-color:#666666; height:1px; widows:640px;"></div></td></tr>
<form name="ff1_contact" method="post">
<tr><td width="250" valign="top"><table width="250" border="0" cellspacing="0" cellpadding="0">
<tr><td height="10"></td></tr>
<tr><td align="left" width="250" style="padding-left:2px;" class="arial_13_grey_bold">Name:</td></tr>
<tr><td style="padding-top:3px;" align="left" width="250"><input type="text" class="form" style="width:250px;" name="fname" value="<?=empty($_POST['fname'])?'':$_POST['fname']?>"<?
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')!==false ||  strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')!==false){ ?>onclick="this.select();"<? }
else{ ?>onfocus="this.select();"<? } ?>></td></tr>
<tr><td height="10"></td></tr>
<tr><td align="left" width="250" style="padding-left:2px;" class="arial_13_grey_bold">Phone:</td></tr>
<tr><td style="padding-top:3px;" align="left" width="250"><input type="text" class="form" style="width:250px;" name="Phone" value="<?=empty($_POST[''])?'':$_POST['Phone']?>"<?
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')!==false ||  strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')!==false){ ?>onclick="this.select();"<? }
else{ ?>onfocus="this.select();"<? } ?>></td></tr>
<tr><td height="10"></td></tr>
<tr><td align="left" width="250" style="padding-left:2px;" class="arial_13_grey_bold">Email:</td></tr>
<tr><td style="padding-top:3px;" align="left" width="250"><input type="text" class="form" style="width:250px;" name="email" value="<?=empty($_POST['email'])?'':$_POST['email']?>" <?
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')!==false ||  strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')!==false){ ?>onclick="this.select();"<? }
else{ ?>onfocus="this.select();"<? } ?>></td></tr>
<tr><td height="10"></td></tr>
<tr><td align="left" width="250" style="padding-left:2px;" class="arial_13_grey_bold">Product Interested:</td></tr>
<tr><td style="padding-top:3px;" align="left" width="250"><input type="text" class="form" style="width:250px;" name="Product_Interested" value="<?=empty($_POST['Product_Interested'])?'':$_POST['Product_Interested']?>" <?
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')!==false ||  strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')!==false){ ?>onclick="this.select();"<? }
else{ ?>onfocus="this.select();"<? } ?>></td></tr>
</table></td>
<td width="50"></td>
<td width="250" valign="top"><table width="250" border="0" cellspacing="0" cellpadding="0">
<tr><td height="10"></td></tr>
<tr><td align="left" width="250" style="padding-left:2px;" class="arial_13_grey_bold">Company Name:</td></tr>
<tr><td style="padding-top:3px;" align="left" width="250"><input type="text" class="form" style="width:250px;" name="Company" value="<?=empty($_POST['Company'])?'':$_POST['Company']?>"<?
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')!==false ||  strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')!==false){ ?>onclick="this.select();"<? }
else{ ?>onfocus="this.select();"<? } ?>></td></tr>
<tr><td height="10"></td></tr>
<tr><td align="left" width="250" style="padding-left:2px;" class="arial_13_grey_bold">Message:</td></tr>
<tr><td style="padding-top:3px;" align="left" width="250"><textarea class="form" style="width:250px; height:77px;" name="message" <?
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')!==false ||  strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')!==false){ ?>onclick="this.select();"<? }
else{ ?>onfocus="this.select();"<? } ?>><?=empty($_POST['message'])?'':$_POST['message']?></textarea></td></tr>
<tr><td height="10"></td></tr>
<tr><td align="left" width="250" style="padding-left:2px;" class="arial_13_grey_bold">Secure Number:</td></tr>
<tr><td style="padding-top:3px;" align="left" width="250"><table border="0" cellspacing="0" cellpadding="0">
<tr><td><input type="text" class="form" style="width:100px;" name="secretNumber" maxlength="6"></td>
<td style="padding-left:10px;"><img src="/inc/secureImage.php" border="0" style="border:#CCC 1px solid; height:22px;" align="absmiddle" /></td></tr>
</table></td></tr>
</table></td></tr>
<tr><td colspan="3"  height="20"></td></tr>
<tr><td colspan="3" align="center"><img src="/img/btn-submit.jpg" onclick="return chkFields();" style="cursor:pointer;" border="0" alt=""></td></tr>
<input type="hidden" name="formSubmit" value=""></form>
</table></td>
<td width="10"></td></tr>
</table>
<? include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php"; ?><script language="JavaScript" type="text/javascript">
var reLogin = /^([a-zA-Z0-9]{4,33})+$/;
var reField = /^([a-zA-Z0-9\'\-\.\,\s]{1,41})+$/;
var rePhone = /^([0-9\)\(\-\.\+\s]{4,41})+$/;

function chSize2(sss, aaa, bbb, ccc){ 
	var rePhone = /^[0-9]+$/;
	if(!rePhone.test(Trim(sss.value)) && Trim(sss.value)!=''){ alert("wrong_phone"); sss.value=""; }
	else if(sss.value.length >= 1*bbb){ document.ff1[aaa].select();}
	}
	
function chkFields(){
		var ret; var mess;
		var reg1 = /(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/; // not valid
		var reg2 = /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/; // valid	
		var rePhoneNumber = new RegExp(/^(\d{1})?\+?\s?\(?[1-9]\d{2}\)?\s?\d{3}\-?\s?\d{4}$/);	
		ret=true;
		mess="";
		sForm=document.ff1_contact;
		if(Trim(sForm.fname.value)==""){		ret=false; 	mess += "\n - Name";}
		//if(Trim(sForm.lname.value)=="" || Trim(sForm.lname.value)=="Last name"){		ret=false; 	mess += "\n - Last name";}
		//if(Trim(sForm.salutaition.value)=="" || Trim(sForm.salutaition.value)=="Title"){		ret=false; 	mess += "\n - Title";}
		//else if(!reField.test(Trim(sForm.fname.value))){mess+="\n - first_name has invalid characters ";	ref=false;}
		
		if(Trim(sForm.Company.value)==""){		ret=false; 	mess += "\n - Company";}
//		if(Trim(sForm.Address.value)=="" || Trim(sForm.Address.value)=="Address"){		ret=false; 	mess += "\n - Address";}
//		if(Trim(sForm.City.value)=="" || Trim(sForm.City.value)=="City"){		ret=false; 	mess += "\n - City";}
//		if(Trim(sForm.Province.value)=="" || Trim(sForm.Province.value)=="Province"){ret=false; 	mess += "\n - Province";}
//		if(Trim(sForm.Country.value)=="" || Trim(sForm.Country.value)=="Country"){	ret=false; 	mess += "\n - Country";}
		//else if(!reField.test(Trim(sForm.lname.value))){mess+="\n - last_name has invalid characters ";	ref=false;}
		
		//if(!rePhoneNumber.test(sForm.phone.value)){		ret=false; 	mess += "\n - wrong_phone";}
		
		//if((Trim(document.ff1.ph1_5.value)).length<3 || (Trim(document.ff1.ph2_5.value)).length<3 || (Trim(document.ff1.ph3_5.value)).length<4){ ret=false; mess += "\n - phone";}
		
		if(Trim(sForm.Phone.value)==""){ ret=false; mess += "\n - Phone";}
		//if(Trim(sForm.Fax.value)=="" || Trim(sForm.Fax.value)=="Fax"){ ret=false; mess += "\n - Fax";}
		
		//else if(rePhone.test(sForm.phone.value)==false){		ret=false; 	mess += "\n - phone  has invalid characters ";}
		
	  	var str = sForm.email.value; // email string
	  	if (reg1.test(str) || !reg2.test(str)) { 	ret=false;	mess += "\n - Email wrong";  }
//		if(Trim(sForm.subject.value)==""){		ret=false; 	mess += "\n - subject";}
//		else if(!reField.test(Trim(sForm.subject.value))){mess+="\n - subject has invalid characters ";	ref=false;}
		
		if(Trim(sForm.message.value)==""){ ret=false; 	mess += "\n - Message";}
		//else if(!reField.test(Trim(sForm.message.value))){mess+="\n - message has invalid characters ";	ref=false;}
		
		if(Trim(sForm.secretNumber.value)==""){		ret=false; 	mess += "\n - Enter the number";}
		else if(!reField.test(Trim(sForm.secretNumber.value))){mess+="\n - Enter the number has invalid characters ";	ref=false;}
		
		if(!ret) {alert("Next Field Required:" + mess); return ret;}
		else{ sForm.formSubmit.value='submited'; sForm.submit(); }
		}
</script>