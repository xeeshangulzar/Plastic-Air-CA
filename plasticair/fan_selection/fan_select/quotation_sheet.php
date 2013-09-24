<?php
include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";


$fan_selected=$_SESSION['fan_selected'];

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
$fan_selected = $_SESSION['fan_selected'];
$inlet_pressure=$_SESSION['inlet_static_pressure'];
$outlet_pressure = $_SESSION['outlet_static_pressure'];
$relative_density = $_SESSION['relative_density'];
$outlet_velocity = $_SESSION['outlet_velocity'];





?>

<!--logo entry in table -->
<table>
    <tr>
        <td>
            <table>
                <img src="../../img/red_logo.png"> </table>
        </td>
        <td>
            <table><tr><td height="50"><font size="8" ><strong><u>Plasticair Inc.                    </u></strong></font></td></tr>
                <tr><td>Specialists in Fan Engineering and Pollution Control Equipment</td></tr>
            </table>
            
            
        </td>
    </tr>
</table>

<!-- quotation Number -->

<br></br>
<table>
    <tr>
        <td>
            Attn : 
        </td>
        <td>Manuel Segura L.</td>
        <td width="200"></td>
        <td><font size="6"><strong>Quotation # :</font></strong></td>
        <td><font size="6"><strong>15623</strong></font></td>
    </tr>
</table>

<!--to date reference -->
<br>
<br>

<table>
<tr>
    <td>
        To
    </td>
    <td>Tecnagent</td>
    <td width="100"></td>
    <td>
        
        <table><tr><td>Date:</td><td><?php echo date("F d, Y"); ?></td></tr>
            <tr><td> Reference: </td><td>#M12010 (Grade C SulfuricAcid Treatment Plant) 4240-FAN-01</td></tr></table>
    </td>
    <td><table><tr><td></td></tr>
        </table>
        
        </td>
</table>
<br>



<table><tr><td width="100"></td><td style=" text-align: center"><font size="6"><u>Plasticair Fan Model <?php echo "$fan_selected"; ?></u></font> </td></tr></table>

<br>
<p><strong>Performance</strong></p>
<hr>

<table>
    <tr><td><strong>Density: </stong></td><td width="50"><?php  echo round($relative_density,2) ; ?></td><td></td><td><strong>Volume:</strong></td><td width="70"><?php echo round($required_volume_flow ,2) ;?> </td><td></td><td><strong>Static:</stong></td><td width="50"><?php echo round($inlet_pressure,0); ?></td><td></td></tr>
    <tr><td><strong>RPM:</strong></td><td><?php echo round($fan_rpm,0)?></td><td></td><td><strong>Power:</strong></td><td><?php echo round($fan_bhp,2) ?></td><td></td><td><strong>Outlet Velocity:</strong></td><td><?php print round($outlet_velocity,2) ?> </td><td></td></tr>
     <tr><td><strong>Class:</strong></td><td></td><td></td><td><strong>Tip Speed:</strong></td><td><?php echo round($tip_speed,2)?></td><td></td><td></td><td></td><td></td></tr>
</table>
<br></br>

<p><strong>Standard Features</strong></p>
<hr>
<table><tr><td>
            <p>All Resin is Epoxy Vinyl Ester (Standard)</p>
           
            <p>Solid FRP Housing</p>
            <p>Fan Stand - Arr. #9 (Epoxy Coated)
</p>
            <p>Drive & Shaft Guard
</p>
            <p>Impellar - Backward Inclined (FRP) - Ø36.5
</p>
            <p>Shaft - 1045 Carbon Steel, with FRP Sleeve
</p>
   </td>
        
        
        <td>
            <p>Teflon Seal & Shaft Sleeve
</p>
            <p>Outlet Connection - Flanged (not drilled)
</p>
            <p>Inlet Connection - Slip Type
</p>
<p>Wheel Width : 100%
</p>

<p>Fasteners - SS304/SS316
</p>
<p>Bearings - Solid Pillow Block  /  200,000 Hours L-10 Life
</p>
     

        </td></tr></table>

<br>

<p><strong>Selected Options</strong></p>
<hr>

<p>FRP Sound Enclusure to meet 82 dBA at 1 meter (not shown in Drawing) , Sound enclosure to treat radiated sound only (not duct sound) , FRP inlet damper, locking quadrant. ( add FRP pipe - 762 mm to the inlet length) , Zero leakage shaft seal, (external 5 PSI - 8 CFM gas supply required by other) , Motor: IP 55, TEFC, 380/3/50, VFD ready 2900 RPM, 14.9KW								
								
							
           
<table ><tr border="1"><td><strong> NOT QUOTED:</strong></td> <td>Roof Curb or Mounting Pad,  Fan Starter,  Duct,  Stack, Freight, VFD, Sales commissions are not included in the price below. 							
							
</p></td></tr>

    <tr><td><strong>EXCEPTIONS: </strong></td><td></td></tr>
    <tr><td></td><td style="text-align: right"><strong> * This quotation is Valid for 30 days </strong></td> </tr></table>



<table><tr><td><strong>Quantity</strong></td><td> 12</td><td><strong>Unit Cost</strong></td><td><strong>$</strong></td><td>8060.00</td><td><strong>Total Cost (USD)</strong></td><td>$</td><td>96720.00</td></tr>
    </table>
<p>Price includes duty / Brokerage / All Customs Documentation. Freight & Commissions are NOT included </p>

<p>Conditions of tenders: Suppliers liability herein is limited to the replacement of the products supplied where to be defective, or the return of monies paid respecting same, at suppliers option, and in no event shall supplier be liable for consequential damage, and without limiting the generality thereof.  The supplier shall not be liable for the cost of installation or for the loss of profit arising from delays in production suffered by the purchaser.									
									
																		
</p>
<hr>
<p>Interest will be charged at 2% per month (24% per annum) on invoice payments not received in full within 45 days of invoice date.									
</p>

<table>
    <tr><td>
            <table>
                <tr><td>Taxes: Extra
</td></tr>
                <tr><td>Terms: Net 30 days
</td></tr>
                <tr>
                    <td>F.O.B. OUR PLASTICAIR, TORONTO CANADA
</td></tr>
            </table>
        </td><td>
            <table>
                <tr><td>Quoted By :
</td></tr>
                <tr><td>Jane Harnden		
</td></tr>
            </table>
        </td><td>
            <table>
                <tr><td>Plasticair</td></tr>
                <tr><td>1275 Crestlawn Drive
</td></tr>
                <tr><td>Mississauga, Ontario
</td></tr>
                <tr><td>Canada, L4W 1A9	
</td></tr>
            </table>
        </td></tr>
</table>




<?php
    include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php";?>