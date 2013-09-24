<?php

require_once "../class/pDraw.class.php";
require_once "../class/pImage.class.php";
require_once "../class/pData.class.php";

$myDataset = array(0, 1, 1, 2, 3, 5, 8, 13);
$myData = new pData();
$myData->addPoints($myDataset);
$myImage = new pImage(500, 300, $myData);
$myImage->setFontProperties(array(
    "FontName" =>"../fonts/GeosansLight.ttf",
    "FontSize" => 15));
$myImage->setGraphArea(25,25, 475,275);
$myImage->drawScale();
$myImage->drawBarChart();
header("Content-Type: image/png");
$myImage->Render(null);
