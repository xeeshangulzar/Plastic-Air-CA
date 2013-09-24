<?php
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

?>



<?php
$projectNameUser=$_SESSION['ProjectNameUser'];
$tagNumberUser=$_SESSION['tagNumberUser'];
$fan_selected=$_SESSION['fan_selected'];
$fan_dia = $_SESSION['fan_dia'];
$fan_curve_data = $_SESSION['fan_curve_data'];
$req_sp = $_SESSION['req_sp'];
$required_volume_flow = $_SESSION['req_volume'] ;
$tip_speed = $_SESSION['tip_speed'];
$wheel_width = $_SESSION['wheel_width'];
$static_pressure = $_SESSION['static_pressure'];
$fan_bhp = $_SESSION['fan_detail_bhp'];
$altitude = $_SESSION['altitude'];
$fan_rpm = $_SESSION['fan_detail_rpm'];
$temprature = $_SESSION['temperature'];
$fan_model = $_SESSION['fan_model'];
$inlet_pressure=$_SESSION['inlet_static_pressure'];
$outlet_pressure = $_SESSION['outlet_static_pressure'];
$relative_density= $_SESSION['relative_density'];
$user_relative_density = $_SESSION['user_relative_density'];
$volume_flow_units=$_SESSION['Volume-flow-unit'];
$sp_units = $_SESSION['sp_units'];
$outlet_velocity = $_SESSION['outlet_velocity'];
$amcaArr_user=$_SESSION['amcaArr_user'];
$fanOrientation_user=$_SESSION['fanOrientation_user'];
$sales_rep=$_SESSION['sales_representative_user'];
$fan_selection_units = $_SESSION['fan_selection_units'];

?>


<table border="0">
  <tr>
    <td valign="top">
      <table  border="0">
        <tr> 
          <td> <img src="../../img/red_logo.png"></td>
        </tr>         
      </table>
    </td>
        
    <td width="500">
      <table border="0">
        <tr>
          <td  height="50 "width="700" style="text-align: center" > <font size="6" color="blue" >Plasticair <?php echo "$fan_selected";?> Fan </font></td>
          <td width="150"></td>
        </tr>
      </table>
            
      <table>
        <tr>
          <td width="500px">
            <table>
              <tr>
                <td><strong>Project:</strong> <?php echo $projectNameUser ?> </td>
              </tr>
              <tr>
                <td><?php echo $_SESSION['project_location'] ;?></td>
              </tr>
              <tr>
                <td><strong>Engineer: </strong><?php print $_SESSION['engineer_user'];?></td>
              </tr>
              <tr>
                <td><?php print $_SESSION['engineer_location'];?></td>
              </tr>
              <tr><td><strong>Contractor: </strong><?php print $_SESSION['contractor_user'];?></td></tr>
              <tr><td><strong>Plasticair Sales Rep:</strong> <?php echo $sales_rep; ?> </td></tr>
             </table>
           </td>
           <td width="225px" valign="top">
             <table>
               <tr><td><strong>Date: </strong><?php echo date("F d, Y"); ?></td></tr>
               <tr><td><Strong>Fan Tag:</strong> <?php echo $tagNumberUser; ?></td></tr>
               <tr><td><strong>Fan Model: </strong><?php echo "$fan_selected";?> </td></tr>
             </table>
           </td>
         </tr>
       </table>
     </td>
   </tr>

</table>
<br>
<hr>


<!--*****************************If fan units selection is imperial****************************************************-->
<?php 
  if($fan_selection_units!='metric'){ ?> 
    <table border="0" style="border-color: solid black">
      <tr>
        <td  style="text-align: right;"><strong>Wheel Ø:</strong></td><td><?php print round($fan_dia,2); ?> </td>
        <td> inches </td><td  width="120" style="text-align: right"><strong>Wheel Width : </strong></td>
        <td><?php  print $_SESSION['wheel_width']; ?></td>
        <td>%</td>
        <td width="150" style="text-align: right"><strong>Arr:</strong></td>
        <td> <?php print 'AMCA ' ?></td>
        <td> <?php echo 'Arr. # '.round($amcaArr_user,1)?></td>
      </tr>

      <tr>
        <td  style="text-align: right;"><strong>Volume / Fan :</strong></td>
        <td><?php print round($required_volume_flow,0) ?></td>
        <td><?php print $volume_flow_units; ?> </td>
        <td  width="120" style="text-align: right"><strong>Fan Power :</strong></td>
        <td><?php if($fan_selection_units!='metric') print round($fan_bhp,2); else print round($fan_bhp * 0.745699872,2); ?></td>
        <td><?php if($fan_selection_units !='metric') echo 'BHP'; else echo 'KW'; ?> </td>
        <td width="150" style="text-align: right"><strong>Altitude :</strong></td>
        <td><?php print round($altitude,1); ?></td>
        <td>ft</td>
      </tr>

      <tr>
        <td style="text-align: right"><strong>Inlet Pressure :</strong></td>
        <td><?php print round($inlet_pressure,2); ?> </td>
        <td><?php print $sp_units ?> </td>
        <td style="text-align: right"><strong>Fan Speed :</strong></td>
        <td><?php print round($fan_rpm,0); ?> </td>
        <td>RPM</td>
        <td style="text-align: right"><Strong>Temperature :</strong></td>
        <td><?php print round($temprature,2);?></td>
        <td><?php if($fan_selection_units!='metric') print '&deg F'; else print '&deg C'; ?></td>
      </tr>
      
      <tr>
        <td style="text-align: right"><strong>Outlet Pressure :</strong></td>
        <td><?php print round($outlet_pressure,2) ?> </td>
        <td></td>
        <td style="text-align: right"><strong>Tip Speed :</strong></td>
        <td><?php print round($tip_speed,0) ; ?></td>
        <td>Ft/Min</td>
        <td style="text-align: right" ><Strong>Relative Density :</strong></td>
        <td><?php  if( $_SESSION['relative_density'] >= 0.074 && $_SESSION['relative_density'] <= 0.076  ){echo 0;}else{ echo round($_SESSION['relative_density'],7);} ?></td>
        <td> 	lb/ft^2 </td>
      </tr>
      
      <tr>
        <td style="text-align: right"><strong>Fan E.S.P. :</strong></td>
        <td><?php print round($inlet_pressure,2)+round($outlet_pressure,2); ?> </td>
        <td><?php print $sp_units ?></td>
        <td style="text-align: right" ><strong>Outlet Velocity :</strong></td>
        <td><?php print round($outlet_velocity,0) ?></td>
        <td>Ft/Min</td>
        <td style="text-align: right" ><strong>Actual Inlet Density :</strong></td>   
        <td> <?php print round($relative_density,3); ?> </td>
        <td>lb/ft^2</td>
      </tr>
  </table>
          
<?php }
// ***********************************if fan units selection is metric ****************************************
else { ?>

    <table border="0" style="border-color: solid black">
      <tr>
        <td  style="text-align: right;"><strong>Wheel Ø:</strong></td><td><?php print round($fan_dia,2); ?> </td>
        <td> inches </td><td  width="120" style="text-align: right"><strong>Wheel Width : </strong></td>
        <td><?php  print $_SESSION['wheel_width']; ?></td>
        <td>%</td><td width="150" style="text-align: right"><strong>Arr:</strong></td>
        <td> <?php print 'AMCA ' ?></td>
        <td><?php echo 'Arr. #'.$amcaArr_user?></td>
      </tr>

      <tr>
        <td  style="text-align: right;"><strong>Volume / Fan :</strong></td>
        <td><?php print round($required_volume_flow,0) ?></td>
        <td><?php print $volume_flow_units; ?> </td>
        <td  width="120" style="text-align: right"><strong>Fan Power :</strong></td>
        <td><?php print round($fan_bhp * 0.745699872,2); ?></td>
        <td><?php echo 'KW'; ?> </td>
        <td width="150" style="text-align: right"><strong>Altitude :</strong></td>
        <td><?php print round($altitude * 0.3048,2); ?></td>
        <td>m</td>
      </tr>

      <tr>
        <td style="text-align: right"><strong>Inlet Pressure :</strong></td>
        <td><?php print round($inlet_pressure,4); ?> </td>
        <td><?php print $sp_units ?> </td>
        <td style="text-align: right"><strong>Fan Speed :</strong></td>
        <td><?php print round($fan_rpm,0); ?> </td>
        <td>RPM</td>
        <td style="text-align: right"><Strong>Temperature :</strong></td>
        <td><?php print round(($temprature-32)/1.8,2);?></td>
        <td>&deg C</td>
      </tr>
      
      <tr>
        <td style="text-align: right"><strong>Outlet Pressure :</strong></td>
        <td><?php print round($outlet_pressure,4) ?> </td>
        <td></td>
        <td style="text-align: right"><strong>Tip Speed :</strong></td>
        <td><?php print round($tip_speed * 0.00508,2) ; ?></td>
        <td>m/sec</td>
        <td style="text-align: right" ><Strong>Relative Density :</strong></td>
        <td><?php  if(  $_SESSION['relative_density'] >= 0.074 && $_SESSION['relative_density'] <= 0.076){echo 0;}else{echo round($_SESSION['relative_density'] / 16.018,3);} ?></td>
        <td> 	kg/m^3 </td>
      </tr>
      
      <tr>
        <td style="text-align: right"><strong>Fan E.S.P. :</strong></td>
        <td><?php print round($inlet_pressure,4)+round($outlet_pressure,4); ?> </td>
        <td><?php print $sp_units ?></td>
        <td style="text-align: right" ><strong>Outlet Velocity :</strong></td>
        <td><?php print round($outlet_velocity * 0.00508,2) ?></td>
        <td>m/sec</td>
        <td style="text-align: right" ><strong>Actual Inlet Density :</strong></td>   
        <td> <?php print round($relative_density / 16.018,3); ?> </td>
        <td>kg/m^3</td>
      </tr>
  </table>
<?php } ?>
<hr>

<!--***************************creating variable name to get dimensions image***************-->
<?php 
  if($amcaArr_user==1)
  {
    $varImage='GIF-ARR1-';
    $amcaValue=200;
  }   
  elseif($amcaArr_user==4)
  {
    $varImage='GIF-400-SUPPORT-';
    $amcaValue=400;
  }
  elseif($amcaArr_user==8)
  {
    $varImage='GIF-ARR8-';
    $amcaValue=200;
  }
  elseif ($amcaArr_user==9) 
  {
    $varImage='GIF-200-SUPPORT-';
    $amcaValue=200;
  }
  elseif($amcaArr_user==10)
  {
    $varImage='GIF-150-SUPPORT-';
    $amcaValue=150;
  }

?>
<!--************************showing image which are saved in folder DWG************************-->
<p><img src="<?php print 'DWG/'.$varImage.$fanOrientation_user. '.png'; ?>"  width="700" alt=' Could not show image'></img></p>


<p align="center"> 
  <font style="color:red">*</font> 
  Plasticair reserves to rights to change any data due to product research & improvement without notice
</p>


<br>


<?php 
// A B C H J K O Q R S T are fixed dimension selected on fan name only (no external feature changes them)
//echo "<br>"; echo $fan_selected; echo "<br>";
  $sSQL2 = "select * from dimensionFixed where fanName='$fan_selected'";  //retrieving fixed dimensions
  $result=mysql_query($sSQL2) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL2);
  while ($row = mysql_fetch_assoc($result)) 
  {
    $fixedDimension= array($row["A"],$row["B"],$row["C"],$row["H"],$row["J"],$row["K"],$row["O"],$row["Q"],$row["R"],$row["S"], $row["T"] );
//         print_r($fixedDimension);  
  }
//echo"<pre>"; print_r($fixedDimension); echo "<pre>";


  $sSQL3="select * from fanstand where fanName='$fan_selected'"; //  retriving list of possible stands for that fan
  $result3= mysql_query($sSQL3) or die ("Mysql err: ".mysql_error(). "<br>/". $sSQL3);

  while($row=  mysql_fetch_assoc($result3))
  {
    $fan_name_db=$row[$amcaValue];
  }
//echo $fan_name_db. "<br>";

$sSQL1="select * from  standDimension where stand='$fan_name_db'"; 
$result1= mysql_query($sSQL1) or die ("Mysql err: ".mysql_error(). "<br>/". $sSQL1);
while($row=  mysql_fetch_assoc($result1))
{
   $fan_stand=$row; 
}
// echo "<pre>"; print_r($fan_stand); echo "</pre>";

$fanNameRotation= $fan_selected.'-'.$fanOrientation_user;
//echo $fanNameRotation;
$sSQL3="select * from rotationdimensions where NameRotation='$fanNameRotation' ";
$result3= mysql_query($sSQL3) or die ("Mysql err: ".mysql_error(). "<br>/". $sSQL3);

while($row3= mysql_fetch_assoc($result3)){
 $rotationDataFetch=$row3;
}
 //echo "<pre>"; print_r($rotationDataFetch); echo "</pre>";
?>
<fieldset><legend><strong>Dimensions Data (inches)</strong></legend>
<table style="border-left: 4px">
    <table><tr>
           
<tr><td>A</td><td width="100" style="text-align: center"><?php echo $fixedDimension[0]; ?></td><td>E</td><td width="100"style="text-align: center"><?php echo $fan_stand['E'];?></td><td>I</td><td width="100" style="text-align: center">N/A</td><td>M</td><td width="100" style="text-align: center"><?php echo $fan_stand['M'];?></td><td>R</td><td width="100" style="text-align: center"><?php echo $fixedDimension[8]; ?></td></tr>
<tr><td>B</td><td style="text-align: center"><?php echo $fixedDimension[1]; ?></td><td>F</td><td style="text-align: center"><?php echo $rotationDataFetch['F']?></td><td>J</td><td style="text-align: center"><?php echo $fan_stand['J']; ?></td><td>N</td><td style="text-align: center"> <?php echo $fan_stand['N']?></td><td>S</td><td style="text-align: center"><?php echo $fixedDimension[9]; ?></td></tr>
<tr><td>C</td><td style="text-align: center"><?php echo $fixedDimension[2]; ?></td><td>G</td><td style="text-align: center"><?php echo $rotationDataFetch['G']?></td><td>K</td><td style="text-align: center"><?php echo $fixedDimension[5]; ?></td><td>P</td><td style="text-align: center"> <?php echo $fan_stand['P'] ?></td><td>T</td><td style="text-align: center"><?php echo $fixedDimension[10]; ?></td></tr>
<tr><td>D</td><td style="text-align: center"> <?php echo $rotationDataFetch['E'] ?></td><td>H</td><td style="text-align: center"><?php echo $fixedDimension[3]; ?></td><td>L</td><td style="text-align: center"><?php echo $fan_stand['L']?></td><td>Q</td><td style="text-align: center"><?php echo $fixedDimension[7]; ?></td><td></td><td></td></tr>
</table>
    </fieldset>


<table width="100%" height="100%">
    <tr><td width="50%" height="100%" valign="top">
            <fieldset><legend><strong>Standard Options</strong></legend> 
                
                <table>
                  <tr><td><?php echo  'All Resin is '; if($_GET['FRP_Resin_user']=='custom'){echo $_GET['FRP_Resin_custom_user'];} else {echo $_GET['FRP_Resin_user']; } ?><td><tr>
                  
                  <?php echo "<tr><td>Housing / Inlet Cone: FRP</td></tr>"; ?>
                   <?php if($amcaArr_user==10){echo "<tr><td>weather cover - FRP Construction - Osha Compliant</td></tr>"; }?> 
                   <?php if($amcaArr_user==9 || $amcaArr_user==1){echo "<tr><td>Belt and shaft guards - FRP Construction - Osha Compliant</td></tr>"; }?>
                   <?php if($amcaArr_user==8){echo "<tr><td>shaft and coupling guards - FRP Construction - Osha Compliant</td></tr>"; }?>
                   
                    <tr><td><?php echo 'Fan Stand Arrangement # '. $amcaArr_user. ' '; 
                    if($amcaArr_user==10 || $amcaArr_user==9 || $amcaArr_user==8 || $amcaArr_user ==4 || $amcaArr_user==1){echo "(Epoxy Coated)";}?></td></tr>
                    <tr><td></td></tr>
                    <tr><td><?php echo 'Impellar - Backward Inclined (FRP) - Ø '. $fan_dia. '' ?></td></tr>
                    <tr><td></td><tr>
                    <tr><td>Shaft - 1045 Carbon Steel, with FRP Sleeve</td></tr>
                    <tr><td>Fasteners - SS304/SS316</td></tr>
                    <tr><td>Bearings - Solid Pillow Block  /  200,000 Hours L-10 Life</td></tr>
                    <tr><td>Teflon Seal & Shaft Sleeve</td></tr>
                    <tr><td>Outlet Connection - Flanged (not drilled)</td></tr>
                    <tr><td>Inlet Connection - Slip Type</td></tr>
                    <tr><td> <?php  echo 'Wheel Width :' . $wheel_width. '%' ?></td></tr>
                </table>
            </fieldset></td><td width="50%" height="100%" valign="top">
                        <fieldset>
                            <legend><strong>Selected Options</strong></legend>

                            <?php  
                            if(isset ($_GET['check_list'])){
                            $check_list = $_GET['check_list'];
                            
                            foreach( $check_list as $key => $arr)
                            {
                                echo $check_list[$key]; echo "<br>";
                            }
                            }
                            else echo "No Option Selected";
                            ?>

                            
                            </fieldset>
                    </td></tr></table>

<fieldset><legend><strong>Address</strong></legend>
    <p> 	Plasticair Inc. 1275 Crestlawn Drive Mississauga, ON L4W 1A9 Canada   <strong>	Phone: </strong> 	
	(905) 625 9164 	
        <strong>Fax:</strong> 	
	(905) 625 0147 	</p>
    </fieldset>

<a href='fan_selection.php' style=" float:right; background-image:url(/img/green-tittle-bg.jpg); background-repeat :repeat-x; border: green; padding : 3px 16px; color :white;">Fan Selection Form
<a href='system_fan_curve.php' style=" float:right; background-image:url(/img/green-tittle-bg.jpg); background-repeat :repeat-x; border: green; padding : 3px 16px; color :white;">System Fan Curve

        <?php
    include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php";?>