<?php
session_start();
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

 include("../class/pData.class.php");
 include("../class/pDraw.class.php");
 include("../class/pImage.class.php");
 include("../class/pScatter.class.php");
 

 
 
$fan_curve_data = $_SESSION['fan_curve_data'];
$req_sp = $_SESSION['req_sp'];
$req_vol_flow = $_SESSION['req_volume'];

//echo"hello<pre>"; print_r($_GET); echo "</pre>"; exit();
$constant = ($req_sp/pow($req_vol_flow,2));
$i=0;
$j=0;
$incrementer=0;
$rpm = array();
$sp = array();
$hp = array();
$system_curve['sp'] = array();
$system_curve['volume'] = array();

$total = count($fan_curve_data);

while($j<$total){
     $volume[$i] = $fan_curve_data[$j]['volume'];
     $sp[$i] = $fan_curve_data[$j]['sp'];
     $hp[$i] = $fan_curve_data[$j]['hp'];
     $system_curve_sp[$j] = $req_sp * $incrementer;
     $system_curve_volume[$j] = sqrt($system_curve_sp[$j]/$constant);
     $i++;
     if($req_vol_flow < $fan_curve_data[$j]['volume'] && $req_vol_flow > $fan_curve_data[$j+1]['volume']){ //needs check if j+1 doesnot exists
         $hp_curve_point = ((($req_vol_flow - $fan_curve_data[$j+1]['volume'])/($fan_curve_data[$j]['volume'] - $fan_curve_data[$j+1]['volume'])) * ($fan_curve_data[$j]['hp'] - $fan_curve_data[$j+1]['hp'])) + $fan_curve_data[$j+1]['hp'];
         $volume[$i] = $req_vol_flow;
         $sp[$i] = $req_sp;
         $hp[$i] = $hp_curve_point;
         $index = $i;
         $i++;
         //print $hp_curve_point; exit();
     }
     $j++;
     $incrementer = $incrementer + 0.125;
}

//echo "<pre>"; print_r($volume); echo "</pre>";
//echo "<pre>"; print_r($system_curve_volume); echo "</pre>"; exit();

$MyData = new pData(); 

$MyData->addPoints($volume,"Volume Flow");
$MyData->addPoints($system_curve_volume,"System Volume Flow");
$MyData->setAxisDisplay(0,AXIS_FORMAT_METRIC);
$MyData->setSerieOnAxis("System Volume Flow",0);
$MyData->setaxisName(0,"Volume (CFM)");
$MyData->setaxisXY(0,AXIS_X);
$MyData->setAxisPosition(0,AXIS_POSITION_BOTTOM );
$MyData->setSerieDrawable("Volume Flow",FALSE);
$MyData->setSerieDrawable("System Volume Flow",FALSE);

$MyData->addPoints($sp,"Static Pressure");
$MyData->addPoints($system_curve_sp,"Fan Curve");
$MyData->setAxisDisplay(1,AXIS_FORMAT_METRIC);
$MyData->setSerieOnAxis("Fan Curve",1);
$MyData->setSerieOnAxis("Static Pressure",1);
$MyData->setaxisName(1,"Static Pressure (in .W.G.)");
$MyData->setaxisXY(1,AXIS_Y);
$MyData->setAxisPosition(1,AXIS_POSITION_LEFT );

$MyData->addPoints($hp,"Power");
$MyData->setAxisDisplay(2,AXIS_FORMAT_METRIC);
$MyData->setSerieOnAxis("Power",2);
$MyData->setaxisName(2,"Power (BHP)");
$MyData->setaxisXY(2,AXIS_Y);
$MyData->setAxisPosition(2,AXIS_POSITION_RIGHT );


//$MyData->addPoints($system_curve_sp,"SystemStaticPressure");
//$MyData->setSerieOnAxis("SystemStaticPressure",5);

//$MyData->setaxisName(5,"Static Pressure");
//$MyData->setaxisXY(5,AXIS_Y);
//$MyData->setAxisPosition(0,AXIS_POSITION_LEFT );
//
////$MyData->addPoints($system_curve_volume,"System Volume Flow");
//$MyData->setSerieOnAxis("System Volume Flow",6);
//$MyData->setaxisName(6,"System Volume");
//$MyData->setaxisXY(6,AXIS_X);
//$MyData->setAxisPosition(6,AXIS_POSITION_BOTTOM );


$MyData->setScatterSerie("Volume Flow","Static Pressure",3);
$MyData->setScatterSerie("Volume Flow","Power",4);
$MyData->setScatterSerie("System Volume Flow","Fan Curve",7);



$myPicture = new pImage(800,400,$MyData);
/* Define the graph area and do some makeup */
$myPicture->setGraphArea(60,50,700,330);
$myPicture->setFontProperties(array("FontName" =>"../fonts/GeosansLight.ttf"));
$myPicture->drawFilledRectangle(60,50,699,350,array("R"=>200,"G"=>200,"B"=>200,"Surrounding"=>-200,"Alpha"=>100));

  
// // Set labels  
// $myPicture->setFontProperties("Fonts/tahoma.ttf",8);  
// $myPicture->setLabel($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1","2","Daily incomes",221,230,174);  
// $myPicture->setLabel($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie2","6","Production break",239,233,195);  
//  


$myPicture->drawGradientArea(60,50,700,350,DIRECTION_VERTICAL,array("StartR"=>220,"StartG"=>220,"StartB"=>220,"EndR"=>255,"EndG"=>255,"EndB"=>255,"Alpha"=>100));
//$myPicture->drawText(60,35,"Fan Curve",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOM));



$AxisBoundaries = array(
    0=>array("Min"=>0,"Max"=>$MyData->getMax("Volume Flow")*1.2),
    1=>array("Min"=>0,"Max"=>$MyData->getMax("Static Pressure")*1.2),
    2=>array("Min"=>0,"Max"=>$MyData->getMax("Power")*1.2),
    5=>array("Min"=>0,"Max"=>$MyData->getMax("Static Pressure")*1.2),
    /*6=>array("Min"=>0,"Max"=>$MyData->getMax("Volume Flow"))*/);
$ScaleSettings = array("Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>$AxisBoundaries, "ScaleSpacing"=>1,"DrawSubTicks"=>TRUE);




$my_scatter_data = new pScatter($myPicture,$MyData);
$my_scatter_data->drawScatterScale($ScaleSettings);
$my_scatter_data->drawScatterLineChart();
//$LabelSettings = array("Decimals"=>3,"TitleMode"=>LABEL_TITLE_NOBACKGROUND,"OverrideTitle"=>TRUE, "DrawPoint"=>TRUE,"DrawLabelBox"=>FALSE);
//$my_scatter_data->writeScatterLabel(3,$index,$LabelSettings);
//$my_scatter_data->writeScatterLabel(4,$index,$LabelSettings);


//$LabelSettings = array("ForceLabels"=>TRUE,"GradientEndR"=>255,"GradientEndG"=>200,"GradientEndB"=>200);
//$myPicture->writeLabel(array("SystemStaticPressure"),8,$LabelSettings);
//$myPicture->writeLabel("Volume Flow",2);
//$myPicture->writeLabel("SystemStaticPressure",2);
//$myPicture->writeLabel("SystemStaticPressure",2);

///* Write a label over the chart */
//$LabelSettings = array("OverrideTitle"=>"Multiple Values","DrawVerticalLine"=>TRUE,"TitleMode"=>LABEL_TITLE_BACKGROUND,"TitleR"=>255,"TitleG"=>255,"TitleB"=>255);
//$myPicture->writeLabel(array("Volume Flow","StaticPressure","HP"),7,$LabelSettings);

/* Draw Legend */
$myPicture->drawLegend(585,80,array("Style"=>LEGEND_ROUND,"Mode"=>LEGEND_VERTICAL));

//$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
//$myPicture->setFontProperties(array("FontName"=>"../fonts/Forgotte.ttf","FontSize"=>11));


$myPicture->stroke();
//<input type=submit value=Show Fan curve size=50 style=background-image: url(/img/green-tittle-bg.jpg);background-repeat: repeat-x; border: green; padding: 3px 16px; color: white;>




////echo "<pre>"; print_r($rpm_table); echo "</pre>";
//global $rpm_table;
//$fan_chart_data = new pData();
//foreach($rpm_table['12GIF'] as $index => $value){
//    $fan_chart_data->addPoints($value['rpm'],"RPM");
//    $fan_chart_data->addPoints($value['sp'],"Static Pressure");
//    $fan_chart_data->addPoints($value['hp'],"Horse Power");
//}
//
//$fan_chart_data->setPalette("RPM",array("R" => 240,"G" => 16, "B" =>16, "Alpha" => 100));
//$fan_chart_data->setPalette("Static Pressure",array("R" => 16, "G" => 240, "B" => 16, "Alpha" => 100));
//$fan_chart_data->setPalette("Horse Power",array("R" => 16,"G" => 16, "B" =>240, "Alpha" => 100));
//
//$fan_chart_image = new pImage(500,300, $fan_chart_data);
//$fan_chart_image -> setFontProperties(array("FontName" =>"../fonts/GeosansLight.ttf","FontSize" => 12));
//$fan_chart_image -> setGraphArea(40,40, 460,260);
//$fan_chart_image -> drawScale();
//$fan_chart_image -> drawLineChart();
//header("Content-Type: image/png");
//$fan_chart_image -> Render(null);






//$MyData = new pData();  
///* Prepare some nice data & axis config */
////$MyData->addPoints($rpm,"Temperature");
//$MyData->addPoints($sp,"Static Pressure");
////$MyData->addPoints($hp,"Humidity 2");
////$MyData->addPoints(array(-1,-1,-1,-1,-1),"Humidity 3");
//$MyData->addPoints($hp,"HP");
////$MyData->setSerieOnAxis("Temperature",0);
//$MyData->setSerieOnAxis("Static Pressure",0);
////$MyData->setSerieOnAxis("Humidity 2",1);
////$MyData->setSerieOnAxis("Humidity 3",1);
//$MyData->setSerieOnAxis("HP",1);
//$MyData->setAxisPosition(1,AXIS_POSITION_RIGHT);
////$MyData->setAxisName(0,"Temperature");
//$MyData->setAxisName(0,"Static Presure");
//$MyData->setAxisName(1,"HP");
//
///* Bind a data serie to the X axis */
//$MyData->addPoints($volume,"Volume Flow");
//$MyData->setSerieOnAxis("Volume Flow",3);
////$MyData->setSerieDescription("Labels","My labels");
////$MyData->addPoints(array(0,4000,8000,12000,16000,20000,24000,28000,32000,36000),"Volume Flow");
//$MyData->setAbscissa("Volume Flow");
//
//$myPicture = new pImage(900,500,$MyData);
///* Define the graph area and do some makeup */
//$myPicture->setGraphArea(90,60,660,190);
//
////$myPicture->drawText(350,55,"My chart title");
//$myPicture->setFontProperties(array("FontName" =>"../fonts/GeosansLight.ttf","FontSize" => 15));
//$myPicture->drawFilledRectangle(90,60,660,190,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
//
///* Compute and draw the scale */
//$myPicture->drawScale(array('Mode' => SCALE_MODE_START0));
//
//$myPicture->drawSplineChart();
//header("Content-Type: image/png");
//$myPicture->render(null);

?>


<!--<h1>hello2</h1>-->