<?php

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
session_start();
if(isset($_SESSION['custom_motor_data'])){ //unset motor_data previously saved
   unset($_SESSION['custom_motor_data']);
     }
$hp = $_GET['Horsepower_custom'];
$RPM = $_GET['RPM_custom'];
$voltage = $_GET['Voltage_custom'];
$enclosure= $_GET['enclosure_custom'];
$frame=$_GET['frame_custom'];
$hz=$_GET['hz_custom'];
$phase=$_GET['phase_custom'];
$notes_cutom= $_GET['motor_notes_custom_id'];


$custom_motor_data = "<strong> HP : </strong>" . $hp. "<strong> RPM : </strong>" .$RPM. "<strong> Voltage : </strong>" . $voltage. "<strong>  Enclosure:</strong>" .$enclosure. "<strong>  Frame:</strong>" .$frame. "<strong>  Hz: </strong>".$hz. "<strong>  Phase: </strong>" .$phase. "<br><strong>Notes : </strong>".$notes_cutom;
echo $custom_motor_data . "<br>";

$_SESSION['custom_motor_data']=$custom_motor_data;
?>
