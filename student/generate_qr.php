<?php 
// Include the PHP QR Code library
include '../qrcodegenerator/phpqrcode/qrlib.php';

// Get the student ID from the URL
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';

// Check if the student ID is valid
if (empty($student_id)) {
    die('Student ID is missing');
}

// Set the path for storing QR codes temporarily
$tempDir = __DIR__ . "/qrcodes/";
if (!file_exists($tempDir)) {
    mkdir($tempDir, 0777, true); // Create directory with correct permissions
}

// Generate the file name for the QR code
$filename = $tempDir . 'qr_' . $student_id . '.png';

// Generate the QR code image
QRcode::png($student_id, $filename, QR_ECLEVEL_L, 5);

// Check if the QR code file was created successfully
if (!file_exists($filename)) {
    die('Failed to generate QR code');
}

// Set headers to prompt download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filename));

// Output the QR code image for download
readfile($filename);

// Optionally, delete the file after serving it
unlink($filename);
exit;
 ?>