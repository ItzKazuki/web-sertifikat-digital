<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../service/connection.php';


require('../../service/fpdf186/fpdf.php');

header("content-type: application/png");

$fontBold = "../../assets/font/montserrat/static/Montserrat-Bold.ttf";
$font = "../../assets/font/montserrat/static/Montserrat-Light.ttf";

$time = time();

$img = imagecreatefrompng("../../assets/uploads/templates/template3.png");
$color = imagecolorallocate($img,19,21,22);

imagettftext($img, 60,0,620,720,$color,$fontBold, "Ayu jhyg uygjmhbv");
imagettftext($img, 29,0,520,820,$color,$font, "Untuk menyelesaikan pelatihan Desain Grafis yang");
imagettftext($img, 29,0,480,865,$color,$font, "diselenggarakan oleh Liceria & Co pada 28 Agustus 2023");

imagettftext($img, 30,0,320,1130,$color,$fontBold, "Ayu jhyg uygjmhbv");
imagettftext($img, 30,0,1270,1130,$color,$fontBold, "Ayu jhyg uygjmhbv");


imagepng($img, "../../assets/uploads/$time.png");
imagedestroy($img);

$pdf = new FPDF();
$pdf->AddPage("L", "A5");

$pdf->Image("../../assets/uploads/$time.png", 0,0,210,148);
ob_end_clean();
$pdf->Output();
