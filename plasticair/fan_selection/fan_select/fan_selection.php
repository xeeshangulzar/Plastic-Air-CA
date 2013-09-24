<?php
  include $_SERVER['DOCUMENT_ROOT']."/inc/header_fanSelect.php";


  $fancurve_data = 1;
  if(isset($_SESSION['motor_data'])){ //unset motor_data previously saved
  unset($_SESSION['motor_data']);
  }
  header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
  //header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>

<script>
  function showFanmodel(str)
  {
  if (str==""){
  document.getElementById("fan-model").innerHTML='<option value="">--select Fan type--</option>';
  return;
  }
  
  if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
  else{// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  
  xmlhttp.onreadystatechange=function(){
  if (xmlhttp.readyState==4 && xmlhttp.status==200){
    document.getElementById("fan-model").innerHTML=xmlhttp.responseText;
    }
  }
  xmlhttp.open("GET","fan_models.php?mount_type="+str,true);
  xmlhttp.send();
  }
</script>




<div style="text-align: center">
    <h2><u>Plasticair Incorparated</u></h2>
    <h3><u>Specialists in Fan Engineering And Pollution Control Equipment</u></h3>
</div>
<hr>

<form name="fan-select-properties" id="fanselectionform" method ="get" action="form-values.php">
  <table>
    <tr>
      <td>Project *: </td>
      <td> <input name="project_user" autocomplete="off" type="text" size="40" value="<?php if(isset($_SESSION['ProjectNameUser'])){echo  $_SESSION['ProjectNameUser'];} else echo 'Plasticair'; ?>" required ></td>
    </tr>
    
    <tr>
      <td>
        Project Location City: </td><td><input autocomplete="off" name="project_location_city_user" value="<?php if(isset($_SESSION['project_city'])){echo $_SESSION['project_city'];} else echo 'Lahore'; ?>" type="text" size="10"></input> State/Province : <input name="project_state_user" value="<?php if(isset($_SESSION['project_state'])){ echo $_SESSION['project_state'];} else echo 'Punjab'; ?>" type="text" size="10"></input>
        Country:</td><td> <input autocomplete="off" name="country_project_user"  value="<?php if(isset($_SESSION['project_country'] )){ echo $_SESSION['project_country'] ;} else echo 'Pakistan'; ?> " type="text" size="10"> </input>
      </td>
    </tr>
        
    <tr><td>Tag Number *: </td><td><input  autocomplete="off" value="<?php if(isset ($_SESSION['tagNumberUser'])){ echo $_SESSION['tagNumberUser'];} else echo '43560'; ?>" name="tagnumber_user" size="40" type="text" required></td></tr>
    <tr><td>Engineer: </td><td> <input  autocomplete="off" value="<?php if(isset($_SESSION['engineer_user'])){echo $_SESSION['engineer_user'];} else echo 'Iqbal Azeem'; ?>" name="engineer_user" size="40" type="text"></td></tr>
    <tr>
      <td>Engineer Location City : </td>
      <td><input  autocomplete="off" value="<?php if(isset($_SESSION['engineer_city'])){ echo $_SESSION['engineer_city'];} else echo 'Lahore'; ?> " name="engineer_location_city_user" size="10" type="text"></input> State/Province: <input name="engineer_state_user"  value="<?php if(isset($_SESSION['engineer_state'])){ echo $_SESSION['engineer_state'];} else echo  'Punjab'; ?>" size="10" type="text"></input>Country: </td>
      <td><input autocomplete="off" value="<?php if(isset($_SESSION['engineer_country'])){ echo $_SESSION['engineer_country']; } else echo 'Pakistan'; ?>" name="engineer_country_user" size="10" type="text"></input></td>
     
    <tr>
      <td>Contractor: </td><td><input autocomplete="off" name="contractor_user" value="<?php if(isset($_SESSION['contractor_user'])){echo $_SESSION['contractor_user'];} else echo 'Contractor ABCD'; ?>" size="40" type="text"></td>
    </tr>
         
    <tr>
      <td>Plasticair Sales Rep: </td><td><input autocomplete="off" name="sales_representative_user"  value="<?php if(isset($_SESSION['sales_representative_user'])){echo $_SESSION['sales_representative_user'];} else echo 'jacquline'; ?>" size="40" type="text"></td>
    </tr>
  </table>

<hr>

<!-- ##################################################-->
<div><h2>Plastic Air Fan Selection Program</h2></div>

<?php 
if(isset($_SESSION['fan_flow_units_user'])){
$fan_units = $_SESSION['fan_flow_units_user'];
 echo  $_SESSION['fan_flow_units_user'];
}
else $fan_units = 'imperial';


  if($fan_units=='metric'){
    
?>  <!--start of if session units are metric-->
<table>
  <tr>
    <td></td>
    <td>
      <strong>Units </strong> 
      <input type="radio" id="fan_units_imperial_id" class="fan_flow_rate_units" name="fan_selection_units" value="imperial" <?php //  if(isset($_SESSION['fan_flow_units_user'])=='metric'){ echo "";}else {echo "checked";}  ?> > Imperial</input>
      <input type="radio"  id="fan_units_metric_id" class="fan_flow_rate_units" name="fan_selection_units" value="metric" <?php  // if(isset($_SESSION['fan_flow_units_user'])=='metric'){ echo "checked";}else {echo "";}  ?> checked> Metric</input>
    </td>
  </tr>
    
  <tr>
    <td>Required Fan CFM *: </td>
    <td>
      <input id="req_cfm_user_value_id" class="testclasscfm" name="req_cfm" type="text"  size="25" autocomplete="off" required value="
       <?php if (isset($_SESSION['req_volume']))
        {echo round($_SESSION['req_volume'],0);} 
      else echo '31780';?>" required> </td>
      
     <?php echo $_SESSION['Volume-flow-unit']; ?>
    <td><select name="Volume-flow-units" id="req_cfm_units_id">
      
        <?php if(isset($_SESSION['Volume-flow-unit'])){
          ?>
        
      <option value="l/sec" <?php if($_SESSION['Volume-flow-unit']=='l/sec') echo 'selected'; ?>>l/sec</option>
      <option value="m^3/sec"  <?php if($_SESSION['Volume-flow-unit']=='m^3/sec') echo 'selected'; ?> >m^3/sec </option>
      <option value="m^3/min"  <?php if($_SESSION['Volume-flow-unit']=='m^3/min') echo 'selected'; ?>>m^3/min</option>
      <option value="m^3/hour" <?php if($_SESSION['Volume-flow-unit']=='m^3/hour') echo 'selected'; ?> >m^3/hour</option>
      <?php }
      
      else {?>
      <option value="l/sec">l/sec</option>
      <option value="m^3/sec">m^3/sec</option>
      <option value="m^3/min">m^3/min</option>
      <option value="m^3/hour" >m^3/hour</option>
      
      <?php
      
            }?>
      
       </select>
     </td>
  </tr>
    
  <tr>
    <td>Inlet Static Pressure *</td>
    <td><input name="inlet-static-pressure"  id="inlet_static_pressure_id" autocomplete="off" type="text" value="<?php if(isset($_SESSION['inlet_static_pressure'])){echo $_SESSION['inlet_static_pressure'];} else echo '0.002' ?>" size="25" required></td>
    <td><select name="sp_units"  id="sp_units_id" onchange="document.getElementById('sp_unit').innerHTML=this.value">
        <option value="Pa">Pa</option>
        <option value="kpa">Kpa</option>
        <option value="mm H.G."> mm H.G. </option>
        </select>
    </td>
  </tr>
    
  <tr>
    <td>Outlet Static Pressure</td>
    <td><input name="outlet-static-pressure" id="outlet-static-pressure_id" autocomplete="off" type="text" value="<?php if(isset($_SESSION['outlet_static_pressure'])){echo $_SESSION['outlet_static_pressure'];}?>" size="25"></td>
    <td>
      <select id="osp_units_id">
        <option value="Pa">Pa</option>
        <option value="kpa">Kpa</option>
        <option value="mm H.G."> mm H.G. </option>
      </select>
    </td>
  </tr>
    
  <tr>
    <td>Wheel Width *</td>
    <td><input name="wheel-width" id="wheelwidthid"type="number" autocomplete="off" value="<?php if(isset($_SESSION['wheel_width'])){echo $_SESSION['wheel_width'];} else echo '100';?>"  min="50" max="100"  style="width: 180px;" required ></td>
    <td>%</td>
  </tr>
    
  <tr>
    <td>Temperature *</td>
    <td><input name="temperature" id="temperature_id" autocomplete="off" type="text" size="25" value="<?Php if(isset($_SESSION['temperature'])){echo round(($_SESSION['temperature']-32)/1.8,2);} else echo '21.11'; ?>" required></td>
    <td id="temp_units_id"> &degC</td>
  </tr>
    
  <tr>
    <td>Altitude *</td>
     <td><input name="altitude" id="altitude_id" autocomplete="off" type="text" size="25" value="<?php if(isset($_SESSION['altitude'])){echo round($_SESSION['altitude'] * 0.3048,3);} else echo '0' ?>" required></td>
     <td id="alt_units_id">m</td>
  </tr>
    
  <tr>
    <td>Relative Density</td>
    <td>
      <input name="relative-density" id="relative_density_id" autocomplete="off"  type="text" value="<?php  
       //echo $_SESSION['relative_density'].'a';
       if(isset($_SESSION['relative_density'])){ 
         if(round($_SESSION['relative_density'],4)<=0.076 && round($_SESSION['relative_density'],4)>=0.074 ){ 
          echo '0'; 
         }
         else{ 
           echo round($_SESSION['relative_density']/16.018,4);
         }
           }
       else{
         echo '0';
       }
             ?>" size="25">
    </td>
     <td id="relative_units_id" >kg/m^3</td>
  </tr>
    
  <tr>
    <td>Fan Type *</td>
     <?php $sql = "select distinct(mount_type) as mount_type from fan_type";
     $result = mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);?>
     <td>
      <select name="mount-type" onload="showFanmodel(this.value)" onchange="showFanmodel(this.value)" required>
        <option value="" selected="selected">--choose--</option>
        <?php while($mount_type = mysql_fetch_assoc($result)){?>
        <option value ="<?php print $mount_type['mount_type']; ?>"><?php print $mount_type['mount_type']; ?></option>
        <?php }?>
      </select>
     </td>
  </tr>
    
  <tr>
    <td>Fan Model *</td>
    <td>
      <select name="fan-model" id="fan-model" required>
        <option value="">--select Fan type--</option>
      </select>
    </td>
  </tr>
    
  <tr>
    <td>Sound Radiation Type:</td>
    <td>
      <select  name="refelective_user" value="1" >
        <option  default value="1">No reflective surface </option>
        <option value="2"> 1 Reflective Surface </option>
        <option value="3"> 2 Reflective Surface </option>
        <option value="4"> 3 Reflective Surface </option> 
      </select>
     </td>
  </tr>
    
  <tr>
    <td>Sound Level Required at *</td>
    <td><input  autocomplete="off" name="soundDistance_user" id="soundDistance_user_id" value="<?php if(isset($_SESSION['soundDistance_user'])){echo round($_SESSION['soundDistance_user']*0.3048,2);} else echo '0.98'; ?>" size="25"  type="number" min="0.01" step="0.01" style="width:142px" required></input></td><td id="sound_level_units_id" >m</td>
  </tr>
    
  <tr>
    <td>Fan Orientation</td> 
    <td>
      <select name="fanOrientation_user" value="CCW 360" >
        <option value="CCW 45"> CCW 45</option>
        <option value="CCW 90">CCW 90</option>
        <option Value="CCW 270">CCW 270</option>
        <option Value="CCW 315">CCW 315</option>
        <option Value="CCW 360">CCW 360</option>
        <option Value="CW 45">CW 45</option>
        <option Value="CW 90">CW 90</option>
        <option value="CW 270">CW 270</option>
        <option value="CW 315">CW 315</option>
        <option value="CW 360">CW 360</option>
      </select>
    </td>
  </tr>
    
  <tr>
    <td>AMCA Drive arrangement</td> 
    <td>
      <select name="AmcaArr_user" value="1">
        <option value="1">AMCA Arr #1 </option>
        <option value="4">AMCA Arr #4</option>
        <option value="8">AMCA Arr #8</option>
        <option value="9">AMCA Arr #9</option>
        <option value="10">AMCA Arr #10</option>
       </select>
    </td>
  </tr>
  <input type="hidden" value="1" name="fan_selection_turn"> 
    
 
    <td><input type="submit" value="GO" size="50" style="background-image: url(/img/green-tittle-bg.jpg);background-repeat: repeat-x; border: green; padding: 3px 16px; color: white;"></td>
  </tr>
</table>
  

<?php } //!--End of if session units are metric-->

else { ?> <!-- start if session units are imperial -->
<table>
  <tr>
    <td></td>
    <td>
      <strong>Units </strong> 
      <input type="radio" id="fan_units_imperial_id" class="fan_flow_rate_units" name="fan_selection_units" value="imperial"  checked> Imperial</input>
      <input type="radio"  id="fan_units_metric_id" class="fan_flow_rate_units" name="fan_selection_units" value="metric" > Metric</input>
    </td>
  </tr>
    
  <tr>
    <td>Required Fan CFM *: </td>
    <td>
      <input id="req_cfm_user_value_id" class="testclasscfm" name="req_cfm" type="text"  size="25" autocomplete="off" value="<?php 
      if (isset($_SESSION['req_volume']))
        {echo round($_SESSION['req_volume'],0);} 
      else echo '15000';  ?> " required> 
      
      
    </td>
    <td><select name="Volume-flow-units" id="req_cfm_units_id">
       <option value="CFM">CFM</option>
<!--   <option>m^3/hour</option>
       <option>m^3/min</option>
       <option>m^3/sec</option>
       <option>l/sec</option>-->
        </select>
     </td>
  </tr>
    
  <tr>
    <td>Inlet Static Pressure *</td>
    <td><input name="inlet-static-pressure"  id="inlet_static_pressure_id" autocomplete="off" type="text" value="<?php if(isset($_SESSION['inlet_static_pressure'])){echo $_SESSION['inlet_static_pressure'];} else echo '0.5' ?>" size="25" required></td>
    <td><select name="sp_units"  id="sp_units_id" onchange="document.getElementById('sp_unit').innerHTML=this.value">
      <option>In. W.G.</option><!--
      <option>Pa</option>
-->   <option>in. H.G.</option><!--
      <option>mm h.g</option>
      <option>Kpa</option>-->
        </select>
    </td>
  </tr>
    
  <tr>
    <td>Outlet Static Pressure</td>
    <td><input name="outlet-static-pressure" id="outlet-static-pressure_id" autocomplete="off" type="text" value="<?php if(isset($_SESSION['outlet_static_pressure'])){echo $_SESSION['outlet_static_pressure'];}?>" size="25"></td>
    <td>
      <select id="osp_units_id">
        <option>In. W.G.</option>
        <option>In. H.G.</option>
      </select>
    </td>
  </tr>
    
  <tr>
    <td>Wheel Width *</td>
    <td><input name="wheel-width" id="wheelwidthid"type="number" autocomplete="off" value="<?php if(isset($_SESSION['wheel_width'])){echo $_SESSION['wheel_width'];} else echo '100';?>" min="50"  max="100" required  style="width: 180px;" ></td>
    <td>%</td>
  </tr>
    
  <tr>
    <td>Temperature *</td>
    <td><input name="temperature" id="temperature_id" autocomplete="off" type="text" size="25" value="<?Php if(isset($_SESSION['temperature'])){echo round($_SESSION['temperature'],0);} else echo '70'; ?>" required></td>
    <td id="temp_units_id"> &degF</td>
  </tr>
    
  <tr>
    <td>Altitude *</td>
     <td><input name="altitude" id="altitude_id" autocomplete="off" type="text" size="25" value="<?php if(isset($_SESSION['altitude'])){echo $_SESSION['altitude'];} else echo '0' ?>" required></td>
     <td id="alt_units_id">ft</td>
  </tr>
    
  <tr>
    <td>Relative Density</td>
    <td><input name="relative-density" id="relative_density_id" autocomplete="off"  type="text" value="<?php  
    if(isset($_SESSION['relative_density'])){ 
      if(round($_SESSION['relative_density'],3)==0.076 && round($_SESSION['relative_density'],3)==0.075 ) 
         echo '0'; 
      else 
        echo round($_SESSION['relative_density'],3);}
        
   else echo '0'; ?>" size="25"></td>
     <td id="relative_units_id" >lb/ft^3</td>
  </tr>
    
  <tr>
    <td>Fan Type *</td>
     <?php $sql = "select distinct(mount_type) as mount_type from fan_type";
     $result = mysql_query($sql) or die ("MySQL err: ".mysql_error()."<br />".$sql);?>
     <td>
      <select name="mount-type" onload="showFanmodel(this.value)" onchange="showFanmodel(this.value)" required>
        <option value="" selected="selected">--choose--</option>
        <?php while($mount_type = mysql_fetch_assoc($result)){?>
        <option value ="<?php print $mount_type['mount_type']; ?>"><?php print $mount_type['mount_type']; ?></option>
        <?php }?>
      </select>
     </td>
  </tr>
    
  <tr>
    <td>Fan Model *</td>
    <td>
      <select name="fan-model" id="fan-model" required>
        <option value="">--select Fan type--</option>
      </select>
    </td>
  </tr>
    
  <tr>
    <td>Sound Radiation Type:</td>
    <td>
      <select  name="refelective_user" value="1" >
        <option  default value="1">No reflective surface </option>
        <option value="2"> 1 Reflective Surface </option>
        <option value="3"> 2 Reflective Surface </option>
        <option value="4"> 3 Reflective Surface </option> 
      </select>
     </td>
  </tr>
    
  <tr><td>Sound Level Required at *</td><td><input  autocomplete="off" name="soundDistance_user" id="soundDistance_user_id" value="<?php if(isset($_SESSION['soundDistance_user'])){echo round($_SESSION['soundDistance_user'],2);} else echo '3.2'; ?>" size="25"  type="number" min="0.01" step="0.01" style="width:142px" required></input></td><td id="sound_level_units_id">ft</td></tr>
    
  <tr>
    <td>Fan Orientation</td> 
    <td>
      <select name="fanOrientation_user" value="CCW 360" >
        <option value="CCW 45"> CCW 45</option>
        <option value="CCW 90">CCW 90</option>
        <option Value="CCW 270">CCW 270</option>
        <option Value="CCW 315">CCW 315</option>
        <option Value="CCW 360">CCW 360</option>
        <option Value="CW 45">CW 45</option>
        <option Value="CW 90">CW 90</option>
        <option value="CW 270">CW 270</option>
        <option value="CW 315">CW 315</option>
        <option value="CW 360">CW 360</option>
      </select>
    </td>
  </tr>
    
  <tr>
    <td>AMCA Drive arrangement</td> 
    <td>
      <select name="AmcaArr_user" value="1">
        <option value="1">AMCA Arr #1 </option>
        <option value="4">AMCA Arr #4</option>
        <option value="8">AMCA Arr #8</option>
        <option value="9">AMCA Arr #9</option>
        <option value="10">AMCA Arr #10</option>
       </select>
    </td>
  </tr>
  <input type="hidden" value="1" name="fan_selection_turn"> 
    
 
    <td><input type="submit" value="GO" size="50" style="background-image: url(/img/green-tittle-bg.jpg);background-repeat: repeat-x; border: green; padding: 3px 16px; color: white;"></td>
  </tr>
</table>







<?php } ?>
<!--end of session units are imperial-->
</form>




<!--Changing Units using Jquery-->



<!--<script>
$(".testclasscfm").keyup(function () {
var value = $(this).val();
$("p").text(value);
//$('#req_cfm_user_value_id').val('2000');

}).keyup();
</script>-->

<script>
    $(document).ready(function() {
      
  // document.getElementById("fan_units_metric_id").checked=true;  
  $(".fan_flow_rate_units").click(function(){
  
  if(this.id == "fan_units_metric_id"){
  //$("#req_cfm_units_id").html("<option value='CFM' >CFM</option><option>2</option>");
    $("#req_cfm_units_id").html("<option value='l/sec' >l/sec</option><option value='m^3/sec'>m^3/sec</option><option value='m^3/min'>m^3/min</option><option value='m^3/hour'>m^3/hour</option>");
    var cfm_calculated = $('#req_cfm_user_value_id').val() / 0.472;
    $('#req_cfm_user_value_id').val(cfm_calculated .toFixed(0));
    $("#sp_units_id").html("<option>Pa</option><option>Kpa</option><option>mm H.G.</option>");
    $("#osp_units_id").html("<option>Pa</option><option>Kpa</option><option>mm H.G.</option>");
    $("#temp_units_id").html("&degC");
    $("#alt_units_id").html("m");
    $("#sound_level_units_id").html("m");
    $("#relative_units_id").html("kg/m^3");
    var osp_calculated = $('#outlet-static-pressure_id').val() / 248.36;
    $('#outlet-static-pressure_id').val(osp_calculated .toFixed(4));
    var isp_calculated = $('#inlet_static_pressure_id').val() / 248.36;
    $('#inlet_static_pressure_id').val(isp_calculated .toFixed(4));
    var temp_calculated = ($('#temperature_id').val() - 32) / 1.800; 
    $('#temperature_id').val(temp_calculated .toFixed(2));
    var altitude_calculated = ($('#altitude_id').val() * 0.3048 ) ; 
    $('#altitude_id').val(altitude_calculated.toFixed(2));
    var sound_distance_calculated = ($('#soundDistance_user_id').val() * 0.3048 ) ; 
    $('#soundDistance_user_id').val(sound_distance_calculated.toFixed(2));
    var relative_density_calculated = ($('#relative_density_id').val() / 16.018 ) ;  // to kg/m^3
    $('#relative_density_id').val(relative_density_calculated.toFixed(4)); 
    
    
    
    }
  else if(this.id == "fan_units_imperial_id"){
    $("#req_cfm_units_id").html("<option value='cfm' >CFM</option>");
    $("#sp_units_id").html("<option>In. W.G.</option><option>In. H.G.</option>");
    $("#osp_units_id").html("<option>In. W.G.</option><option>In. H.G.</option>");
    $("#temp_units_id").html("&degF");
    $("#alt_units_id").html("ft");
    $("#sound_level_units_id").html("ft");
    $("#relative_units_id").html("lb/ft^3");
    var cfm_calculated = $('#req_cfm_user_value_id').val() * 0.472;
    $('#req_cfm_user_value_id').val(cfm_calculated .toFixed(0));
    var osp_calculated = $('#outlet-static-pressure_id').val() * 248.36;
    $('#outlet-static-pressure_id').val(osp_calculated .toFixed(2));
    var isp_calculated = $('#inlet_static_pressure_id').val() * 248.36;
    $('#inlet_static_pressure_id').val(isp_calculated .toFixed(2));
    var temp_calculated = ($('#temperature_id').val() * 1.8) + 32; 
    $('#temperature_id').val(temp_calculated .toFixed(2));
    var altitude_calculated = ($('#altitude_id').val() / 0.3048 ) ; 
    $('#altitude_id').val(altitude_calculated.toFixed(2));
    var sound_distance_calculated = ($('#soundDistance_user_id').val() / 0.3048 ) ; 
    $('#soundDistance_user_id').val(sound_distance_calculated.toFixed(2));
    if($('#relative_density_id').val()==0.075){
      var relative_density_calculated = 0; 
    } 
    else {
      var relative_density_calculated = $('#relative_density_id').val()* 16.018;
    } 
    $('#relative_density_id').val(relative_density_calculated.toFixed(2));
        
       
    }
  
});
    });
</script>

<?php
//session_destroy();
include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php";?>




