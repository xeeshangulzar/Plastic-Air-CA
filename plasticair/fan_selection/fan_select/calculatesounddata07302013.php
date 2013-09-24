<?php

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>


<?php

function AweightedSound($fan_model,$cfm,$rpmActual,$fan_dia,$distance,$soundSelection){
    

//********************************Values needed for A weighted Sound********************************
    
//$fan_model = $_SESSION['fan_selected'];
//$cfm =  $_SESSION['req_volume'];
//$rpmActual=$_SESSION['fan_detail_rpm'];
//$fan_dia=$_SESSION['fan_dia'];
// $distance=$_SESSION['soundDistance_user'];
//$soundSelection=$_SESSION['reflectiveSurface_user'];

//####################################################################################################



//////////////////////// Sound Data Calculation //////////////////////////////////




$testNumber=1; //this value will be changed by chossing between sound data



// *************************Obtain value from database***************************************************
//
//


if($fan_model=='30GIF')
{
    $fan_model_sound=$fan_model;    
}
elseif($fan_model=='12GIF' || $fan_model=='15GIF' || $fan_model=='18GIF')
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
       
        $rpmtestsound=$row["testRpm"];
        $cfmtestsound=$row["testCfm"];
        $sptestsound=$row["sp"];
         $diatestsound = $row["testDiameter"];
  
}
   

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
echo round(10*log10($AweightedSum),2);
//print_r ($x1). "<br><br>";
//print_r ($x2). "<br><br>";
//print_r ($x3). "<br><br>";
//print_r ($x4). "<br><br>";

}



 

?>

 
