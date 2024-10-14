<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['officerid'])) {
    $officerId = $_POST['officerid'];

    // Prepare the delete statement
    $sql = "DELETE FROM tblofficer WHERE officerid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $officerId);

    // Execute the statement and check the result
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
}

$conn->close();
 ?>