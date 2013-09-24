<?php
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past



?>
<?php 
$fanTagUser=$_SESSION['tagNumberUser'] ;
$fan_model = $_SESSION['fan_selected'];
$fan_selected=$_SESSION['fan_selected'];
$projectNameUser=$_SESSION['ProjectNameUser'];
$sales_rep=$_SESSION['sales_representative_user'];
?>
 <table border="0">
    <tr><td valign="top">
            <table  border="0"><tr> <td> <img src="../../img/red_logo.png"></td></tr>
                
<!--                <tr><td><?php // echo date("F d, Y"); ?></td></tr>
                <tr><td height="0"></td></tr>-->
            </table>
        </td>
        
        <td width="700">
            
   
            <table border="0"><tr><td  height="50 "width="700" style="text-align: center" > <font size="6" color="blue" ><?php echo "$fan_selected";?> Fan Performance</font></td>
                    <td width="150"></td></tr>
            </table>
            
            <table>
                <tr>
                    <td width="400px">
              <table>
                  <tr><td><strong>Project:</strong> <?php echo $projectNameUser ?> </td></tr>
                <tr><td><?php echo $_SESSION['project_location'] ;?></td></tr>
                <tr><td><strong>Engineer: </strong><?php print $_SESSION['engineer_user'];?></td></tr>
                <tr><td><?php print $_SESSION['engineer_location'];?></td></tr>
                <tr><td><strong>Contractor: </strong><?php print $_SESSION['contractor_user'];?></td></tr>
                <tr><td><strong>Plasticair Sales Rep:</strong> <?php echo $sales_rep; ?> </td></tr>
               </table>
                        </td>
                       <td width="300px" valign="top">
                <table>
                    <tr><td><strong>Date: </strong><?php echo date("F d, Y"); ?></td></tr>
                <tr><td><Strong>Fan Tag:</strong> <?php echo $fanTagUser; ?></td></tr>
                <tr><td><strong>Fan Model: </strong><?php echo "$fan_selected";?> </td></tr>
               </table>
                       </td>
                </tr>
            </table>
            
            

            
        </td>
        
   
    </tr>

</table>



<div align="center">

<?php 

print '<div id="as">';
print '<img src ="/fan_selection/fan_select/fan_curve_system.php">';
print '</div>'; 
?>
    </div>
<br></br>
<br></br>
<div align="center">
<table  border="0" style="border:1px solid black">
    <tr> 
        <td width="70">CFM :-</td><td width="100"><?php  print ($_SESSION['req_volume']) ?></td> 
        <td width="70"> RPM </td> 
        <td width="100"> <?php  print( round($_SESSION['fan_detail_rpm'],0));?></td>
        <td width="100"> Static Pressure</td><td width="100">
            <?php  print ( round ($_SESSION['static_pressure'],2))?>
        </td><td>BHP</td><td width="100"><?php  print (round($_SESSION['fan_detail_bhp'],2));?></td></tr>
    </table>
</div>

<?php
$relativeDensity = $_SESSION['relative_density'];
//inlet density is yet to work on.....!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
if($_SESSION['relative_density']==0){
$inlet_density=0.075; 
}
else{
    $inlet_density=$_SESSION['relative_density'];
}
$inletStaticPressure = $_SESSION['inlet_static_pressure'];
$outletVelocity= $_SESSION['outlet_velocity'] ;  
$staticPressure=$_SESSION['static_pressure'] ;
$bhp=$_SESSION['fan_detail_bhp'];
$cfm =  $_SESSION['req_volume'];
//**************try catch for Effeciency *********************
try{
{ if(!$bhp)
{
    throw new Exception('You entered Invalid arguments');}
    
else {
   $effeciencyMech= $cfm * (($staticPressure + pow($outletVelocity/1096.7,2)*$inlet_density))/(6356 *$bhp ); 
   $effeciencyStatic=($cfm * $staticPressure)/(6362*$bhp);
    }

}
}
catch (Exception $d) {
    
    echo 'Caught exception: ',  $d->getMessage(), "\n";
    

}

////********try catch for CFM ***********
try{
{ if(!$cfm)
{
    throw new Exception('Invalid CFM ');}
    
else {
  $tempChangePower = ($bhp*550)/(0.24*(($cfm*$relativeDensity)/60)*778.2);
    }

}
}
catch (Exception $e) {
    
    echo 'Caught exception: ',  $e->getMessage(), "\n";
    

}




$tempChangePressure = ($inletStaticPressure * 0.9923 * 5.1933)/($relativeDensity * 0.24 * 778.2 * 0.78 ) ;

$tempChangePower = ($bhp*550)/(0.24*(($cfm*$relativeDensity)/60)*778.2);


if($tempChangePower >=$tempChangePressure)
{
    $tempChange=$tempChangePower;
}
 else {
 
     $tempChange = $tempChangePressure;
 
}


//////////////////////// Sound Data Calculation //////////////////////////////////




$testNumber=1; //this value will be changed by chossing between sound data

$rpmActual=$_SESSION['fan_detail_rpm'];
$fan_dia=$_SESSION['fan_dia'];
//$smoothingtestsound=64.25;
//$partialfreqtestsound=90;


// *************************Obtain value from database***************************************************
//
//


if($fan_model=='30GIF')
{
    $fan_model_sound=$fan_model;    
}
elseif($fan_model=='12GIF' || $fan_model=='15GIF' || $fan_model=='18GIF' || $fan_model=='20GIF' || $fan_model=='22GIF')
{
    $fan_model_sound='12GIF';
}

else
    {
    $fan_model_sound='24GIF';
    }

$sSQL1 = "select * from soundtestdata where Number='$testNumber' and fanName='$fan_model_sound'";
    $result=mysql_query($sSQL1) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL1);
    
 while ($row = mysql_fetch_assoc($result)) 
 {
        echo "<br>";
        $rpmtestsound=$row["testRpm"];
        $cfmtestsound=$row["testCfm"];
        $sptestsound=$row["sp"];
         $diatestsound = $row["testDiameter"];
  
}
  // echo $rpmtestsound ." ". $cfmtestsound ." ". $sptestsound ." ".  $diatestsound . "a" ; 

$effeciencySound=$cfm/($rpmActual*$cfmtestsound/$rpmtestsound * pow(($fan_dia/$diatestsound),3));
//echo $effeciencySound*100;

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
//###############################################################################################

//**************************Selecting Octave bands frequency************************************

$sSQL1 = "select * from octavebands where Number='$octaveNumber' and fanName='$fan_model_sound'";
    $result=mysql_query($sSQL1) or die ("MySQL err: ".mysql_error()."<br/>".$sSQL1);
    

 while ($row = mysql_fetch_assoc($result)) 
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

//print_r ($valueArray) ."<br>";

//#################################################################################################

$frequencyArray= array(63,125,250,500,1000,2000,4000,8000);  //fixed values for frequencies
$partialFrequencyArray=array(45,90,175,355,690,1400,2800,5600); //fixed values for partial Prequencies
//$reflectiveArray =  array (36.09499957,26.09499957,19.09499957,13.59499957,10.59499957,9.594999566,9.594999566,11.59499957); //for Q1 at 3.2
$offsetSound= array( 25.5,15.5,9,3,0,-1,-1,1);

$soundSelection=$_SESSION['reflectiveSurface_user'];

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

$distance=$_SESSION['soundDistance_user'];
    
foreach($offsetSound as $key => $value)
{
   $reflectiveArray[]=20*log10($distance) + $soundVariable + $offsetSound[$key];
}



foreach ($frequencyArray as $key => $value)
{
    $x1[]=10*log10($frequencyArray[$key]/$rpmtestsound)+20;
   // echo round(($x1),2) ."<br>";
    $x4[]=10*log10($frequencyArray[$key]/$rpmActual)+20;
   // echo round(($x4[$key]),2) ."x4<br>";
    //echo $frequencyArray[$key]. "fa <br>";
   
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
$anstestsound[$i]= $x5[$i] + (40*log10($rpmActual/1000)) + (70*log10($fan_dia/20)) + (10*log10($partialFrequencyArray[$i]));
}

for($i=0; $i<8 ; $i++)
{
   $pressureSound[$i] = $anstestsound[$i] - $reflectiveArray[$i];
}
$AweightedSum=0;
for($i=0; $i<8; $i++ ){
    $AweightedSum = pow(10,$pressureSound[$i]/10) + $AweightedSum; 
}
//echo "Aweighted Sum". round(10*log10($AweightedSum),0);
//print_r ($x1). "<br><br>";
//print_r ($x2). "<br><br>";
//print_r ($x3). "<br><br>";
//print_r ($x4). "<br><br>";

$tip_speed = $_SESSION ['tip_speed'];

if($tip_speed<=1500)
{ $fan_class='Class 2';
}
 else {
    $fan_class='Class 3';
}


?>

 <h3><u><strong> Sound Data:</strong></u></h3>

 <div align="center">
   

    <table border="1" style="text-align: center">
        <tr><td><strong>Octave band center frequency (Hz)</strong></td><td width="50"><strong>63</strong></td><td width="50"><strong>125</strong></td><td width="50"><strong>250</strong></td><td width="50"> <strong>500 </strong> </td><td width="50"><strong>1K</strong></td><td width="50"><strong>2K</strong></td><td width="50"><strong>4K</strong></td><td width="50"><strong>8K</strong></td></tr>
        <tr><td>Inlet sound power levels (db)</td><td><?php echo round($anstestsound[0],0) ?></td><td><?php echo round($anstestsound[1],0) ?></td><td><?php echo round($anstestsound[2],0) ?></td><td><?php echo round($anstestsound[3],0) ?></td><td><?php echo round($anstestsound[4],0) ?></td><td><?php echo round($anstestsound[5],0) ?></td><td><?php echo round($anstestsound[6],0) ?></td><td><?php echo round($anstestsound[7],0) ?></td></tr>
        <tr><td>Sound Pressure levels at <?php echo $distance ?> feet (A-weighted) </td><td><?php echo round($pressureSound[0],0) ?></td><td><?php echo round($pressureSound[1],0) ?></td><td><?php echo round($pressureSound[2],0) ?></td><td><?php echo round($pressureSound[3],0) ?></td><td><?php echo round($pressureSound[4],0) ?></td><td><?php echo round($pressureSound[5],0) ?></td><td><?php echo round($pressureSound[6],0) ?></td><td><?php echo round($pressureSound[7],0) ?></td></tr>
       
        <tr>
          <td width="280px"><strong> * Total (A-weighted) Sound Pressure levels at <?php echo $distance ?> feet Fan (dBA) is <?php print round(10*log10($AweightedSum),0)?></strong></td>
          
        </tr>
       
    </table>
 </div>
 

 
 <br></br>
 
 <table border="0" align="center" >
     <tr>
         <td width="200"><img src="../../img/AMCA-Sound-Air Seal.jpg"></td>
         <td width="250" valign="top">
             <table><tr><h3 style="text-align:center">Other performance data</h3></tr>
     <tr><td style="text-align: right">Tip Speed:</td><td width="80">
             <?php 
             print  round($tip_speed,0);
             ?> ft/min
         </td></tr>
     <tr><td style="text-align: right">Fan Class:</td><td width="80"><?php print( $fan_class); ?></td></tr>
     <tr><td style="text-align: right">Efficiency (Mech):</td><td width="80"><?php echo round($effeciencyMech*100,0) . "%" ;?></td></tr>
     <tr><td style="text-align: right">Efficiency (Static) :</td><td width="80"> <?php echo round($effeciencyStatic*100,0)."%" ;?></td></tr>
     <tr><td style="text-align: right" >Airstream Temp Change **:</td><td width="80"><?php echo round($tempChange,2); ?> &degF</td></tr>
     <tr><td style="text-align: right">Wheel Width (%) :</td><td width="80">
             
             <?php $wheel_width=$_SESSION['wheel_width'];
             print round($wheel_width,0);
             ?>
         </td></tr>
     <tr><td  style="text-align: right"> Outlet Velocity: </td> <td width="80"><?php echo round($outletVelocity,0) ?></td>
     
 </table>
         </td>
         <td width="250" valign="top">
             <table> <tr><h3>Surrounding Conditions</h3></tr>
             <tr><td>Temperature:</td><td><?php print ($_SESSION['temperature']);  ?> &degF </td><td></td></tr>
             <tr><td>Altitude:</td><td><?php print ($_SESSION['altitude']); ?> ft</td></tr>
             <tr><td>Density:</td><td><?php echo round($relativeDensity,3) ?> lb/ft^2</td></tr>
            </table>
 </td>  </tr>   
 </table>
       
     
 <p>* The environment for the fan installation effects the measured sound values, therefor the dBA levels cannot be guaranteed. A fans dBA is influenced by nearby sound reflecting surfaces. A-weighted Octave Band Sound Pressure levels (dBA) are not licensed by AMCA International. Please consult AMCA publication 303 for more information.
</p>
<br>
<p>
    ** The airstream temperature change result is a calculated estimation and should not be used for design purposes.
</p>
<p>*** The Sound Pressure levels are based on a fan installation next to
 <?php if($_SESSION['reflectiveSurface_user']==1){echo "No Reflective Surface";} 
 elseif($_SESSION['reflectiveSurface_user']==2){echo "1 refelective Surface (Floor)";} 
 elseif($_SESSION['reflectiveSurface_user']==3){ echo "2 Reflective Surface (Floor and 1 wall)";}
 elseif($_SESSION['reflectiveSurface_user']==4){ echo "3 Reflective Surface (in a corner)";} 
 ?>
</p>

<br></br>

<?php
  //print '<form action="quotation_sheet.php"><input type="submit" value="Create Quotation Sheet" style=" float:right; background-image:url(/img/green-tittle-bg.jpg); background-repeat :repeat-x;
    //    border: green; padding : 3px 16px;
      //  color :white;"/></form>';
      
?>


<?php
  print '<form action="option_select.php"><input type="submit" value="Fan Submital" style=" float:right; background-image:url(/img/green-tittle-bg.jpg); background-repeat :repeat-x;
        border: green; padding : 3px 16px;
        color :white;"/></form>';
?>
<a href='fan_selection.php' style=" float:right; background-image:url(/img/green-tittle-bg.jpg); background-repeat :repeat-x; border: green; padding : 3px 16px; color :white;" >Fan Selection Form
<?php
    include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php";?>