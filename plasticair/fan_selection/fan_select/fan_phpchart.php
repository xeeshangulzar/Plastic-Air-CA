<?php
session_start();
require_once("../conf.php");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>phpChart - Basic Chart</title>
</head>
<body>
   
<?php


//previous calculated values

 
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

//CHARting


$l1 = array(2, 3, 1, 4, 3);
$l2 = array(1, 4, 3, 2, 5);
$l3 = array(7, 9, 11, 6, 8);


$pc = new C_PhpChartX(array($sp,$hp,$volume),'plot1');
$pc->set_animate(true);
$pc->set_grid(array(
    'background'=>'lightyellow',
    'borderWidth'=>0,
    'borderColor'=>'#000000',
    'shadow'=>true,
    'shadowWidth'=>10,
    'shadowOffset'=>3,
    'shadowDepth'=>3,
    'shadowColor'=>'rgba(230, 230, 230, 0.07)'
    ));
$pc->set_xaxes(array(
    'xaxis'  => array(
        'borderWidth'=>2,
        'borderColor'=>'#999999',
        'tickOptions'=>array('showGridline'=>true))
    ));
$pc->set_legend(array(
    'renderer' => 'plugin::EnhancedLegendRenderer',
    'show' => true,
    'location' => 'e',
    'placement' => 'outside',
    'yoffset' => 30,
    'rendererOptions' => array('numberRows'=>2),
    'labels'=>array('Oil', 'Renewables')  
    ));

$pc->set_yaxes(array(
    'yaxis' => array(
        'borderWidth'=>0,
        'borderColor'=>'#ffffff',
        'autoscale'=>true,
        'min'=>'0',
        'max'=>20,
        'numberTicks'=>21,
        
        'label'=>'Energy Use')
    ));
$pc->set_yaxes(array(
    'yaxis' => array(
        'borderWidth'=>1,
        'borderColor'=>'#ffffff',
        'autoscale'=>true,
        'min'=>'0',
        'max'=>20,
        'numberTicks'=>21,
        
        'label'=>'Energy Use 2')
    ));
$pc->set_legend(array(
    
    'show' => true,
    'location' => 'e',
    'placement' => 'outside',
    'yoffset' => 30,
    'rendererOptions' => array('numberRows'=>2),
    'labels'=>array('Oil', 'Renewables')  
    ));

$pc->draw();
?>




<!--<!DOCTYPE HTML>
<html>
    <head>
<style type="text/css">
    div.plot {
        margin-bottom: 70px;
        margin-left: 20px;
    }
    
    p {
        margin: 2em 0;
    }
    
    .jqplot-target {
        border: 1px solid #cccccc;
    }
</style>
    </head>
    <body>
        <div><span> </span><span id="info1b"></span></div>-->

<?php
    
//
//    $s1 = array(1,6,9,8);
//    $s2 = array(4,3,1,2);
//    $s3 = array(6,2,4,1);
//    $s4 = array(1,2,3,4);
//    $s5 = array(4,3,2,1);
//    $s6 = array(8,2,6,3);

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Chart 1 Example
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    
//    $pc = new C_PhpChartX(array($s1,$s2,$s3,$s4,$s5,$s6),'chart1');
//    
//    $pc->set_stack_series(true);
//    $pc->set_series_default(array('fill'=>true,'showMarker'=>false));
//    $pc->set_animate(true);
//
//    $pc->set_legend(array(
//        //'renderer'=>'plugin::EnhancedLegendRenderer',
//        'show'=>true,
//        'showLabels'=>true,
//        'labels'=>array('Fog', 'Rain', 'Frost', 'Sleet', 'Hail', 'Snow'),
//        'rendererOptions'=>array('numberRows'=>1),
//        'placement'=>'outsideGrid',
//        'location'=>'s'
//    ));
//    
//    $pc->set_axes(array(
//        'xaxis' => array('pad'=>0),
//        'yaxis' => array('min'=>0,'max'=>35)
//    ));
//    $pc->draw(500,300);

    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Chart 2 Example
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    
//    $pc = new C_PhpChartX(array($s1,$s2,$s3,$s4,$s5,$s6),'chart2');
//    
//	$pc->set_animate(true);
//	$pc->set_stack_series(true);
//    $pc->set_title(array('text'=>'Precipitation Potential'));
//    $pc->set_series_default(array('renderer'=>'plugin::BarRenderer'));
//    $pc->set_legend(array(
//       // 'renderer'=>'plugin::EnhancedLegendRenderer',
//        'show'=>true,
//        'showLabels'=>true,
//        'labels'=>array('Fog', 'Rain', 'Frost', 'Sleet', 'Hail', 'Snow'),
//       // 'rendererOptions'=>array('numberColumns'=>1,'seriesToggle'=>900,'disableIEFading'=>false),
//        'placement'=>'outside',
//        'marginLeft'=>'25px',
//        'marginTop'=>'0px'
//    ));
//    
//    $pc->set_axes(array(
//        'xaxis' => array('min'=>0,'max'=>35),
//        'yaxis' => array('min'=>0,'max'=>35)
//    ));
//    
//    $pc->draw(500,300);    
//    
//    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    //Chart 2b Example
//    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    
//    $pc = new C_PhpChartX(array($s1,$s2,$s3,$s4,$s5,$s6),'chart3');
//	   
//	$pc->set_animate(true);
//	$pc->set_stack_series(true);
//    $pc->set_series_default(array('fill'=>false,'showMarker'=>false));
//    $pc->add_series(array('yaxis'=>'y2axis'));
//    $pc->add_series(array('yaxis'=>'y3axis'));
//   
//    $pc->set_legend(array(
//        //'renderer'=>'plugin::EnhancedLegendRenderer',
//        'show'=>true,
//        'showLabels'=>true,
//        'labels'=>array('Fog', 'Rain', 'Frost', 'Sleet', 'Hail', 'Snow'),
//        'rendererOptions'=>array('numberColumns'=>1),
//        'placement'=>'outsideGrid',
//        'shrinkGrid'=>true,
//        'location'=>'e',
//        'marginLeft'=>'0px'
//    ));
//    
//    $pc->set_axes(array(
//        'xaxis' => array('pad'=>0),
//        'yaxis' => array('autoscale'=>true),
//        'y2axis' => array('autoscale'=>true),
//        'y3axis' => array('autoscale'=>true)
//    ));
//    $pc->draw(500,300);    
//
//    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    //Chart 4 Example
//    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    
//    $pc = new C_PhpChartX(array($s1,$s2,$s3,$s4,$s5,$s6),'chart4');
//    
//    $pc->set_animate(true);
//	$pc->set_title(array('text'=>'Progressive Projection'));
//    $pc->set_stack_series(true);
//    $pc->set_series_default(array('fill'=>true,'showMarker'=>false));
//    $pc->add_series(array('xaxis'=>'x2axis'));
//   
//    $pc->set_legend(array(
//        'renderer'=>'plugin::EnhancedLegendRenderer',
//        'show'=>true,
//        'labels'=>array('Fog', 'Rain', 'Frost', 'Sleet', 'Hail', 'Snow'),
//        'rendererOptions'=>array('numberRows'=>1),
//        'placement'=>'outsideGrid',
//        'location'=>'s'
//     ));
//    
//    $pc->set_axes(array(
//        'xaxis' => array('pad'=>0, 'label'=>'Dilution Count'),
//        'x2axis' => array('pad'=>0),
//        'yaxis' => array('min'=>0,'max'=>35)
//    ));
//    $pc->draw(500,300);    
//
//    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    //Chart 5 Example
//    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    
//    $pc = new C_PhpChartX(array($s1,$s2,$s3,$s4,$s5,$s6),'chart5');
//    
//	$pc->set_animate(true);
//	$pc->set_title(array('text'=>'Progressive Projection'));
//    $pc->set_stack_series(true);
//    $pc->set_series_default(array('fill'=>true,'showMarker'=>false));
//    $pc->add_series(array('xaxis'=>'x2axis'));
//   
//    $pc->set_legend(array(
//        'renderer'=>'plugin::EnhancedLegendRenderer',
//        'show'=>true,
//        'labels'=>array('Fog', 'Rain', 'Frost', 'Sleet', 'Hail', 'Snow'),
//        'rendererOptions'=>array('numberRows'=>1),
//        'placement'=>'outsideGrid',
//        'location'=>'n'
//     ));
//    
//    $pc->set_axes(array(
//        'xaxis' => array('pad'=>0, 'label'=>'Dilution Count'),
//        'x2axis' => array('pad'=>0),
//        'yaxis' => array('min'=>0,'max'=>35)
//    ));
//    $pc->draw(500,300);    
//    
//     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    //Chart 6 Example
//    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    
//    $pc = new C_PhpChartX(array($s1,$s2,$s3,$s4,$s5,$s6),'chart6');
//    
//	$pc->set_animate(true);
//	$pc->set_title(array('text'=>'Progressive Projection'));
//    $pc->set_stack_series(true);
//    $pc->set_series_default(array('fill'=>true,'showMarker'=>false));
//    $pc->add_series(array('xaxis'=>'x2axis'));
//   
//    $pc->set_legend(array(
//        'renderer'=>'plugin::EnhancedLegendRenderer',
//        'show'=>true,
//        'labels'=>array('Fog', 'Rain', 'Frost', 'Sleet', 'Hail', 'Snow'),
//        'rendererOptions'=>array('numberRows'=>1),
//        'placement'=>'outside',
//        'location'=>'n'
//     ));
//    
//    $pc->set_axes(array(
//        'xaxis' => array('pad'=>0, 'label'=>'Dilution Count'),
//        'x2axis' => array('pad'=>0),
//        'yaxis' => array('min'=>0,'max'=>35)
//    ));
//    $pc->draw(500,300);    

    ?>

    </body>
</html>

