<?php
include '../sql/sql.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->event_id)) {
        echo json_encode(array('error' => 'Please fill all fields'));
        return;
    }
    
    $event_id = $data->event_id;

    $stmt = $conn->prepare(
        "SELECT 
            e.eventid, 
            e.title_event, 
            e.venue, 
            e.duration, 
            e.from_, 
            e.to_, 
            s.student_id, 
            s.stud_fname,
            s.stud_lname,
            p.program,
            s.yrlevel,
            s.stud_section,
            GROUP_CONCAT(CONCAT(a.type_, ': ', a.attendance) SEPARATOR '; ') AS attendance_details
         FROM tblevent e
         JOIN tblattendance a ON e.eventid = a.event_id
         JOIN tblstudent s ON a.id_student = s.studentid
         JOIN tblprogram p ON e.program_idd = p.programid
         WHERE a.event_id = ?
         GROUP BY s.student_id, s.stud_fname, s.stud_lname, e.eventid, s.yrlevel, s.stud_section"
    );
    
    $stmt->bind_param("i", $event_id); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $events = array();

        while ($row = $result->fetch_assoc()) {

            $event = array();
            foreach ($row as $key => $value) {
                $event[$key] = (string)$value;
            }
            $events[] = $event; 
        }

        echo json_encode($events);
    } else {
        echo json_encode(array('error' => 'No events or attendance records found for this event'));
    }

    $stmt->close();
} else {
    echo json_encode(array('error' => 'Invalid request'));
}
?>
