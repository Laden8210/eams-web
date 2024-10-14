<?php
include '../sql/sql.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->username) || empty($data->password)) {
        echo json_encode(array('error' => 'Please fill all fields'));
        return;
    }
    $username = $data->username;
    $password = $data->password;

    $sql = "SELECT * FROM tblofficer WHERE off_username = '$username' AND off_passw = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array('error' => 'Invalid username or password'));
    }
} else {
    echo json_encode(array('error' => 'Invalid request'));
}
