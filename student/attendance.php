<?php 
	include '../sql/sql.php';
	session_start();
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Event Attendance Management System</title>
	<?php include 'include/css.php'; ?>
<body>
	<div id="app">
		<?php include 'include/sidebar.php'; ?>

		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<div id="main">
			<header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
            	<div class="page-title">
			        <div class="row">
			            <div class="col-12 col-md-6 order-md-1 order-last">
			                <h3>Student</h3>
			                <p class="text-subtitle text-muted">Student Section</p>
			            </div>
			            <div class="col-12 col-md-6 order-md-2 order-first">
			                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
			                    <ol class="breadcrumb">
			                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
			                        <li class="breadcrumb-item active" aria-current="page">Attendance</li>
			                    </ol>
			                </nav>
			            </div>
			        </div>
			    </div>

			    <div>
			    	<!-- <button class="btn btn-primary mb-2" type="button" data-bs-toggle="modal" data-bs-target="#modaladdstudent"> <i class="bi bi-plus-circle-fill"></i> Add Event</button> -->
			    </div>
			    <section  class="section">
			    	<div class="card">
			    		<div class="card-header">
			                Attendance Records
			            </div>

			            <div class="card-body">
			            	<?php 
			            		$studentId = $_SESSION['studid'];

								// Fetch attendance data from the database
								$qryAttendance = $conn->query("
								    SELECT
								        e.eventid, 
								        e.title_event,
								        MAX(CASE WHEN a.session = 'Morning' AND a.type_ = 'Time In' THEN a.attendance ELSE '' END) AS am_in,
								        MAX(CASE WHEN a.session = 'Morning' AND a.type_ = 'Time Out' THEN a.attendance ELSE '' END) AS am_out,
								        MAX(CASE WHEN a.session = 'Afternoon' AND a.type_ = 'Time In' THEN a.attendance ELSE '' END) AS pm_in,
								        MAX(CASE WHEN a.session = 'Afternoon' AND a.type_ = 'Time Out' THEN a.attendance ELSE '' END) AS pm_out,
								        COUNT(CASE WHEN a.type_ = 'Time In' THEN 1 END) AS total_present,
								        COUNT(CASE WHEN a.type_ = 'Absent' THEN 1 END) AS total_absent,
								        e.amount,
								        IFNULL(p.status, 'Unpaid') AS payment_status
								    FROM tblattendance a
								    JOIN tblevent e ON a.event_id = e.eventid
								    LEFT JOIN tblpayment p ON p.pay_eventid = e.eventid AND p.stud_payid = '$studentId'
								    WHERE a.id_student = '$studentId'
								    GROUP BY e.eventid, e.title_event, e.amount, p.status
								") or die($conn->error);

								$totalAmount = 0;
								$isFullPaid = true; // Flag to check if all events are paid

								echo '<table class="table table-bordered">
					                    <thead>
					                        <tr>
					                            <th colspan="11"></th>
					                        </tr>
					                        <tr>
					                            <th rowspan="2">Event</th>
					                            <th colspan="4">Status</th>
					                            <th rowspan="2">Total no. of Present</th>
					                            <th rowspan="2">Total no. of Absent</th>
					                            <th rowspan="2">Amount of Penalty</th>
					                            <th rowspan="2">Total no. of Payment</th>
					                            <th rowspan="2">Payment Status</th>
					                        </tr>
					                        <tr class="status-row">
					                            <th>AM In</th>
					                            <th>AM Out</th>
					                            <th>PM In</th>
					                            <th>PM Out</th>
					                        </tr>
					                    </thead>
					                    <tbody>';
					            while ($rowAttendance = $qryAttendance->fetch_assoc()) {
						    $penaltyAmount = floatval($rowAttendance['amount']);
						    $totalAmount += $penaltyAmount;
						    $paymentStatus = $rowAttendance['payment_status'];

						    // Determine if the button should be disabled
						    $isPaid = strtolower($paymentStatus) === 'paid';
						    $buttonClass = $isPaid ? 'btn-secondary' : 'btn-primary';
						    $buttonText = $isPaid ? 'Paid' : 'Pay';

						    // Check if all events are paid
						    if (!$isPaid) {
						        $isFullPaid = false;
						    }

						    echo "<tr>
						            <td>{$rowAttendance['title_event']}</td>
						            <td>{$rowAttendance['am_in']}</td>
						            <td>{$rowAttendance['am_out']}</td>
						            <td>{$rowAttendance['pm_in']}</td>
						            <td>{$rowAttendance['pm_out']}</td>
						            <td class='totalPresent'>{$rowAttendance['total_present']}</td>
						            <td class='totalAbsent'>{$rowAttendance['total_absent']}</td>
						            <td class='amountpenalty'>{$rowAttendance['amount']}</td>
						            <td class='totalPayment'>{$rowAttendance['amount']}</td>
						            <td class='payment-status " . strtolower($paymentStatus) . "'>$paymentStatus</td>
						          </tr>";
						}

						// Determine the full payment button state
						$fullPaymentButtonDisabled = $isFullPaid ? 'disabled' : '';
						$fullPaymentButtonClass = $isFullPaid ? 'btn-secondary' : 'btn-primary';
						$fullPaymentButtonText = $isFullPaid ? 'Paid' : 'Full Payment';

						echo "<tr>
						        <td colspan='8' class='full-width' style='text-align:end;'>Total amount of Payment:</td>
						        <td id='totalAmountPaymentFirstSemester' style='font-weight: bold; text-align: center; font-size: 12px;'>" . number_format($totalAmount, 2) . "</td>
						        <td class='payment-status " . ($isFullPaid ? 'paid' : 'unpaid') . "'>" . ($isFullPaid ? 'Paid' : 'Unpaid') . "</td>
						        
						      </tr>
						    </tbody>
						    </table>";

						    $conn->close();
			            	 ?>
			            </div>
			    	</div>
			    </section>

            </div>
		</div>
		<!-- main div -->
	</div>
	<!-- main content -->


	<?php include 'include/js.php'; ?>
</body>
</html>