<?php
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
$fan_model = $_SESSION['fan_selected'];

?>

<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script language="JavaScript">
  function showhidefield()
  {
    if (document.frm.chkbox.checked)
    {
      document.getElementById("hideablearea").style.visibility = "visible";
    }
    else
    {
      document.getElementById("hideablearea").style.visibility = "hidden";
    }
  }
</script>

</head>
<body>
    
    <form name='frm' action='nextpage.asp'>
  <input type="checkbox" name="chkbox" onclick="showhidefield()">
  Check/uncheck here to show/hide the other form fields
  <br>
  <div id='hideablearea' style='visibility:hidden;'>
    <input type='text'><br>
    <input type='submit'>
  </div>
  This is a text line below the hideable form fields.<br>
</form>
<!--<form>
<input type="checkbox" name="c1" onclick="showMe('div1', this)" checked>Show Hide Checkbox
</form>-->
<!--
<div id="div1">
<h3 align="center"> This JavaScript shows how to hide divisions </h3>
<table border=1 id="t1">
<tr>
<td>i am here!</td>
</tr>
</table>
<h1 align="center">Select Options </h1>
<form action="fan_submital.php" method="get">
  
<input type="checkbox" name="check_list[]" value="Drain">Drain<br>
<input type="checkbox" name="check_list[]" value="Drive Set">Drive Set<br>
<input type="checkbox" name="check_list[]"  value="Access Door - Bolted" name="c1" onclick="showMe('div1', this)" checked>Access Door - Bolted<br>
<input type="checkbox" name="check_list[]" value="Fan Stand Material / Coating">Fan Stand Material / Coating <br>
<input type="checkbox" name="check_list[]" value="Shaft Seal Upgrade">Shaft Seal Upgrade<br>
<input type="checkbox" name="check_list[]" value="Electrical Disconnect - NEMA 3R - Mounted & Wired">Electrical Disconnect - NEMA 3R - Mounted & Wired<br>
<input type="checkbox" name="check_list[]" value="Inlet Connection Type ">Inlet Connection Type <br>
<input type="checkbox" name="check_list[]" value="Outlet Connection Type">Outlet Connection Type<br>
<input type="checkbox" name="check_list[]" value="Spark Resistant Construction">Spark Resistant Construction<br>
<input type="checkbox" name="check_list[]" value="Nexus Lining">Nexus Lining<br>
<input type="checkbox" name="check_list[]" value="Impellar spray washdown nozzle (at inlet)"> Impellar spray washdown nozzle (at inlet) <br>
<input type="checkbox" name="check_list[]" value="Backdraft Dampers - Gravity Operated">Backdraft Dampers - Gravity Operated<br>
<input type="checkbox" name="check_list[]" value="Extra Case Thickness for Sound Absorbtion">Extra Case Thickness for Sound Absorbtion <br>
<input type="checkbox" name="check_list[]" value="Inlet Screen - SS304">Inlet Screen - SS304<br>
<input type="checkbox" name="check_list[]" value="Unitary Base (Dimension vary by motor)">Unitary Base (Dimension vary by motor) <br>
<input type="checkbox" name="check_list[]" value="Vibration Isolators ">Vibration Isolators <br>
<input type="submit" value="Submit" style=" float:right; background-image:url(/img/green-tittle-bg.jpg); background-repeat :repeat-x;
        border: green; padding : 3px 16px;
        color :white;">
</form>
</div>




<h1 align="center">Select Options </h1>
<form action="fan_submital.php" method="get">
  
<input type="checkbox" name="check_list[]" value="Drain">Drain<br>
<input type="checkbox" name="check_list[]" value="Drive Set">Drive Set<br>
<input type="checkbox" name="check_list[]" value="Access Door - Bolted">Access Door - Bolted<br>
<input type="checkbox" name="check_list[]" value="Fan Stand Material / Coating">Fan Stand Material / Coating <br>
<input type="checkbox" name="check_list[]" value="Shaft Seal Upgrade">Shaft Seal Upgrade<br>
<input type="checkbox" name="check_list[]" value="Electrical Disconnect - NEMA 3R - Mounted & Wired">Electrical Disconnect - NEMA 3R - Mounted & Wired<br>
<input type="checkbox" name="check_list[]" value="Inlet Connection Type ">Inlet Connection Type <br>
<input type="checkbox" name="check_list[]" value="Outlet Connection Type">Outlet Connection Type<br>
<input type="checkbox" name="check_list[]" value="Spark Resistant Construction">Spark Resistant Construction<br>
<input type="checkbox" name="check_list[]" value="Nexus Lining">Nexus Lining<br>
<input type="checkbox" name="check_list[]" value="Impellar spray washdown nozzle (at inlet)"> Impellar spray washdown nozzle (at inlet) <br>
<input type="checkbox" name="check_list[]" value="Backdraft Dampers - Gravity Operated">Backdraft Dampers - Gravity Operated<br>
<input type="checkbox" name="check_list[]" value="Extra Case Thickness for Sound Absorbtion">Extra Case Thickness for Sound Absorbtion <br>
<input type="checkbox" name="check_list[]" value="Inlet Screen - SS304">Inlet Screen - SS304<br>
<input type="checkbox" name="check_list[]" value="Unitary Base (Dimension vary by motor)">Unitary Base (Dimension vary by motor) <br>
<input type="checkbox" name="check_list[]" value="Vibration Isolators ">Vibration Isolators <br>
<input type="submit" value="Submit" style=" float:right; background-image:url(/img/green-tittle-bg.jpg); background-repeat :repeat-x;
        border: green; padding : 3px 16px;
        color :white;">
</form>

 <table><tr><td>
                                    

                        </td></tr> -->
								
								</body>
                            
                            
                       
<?php
    include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php";?>