<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/inc/dbconnect.php";
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
session_start();


$motortype = $_GET['motortype_user'];
$safetyfactor = $_GET['safetyfactor_user'];
$bhp=$_SESSION['fan_detail_bhp'];
$motorsafety=$bhp*$safetyfactor;
if(isset($_SESSION['motor_data'])){ //unset motor_data previously saved
   unset($_SESSION['motor_data']);
     }
if(isset($_SESSION['custom_motor_data'])){ //unset motor_data previously saved
   unset($_SESSION['custom_motor_data']);
     }
?>


<?php
if ($safetyfactor < 1.07){
    echo "Enter Safety Factor greater than 1.07";
    exit();
}
if($motortype=='Select'){
    echo " *Please Select Motor Type";
   exit();
}
elseif($motortype=='TEFC - 575V'){
  $enclosure='TEFC';
  $voltage='575';  
}
elseif($motortype=='TEFC - 575V - Prem Eff'){
   $enclosure='TEFC - Prem Eff';
   $voltage='575';
}
elseif($motortype=='TEFC - 208-230/460V'){
   $enclosure='TEFC';
   $voltage='208-230/460';

}
elseif($motortype=='TEFC - 208-230/460V - Prem Eff'){
  $enclosure='TEFC - Prem Eff';
  $voltage='208-230/460';
}
elseif($motortype=='TEFC - 115-208/230V'){
  $enclosure='TEFC';
  $voltage='115-208/230';
}
elseif($motortype=='Explosion - 575V'){
  $enclosure='Explosion Proof';
  $voltage='575';
}
elseif($motortype=='Explosion - 208-230/460V'){
  $enclosure='Explosion';
  $voltage='208-230/460';
}
elseif($motortype=='Explosion - 115-208/230V'){
  $enclosure='Explosion Proof';
  $voltage='115-208/230';
}
elseif($motortype=='TEFC Resilient - 115-208/230V'){
  $enclosure='TEFC-Resilient';
  $voltage='115-208/230';
}
elseif($motortype=='TEFC Resilient - 208-230/460V'){
  $enclosure='TEFC-Resilient';
  $voltage='208-230/460';
}
elseif($motortype=='ODP Resilient - 115-208/230V'){
  $enclosure='ODP-Resilient';
  $voltage='115-208/230';
}
elseif($motortype=='ODP Resilient - 208-230/460V'){
  $enclosure='ODP-Resilient';
  $voltage='208-230/460';
}

elseif($motortype=='ODP Resilient - 575V'){
  $enclosure='ODP-Resilient';
  $voltage='575';
}
elseif($motortype=='MillChem - 230/460V'){
  $enclosure='MillChem';
  $voltage='230/460';
}


elseif($motortype=='MillChem - 575V'){
  $enclosure='MillChem';
  $voltage='575';
  
}

//elseif($motortype=='Custom'){
//  //  echo  //'<form name="custom_motorselection" >
//    // HP : <input type="text" name="Horsepower_custom" size="5"> RPM: <input type="text" name="RPM_custom" size="5"> Voltage: <input type="text" name="Voltage_custom" size="5"> Enclosure: <input type="text" name="enclosure_custom" size="5">
//// </form>';
//    echo "Custom Selection";
    
//}
?>



<?php



//if($motortype=='Custom'){
//    $custom_motor_data=0;
//    
//    echo "entered custom";
//     $_SESSION['motor_data'][]=$custom_motor_data;
//   //   echo $_SESSION['custom_motor_data']. "<br>";
//    
//}

//else {
//echo $motortype ."<br>";
//echo $safetyfactor. "<br>";
//echo $bhp. "<br>";
//echo $motorsafety. "<br>";


$sSQL="select distinct(HorsePower) from motor_data where HorsePower >='$motorsafety' AND Enclosure='$enclosure' AND Voltage='$voltage'  LIMIT 1 ";
$result1=mysql_query($sSQL) or die ("MySQL err: ".mysql_error()."<br />".$sSQL);
$motorHP=  mysql_fetch_array($result1);

$motorHP=$motorHP['HorsePower'];
//echo $motorHP ."<br>";

if($motorHP==NULL || $motorHP==""){
    echo " No Motor for this safety factor";
    exit();
}

//$enclosure='TEFC - Prem Eff';
//$voltage='208-230/460';

 $sSQL1 = "select * from motor_data where Enclosure='$enclosure' and Voltage='$voltage' and HorsePower = '$motorHP' ";
 $result=mysql_query($sSQL1) or die ("MySQL err: ".mysql_error()."<br />".$sSQL1);
 
  echo "<br>";
 while($motor_data = mysql_fetch_assoc($result)){
//     echo "<pre>"; print_r($motor_data); echo "<pre>";
     if($motor_data['Enclosure']=='MillChem'){
         $motor_data['Enclosure']= 'Mill and Chem';
     }
     
//     foreach($motor_data as $key => $value){
//         $motor_selection[$key]= $value;
//     }
     
     
    
    
  
     $_SESSION['motor_data'][]=$motor_data;
     echo  "<strong> HP: </strong>" .round($motor_data['HorsePower'],2). " <strong>    RPM:</strong>". round($motor_data['RPM'],2). " <strong>    Voltage: </strong> ". $motor_data['Voltage']. " <strong>    Enclosure:</strong>".$motor_data['Enclosure'] . " <strong>   Frame: </strong>". $motor_data['Frame'] ."<br>";
                                                    }
//}
//echo "<pre>";  print_r ($_SESSION['motor_data']); echo "<pre>";
 
 
 
 
 ?>



