<?php
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
header('Cache-Control: no-store, no-cache, must-revalidate');   
?>

<script>
function showFanmodel2(str, str1){
if (str=="")
  {
document.getElementById("motortypeid").innerHTML='<option value="">option sent by ajax</option>';
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("showinme").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","motor_values.php?motortype_user="+str+ "&safetyfactor_user="+str1,true);
xmlhttp.send();
}
</script>

<?php
$fanbhp=$_SESSION['fan_detail_bhp'];

?>

<div style="float:right;">
    <h2 style="text-align: center"> Motor Selection </h2>

  
    <table><tr><td style="text-align: right;">
                <strong> Motor Type:</strong>
            </td>
            <td>
     <select name="motortype_user" id="motortypeid"   onchange="showFanmodel2(document.getElementById('motortypeid').value, document.getElementById('safetyfactorid').value )" >
       <option value="Select">Select</option>
      <option value="TEFC - 575V">TEFC - 575V </option>
      <option value="TEFC - 575V - Prem Eff"> TEFC - 575V - Prem Eff</option>
      <option value="TEFC - 208-230/460V">TEFC - 208-230/460V</option>
      <option value="TEFC - 208-230/460V - Prem Eff"> TEFC - 208-230/460V - Prem Eff</option> 
      <option value="TEFC - 115-208/230V"> TEFC - 115-208/230V </option>
      <option value="Explosion - 575V"> Explosion - 575V </option>
      <option value="Explosion - 208-230/460V"> Explosion - 208-230/460V</option> 
      <option value="Explosion - 115-208/230V"> Explosion - 115-208/230V </option>
      <option value="TEFC Resilient - 115-208/230V"> TEFC Resilient - 115-208/230V</option>
      <option value="TEFC Resilient - 208-230/460V"> TEFC Resilient - 208-230/460V</option> 
      <option value="ODP Resilient - 115-208/230V"> ODP Resilient - 115-208/230V</option>
      <option value="ODP Resilient - 208-230/460V">ODP Resilient - 208-230/460V</option>
      <option value="ODP Resilient - 575V">ODP Resilient - 575V</option>
      <option value="MillChem - 230/460V">Mill & Chem - 230/460V</option>
      <option value="MillChem - 575V"> Mill & Chem - 575V</option>
     
    </select>

   
            </td></tr>
        <tr><td style="text-align: right;"> <strong>Safety Factor :</strong></td><td><input id="safetyfactorid" name="safetyfactor_user"  onchange="showFanmodel2(document.getElementById('motortypeid').value, document.getElementById('safetyfactorid').value )" type="text" value="1.1"></td></tr>
        <tr><td></td><td>
                
               
                
   <tr><td></td><td</td></tr></table>
    <div style="width:320px; " id="showinme"></div> 
    
                
                
                <!--<input type="submit" value="Go" style="background-image: url(/img/green-tittle-bg.jpg);background-repeat: repeat-x; border: green; padding: 3px 16px; color: white;"></input></td></tr></table>
    -->

</div>  


