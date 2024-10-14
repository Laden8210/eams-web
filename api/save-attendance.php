<?php
include '../sql/sql.php';

date_default_timezone_set('Asia/Manila'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->event_id) || empty($data->student_id) || empty($data->type)) {
        echo json_encode(array('error' => 'Please fill all fields'));
        return;
    }

    $student_id = $data->student_id;
    $event_id = $data->event_id;
    $type = $data->type;  

    $morningOrAfter = date('H') < 12 ? 'Morning' : 'Afternoon';  

    $checkStmt = $conn->prepare("SELECT * FROM tblattendance WHERE event_id = ? AND id_student = ? AND session = ? AND type_ = ?");
    $checkStmt->bind_param("ssss", $event_id, $student_id, $morningOrAfter, $type);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(array('error' => 'Student has already timed ' . $type . ' for this session'));
        $checkStmt->close();
        return;
    }

    $checkStmt->close();


    $stmt = $conn->prepare("INSERT INTO tblattendance (event_id, id_student, session, type_, attendance, dt_attendance) VALUES (?, ?, ?, ?, ?, NOW())");
    
    if (!$stmt) {
        echo json_encode(array('error' => 'Failed to prepare statement'));
        exit();
    }

    $attendance = date('H:i:s');
    $stmt->bind_param("sssss", $event_id, $student_id, $morningOrAfter, $type, $attendance);

    if ($stmt->execute()) {
        echo json_encode(array('success' => 'Attendance recorded successfully'));
    } else {
        echo json_encode(array('error' => 'Failed to record attendance', 'details' => $stmt->error));
    }

    $stmt->close();
} else {
    echo json_encode(array('error' => 'Invalid request'));
}

$conn->close();
?>
