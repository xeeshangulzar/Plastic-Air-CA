<?
session_start();
header("Content-type: image/png");
$_SESSION["secureNumber"]="";
	$im = imagecreate(80, 19);		
	// Create some colors FFFFD7
	$white = imagecolorallocate($im, 240, 240, 240);
	$grey = imagecolorallocate($im, 128, 128, 128);
	$black = imagecolorallocate($im, 0, 0, 0);		


	// The text to draw
	//$text = $_SESSION["secure"];
	// Replace path by your own font path
	$font = 'LHANDW.TTF';		
	// Add some shadow to the text
	for($i=0;$i<=5;$i++)
	{
		$numb=rand(0, 9);
		$_SESSION["secureNumber"].=$numb;
		$angle=rand(-25, 25);
		imagettftext($im, 10, $angle, 8+11*$i, 16, $grey, $font, $numb);		
	// Add the text
		imagettftext($im, 10, $angle, 7+11*$i, 15, $black, $font, $numb);	
	}
$_SESSION["secureNumber"]=md5($_SESSION["secureNumber"]);
imagepng($im);
imagedestroy($im);
?>