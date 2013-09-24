<?php
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";


$req_static_pressure = $_GET['inlet-static-pressure']+$_GET['outlet-static-pressure'];
$required_cfm = $_GET['req_cfm'];
$fan_model = $_GET['fan-model'];
$wheel_width = $_GET['wheel-width'];

$total_suitable_fan = array();  // contains list of all suitable fans for selected requirements as $total_suitable_fan[fan_name][outlet_velocity].....
$rpm_table = array();           //contains all rpm table of all fans as $rpm_table[fan_name][row_num][rpm],$rpm_table[fan_name][row_num][sp] ...

if($req_static_pressure > 0){
    $sSQL1 = "select * from fan_specs where fan_series='$fan_model'";
    $result=mysql_query($sSQL1) or die ("MySQL err: ".mysql_error()."<br />".$sSQL1);
    
    $ratio_v_by_wov = 0.485; // Given ratio of volume/wide open volume;
    $max_allowed_tip_speed = 18000;
    $max_allowed_outlet_velocity = 6500;
    
// Main while loop to fetch given constant values of fan
    while($fan_constants=mysql_fetch_assoc($result)){
        $amca_test_number = $fan_constants['amca_test'];
        $sSQL2 = "select * from fan_tech_detail where amca_test='$amca_test_number' order by cfm desc";
        $result2=mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br />".$sSQL2);
        $first_iteration_test = true;
        $sp_limit_found = false; // check if static pressure upper and lower limit found
        $i=0;
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
                if($fan_constants['fan_name'] == $fan_tested_data['fan_name']){
                $rpm_table[$fan_constants['fan_name']][$i]['rpm'] = $fan_constants['test_rpm'] * ($required_cfm / $fan_tested_data['cfm']);
                $rpm_table[$fan_constants['fan_name']][$i]['sp'] = $fan_tested_data['static_pressure'] * pow(($rpm_table[$fan_constants['fan_name']][$i]['rpm']/$fan_constants['test_rpm']),2);
                $rpm_table[$fan_constants['fan_name']][$i]['hp'] = $fan_tested_data['hp'] * pow(($rpm_table[$fan_constants['fan_name']][$i]['rpm']/$fan_constants['test_rpm']),3);
                }
                // fixed for adjusted perfomance of 100%
                else{
                    $adjusted_perfomance['cfm'] = (pow(($fan_constants['wheel_dia']/$base_fan_constants['wheel_dia']),3) * $fan_tested_data['cfm'])*($wheel_width/100);
                    $adjusted_perfomance['sp'] = (pow(($fan_constants['wheel_dia']/$base_fan_constants['wheel_dia']),2) * $fan_tested_data['static_pressure'])*pow(($wheel_width/100),0.147);
                    $adjusted_perfomance['hp'] = (pow(($fan_constants['wheel_dia']/$base_fan_constants['wheel_dia']),5) * $fan_tested_data['hp'])*($wheel_width/100);

                    $rpm_table[$fan_constants['fan_name']][$i]['rpm'] = $fan_constants['test_rpm'] * ($required_cfm / $adjusted_perfomance['cfm']);
                    $rpm_table[$fan_constants['fan_name']][$i]['sp'] = $adjusted_perfomance['sp'] * pow(($rpm_table[$fan_constants['fan_name']][$i]['rpm']/$fan_constants['test_rpm']),2);
                    $rpm_table[$fan_constants['fan_name']][$i]['hp'] = $adjusted_perfomance['hp'] * pow(($rpm_table[$fan_constants['fan_name']][$i]['rpm']/$fan_constants['test_rpm']),3);
                }
                
                
                
//                if($rpm_table[$fan_constants['fan_name']][$i]['sp'] > $req_static_pressure){
//                    if($sp_upper_limit == null || $sp_upper_limit > $rpm_table[$fan_constants['fan_name']][$i]['sp']){
//                        $sp_upper_limit = $rpm_table[$fan_constants['fan_name']][$i]['sp'];
//                        $current_row_data = $rpm_table[$fan_constants['fan_name']][$i];
//                    }
//                }
//                else{
//                    if($sp_lower_limit == null || $sp_lower_limit > $rpm_table[$fan_constants['fan_name']][$i]['sp']){
//                        $sp_lower_limit = $rpm_table[$fan_constants['fan_name']][$i]['sp'];
//                        $previous_row_data = $rpm_table[$fan_constants['fan_name']][$i];
//                    }
//                }
//                      $i++;         
                    
                    
                if($req_static_pressure > $rpm_table[$fan_constants['fan_name']][$i]['sp'] && $sp_limit_found == false){
                    $previous_row_data = $rpm_table[$fan_constants['fan_name']][$i];
                }
                elseif($req_static_pressure < $rpm_table[$fan_constants['fan_name']][$i]['sp'] && $sp_limit_found == false){ // if static pressure is in b/w previous row data and current row data
                    $current_row_data = $rpm_table[$fan_constants['fan_name']][$i]; 
                    $sp_limit_found = true;

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
                        $fan_is_suitable = true;
                        $total_suitable_fan[$fan_constants['fan_name']] = $suitable_fan_detail;
                    }
                }
            }
            $i++;
//             if($sp_upper_limit != null && $sp_lower_limit != null){
//            $calculated_rpm = (((($req_static_pressure-$previous_row_data['sp'])*($current_row_data['rpm']-$previous_row_data['rpm']))/($current_row_data['sp']-$previous_row_data['sp']))+$previous_row_data['rpm']);
//            $calculated_bhp = (((($req_static_pressure-$previous_row_data['sp'])*($current_row_data['hp']-$previous_row_data['hp']))/($current_row_data['sp']-$previous_row_data['sp']))+$previous_row_data['hp']);
//            $calculated_efficiency = (($required_cfm * $req_static_pressure)/(6362 * $calculated_bhp))*100;
//            $calculated_tip_speed = (2*M_PI)*(($fan_constants['wheel_dia']/2)/12)*$calculated_rpm;
//            $calculated_outlet_velocity = $required_cfm/$fan_constants['outlet_area'];
//            $wide_open_volume = ($calculated_rpm/$fan_constants['test_rpm'])*$adjusted_perfomance_first_cfm;
//            if(($required_cfm/$wide_open_volume)>$ratio_v_by_wov && $calculated_tip_speed <= $max_allowed_tip_speed && $calculated_outlet_velocity < $max_allowed_outlet_velocity){
//                $suitable_fan_detail['rpm'] = $calculated_rpm;
//                $suitable_fan_detail['bhp'] = $calculated_bhp;
//                $suitable_fan_detail['efficiency'] = $calculated_efficiency;
//                $suitable_fan_detail['diameter'] = $fan_constants['wheel_dia'];
//                $suitable_fan_detail['outlet_area'] = $fan_constants['outlet_area'];
//                $suitable_fan_detail['tip_speed'] = $calculated_tip_speed;
//                $suitable_fan_detail['outlet_velocity'] = $calculated_outlet_velocity;
//                $total_suitable_fan[$fan_constants['fan_name']] = $suitable_fan_detail;
//            }  
//        }
         
        }
    }    
    foreach($total_suitable_fan as $fan_name=>$fan_details) $temp[$fan_name] = $fan_details['efficiency'];
    array_multisort($temp, SORT_DESC, $total_suitable_fan);     //sorting arrays with efficiency
}


//echo"<pre>"; print_r($rpm_table); echo"</pre>";

?>

<table border="1" style="border-spacing: 0px;">
    <thead style="font-weight: bold;">
        <td>Fan Size</td>
        <td>Wheel diameter(inches)</td>
        <td>Outlet Area(ft^2)</td>
        <td>RPM</td>
        <td>Tip speed(f/min)</td>
        <td>Fan Power(BHP)</td>
        <td>Fan Static Efficiency</td>
        <td>Outlet velocity(f/min)</td>
        <td>Sound Pressure at 3.2ft</td>
    </thead>
 <?php  foreach($total_suitable_fan as $fanname=>$fan_details){ ?>
    <tr>
        <td><?php print $fanname; ?></td>
        <td><?php print round($fan_details['diameter'],5); ?></td>
        <td><?php print round ($fan_details['outlet_area'],5); ?></td>
        <td><?php print round($fan_details['rpm'],5); ?></td>
        <td><?php print round($fan_details['tip_speed'],5);?></td>
        <td><?php print round($fan_details['bhp'],5); ?></td>
        <td><?php print round($fan_details['efficiency'],5).'%'; ?></td>
        <td><?php print round($fan_details['outlet_velocity'],5); ?></td>
        <td>49.75</td>
    </tr>
    <?php } ?>
    
</table>



<?php include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php"; ?>