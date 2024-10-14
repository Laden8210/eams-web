<?php 

	$officer_id = $_GET['id']; // Get officer ID from AJAX request
	$result = $conn->query("SELECT * FROM tblofficer WHERE officerid = '$officer_id'");

	if ($result->num_rows > 0) {
	    echo json_encode($result->fetch_assoc());
	} else {
	    echo json_encode([]);
	}

	$conn->close();
 ?>