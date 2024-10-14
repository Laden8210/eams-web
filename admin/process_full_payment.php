<?php 
	session_start();
include '../sql/sql.php';

$studentId = $_POST['student_id'];

// Update all payments to 'Paid' for the selected student
$updateQuery = $conn->query("
    UPDATE tblpayment 
    SET status = 'Paid' 
    WHERE stud_payid = '$studentId'
");

if ($updateQuery) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$conn->close();
 ?>