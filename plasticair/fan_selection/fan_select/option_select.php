<?php
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>
<head>
    <script type="text/javascript">
      function test(){
        document.getElementById("custom_FRP_id").style.display = 'block';
    }
    
    function test2(){
       document.getElementById("custom_FRP_id").style.display = 'none';
    }
      
		var checkDisplay = function(check, form) { //check ID, form ID
			form = document.getElementById(form);
                        check = document.getElementById(check);
                        
			check.onclick = function(){
				form.style.display = (this.checked) ? "block" : "none";
                              
				form.reset();
			};
			//check.onclick();
              
		};
    </script>
</head>

<?php 
  $fan_model = $_SESSION['fan_selected'];
  $amcaArr_user=$_SESSION['amcaArr_user'];
  if(isset($_SESSION['motor_data'])){ //check motor data selected or not
   $motor_data = $_SESSION['motor_data'];
     }
   
   
  
  // echo "<pre>"; print_r($motor_data); echo "<pre>";
  ?>

<h1 align="center">Select Options </h1>
<form action="fan_submital.php" method="get">
     <input type="checkbox" name="FRP_Resin_user" disabled="disabled"  checked value="Epoxy Vinyl Ester (Standard)"> FRP Resin Type<br>
<div style="padding-left:20px;  margin: 10px 10px 10px 10px;">
                <input  type="radio" name="FRP_Resin_user" checked value='Epoxy Vinyl Ester (Standard)' onclick="test2()"/> Epoxy Vinyl Ester (Standard)<br>
                <input  type="radio" name="FRP_Resin_user" value='Epoxy Vinyl Ester - Class 1 Flame Resistance' onclick="test2()"/>Epoxy Vinyl Ester - Class 1 Flame Resistance<br>
                <input  type="radio" name="FRP_Resin_user" value="custom" id="custom_FRP_Resin_user_id" onclick="test()"/> Custom <br>
 
                <div  id="custom_FRP_id" style="display: none">
                  <TEXTAREA  name="FRP_Resin_custom_user" id="FRP_Resin_user_id" ROWS=2 COLS=40 ></TEXTAREA>
                </div>
</div>
          
<br>
<input type="checkbox" name="check_list[drain]" id="drain" value="PVC - NTP laminated into lowest point of housing">Drain<br>

<div id="drainsub" style="display: none; margin: 10px 10px 10px 10px;">
    <div style="padding-left:20px">
                <input id="drainid1" type="radio" name="check_list[drain]" value='PVC - NTP laminated into lowest point of housing  ' /> PVC - NTP laminated into lowest point of housing  <br>
                <input id="drainid2" type="radio" name="check_list[drain]" value='FRP flanged laminated into lowest point of housing '/>FRP flanged laminated into lowest point of housing<br>
                
                
    </div>
    </div>


   <?php if($amcaArr_user==1 || $amcaArr_user==9 || $amcaArr_user==10){
       echo '<input type="checkbox"  id="driveset" name="check_list[driveset]" value="Fixed drive set at 150% S.F to BHP">Drive Set<br>'; 
   }?>

<div id="drivesetsub" style="display: none; ">
    <div style="padding-left:20px;  margin: 10px 10px 10px 10px;">
                <input type="radio" id="drivesetid1" name="check_list[driveset]" value='Fixed drive set at 150% S.F to BHP' /> Fixed drive set at 150% S.F to BHP   <br>
                <input type="radio" id="drivesetid2" name="check_list[driveset]" value='Adjustable drive set at 150% S.F. to BHP  '/>Adjustable drive set at 150% S.F. to BHP <br>
                <input type="radio" id="drivesetid3"name="check_list[driveset]" value='Fixed drive set at 200% S.F. to BHP  '/>Fixed drive set at 200% S.F. to BHP <br>
                
    </div>
</div>

<?php if($amcaArr_user==4) {echo '<input type="checkbox" name="check_list[]" value="Weather covered FRP ">Weather covered FRP <br>';} ?>
<input type="checkbox" name="check_list[]" value="Access Door - Bolted">Access Door - Bolted<br>
<input type="checkbox" name="check_list[coated]" id="fan_stand_coating" value="Fan Stand Material / Coating">Fan Stand Material / Coating <br>
    <div id="fan_stand_coatingsub" style="display:none;">
    <div style="padding-left:20px;  margin: 10px 10px 10px 10px;">
                <input type="radio" name="check_list[coated]" value='Entire mild steel fan base FRP coated 3/16 inch (4.7mm) thickness UV stable rust proof  ' /> Entire mild steel fan base FRP coated 3/16"(4.7mm) thickness UV stable rust proof  <br>
                <input type="radio" name="check_list[coated]" value='Entire mild steel fan base Epoxy coated - 8 mils Thickness '/>Entire mild steel fan base Epoxy coated - 8 mils Thickness<br>
                <input type="radio" name="check_list[coated]" value='Entire fan base constructed in 316 ss'/> Entire fan base constructed in 316 ss<br>
                <input type="radio" name="check_list[coated]" value='Entire fan base constructed in 304 ss' /> Entire fan base constructed in 304 ss<br>
                
    </div>
    </div>

<input type="checkbox" name="check_list['shaftsteal']" id="shaftseal" value="Teflon Disc">Shaft Seal Upgrade<br>

    
    <div id="shaftsealsub" style="display:none;">
    
        <div style="padding-left:20px;   margin: 10px 10px 10px 10px;">
            <input type="radio"  id="shaftsealid1" name="check_list['shaftsteal']" value='Teflon Disc' /> Teflon Disc  <br>
                <input type="radio" name="check_list['shaftsteal']" value='Teflon Packed spring loaded shaft seal with FRP case.  ' /> Teflon Packed spring loaded shaft seal with FRP case.  <br>
                <input type="radio" name="check_list['shaftsteal']" value='Teflon Pack spring loaded air purge seal for zero leakage performance with FRP case '/>Teflon Pack spring loaded air purge seal for zero leakage performance with FRP case<br>
               
        </div>
    </div>
<input type="checkbox" name="check_list['electrical_disconect']" id="electrical_disconect" value="NEMA 3R disconnect - mounted - not wired">Electrical Disconnect <br>
     
<div id="nema" style="display:none;">
            <div style="padding-left: 20px;  margin: 10px 10px 10px 10px;">
               
		<input type="radio" name="check_list['electrical_disconect']" id="electrical_disconectid1" value='NEMA 3R disconnect - mounted - not wired' /> NEMA 3R disconnect - mounted - not wired<br>
                <input type="radio" name="check_list['electrical_disconect']" value='NEMA 3R disconnect - mounted and wired'/>NEMA 3R disconnect - mounted and wired <br>
                <input type="radio" name="check_list['electrical_disconect']" value='NEMA 4X disconnect - mounted - not wired'/> NEMA 4X disconnect - mounted - not wired<br>
                <input type="radio" name="check_list['electrical_disconect']" value='NEMA 7 & 9 disconnect - mounted - not wired ' /> NEMA 7 & 9 disconnect - mounted - not wired <br>
                <input type="radio" name="check_list['electrical_disconect']" value='NEMA 3R, 7 & 9 disconnect - mounted - not wired ' /> NEMA 3R, 7 & 9 disconnect - mounted - not wired <br>
            </div>

</div>
  
<input type="checkbox" name="check_list['inlet_connection']" id="inlet_connection" value="Inlet-Slip fit">Inlet Connection Type <br>
     
<div id="inlet_connectionsub" style="display:none;">
            <div style="padding-left: 20px;  margin: 10px 10px 10px 10px;">
		<input type="radio" name="check_list['inlet_connection']" value='Inlet-Slip fit ' /> Slip fit <br>
                <input type="radio" name="check_list['inlet_connection']" value='Inlet-Flanged - not drilled'/>Flanged - not drilled <br>
                <input type="radio" name="check_list['inlet_connection']" value='Inlet-Flanged & drilled'/> Flanged & drilled<br>
                <input type="radio" name="check_list['inlet_connection']" value='Inlet-Flanged & drilled with companion flange for slip fit ' /> Flanged & drilled with companion flange for slip fit  <br>
            </div>

</div>
  
<input type="checkbox" name="check_list['outletconnection']" id="outletconnection" value="outlet-Flanged - not drilled ">Outlet Connection Type<br>

<div id="outletconnectionsub" style="display:none;">
            <div style="padding-left: 20px;  margin: 10px 10px 10px 10px;">
		<input type="radio" name="check_list['outletconnection']" value='outlet-Flanged - not drilled ' /> Flanged - not drilled  <br>
                <input type="radio" name="check_list['outletconnection']" value='outlet-Flanged - drilled'/>Flanged - drilled <br>
                <input type="radio" name="check_list['outletconnection']" value='outlet-Flanged with companion flange to round slip fit (inlet and out diameters to match)'/> Flanged with companion flange to round slip fit (inlet and out diameters to match)<br>
              
         </div>
</div>

 
    
    
<input type="checkbox" name="check_list[]" value="Spark resistant construction equal to AMCA-A with grounding kit">Spark resistant construction equal to AMCA-A with grounding kit<br>
<input type="checkbox" name="check_list[]" value="Nexus lining (required for applications with Hydroflouric Acid)">Nexus lining (required for applications with Hydroflouric Acid)<br>
<input type="checkbox" name="check_list[]" value="Impellar spray washdown nozzle (at inlet)"> Impellar spray washdown nozzle (at inlet) <br>
<input type="checkbox" name="check_list[]" value="Backdraft Dampers - Gravity Operated">Backdraft Dampers - Gravity Operated<br>
<input type="checkbox" name="check_list[]" value="Extra Case Thickness for Sound Absorbtion">Extra Case Thickness for Sound Absorbtion <br>
<input type="checkbox" name="check_list[]" value="Inlet Screen - SS304">Inlet Screen - SS304<br>
<input type="checkbox" name="check_list[]" value="Unitary Base (Dimension vary by motor)">Unitary Base (Dimension vary by motor) <br>
<input type="checkbox" name="check_list[vibration]"  id="vibration_isolators" value="Neoprene mount - restrained">Vibration Isolators <br>

<div id="vibration_isolatorssub" style="display:none;" >
            <div style="padding-left: 20px;  margin: 10px 10px 10px 10px;">
                 <input type="radio" name="check_list[vibration]" value='Neoprene mount - restrained' /> Neoprene mount - restrained<br>
		<input type="radio" name="check_list[vibration]" value='1 inch spring housed' /> 1" spring housed<br>
                <input type="radio" name="check_list[vibration]" value='1 inch spring housed and restrained '/> 1" spring housed and restrained <br>
                <input type="radio" name="check_list[vibration]" value='2 inches spring housed'/> 2" spring housed<br>
                <input type="radio" name="check_list[vibration]" value='2 inches spring housed and restrained' /> 2" spring housed and restrained<br>
               
            </div>

</div>
   <?php if(isset($_SESSION['custom_motor_data']) || isset($_SESSION['motor_data']))  {
   echo "<strong>Motor Selection </strong><br>";
   }
    if(isset($_SESSION['custom_motor_data'])){?>
 <input type="radio" name="check_list[motor_selection]" id="custom_motor" value="<?php echo  $_SESSION['custom_motor_data']; ?>" > <?php echo $_SESSION['custom_motor_data']; ?> (Custom) <br>
  <?php }?>
<?php 




if(isset($_SESSION['motor_data'])){
   

  // echo "<pre>" ; Print_r ($motor_data); echo "</pre>";
  
  foreach ($motor_data as $key => $value){
    
    $motor_string= "<strong> HP: </strong>".round($motor_data[$key]['HorsePower'],2). "<strong> RPM: </strong>". round($motor_data[$key]['RPM'],2). "<strong> Voltage: </strong>". $motor_data[$key]['Voltage']. "<strong> Enclosure: </strong>" .$motor_data[$key]['Enclosure']. "<strong> Frame: </strong>" .$motor_data[$key]['Frame'];
        
    
 
?>
  <input type="radio" name="check_list[motor_selection]" value="<?php echo $motor_string ?>">

<?php

  echo $motor_string. "<br>";
   }

}

?>
 






<input type="submit" value="Submit" style=" float:right; background-image:url(/img/green-tittle-bg.jpg); background-repeat :repeat-x;
        border: green; padding : 3px 16px;
        color :white;">
</form>




 
     <script type="text/javascript">
	checkDisplay("vibration_isolators", "vibration_isolatorssub");
        checkDisplay("fan_stand_coating", "fan_stand_coatingsub");
        checkDisplay("drain", "drainsub");
        checkDisplay("electrical_disconect", "nema");
        checkDisplay("driveset", "drivesetsub");
        checkDisplay("shaftseal", "shaftsealsub");
        checkDisplay("inlet_connection", "inlet_connectionsub");
        checkDisplay("outletconnection", "outletconnectionsub");
        //checkDisplay("custom_FRP_Resin_user_id", "custom_FRP_id");
        
          
 
    </script>
    
   
                            
                            
                       
<?php
    include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php";?>