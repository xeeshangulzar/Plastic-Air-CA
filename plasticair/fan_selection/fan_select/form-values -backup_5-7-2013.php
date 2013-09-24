<?php
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
header('Cache-Control: no-store, no-cache, must-revalidate');   
?>


<?php
$req_sp = $_GET['inlet-static-pressure']+$_GET['outlet-static-pressure'];
$required_volume_flow = $_GET['req_cfm'];
$fan_model = $_GET['fan-model'];
$wheel_width = $_GET['wheel-width'];
$relative_density = $_GET['relative-density'];
$temprature = $_GET['temperature'];
$altitude = $_GET['altitude'];
$volume_flow_unit = $_GET['Volume-flow-units'];
$sp_unit = $_GET['sp_units'];
$mount_type = $_GET['mount-type'];
$fan_selected=$_GET['fan-selection'];

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


if($relative_density == 0) {
    $relative_density = (((0.075*530)/(460+$temprature))*pow(((288-(0.00198*$altitude))/288),5.256));
}



$total_suitable_fan = array();  // contains list of all suitable fans for selected requirements as $total_suitable_fan[fan_name][outlet_velocity].....
$rpm_table = array();           //contains all rpm table of all fans as $rpm_table[fan_name][row_num][rpm],$rpm_table[fan_name][row_num][sp] ...
$fancurve_data = array();

if($req_static_pressure > 0 && $req_static_pressure>0 && $wheel_width>50){
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
    foreach($total_suitable_fan as $fan_name=>$fan_details) $temp[$fan_name] = $fan_details['efficiency'];
    array_multisort($temp, SORT_DESC, $total_suitable_fan);     //sorting arrays with fan efficiency
}


// parse_str($_SERVER['QUERY_STRING'], $query_string);
//$rdr_str = http_build_query($query_string);
//print $rdr_str;
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
     
    
<table border="1" style="border-spacing: 0px;">
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
    </thead>
    
 <?php 
 foreach($total_suitable_fan as $fanname=>$fan_details){ ?>
    <tr>
        <td><input type="radio" id="<?php print $fanname; ?>" name="fan-selection" value="<?php print $fanname; ?>"></td>
        <td><?php print $fanname; ?></td>
        <td><?php print round($fan_details['diameter'],3); ?></td>
        <td><?php print round ($fan_details['outlet_area'],3); ?></td>
        <td><?php print round($fan_details['rpm'],3); ?></td>
        <td><?php print round($fan_details['tip_speed'],3);?></td>
        <td><?php print round($fan_details['bhp'],3); ?></td>
        <td><?php print round($fan_details['efficiency'],3).'%'; ?></td>
        <td><?php print round($fan_details['outlet_velocity'],3); ?></td>
        <td>49.75</td>
    </tr>
    <?php } ?>
</table>
    <input type="submit" value="Go" size="50" style="background-image: url(/img/green-tittle-bg.jpg);background-repeat: repeat-x; border: green; padding: 3px 16px; color: white;">
</form>
    
<?php 
if(isset($_GET['fan-selection'])){ 
    
//    <form>
//        <input type='button' value='Show fan curve' onClick='http://www.hyperlinkcode.com/button-links.php' 
//        size='50' 
//        style='
//        float: right;  
//        background-image: url(/img/green-tittle-bg.jpg);
//        background-repeat: repeat-x;
//        border: green;
//        padding: 3px 16px; 
//        color: white;'>
//        </form>";
//            
    print '<form action="system_fan_curve.php"><input type="submit" value="Show Fan Curve" style=" float:right; background-image:url(/img/green-tittle-bg.jpg); background-repeat :repeat-x;
        border: green; padding : 3px 16px;
        color :white;"/></form>';
    
    echo "<br>";
    echo "<br>";
    echo "<br>";
    
   
    print '<form action="quotation_sheet.php"><input type="submit" value="Create Quotation Sheet" style=" float:right; background-image:url(/img/green-tittle-bg.jpg); background-repeat :repeat-x;
        border: green; padding : 3px 16px;
        color :white;"/></form>';
    
    echo "<br>";
    echo "<br>";
  print '<form action="fan_submital.php"><input type="submit" value="Fan Submital" style=" float:right; background-image:url(/img/green-tittle-bg.jpg); background-repeat :repeat-x;
        border: green; padding : 3px 16px;
        color :white;"/></form>'; 
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
$_SESSION['fan_selected']=$fan_selected;
$_SESSION['inlet_static_pressure'] = $_GET['inlet-static-pressure'];
$_SESSION['outlet_static_pressure'] = $_GET['outlet-static-pressure'];
$_SESSION['relative_density'] = $relative_density;
$_SESSION['Volume-flow-unit'] = $volume_flow_unit;
$_SESSION['sp_units'] = $sp_unit;
$_SESSION['fan_dia'] = $fan_details['diameter'];
$_SESSION['outlet_velocity'] = $fan_details['outlet_velocity'];

print '<div id="as">';
print '<img src ="/fan_selection/fan_select/fan_chart.php">';
print '</div>'; 

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