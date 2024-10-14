<?php
include '../sql/sql.php';
session_start();

$studentId = $_GET['sid'];

use Dompdf\Dompdf;
use Dompdf\Options;

require 'dompdf/autoload.inc.php'; // Ensure the path is correct
$output = '';
$totalAmount = 0.0; // Initialize total amount
$allPaid = true; // Flag to track if all rows are paid

$qryname = $conn->query("Select * from tblstudent where studentid='$studentId'") or die($conn->error);
$rowfullname = $qryname->fetch_array();
$fullname = $rowfullname['stud_fname']." ".$rowfullname['stud_lname'];

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

// Set up Dompdf options
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('chroot', realpath(''));

// Instantiate Dompdf
$dompdf = new Dompdf($options);

$html = '<style type="text/css">
            table {
                border-collapse: collapse;
            }
            .table-bordered {
                border:1px solid #000000;
            }
            td, th {
                border:1px solid #000000;
                border-bottom-width:2px;
            }
            .circle-image {
                width: 90px;
                height: auto;
                float: left;
            }
            .header-container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }
            .header-text {
                text-align: center;
                flex: 1;
            }
            .header-text p {
                margin: 0;
            }
            .text {
                color: black;
                font-size: 18px;
                font-weight: bold;
                text-align: center;
            }
            .text5 {
                color: black;
                text-align: center;
                font-size: 18px;
                font-weight: bold;
                display: block;
            }
            .text1 {
                color: maroon;
                font-size: 18px;
                font-weight: bold;
                text-align: center;
            }
            .text2 {
                color: green;
                font-size: 18px;
                font-weight: bold;
                text-align: center;
            }
            .text3 {
                color: black;
                font-size: 18px;
                font-weight: bold;
                text-align: center;
            }
            .text4 {
                color: black;
                font-size: 19px;
                font-weight: bold;
                text-align: center;
            }
            .additional-logo {
                width: 90px;
                height: auto;
                float: right;
                margin-top: -100px
            }
            .logo {
                width: 700px;
                text-align: center;
                display: block;
            }
        </style>

        <div class="header-container">
            <img src="include/logo/logo2.png" alt="Circle Image" class="circle-image">
            <div class="header-text" style="margin-right:90px">
                <p class="text">Republic of the Philippines</p>
                <p class="text1">MINDANAO STATE UNIVERSITY</p>
                <p class="text2">LANAO DEL NORTE AGRICULTURAL COLLEGE</p>
                <p class="text3">Ramain, Sultan Naga Dimaporo, Lanao Del Norte, Philippines</p>
                <p class="text4">COLLEGE OF COMPUTER STUDIES</p>
            </div>
            <img src="include/logo/logo1.png" alt="Additional Logo" class="additional-logo">
        </div>
        <div><img src="include/logo/logo3.jfif" alt="Logo" class="logo"></div>
        <div><p class="text5">Attendance Records</p></div>
        <h5>Name: '.$fullname.'</h5>
        <table class="table table-bordered">
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

    // Check if all statuses are "Paid"
    if (strtolower($paymentStatus) !== 'paid') {
        $allPaid = false; // Set the flag to false if any status is not "Paid"
    }

    // Append each row to the output
    $output .= "<tr>
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

// Determine the final payment status
$finalPaymentStatus = $allPaid ? 'Paid' : 'Unpaid';

$html2 = "<tr>
    <td colspan='8' class='full-width' style='text-align:end;'>Total amount of Payment:</td>
    <td id='totalAmountPaymentFirstSemester' style='font-weight: bold; text-align: center; font-size: 12px;'>" . number_format($totalAmount, 2) . "</td>
    <td class='payment-status " . strtolower($finalPaymentStatus) . "'>$finalPaymentStatus</td>
</tr>
</tbody>
</table>";

// Close the database connection
$conn->close();

// Load the complete HTML into Dompdf
$dompdf->loadHtml($html . $output . $html2);

// Set the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML to PDF
$dompdf->render();

// Output the generated PDF to the browser
$dompdf->stream('document.pdf', array("Attachment" => false)); // Ensure the filename is a string
?>
