<?php

require('../../service/fpdf186/fpdf.php');

header('content-type: image/jpeg');

$time = time();

$image = imagecreatefrompng('../../assets/uploads/certificates/Blue%20and%20Gold%20Classic%20Certificate%20of%20Participation.png');


$color = imagecolorallocate($image,19,21,22);

// imagettftext($image, 60,0,690,800);
imagejpeg($image, "../../assets/uploads/user-certificate-$time.jpg");

imagedestroy($image);

$pdf = new FPDF();
$pdf->AddPage();

$pdf->Image("../../assets/uploads/user-certificate-$time.jpg", 0,0,210,148);

ob_end_clean();

$pdf->Output();