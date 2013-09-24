<?php
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
header('Cache-Control: no-store, no-cache, must-revalidate');   
?>
<script>
function showFanmodel2(str, str1){
if (str=="")
  {
document.getElementById("motortypeid").innerHTML='<option value="">Option</option>';
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

<script>
    
function customMotorData(str, str1, str2, str3, str4, str5, str6, str7){

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
    document.getElementById("custom_motortype").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","custom_motor.php?Horsepower_custom="+str+ "&RPM_custom="+str1+ "&Voltage_custom="+str2+ " &enclosure_custom="+str3+ " &frame_custom="+str4+"&hz_custom=" +str5+"&phase_custom="+str6+"&motor_notes_custom_id="+str7,true);
xmlhttp.send();
}
</script>

 <script type="text/javascript">
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


<?php

if(isset($_GET['project_user'])){
    
    $projectNameUser=$_GET['project_user'];
   // echo $projectNameUser;
    
    } 
if(isset($_GET['sales_representative_user'])){
    $sales_rep = $_GET['sales_representative_user'];
}

 if(isset($_SESSION['custom_motor_data'])){ //unset motor_data previously saved
   unset($_SESSION['custom_motor_data']);
     }
     
$project_location_city = $_GET['project_location_city_user']; 
$project_location_state = $_GET['project_state_user'];
$project_location_country = $_GET['country_project_user'];
$engineer_user = $_GET['engineer_user'];
$engineer_location_city = $_GET['engineer_location_city_user']; 
$engineer_location_state = $_GET['engineer_state_user'];
$engineer_location_country=$_GET['engineer_country_user'];
$contractor_user = $_GET['contractor_user'];


$_SESSION['project_location']= $project_location_city .",".$_GET['project_state_user'].",".$_GET['country_project_user'] ; 
$_SESSION['engineer_user'] = $_GET['engineer_user'];
$_SESSION['engineer_location'] = $_GET['engineer_location_city_user'].",".$_GET['engineer_state_user'].",".$_GET['engineer_country_user']; 
$_SESSION['contractor_user']= $_GET['contractor_user'];



$tagNumberUser= $_GET['tagnumber_user'];
$amcaArr_user=$_GET['AmcaArr_user'];
$fanOrientation_user=$_GET['fanOrientation_user'];
$soundDistance_user = $_GET['soundDistance_user'];
$reflective_user = $_GET['refelective_user'];
$req_sp = $_GET['inlet-static-pressure']+$_GET['outlet-static-pressure'];
$required_volume_flow = $_GET['req_cfm'];
$fan_model = $_GET['fan-model'];
$wheel_width = $_GET['wheel-width'];

//****************************relative density**************************

//$relative_density_user = $_GET['relative-density-user'];
$relative_density = $_GET['relative-density'];
//$_SESSION['relative-density-user'] = $relative_density_user;
//echo $relative_density_user. "Relative density user variable <br>";
//echo $_SESSION['relative-density-user']."session user<br>";
//echo $_GET['relative-density'] ."Relative Density <br>";

//#########################################################

$temprature = $_GET['temperature'];
$altitude = $_GET['altitude'];
$volume_flow_unit = $_GET['Volume-flow-units'];
$sp_unit = $_GET['sp_units'];
$mount_type = $_GET['mount-type'];
//$fan_selected=$_GET['fan-selection'];

if(isset($_GET['fan-selection'])){$selected_fan = $_GET['fan-selection'];}

$required_cfm = $required_volume_flow;
if($volume_flow_unit == 'm^3/hour') $required_cfm = $required_cfm/1.699;
elseif($volume_flow_unit == 'm^3/min') $required_cfm = $required_cfm/0.02832;
elseif($volume_flow_unit == 'm^3/sec') $required_cfm = $required_cfm/0.000472;
elseif($volume_flow_unit == 'l/sec') $required_cfm = $required_cfm/0.472;

$req_static_pressure = $req_sp;
if($sp_unit == 'Pa') $req_static_pressure = $req_static_pressure/248.36;
elseif($sp_unit == 'in. h.g') $req_static_pressure = $req_static_pressure/0.07343;
elseif($sp_unit == 'mm h.g') $req_static_pressure = $req_static_pressure/1.8651;
elseif($sp_unit == 'Kpa') $req_static_pressure = $req_static_pressure/0.24836;

$user_entered_relative_density = $relative_density; 
if($relative_density == 0) {
//   $relative_density_user=0;
   
   $relative_density = (((0.075*530)/(460+$temprature))*pow(((288-(0.00198*$altitude))/288),5.256));
}



$total_suitable_fan = array();  // contains list of all suitable fans for selected requirements as $total_suitable_fan[fan_name][outlet_velocity].....
$rpm_table = array();           //contains all rpm table of all fans as $rpm_table[fan_name][row_num][rpm],$rpm_table[fan_name][row_num][sp] ...
$fancurve_data = array();

if($req_static_pressure > 0 && $req_static_pressure>0 && $wheel_width>=50){
    $sSQL1 = "select * from fan_specs where fan_series='$fan_model'";
    $result=mysql_query($sSQL1) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL1);
    
    $ratio_v_by_wov = 0.485; // Given ratio of volume/wide open volume;
    $max_allowed_tip_speed = 18000;
    $max_allowed_outlet_velocity = 6500;
    
    while($fan_constants=mysql_fetch_assoc($result)){
        $amca_test_number = $fan_constants['amca_test'];
        $sSQL2 = "select * from fan_tech_detail where amca_test='$amca_test_number' order by cfm desc";
        $result2=mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL2);
        $first_iteration_test = true;
        $i=0;
        $j=0;
        $sp_upper_limit = null;
        $sp_lower_limit = null;
        
        while($fan_tested_data = mysql_fetch_assoc($result2)){
            if($first_iteration_test == true){
                $base_fan_name = $fan_tested_data['fan_name'];
                $sSQL3 = "select * from fan_specs where fan_name='$base_fan_name'";
                $result3=mysql_query($sSQL3) or die ("MySQL err: ".mysql_error()."<br />".$sSQL3);
                $base_fan_constants = mysql_fetch_assoc($result3);
                $adjusted_perfomance_first_cfm = pow(($fan_constants['wheel_dia']/$base_fan_constants['wheel_dia']),3) * $fan_tested_data['cfm'];
                $first_iteration_test = false;
            }
            if($fan_tested_data['cfm'] != 0 ){
                $adjusted_perfomance[$fan_constants['fan_name']][$j]['cfm'] = (pow(($fan_constants['wheel_dia']/$base_fan_constants['wheel_dia']),3) * $fan_tested_data['cfm'])*($wheel_width/100);
                $adjusted_perfomance[$fan_constants['fan_name']][$j]['sp'] = (pow(($fan_constants['wheel_dia']/$base_fan_constants['wheel_dia']),2) * $fan_tested_data['static_pressure'])*pow(($wheel_width/100),0.147)*($relative_density/0.075);
                $adjusted_perfomance[$fan_constants['fan_name']][$j]['hp'] = (pow(($fan_constants['wheel_dia']/$base_fan_constants['wheel_dia']),5) * $fan_tested_data['hp'])*($wheel_width/100)*($relative_density/0.075);

                $rpm_table[$fan_constants['fan_name']][$i]['rpm'] = $fan_constants['test_rpm'] * ($required_cfm / $adjusted_perfomance[$fan_constants['fan_name']][$j]['cfm']);
                $rpm_table[$fan_constants['fan_name']][$i]['sp'] = $adjusted_perfomance[$fan_constants['fan_name']][$j]['sp'] * pow(($rpm_table[$fan_constants['fan_name']][$i]['rpm']/$fan_constants['test_rpm']),2);
                $rpm_table[$fan_constants['fan_name']][$i]['hp'] = $adjusted_perfomance[$fan_constants['fan_name']][$j]['hp'] * pow(($rpm_table[$fan_constants['fan_name']][$i]['rpm']/$fan_constants['test_rpm']),3);
                
                // Start finding the range where required static pressure lies 
                if($rpm_table[$fan_constants['fan_name']][$i]['sp'] > $req_static_pressure){
                    if($sp_upper_limit == null || $sp_upper_limit > $rpm_table[$fan_constants['fan_name']][$i]['sp']){
                        $sp_upper_limit = $rpm_table[$fan_constants['fan_name']][$i]['sp'];
                        $current_row_data = $rpm_table[$fan_constants['fan_name']][$i];
                       }
                }
                else{
                    if($sp_lower_limit == null || $sp_lower_limit < $rpm_table[$fan_constants['fan_name']][$i]['sp']){
                        $sp_lower_limit = $rpm_table[$fan_constants['fan_name']][$i]['sp'];
                        $previous_row_data = $rpm_table[$fan_constants['fan_name']][$i];
                       }
                }
                $i++;
                $j++;
            }
            else{
                $adjusted_perfomance[$fan_constants['fan_name']][$j]['cfm'] = (pow(($fan_constants['wheel_dia']/$base_fan_constants['wheel_dia']),3) * $fan_tested_data['cfm'])*($wheel_width/100);
                $adjusted_perfomance[$fan_constants['fan_name']][$j]['sp'] = (pow(($fan_constants['wheel_dia']/$base_fan_constants['wheel_dia']),2) * $fan_tested_data['static_pressure'])*pow(($wheel_width/100),0.147)*($relative_density/0.075);
                $adjusted_perfomance[$fan_constants['fan_name']][$j]['hp'] = (pow(($fan_constants['wheel_dia']/$base_fan_constants['wheel_dia']),5) * $fan_tested_data['hp'])*($wheel_width/100)*($relative_density/0.075);
                $j++;
            }
        }
        // check if range of static pressure exists
        if($sp_upper_limit !== null && $sp_lower_limit !== null){
            $calculated_rpm = (((($req_static_pressure-$previous_row_data['sp'])*($current_row_data['rpm']-$previous_row_data['rpm']))/($current_row_data['sp']-$previous_row_data['sp']))+$previous_row_data['rpm']);
            $calculated_bhp = (((($req_static_pressure-$previous_row_data['sp'])*($current_row_data['hp']-$previous_row_data['hp']))/($current_row_data['sp']-$previous_row_data['sp']))+$previous_row_data['hp']);
            $calculated_efficiency = (($required_cfm * $req_static_pressure)/(6362 * $calculated_bhp))*100;
            $calculated_tip_speed = (2*M_PI)*(($fan_constants['wheel_dia']/2)/12)*$calculated_rpm;
            $calculated_outlet_velocity = $required_cfm/$fan_constants['outlet_area'];
            $wide_open_volume = ($calculated_rpm/$fan_constants['test_rpm'])*$adjusted_perfomance_first_cfm;
            if(($required_cfm/$wide_open_volume)>$ratio_v_by_wov && $calculated_tip_speed <= $max_allowed_tip_speed && $calculated_outlet_velocity < $max_allowed_outlet_velocity){
                $suitable_fan_detail['rpm'] = $calculated_rpm;
                
                $suitable_fan_detail['bhp'] = $calculated_bhp;
                $suitable_fan_detail['efficiency'] = $calculated_efficiency;
                $suitable_fan_detail['diameter'] = $fan_constants['wheel_dia'];
                $suitable_fan_detail['outlet_area'] = $fan_constants['outlet_area'];
                $suitable_fan_detail['tip_speed'] = $calculated_tip_speed;
                $suitable_fan_detail['outlet_velocity'] = $calculated_outlet_velocity;
                $suitable_fan_detail['test_rpm'] = $fan_constants['test_rpm'];
                $total_suitable_fan[$fan_constants['fan_name']] = $suitable_fan_detail;
                
                // fan curve data here
            }  
        }    
    } 
    //**************try catch for Suitable Fans *********************
try{
{ if(!$total_suitable_fan)
{
    throw new Exception('You entered Invalid arguments');}
    
else {
   foreach($total_suitable_fan as $fan_name=>$fan_details) $temp[$fan_name] = $fan_details['efficiency'];
    array_multisort($temp, SORT_DESC, $total_suitable_fan);     //sorting arrays with fan efficiency
    }

}
}
catch (Exception $d) {
    
    echo  "\n";
    

}
    
}


// parse_str($_SERVER['QUERY_STRING'], $query_string);
//$rdr_str = http_build_query($query_string);
//print $rdr_str;
echo "<div style='padding: 10px 10px 10px 10px; font-size: 15px;' >";

echo "Fan Perfomrance CFM <strong>".$_GET['req_cfm']."</strong>  " . $_GET['Volume-flow-units']. " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

echo "Static Pressure <strong>" .$_GET['inlet-static-pressure']." </strong>".$_GET['sp_units']. "<br>";

echo "</div>";
?>
  
<form name="fan-select" method="get" action="form-values.php">
    
    <input type="hidden" name="inlet-static-pressure" value="<?php print $_GET['inlet-static-pressure']; ?>">
    <input type="hidden" name="outlet-static-pressure" value="<?php print $_GET['outlet-static-pressure']; ?>">
    <input type="hidden" name="req_cfm" value="<?php print $required_volume_flow; ?>">
    <input type="hidden" name="fan-model" value="<?php print $fan_model; ?>">
    <input type="hidden" name="wheel-width" value="<?php print $wheel_width; ?>">
    <input type="hidden" name="relative-density" value="<?php print $relative_density; ?>">
    <input type="hidden" name="temperature" value="<?php print $temprature; ?>">
    <input type="hidden" name="altitude" value="<?php print $altitude; ?>">
    <input type="hidden" name="Volume-flow-units" value="<?php print $volume_flow_unit; ?>">
    <input type="hidden" name="sp_units" value="<?php print $sp_unit; ?>">
    <input type="hidden" name="mount-type" value="<?php print $mount_type ; ?>">
    <input type="hidden" name="refelective_user" value="<?php print $reflective_user ; ?>">
    <input type="hidden" name="soundDistance_user" value="<?php print $soundDistance_user ; ?>">
    <input type="hidden" name="fanOrientation_user" value="<?php print $fanOrientation_user; ?>">
    <input type="hidden" name="AmcaArr_user" value="<?php print $amcaArr_user; ?>">
    <input type="hidden" name="project_user" value="<?php print $projectNameUser; ?>" >
    <input type="hidden" name="project_location_city_user" value="<?php print $_GET['project_location_city_user']; ?>" >
    <input type="hidden" name="project_state_user" value="<?php print $_GET['project_state_user']; ?>" >
    <input type="hidden" name="country_project_user" value="<?php print $_GET['country_project_user']  ?>" >
    <input type="hidden" name="engineer_user" value="<?php print  $_GET['engineer_user'] ?>" >
    <input type="hidden" name="engineer_location_city_user" value="<?php print $_GET['engineer_location_city_user']?>" >
    <input type="hidden" name="engineer_state_user" value="<?php print $_GET['engineer_state_user'];?>" >
    <input type="hidden" name="engineer_country_user" value="<?php print $_GET['engineer_country_user'] ?>" >
    <input type="hidden" name="contractor_user" value="<?php print $_GET['contractor_user'];?>">
      <input type="hidden" name="tagnumber_user" value="<?php print $tagNumberUser?>">
    <input type="hidden" name="sales_representative_user" value="<?php print $sales_rep ?>">
   
 
    <?php  if(!$total_suitable_fan){
        echo "No Fan Found For Your Requirements";
        echo "<br>";
        echo " <a href='fan_selection.php'>Click Here</a> to enter Again ";
        }
        
    else {
        echo ('<table border="1" style="border-spacing: 0px;">
        <thead style="font-weight: bold;">
        <td>Select</td>
        <td>Fan Size</td>
        <td>Wheel dia(inches)</td>
        <td>Outlet Area(ft^2)</td>
        <td>RPM</td>
        <td>Tip speed(f/min)</td>
        <td>Fan Power(BHP)</td>
        <td>Fan Static Efficiency</td>
        <td>Outlet velocity(f/min)</td>
        <td>Sound Pressure at 3.2ft</td>
    </thead>');
    
    }
    ?>
    

    
 <?php 
 foreach($total_suitable_fan as $fanname=>$fan_details){ ?>
    <tr>
        <td><input type="radio" id="<?php print $fanname; ?>" name="fan-selection" value="<?php print $fanname; ?>"></td>
        <td><?php print $fanname; ?></td>
        <td><?php print round($fan_details['diameter'],2); ?></td>
        <td><?php print round ($fan_details['outlet_area'],3); ?></td>
        <td><?php print round($fan_details['rpm'],0); ?></td>
        <td><?php print round($fan_details['tip_speed'],0);?></td>
        <td><?php print round($fan_details['bhp'],3); ?></td>
        <td><?php print round($fan_details['efficiency'],1).'%'; ?></td>
        <td><?php print round($fan_details['outlet_velocity'],0); ?></td>
        <td>
          
           <?php
           //calculating sound data for each fan
           
           if($fanname=='30GIF'){
            $fan_model_sound=$fanname;    
            }
            elseif($fanname=='12GIF' || $fanname=='15GIF' || $fanname=='18GIF')
            {
            $fan_model_sound='12GIF';
            }
            else
            {
            $fan_model_sound='24GIF';
            }
            
            $sSQL1_sound = "select * from soundtestdata where Number='1' and fanName='$fan_model_sound'";
            $result_sound=mysql_query($sSQL1_sound) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL1_sound);
    
            while ($row = mysql_fetch_assoc($result_sound)) 
            {
           
            $rpmtestsound=$row["testRpm"];
            $cfmtestsound=$row["testCfm"];
            $sptestsound=$row["sp"];
            $diatestsound = $row["testDiameter"];
  
            }

            $effeciencySound=$required_volume_flow/($fan_details['rpm']*$cfmtestsound/$rpmtestsound * pow(($fan_details['diameter']/$diatestsound),3));
            //echo $effeciencySound*100 . "<br>";

            //**************************Selecting level from effeciency***********************************

            if($effeciencySound>0.88)
            {
                $octaveNumber=1;
            }
            elseif($effeciencySound>0.7)
            {
                $octaveNumber=2;
            }
            elseif($effeciencySound>0.5)
            {
                $octaveNumber=3;
            }
            else
            {
                $octaveNumber=4;
            }


            //echo $octaveNumber ."<br>";
            //echo $fan_model_sound. "<br>";
            //###############################################################################################
            //**************************Selecting Octave bands frequency************************************
            $sSQL1_sound2 = "select * from octavebands where Number=1 and fanName='$fan_model_sound'";
                $result_sound2=mysql_query($sSQL1_sound2) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL1_sound2);


              while ($row = mysql_fetch_assoc($result_sound2)) 
                {
                $valueArray[0]=$row['63'];
                $valueArray[1]=$row['125'];
                $valueArray[2]=$row['250'];
                $valueArray[3]=$row['500'];
                $valueArray[4]=$row['1k'];
                $valueArray[5]=$row['2k'];
                $valueArray[6]=$row['4k'];
                $valueArray[7]=$row['8k'];
    
                }
            
             
             $frequencyArray= array(63,125,250,500,1000,2000,4000,8000);  //fixed values for frequencies
             $partialFrequencyArray=array(45,90,175,355,690,1400,2800,5600); //fixed values for partial Prequencies
            //$reflectiveArray =  array (36.09499957,26.09499957,19.09499957,13.59499957,10.59499957,9.594999566,9.594999566,11.59499957); //for Q1 at 3.2
$offsetSound= array( 25.5,15.5,9,3,0,-1,-1,1);

$soundSelection=$reflective_user;

if($soundSelection==1)
{
    $soundVariable=0.492;
}
elseif ($soundSelection==2)
{
    $soundVariable=-2.5184;
}
 elseif ($soundSelection==3) {
     $soundVariable=-5.5285;
        
}
else{
    $soundVariable=-8.5388;
}

$distance = 3.2;
    
foreach($offsetSound as $key => $value)
{
   $reflectiveArray[]=20*log10($distance) + $soundVariable + $offsetSound[$key];
}



foreach ($frequencyArray as $key => $value)
{
    $x1[]=10*log10($frequencyArray[$key]/$rpmtestsound)+20;
   // echo round(($x1),2) ."<br>";
    $x4[]=10*log10($frequencyArray[$key]/$fan_details['rpm'])+20;
    //echo round(($x4),2) ."<br>";
}

foreach ($partialFrequencyArray as $key =>$value)
{
$x2[]=$valueArray[$key] -(40*log10($rpmtestsound/1000))-(70*log10($diatestsound/20)) - (10*log10($partialFrequencyArray[$key]));
//echo round($x2,2) . "<br>";
}



$x3 = array(0,1,2,3,4,5,6,7);
//echo $x3[0]. " ";

for ($key = 1; $key < 8; $key = $key+1)
{
    $x3[$key]=($x2[$key]-$x2[$key-1])/($x1[$key]-$x1[$key-1]);
    //echo round($x3[$key],2) . "  ";
    //echo $x2[$key] ."<br>";
}

for ( $i=0; $i<8; $i++)
{
  
        if($x4[$i] < $x1[0])             //1
            {
            $x5[$i] = $x2[0];
               
            }
        elseif ($x4[$i]<$x1[1])                  //2
            {
                $x5[$i]=$x2[0]+($x3[1]*($x4[$i]-$x1[0]));
                
            }
        elseif ($x4[$i]<$x1[2])                  //3
            {
                $x5[$i]=$x2[1]+($x3[2]*($x4[$i]-$x1[1]));
                
            }
        elseif ($x4[$i]<$x1[3])                  //4
            {
                $x5[$i]=$x2[2]+($x3[3]*($x4[$i]-$x1[2]));
                
            }
        elseif ($x4[$i]<$x1[4])                      //5
            {   
                $x5[$i]=$x2[3]+($x3[4]*($x4[$i]-$x1[3]));
                
            }
         elseif ($x4[$i]<$x1[5])                  //6
            {
                $x5[$i]=$x2[4]+($x3[5]*($x4[$i]-$x1[4]));
                
            }
          
         elseif ($x4[$i]<$x1[6])                      //7
            {
                $x5[$i]=$x2[5]+($x3[6]*($x4[$i]-$x1[5]));
                
            }
          else                                          //8
              {
     
                     $x5[$i]=$x2[6]+($x3[7]*($x4[$i]-$x1[6]));
                }

        
    
}

//print_r ($x5)."<br>";
//echo "<br>";

for($i=0; $i<8; $i++ )
{
$anstestsound[$i]= $x5[$i] + (40*log10($fan_details['rpm']/1000)) + (70*log10($fan_details['diameter']/20)) + (10*log10($partialFrequencyArray[$i]));
}
for($i=0; $i<8 ; $i++)
{
   $pressureSound[$i] = $anstestsound[$i] - $reflectiveArray[$i];
}

$AweightedSum=0;
for($i=0; $i<8; $i++ ){
    $AweightedSum = pow(10,$pressureSound[$i]/10) + $AweightedSum; 
    echo $pressureSound[$i]. "<br>";
}
echo round(10*log10($AweightedSum),2);



?>
            
            
            
        </td>
    </tr>
    <?php } ?>
</table>
    <input type="submit" value="Go" size="50" style="background-image: url(/img/green-tittle-bg.jpg);background-repeat: repeat-x; border: green; padding: 3px 16px; color: white;">
</form>


<?php 
if(isset($_GET['fan-selection'])){
    
   // <!--**************Motor Selection form ***************************-->
    
    $bhpfan=$fan_details['bhp'];

    
?>
<br>
<div>
    <fieldset><legend> <strong>Motor Selection</strong></legend>
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
    <div id="showinme">
       
    </div> 
    
     <input type="checkbox" name="custom_check" id="custom_check"> Custom
        
    <div id="custom_motortype_form"  name="custom_motortype_form" style="display:none;"> 
      <form name="custom_motorselection" >
       HP : <input type="text" name="Horsepower_custom" id="Horsepower_custom_id" value="0" size="4" onchange="customMotorData(document.getElementById('Horsepower_custom_id').value, document.getElementById('RPM_custom_id').value, document.getElementById('Voltage_custom_id').value, document.getElementById('enclosure_custom_id').value, document.getElementById('frame_custom_id').value, document.getElementById('hz_custom_id').value, document.getElementById('phase_custom_id').value, document.getElementById('motor_notes_custom_id').value)">
       RPM: <input type="text" name="RPM_custom" id="RPM_custom_id" size="4" value="0" onchange="customMotorData(document.getElementById('Horsepower_custom_id').value, document.getElementById('RPM_custom_id').value, document.getElementById('Voltage_custom_id').value, document.getElementById('enclosure_custom_id').value, document.getElementById('frame_custom_id').value, document.getElementById('hz_custom_id').value, document.getElementById('phase_custom_id').value, document.getElementById('motor_notes_custom_id').value )"> 
       Voltage: <input type="text" name="Voltage_custom" id="Voltage_custom_id" value="0" size="4" onchange="customMotorData(document.getElementById('Horsepower_custom_id').value, document.getElementById('RPM_custom_id').value, document.getElementById('Voltage_custom_id').value, document.getElementById('enclosure_custom_id').value, document.getElementById('frame_custom_id').value, document.getElementById('hz_custom_id').value, document.getElementById('phase_custom_id').value , document.getElementById('motor_notes_custom_id').value)">
       Enclosure: <input type="text" name="enclosure_custom"  id="enclosure_custom_id" value="0" size="4" onchange="customMotorData(document.getElementById('Horsepower_custom_id').value, document.getElementById('RPM_custom_id').value, document.getElementById('Voltage_custom_id').value, document.getElementById('enclosure_custom_id').value, document.getElementById('frame_custom_id').value, document.getElementById('hz_custom_id').value, document.getElementById('phase_custom_id').value, document.getElementById('motor_notes_custom_id').value )">
       Frame: <input type="text" name="frame_custom"  id="frame_custom_id" value="0" size="4" onchange="customMotorData(document.getElementById('Horsepower_custom_id').value, document.getElementById('RPM_custom_id').value, document.getElementById('Voltage_custom_id').value, document.getElementById('enclosure_custom_id').value, document.getElementById('frame_custom_id').value, document.getElementById('hz_custom_id').value, document.getElementById('phase_custom_id').value, document.getElementById('motor_notes_custom_id').value )">
       <br><br>
       
       Hertz:<input type="text" name="hz_custom"  id="hz_custom_id" value="0" size="4" onchange="customMotorData(document.getElementById('Horsepower_custom_id').value, document.getElementById('RPM_custom_id').value, document.getElementById('Voltage_custom_id').value, document.getElementById('enclosure_custom_id').value, document.getElementById('frame_custom_id').value, document.getElementById('hz_custom_id').value, document.getElementById('phase_custom_id').value, document.getElementById('motor_notes_custom_id').value )">
       Phase:<input type="text" name="phase_custom"  id="phase_custom_id" value="0" size="4" onchange="customMotorData(document.getElementById('Horsepower_custom_id').value, document.getElementById('RPM_custom_id').value, document.getElementById('Voltage_custom_id').value, document.getElementById('enclosure_custom_id').value, document.getElementById('frame_custom_id').value, document.getElementById('hz_custom_id').value, document.getElementById('phase_custom_id').value, document.getElementById('motor_notes_custom_id').value )">
       <br>
       <strong>Notes: </strong><br><TEXTAREA name="motor_notes_custom" id="motor_notes_custom_id" ROWS=2 COLS=40 onchange="customMotorData(document.getElementById('Horsepower_custom_id').value, document.getElementById('RPM_custom_id').value, document.getElementById('Voltage_custom_id').value, document.getElementById('enclosure_custom_id').value, document.getElementById('frame_custom_id').value, document.getElementById('hz_custom_id').value, document.getElementById('phase_custom_id').value, document.getElementById('motor_notes_custom_id').value) "></TEXTAREA>
      </form>
       
   </div>
        <div id="custom_motortype" class="custom_motortype"></div>
    
    
   <?php //  echo "<pre>";  print_r ($_SESSION['motor_data']); echo "<pre>"; ?>
    
                
                
      
</fieldset>
</div>  

<!--#############################################################################-->

    
<?php

print '<div id="as" style="width:60%;" >';   //show fan curve on page
print '<h2 style="text-align:center; padding-left: 80px;">FAN CURVE - ' .$selected_fan. '</h2>';
print '<img src ="/fan_selection/fan_select/fan_chart.php">';
print '</div>'; 

?>

<a href='option_select.php' style=" float:right; background-image:url(/img/green-tittle-bg.jpg); background-repeat :repeat-x; border: green; padding : 3px 16px; color :white;">Fan Submittal
<a href='system_fan_curve.php' style=" float:right; background-image:url(/img/green-tittle-bg.jpg); background-repeat :repeat-x; border: green; padding : 3px 16px; color :white;">System Fan Curve

 <?php 
     
//  print '<form action="quotation_sheet.php"><input type="submit" value="Create Quotation Sheet" style=" float:right; background-image:url(/img/green-tittle-bg.jpg); background-repeat :repeat-x;
//        border: green; padding : 3px 16px;
//        color :white;"/></form>';
  

  
  
  
//foreach($total_suitable_fan as $fanname=>$fan_details){
    $fan_details = $total_suitable_fan[$selected_fan];
    foreach($adjusted_perfomance[$selected_fan] as  $key=>$value){
        $fancurve_data[$selected_fan][$key]['volume'] = ($fan_details['rpm']/$fan_details['test_rpm'])*$value['cfm'];
        $fancurve_data[$selected_fan][$key]['sp'] = pow(($fan_details['rpm']/$fan_details['test_rpm']),2)*$value['sp'];
        $fancurve_data[$selected_fan][$key]['hp'] = pow(($fan_details['rpm']/$fan_details['test_rpm']),3)*$value['hp'];
        
        if($volume_flow_unit == 'm^3/hour') $fancurve_data[$selected_fan][$key]['volume'] = $fancurve_data[$selected_fan][$key]['volume']*1.699;
        elseif($volume_flow_unit == 'm^3/min') $fancurve_data[$selected_fan][$key]['volume'] = $fancurve_data[$selected_fan][$key]['volume']*0.02832;
        elseif($volume_flow_unit == 'm^3/sec') $fancurve_data[$selected_fan][$key]['volume'] = $fancurve_data[$selected_fan][$key]['volume']*0.000472;
        elseif($volume_flow_unit == 'l/sec') $fancurve_data[$selected_fan][$key]['volume'] = $fancurve_data[$selected_fan][$key]['volume']*0.472;
        
        if($sp_unit == 'Pa') $fancurve_data[$selected_fan][$key]['sp'] = $fancurve_data[$selected_fan][$key]['sp']*248.36;
        elseif($sp_unit == 'in. h.g') $fancurve_data[$selected_fan][$key]['sp'] = $fancurve_data[$selected_fan][$key]['sp']*0.07343;
        elseif($sp_unit == 'mm h.g') $fancurve_data[$selected_fan][$key]['sp'] = $fancurve_data[$selected_fan][$key]['sp']*1.8651;
        elseif($sp_unit == 'Kpa') $fancurve_data[$selected_fan][$key]['sp'] = $fancurve_data[$selected_fan][$key]['sp']*0.24836;
       
    }
//}

$_SESSION['fan_curve_data'] = $fancurve_data[$selected_fan];
$_SESSION['req_sp'] = $req_sp;
$_SESSION['req_volume'] = $required_volume_flow;
$_SESSION['tip_speed']=$fan_details['tip_speed'];
$_SESSION['wheel_width']=$wheel_width;
$_SESSION['required_volume_flow'] = $required_volume_flow;
$_SESSION['static_pressure'] = $_GET['inlet-static-pressure'] + $_GET['outlet-static-pressure'];
$_SESSION['fan_detail_bhp'] = $fan_details['bhp'];
$_SESSION['altitude'] = $altitude;
$_SESSION['fan_detail_rpm'] = $fan_details['rpm'];
$_SESSION['temperature'] = $temprature;
$_SESSION['fan_model']=$fan_model;
$_SESSION['fan_selected']=$selected_fan ;
$_SESSION['inlet_static_pressure'] = $_GET['inlet-static-pressure'];
$_SESSION['outlet_static_pressure'] = $_GET['outlet-static-pressure'];
$_SESSION['relative_density'] = $relative_density;
$_SESSION['user_relative_density'] = $user_entered_relative_density;
$_SESSION['Volume-flow-unit'] = $volume_flow_unit;
$_SESSION['sp_units'] = $sp_unit;
$_SESSION['fan_dia'] = $fan_details['diameter'];
$_SESSION['outlet_velocity'] = $fan_details['outlet_velocity'];
$_SESSION['soundDistance_user']=$soundDistance_user;
$_SESSION['reflectiveSurface_user']=$reflective_user;
$_SESSION['fanOrientation_user']=$fanOrientation_user;
$_SESSION['amcaArr_user']=$amcaArr_user;
$_SESSION['ProjectNameUser']=$projectNameUser;
$_SESSION['tagNumberUser']=$tagNumberUser;
$_SESSION['sales_representative_user']=$sales_rep;




 } 
 
//$fan_image = fan_chart_func($rpm_table);
include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php"; 
?>

<script>
    
function getParameterByName(name)
{
  name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
  var regexS = "[\\?&]" + name + "=([^&#]*)";
  var regex = new RegExp(regexS);
  var results = regex.exec(window.location.search);
  if(results == null)
    return "";
  else
    return decodeURIComponent(results[1].replace(/\+/g, " "));
}

window.onload = function(){
var selected_fan = getParameterByName("fan-selection");
document.getElementById(selected_fan).checked=true;
}
</script>

<script type="text/javascript">
    checkDisplay("custom_check", "custom_motortype_form");
</script>