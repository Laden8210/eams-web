<?php 
	include '../sql/sql.php';

		// Initialize an empty events array
		$events = [];

		// Query to fetch events from the tblevent table
		$sql = "SELECT title_event, venue, duration, from_, to_ FROM tblevent";
		$result = $conn->query($sql);

		// Check if the query executed successfully
		if (!$result) {
		    // Output SQL error for debugging
		    echo json_encode(['error' => 'Database query failed: ' . $conn->error]);
		    exit;
		}

		// Fetch events and format them for FullCalendar
		while ($row = $result->fetch_assoc()) {
		    $events[] = [
		        'title' => $row['title_event'],
		        'start' => $row['from_'], // Start date
		        'end' => $row['to_'], // End date
		        'description' => $row['venue'], // Optional venue description
		    ];
		}

		// Set the content type to JSON and output the events
		header('Content-Type: application/json');
		echo json_encode($events);
 ?>