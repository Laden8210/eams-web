<?php 
	include '../sql/sql.php';

	$studentId = $_POST['student_id'];
	$eventId = $_POST['event_id'];
	$amount = $_POST['amount'];

	// Check if a payment record already exists
	$checkPayment = $conn->query("SELECT * FROM tblpayment WHERE stud_payid = '$studentId' AND pay_eventid = '$eventId'");

	if ($checkPayment->num_rows > 0) {
	    // Update the existing payment record to "Paid"
	    $updatePayment = $conn->query("UPDATE tblpayment SET status = 'Paid', totalamount = '$amount' WHERE stud_payid = '$studentId' AND pay_eventid = '$eventId'");
	    if ($updatePayment) {
	        echo "Payment updated successfully.";
	    } else {
	        echo "Failed to update payment.";
	    }
	} else {
	    // Insert new payment record with "Paid" status
	    $insertPayment = $conn->query("INSERT INTO tblpayment (stud_payid, pay_eventid, totalamount, status) VALUES ('$studentId', '$eventId', '$amount', 'Paid')");
	    if ($insertPayment) {
	        echo "Payment inserted successfully.";
	    } else {
	        echo "Failed to insert payment.";
	    }
	}

	$conn->close();
 ?>