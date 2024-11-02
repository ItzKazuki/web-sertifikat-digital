<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('../../service/fpdf186/fpdf.php');

header("content-type: application/png");

$font = "../../assets/font/calibri-font-family/calibri-regular.ttf";
$time = time();

$img = imagecreatefrompng("../../assets/uploads/certificates/template1.png");
$color = imagecolorallocate($img,19,21,22);
imagettftext($img, 60,0,690,750,$color,$font, "Ayu jhyg uygjmhbv");
imagepng($img, "../../assets/uploads/$time.png");
imagedestroy($img);

$pdf = new FPDF();
$pdf->AddPage("L", "A5");

$pdf->Image("../../assets/uploads/$time.png", 0,0,210,148);
ob_end_clean();
$pdf->Output();
