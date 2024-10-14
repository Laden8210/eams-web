<?php 
	session_start();
	include '../sql/sql.php';

	if (isset($_POST['btnsave'])) {
    // Retrieve form data
    $titleEvent = $_POST['txttitlevent'];
    $venue = $_POST['txtvenue'];
    $duration = $_POST['cmbduration'];
    $fromDate = $_POST['txtfrom'];
    $toDate = $_POST['txtto'];
    $officerId = $_POST['cmbofficer'];
    $program = $_POST['cmbprogram2'];
    $amount = $_POST['txtsanction'];
    $semester = $_POST['cmbsem'];
    $timeInMorning = isset($_POST['time_in_morning']) ? $_POST['time_in_morning'] : null;
    $timeOutMorning = isset($_POST['time_out_morning']) ? $_POST['time_out_morning'] : null;
    $timeInAfternoon = isset($_POST['time_in_afternoon']) ? $_POST['time_in_afternoon'] : null;
    $timeOutAfternoon = isset($_POST['time_out_afternoon']) ? $_POST['time_out_afternoon'] : null;

    // Insert data into the database
    $sql = "INSERT INTO tblevent 
            (title_event, venue, duration, from_, to_, officer_id, program_idd, time_in_morning, time_out_morning, time_in_afternoon, time_out_afternoon, amount, sem) 
            VALUES 
            ('$titleEvent', '$venue', '$duration', '$fromDate', '$toDate', '$officerId', '$program', '$timeInMorning', '$timeOutMorning', '$timeInAfternoon', '$timeOutAfternoon', '$amount', '$semester')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        $resmsg = '<script>
            Swal.fire({
                title: "Success",
                text: "Event added successfully",
                icon: "success",
                confirmButtonText: "OK"
            }).then(function() {
                window.location.href = window.location.href;
            });
          </script>';
    } else {
        $resmsg = '<script>
            Swal.fire({
                title: "Error",
                text: "There was an error adding the event: ' . $conn->error . '",
                icon: "error",
                confirmButtonText: "OK"
            }).then(function() {
                window.location.href = window.location.href;
            });
          </script>';
    }

    // Close the connection
   
}

	$qryloadevent = $conn->query("Select * from tblevent inner join tblprogram on tblprogram.programid=tblevent.program_idd inner join tblofficer on tblofficer.officerid=tblevent.officer_id") or die($conn->error);
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Event Attendance Management System</title>
	<?php include 'include/css.php'; ?>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
	<div id="app">
		<?php include 'include/sidebar.php'; ?>

		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    	<?php 
    		echo $resmsg;
    	 ?>

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
			                        <li class="breadcrumb-item active" aria-current="page">Student</li>
			                    </ol>
			                </nav>
			            </div>
			        </div>
			    </div>

			    <div>
			    	<button class="btn btn-primary mb-2" type="button" data-bs-toggle="modal" data-bs-target="#modaladdstudent"> <i class="bi bi-plus-circle-fill"></i> Add Event</button>
			    </div>
			    <section  class="section">
			    	<div class="card">
			    		<div class="card-header">
			                Student List
			            </div>

			            <div class="card-body">
			            	<table class="table table-striped" id="table1">
			            		 <thead>
			                        <tr>
			                        	<!-- <th style="display: none;"></th>
			                        	<th style="display: none;"></th>
			                        	<th style="display: none;"></th> -->
			                        	<th style="display: none;"></th>
			                            <th>Event</th>
			                            <th>Venue</th>
			                            <th>Duration</th>
			                            <th>Date</th>
			                            <th>Program</th>
			                            <th>Officers Assigned</th>
			                        </tr>
			                    </thead>
			                    <tbody>
			                    	<?php 
			                    		while ($rowloadevent = $qryloadevent->fetch_assoc()) {
			                    			$date = $rowloadevent['from_']."-".$rowloadevent['to_'];
			                    			$fullname = $rowloadevent['off_fname']." ".$rowloadevent['off_lname'];
			                    			echo '<tr>
			                    					<td style="display: none;">'.$rowloadevent['eventid'].'</td>
			                    					<td>'.$rowloadevent['title_event'].'</td>
			                    					<td>'.$rowloadevent['venue'].'</td>
			                    					<td>'.$rowloadevent['duration'].'</td>
			                    					<td>'.$date.'</td>
			                    					<td>'.$rowloadevent['program'].'</td>
			                    					<td>'.$fullname.'</td>
			                    			      </tr>';
			                    		}
			                    	 ?>
			                    </tbody>
			            	</table>
			            </div>
			    	</div>
			    </section>

            </div>
		</div>
		<!-- main div -->
	</div>
	<!-- main content -->

	<div class="modal fade text-left" id="modaladdstudent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <form method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel1">Add Student</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Title of Event</label>
                            <input type="text" name="txttitlevent" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label>Venue</label>
                            <input type="text" name="txtvenue" class="form-control">
                        </div>
                        
                        <div class="col-md-6">
                            <label>From</label>
                            <input type="date" name="txtfrom" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>To</label>
                            <input type="date" name="txtto" class="form-control">
                        </div>
                          <div class="col-md-6">
                            <label>Program</label>
                         <select class="form-control" name="cmbprogram2" id="edit-cmbprogram">
						    <option disabled selected>Select Program</option>
						    <?php 
						       $qryloadprogram = $conn->query("Select * from tblprogram") or die($conn->error);
                                  while ($rowloadprogram = $qryloadprogram->fetch_assoc()) {
                                   echo '<option value="'.$rowloadprogram['programid'].'">'.$rowloadprogram['program'].'</option>';
                              }
						    ?>
						    <option>All</option>
						</select>

                        </div>
                        <div class="col-md-6">
                            <label>Officer</label>
                            <select class="form-control" name="cmbofficer">
                            	<option disabled selected>
                            		Select Option	
                            	</option>
                            	<?php 
                            			$qryloadofficer = $conn->query("Select * from tblofficer") or die($conn->error);
                            			while ($rowloadofficer = $qryloadofficer->fetch_assoc()) {
                            				$fullname = $rowloadofficer['off_fname']." ".$rowloadofficer['off_lname'];
                            				echo '<option value="'.$rowloadofficer['officerid'].'">'.$fullname.'</option>';
                            			}

                            			 $conn->close();
                            		 ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Sanction Fee</label>
                            <input type="text" name="txtsanction" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Semester</label>
                           <select class="form-control" name="cmbsem">
                               <option disabled selected>Select Semester</option>
                               <option>1st</option>
                               <option>2nd</option>
                           </select>
                        </div>


                        <div class="col-md-12">
                            <label>Duration</label>
                            <select class="form-control" name="cmbduration" id="cmbduration">
                                <option disabled selected>Select Option</option>
                                <option value="whole-day">Whole Day</option>
                                <option value="half-day-morning">Half-Day Morning</option>
                                <option value="half-day-afternoon">Half-Day Afternoon</option>
                            </select>
                        </div>

                        <!-- Time In and Time Out fields -->
                        <div class="col-md-6 time-fields" id="time-in-morning" style="display: none;">
                            <label>Time In Morning</label>
                            <input type="time" name="time_in_morning" class="form-control">
                        </div>
                        <div class="col-md-6 time-fields" id="time-out-morning" style="display: none;">
                            <label>Time Out Morning</label>
                            <input type="time" name="time_out_morning" class="form-control">
                        </div>
                        <div class="col-md-6 time-fields" id="time-in-afternoon" style="display: none;">
                            <label>Time In Afternoon</label>
                            <input type="time" name="time_in_afternoon" class="form-control">
                        </div>
                        <div class="col-md-6 time-fields" id="time-out-afternoon" style="display: none;">
                            <label>Time Out Afternoon</label>
                            <input type="time" name="time_out_afternoon" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button name="btnsave" type="submit" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Save</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>



	<?php include 'include/js.php'; ?>
	 <script>
	    // Simple Datatable
	    let table1 = document.querySelector('#table1');
	    let dataTable = new simpleDatatables.DataTable(table1);
	</script>
	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function () {
        const durationSelect = document.getElementById('cmbduration');
        const timeInMorning = document.getElementById('time-in-morning');
        const timeOutMorning = document.getElementById('time-out-morning');
        const timeInAfternoon = document.getElementById('time-in-afternoon');
        const timeOutAfternoon = document.getElementById('time-out-afternoon');

        durationSelect.addEventListener('change', function () {
            // Hide all time fields by default
            timeInMorning.style.display = 'none';
            timeOutMorning.style.display = 'none';
            timeInAfternoon.style.display = 'none';
            timeOutAfternoon.style.display = 'none';

            // Show fields based on selected duration
            if (this.value === 'half-day-morning') {
                timeInMorning.style.display = 'block';
                timeOutMorning.style.display = 'block';
            } else if (this.value === 'half-day-afternoon') {
                timeInAfternoon.style.display = 'block';
                timeOutAfternoon.style.display = 'block';
            } else if (this.value === 'whole-day') {
                timeInMorning.style.display = 'block';
                timeOutMorning.style.display = 'block';
                timeInAfternoon.style.display = 'block';
                timeOutAfternoon.style.display = 'block';
            }
        });
    });
	</script>
</body>
</html>