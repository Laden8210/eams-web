<?php 
	session_start();
	include '../sql/sql.php';

	$studentId = $_GET['student_id'];
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
        IFNULL(p.status, 'Unpaid') AS payment_status -- Fetch payment status, default to 'Unpaid'
    FROM tblattendance a
    JOIN tblevent e ON a.event_id = e.eventid
    LEFT JOIN tblpayment p ON p.pay_eventid = e.eventid AND p.stud_payid = '$studentId' -- Join with payment table
    WHERE a.id_student = '$studentId'
    GROUP BY e.eventid, e.title_event, e.amount, p.status
") or die($conn->error);

echo '<table class="table table-bordered">
        <thead>
            <tr>
                <th colspan="11"></th>
            </tr>
            <tr>
                <th style="display:none;"></th>
                <th rowspan="2">Event</th>
                <th colspan="4">Status</th>
                <th rowspan="2">Total no. of Present</th>
                <th rowspan="2">Total no. of Absent</th>
                <th rowspan="2">Amount of Penalty</th>
                <th rowspan="2">Total no. of Payment</th>
                <th rowspan="2">Payment Status</th>
                <th rowspan="2">Options</th>
            </tr>
            <tr class="status-row">
                <th>AM In</th>
                <th>AM Out</th>
                <th>PM In</th>
                <th>PM Out</th>
            </tr>
        </thead>
        <tbody>';

$totalAmount = 0;
$isFullPaid = true; // Flag to check if all events are paid
while ($rowAttendance = $qryAttendance->fetch_assoc()) {
    $penaltyAmount = floatval($rowAttendance['amount']);
    $totalAmount += $penaltyAmount;
    $paymentStatus = $rowAttendance['payment_status'];

    // Determine if the button should be disabled
    $isPaid = strtolower($paymentStatus) === 'paid';
    $buttonDisabled = $isPaid ? 'disabled' : '';
    $buttonClass = $isPaid ? 'btn-secondary' : 'btn-primary';
    $buttonText = $isPaid ? 'Paid' : 'Pay';

    // Check if all events are paid
    if (!$isPaid) {
        $isFullPaid = false;
    }

    echo "<tr>
            <td style='display:none;'>{$rowAttendance['eventid']}</td>
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
            <td>
                <button class='btn $buttonClass btn-sm pay-button' type='button' data-student-id='$studentId' $buttonDisabled>$buttonText</button>
            </td>
          </tr>";
}

// Determine the full payment button state
$fullPaymentButtonDisabled = $isFullPaid ? 'disabled' : '';
$fullPaymentButtonClass = $isFullPaid ? 'btn-secondary' : 'btn-primary';
$fullPaymentButtonText = $isFullPaid ? 'Paid' : 'Full Payment';

echo "<tr>
        <td colspan='8' class='full-width' style='text-align:end'>Total amount of Payment:</td>
        <td id='totalAmountPaymentSecondSemester' style='font-weight: bold; text-align: center; font-size: 12px;'>" . number_format($totalAmount, 2) . "</td>
        <td class='payment-status " . ($isFullPaid ? 'paid' : 'unpaid') . "'>" . ($isFullPaid ? 'Paid' : 'Unpaid') . "</td>
        <td><button class='btn $fullPaymentButtonClass btn-sm fullpayment-button' data-student-id='$studentId' type='button' $fullPaymentButtonDisabled>$fullPaymentButtonText</button></td>
      </tr>
    </tbody>
</table>";

$conn->close();



 ?>