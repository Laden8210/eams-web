<?php 
	// Include the PHP QR Code library
include '../qrcodegenerator/phpqrcode/qrlib.php';

// Get the student ID from the URL
$student_id = $_GET['student_id'];

// Set the path for storing QR codes temporarily
$tempDir = "qrcodes/";
if (!file_exists($tempDir)) {
    mkdir($tempDir);
}

// Generate the file name for the QR code
$filename = $tempDir . 'qr_' . $student_id . '.png';

// Generate the QR code image
QRcode::png($student_id, $filename, QR_ECLEVEL_L, 5);

// Output the QR code image
header('Content-Type: image/png');
readfile($filename);

// Optionally, delete the file after serving it
unlink($filename);
 ?>