<?php
include '../sql/sql.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->officer_id)) {
        echo json_encode(array('error' => 'Please fill all fields'));
        return;
    }
    
    $officerId = $data->officer_id;

    $stmt = $conn->prepare("SELECT * FROM tblevent 
    join tblprogram on tblevent.program_idd = tblprogram.programid
    WHERE officer_id = ?");
    $stmt->bind_param("i", $officerId); 
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
        echo json_encode(array('error' => 'No events found for this officer'));
    }

    $stmt->close();
} else {
    echo json_encode(array('error' => 'Invalid request'));
}
?>
