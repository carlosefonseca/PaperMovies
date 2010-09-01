<?php
require("fpdf/fpdf.php");

$pdf = new FPDF("L", "mm", "A5");
$pdf->SetAutoPageBreak(false);
$s = "     ~     ";
$pdf->AddPage();

$pdf->Image('poster.jpg',5,10,88);

// TITLE PT
$pdf->SetFont("Arial","B","25");
$pdf->SetTextColor(0,80,180);
$pdf->SetX(95);
$pdf->MultiCell(110,9, utf8_decode($titlept),0,"C");


// TITLE EN
if (strpos($titlept, $title) === false) {
	$fsize = 13;
	$pdf->SetFont("Arial","B",$fsize);
	while ($pdf->GetStringWidth($title) > 110) {
		$fsize--;
		$pdf->SetFont("Arial","B",$fsize);
	}
	$pdf->SetTextColor(50,130,180);
	$pdf->SetX(95);
	$pdf->Cell(110,13,$title,0,1,"C");
}

// PLOT
//$plot = quotes($plot);
$plot = utf8_decode($plot);
$pdf->SetTextColor(0,0,0);
$pdf->SetX(95);
if (strlen($plot)<600) {
	$pdf->SetFont("Arial","","14");
	$pdf->MultiCell(110,6,$plot,0,"E");	
} else if (strlen($plot)<1000) {
	$pdf->SetFont("Arial","","11");
	$pdf->MultiCell(110,5,strtr($plot,array("\n"=>" ")),0,"E");
} else { //if (strlen($plot)<1200) {
	$pdf->SetFont("Arial","","10");
	$pdf->MultiCell(110,4,strtr($plot,array("\n"=>" ")),0,"E");
}

// FOTTER
$pdf->SetTextColor(50,130,180);
$pdf->SetDrawColor(0,80,130);
$pdf->SetY(-18);
$pdf->SetX(95);

$fotter = utf8_decode($genre).$s.utf8_decode($year).$s.utf8_decode($rating)."/10";
$fsize = 14;
$pdf->SetFont("Arial","",$fsize);
while ($pdf->GetStringWidth($fotter) > 110) {
	$fsize--;
	$pdf->SetFont("Arial","",$fsize);
}
$pdf->Cell(110,7,$fotter,"T",0,"C");

$pdf->Output("gerados/".strtr($title,array(" "=>"-")).".pdf","F");





function quotes($txt) {
	//Quotes: Replace smart double quotes with straight double quotes.
	//ANSI version for use with 8-bit regex engines and the Windows code page 1252.
	$txt = preg_replace('[\x84\x93\x94]', '"', $text);
	
	//Quotes: Replace smart double quotes with straight double quotes.
	//Unicode version for use with Unicode regex engines.
	$txt = preg_replace('[\u201C\u201D\u201E\u201F\u2033\u2036]', '"', $text);
	
	//Quotes: Replace smart single quotes and apostrophes with straight single quotes.
	//Unicode version for use with Unicode regex engines.
	$txt = preg_replace("[\u2018\u2019\u201A\u201B\u2032\u2035]", "'", $text);
	
	//Quotes: Replace smart single quotes and apostrophes with straight single quotes.
	//ANSI version for use with 8-bit regex engines and the Windows code page 1252.
	$txt = preg_replace("[\x82\x91\x92]", "'", $text);
	return $txt;
}