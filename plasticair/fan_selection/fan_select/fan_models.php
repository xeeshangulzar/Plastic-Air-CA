<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/inc/dbconnect.php";
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$mount_type = $_GET['mount_type'];

$sql_query = "select fan_series from fan_type where mount_type = '$mount_type'";
$result = mysql_query($sql_query) or die ("MySQL err: ".mysql_error()."<br />".$sql_query);
//echo '<select name="fan-model">';
while($fan_model = mysql_fetch_assoc($result)){
    echo '<option value="'.$fan_model['fan_series'].'">'.$fan_model['fan_series'].'</option>';
}
//echo "</select>";