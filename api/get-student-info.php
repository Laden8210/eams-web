<?php
include '../sql/sql.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->student_id)) {
        echo json_encode(array('error' => 'Please fill all fields'));
        return;
    }
    $student_id = $data->student_id;


    $sql = "SELECT * FROM tblstudent 
    JOIN tblprogram ON tblstudent.stud_programid = tblprogram.programid
    WHERE tblstudent.student_id = '$student_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array('error' => 'Student not found'));
    }
} else {
    echo json_encode(array('error' => 'Invalid request'));
}
