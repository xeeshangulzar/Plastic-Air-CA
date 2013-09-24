<?php
require('fpdf.php');

class PDF extends FPDF
{
var $B;
var $I;
var $U;
var $HREF;

function PDF($orientation='P', $unit='mm', $size='A4')
{
    // Call parent constructor
    $this->FPDF($orientation,$unit,$size);
    // Initialization
    $this->B = 0;
    $this->I = 0;
    $this->U = 0;
    $this->HREF = '';
}

function Header()
{
    // Select Arial bold 15
    $this ->SetTextColor (40,40,40);
    $this->SetFont('Arial','B',30);
    $this->SetFont('Arial','U',30);
    // Move to the right
    $this->Cell(80);
    // Framed title
    $this->Cell(15,15,'Plasticair Inc.',0,0,'C');
    $this->SetFont('Arial','',14);
    $this->Cell(15,33,'Specialists in Fan Engineering and Pollution Control Equipment',0,0,'C');
    // Line break
    $this->Ln(20);
}

function WriteHTML($html)
{
    // HTML parser
    $html = str_replace("\n",' ',$html);
    $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            // Text
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            else
                $this->Write(5,$e);
        }
        else
        {
            // Tag
            if($e[0]=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                // Extract attributes
                $a2 = explode(' ',$e);
                $tag = strtoupper(array_shift($a2));
                $attr = array();
                foreach($a2 as $v)
                {
                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                        $attr[strtoupper($a3[1])] = $a3[2];
                }
                $this->OpenTag($tag,$attr);
            }
        }
    }
}

function OpenTag($tag, $attr)
{
    // Opening tag
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,true);
    if($tag=='A')
        $this->HREF = $attr['HREF'];
    if($tag=='BR')
        $this->Ln(5);
}

function CloseTag($tag)
{
    // Closing tag
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF = '';
}

function SetStyle($tag, $enable)
{
    // Modify style and select corresponding font
    $this->$tag += ($enable ? 1 : -1);
    $style = '';
    foreach(array('B', 'I', 'U') as $s)
    {
        if($this->$s>0)
            $style .= $s;
    }
    $this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
    // Put a hyperlink
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}
}

$html = '';

$date= date("F d, Y");
$pdf = new PDF();
// First page
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
//$pdf->Write(5,"To find out what's new in this tutorial, click ");
//$pdf->SetFont('','U');
//$link = $pdf->AddLink();
//$pdf->Write(5,'here',$link);
//$pdf->SetFont('');
// Second page
//$pdf->AddPage();

//$pdf->SetLink($link);
//$fan_selected="Iqbal";
$pdf->Image('logo.png',10,12,30,0,'','#');
$pdf->Cell(40,35,$date);
$pdf->Cell(10,55,'Attn : Manuel Segura L. ');
$pdf->Cell(80,35,'Quotation # : 15623');
$pdf->Cell(200,35,'To : Tecnagent');
//$pdf->Cell(20,45,'');
$pdf->ln(20);
$pdf->Text(35,70,"Reference: #M12010 (Grade C Sulfuric Acid Treatment Plant) 4240-FAN-01");
$pdf->Line(10,85,200,85);
$pdf->ln(38);
$pdf->SetFont('Arial','B',10);
$pdf->Text(12,80,"Selected Options");
$pdf->SetFont('Arial','',10);

        
$pdf->Write(5,"FRP Sound Enclusure to meet 82 dBA at 1 meter (not shown in Drawing) , Sound enclosure to treat radiated sound only (not duct sound) , FRP inlet damper, locking quadrant. ( add FRP pipe - 762 mm to the inlet length) , Zero leakage shaft seal, (external 5 PSI - 8 CFM gas supply required by other) , Motor: IP 55, TEFC, 380/3/50, VFD ready 2900 RPM, 14.9KW");
$pdf->ln(5);
//$pdf->Write(5,$fan_selected);
$pdf->ln(10);
$pdf->Write(5,"Roof Curb or Mounting Pad, Fan Starter, Duct, Stack, Freight, VFD, Sales commissions are not included in the price below.");
$pdf->ln(10);
$pdf->Write(5,"* This quotation is Valid for 30 days");
$pdf->ln(10);
$pdf->Write(5,"Quantity	12	 Unit Cost	 $	 8060.00	 Total Cost (USD)	 $	 96720.00");
$pdf->ln(10);
$pdf->Write(5,"Price includes duty / Brokerage / All Customs Documentation. Freight & Commissions are NOT included ");
$pdf->ln(10);
$pdf->Write(5,"Conditions of tenders: Suppliers liability herein is limited to the replacement of the products supplied where to be defective, or the return of monies paid respecting same, at suppliers option, and in no event shall supplier be liable for consequential damage, and without limiting the generality thereof. The supplier shall not be liable for the cost of installation or for the loss of profit arising from delays in production suffered by the purchaser. ");
$pdf->ln(10);
$pdf->Line(15,185,200,185);
$pdf->ln(10);
$pdf->Write(5,"Interest will be charged at 2% per month (24% per annum) on invoice payments not received in full within 45 days of invoice date. ");

//$pdf->Cell(10,60,"Taxes: Extra");
//$pdf->Cell(20,60, "Terms: Net 30 days");
//$pdf->Cell (30,60," F.O.B. OUR PLASTICAIR,");
//$pdf->Cell(10,65,"TORONTO CANADA ");
//	
$pdf->SetLeftMargin(45);
$pdf->SetFontSize(14);
$pdf->WriteHTML($html);
$pdf->Output();
?>