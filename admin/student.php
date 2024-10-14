<?php 
	session_start();
	include '../sql/sql.php';
	// include '../qrcodegenerator/phpqrcode/qrlib.php';
	error_reporting(0);

	$resmsg = '';

	if (isset($_POST['btnsave'])) {
		$studentid = $_POST['txtsudentid']; // Student ID
		$fname = $_POST['txtfname'];        // Firstname
		$mdname = $_POST['txtmdname'];      // Middlename
		$lname = $_POST['txtlname'];        // Lastname
		$program = $_POST['cmbprogram'];    // Program
		$yrlevel = $_POST['txtyrlevel'];    // Year Level
		$section = $_POST['txtsection'];    // Section
		$mobile = $_POST['txtmobile'];      // Mobile No.
		$profpic = $_FILES['txtprofpic']; 

		 $username = strtolower(str_replace(' ', '', $fname)) . '.' . strtolower(str_replace(' ', '', $lname)); // Combine fname and lname, remove spaces
    	 $password = 'password123'; // Password hash for security

    	  // Handle file upload
		    $target_dir = "../uploads/"; // Folder to save the uploaded images
		    $filename = basename($_FILES["txtprofpic"]["name"]);
		    $target_file = $target_dir . basename($_FILES["txtprofpic"]["name"]);
		    move_uploaded_file($_FILES["txtprofpic"]["tmp_name"], $target_file);

		 // Insert data into the database
    $sql = "INSERT INTO tblstudent 
            (student_id, stud_fname, stud_mname, stud_lname, stud_programid, yrlevel, stud_section, stud_mobile, stud_profpic, stud_username, stud_password) 
            VALUES('$studentid','$fname','$mdname','$lname','$program','$yrlevel','$section','$mobile','$filename','$username','$password')";

        if ($conn->query($sql) === TRUE) {
          $resmsg = '<script>
                    Swal.fire({
                        title: "Success",
                        text: "Student has been Successfully saved",
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
                        text: "There was an error adding the Student",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(function() {
                        window.location.href = window.location.href;
                    });
                  </script>';
    }

	}

	$qryloadstudent = $conn->query("Select * from tblstudent inner join tblprogram on tblprogram.programid=stud_programid") or die($conn->error);

	if (isset($_POST['btnupdate'])) {

    // Retrieve form data
    $studentid = $_POST['studentid'];
    $fname = $_POST['txtfname'];
    $mname = $_POST['txtmname'];
    $lname = $_POST['txtlname'];
    $program = $_POST['cmbprogram2'];
    $yrlevel = $_POST['txtyrlevel'];
    $section = $_POST['txtsection'];
    $mobile = $_POST['txtmobile'];

    // Fetch current values from the database
    $result = $conn->query("SELECT * FROM tblstudent WHERE studentid = '$studentid'");
    $currentValues = $result->fetch_assoc();

    // Check if any values have changed
    if ($currentValues['stud_fname'] != $fname ||
        $currentValues['stud_mname'] != $mname ||
        $currentValues['stud_lname'] != $lname ||
        $currentValues['stud_programid'] != $program ||
        $currentValues['yrlevel'] != $yrlevel ||
        $currentValues['stud_section'] != $section ||
        $currentValues['stud_mobile'] != $mobile) {

        // Update data in the database
        $sql1 = "UPDATE tblstudent SET 
                    stud_fname = '$fname', 
                    stud_mname = '$mname', 
                    stud_lname = '$lname', 
                    stud_programid = '$program', 
                    yrlevel = '$yrlevel', 
                    stud_section = '$section', 
                    stud_mobile = '$mobile' 
                WHERE studentid = '$studentid'";

        if ($conn->query($sql1) === TRUE) {
            $resmsg = '<script>
                Swal.fire({
                    title: "Success",
                    text: "Student updated successfully",
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
                    text: "There was an error updating the student",
                    icon: "error",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location.href = window.location.href;
                });
              </script>';
        }
    } else {
        // No changes detected
        $resmsg = '<script>
            Swal.fire({
                title: "No Changes",
                text: "No changes detected in the student details.",
                icon: "info",
                confirmButtonText: "OK"
            });
          </script>';
    }
}

	if (isset($_POST['btndelete'])) {
		$deleteid = $_POST['txtdelid'];

		$sqldelete = "Delete from tblstudent where studentid='$deleteid'";

		if ($conn->query($sqldelete) === TRUE) {

			$resmsg = '<script>
                    Swal.fire({
                        title: "Success",
                        text: "Records Successfully Deleted",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(function() {
                        window.location.href = window.location.href;
                    });
                  </script>';
		}else{
			$resmsg = '<script>
                    Swal.fire({
                        title: "Error",
                        text: "There was an error deleting the officer",
                        icon: "error",
                        confirmButtonText: "OK"
                    }).then(function() {
                        window.location.href = window.location.href;
                    });
                  </script>';
		}
	}


			if (isset($_POST['btnattendance'])) {
			    // Retrieve form data
			    $studentId = $_POST['txtstudid'];
			    $eventId = $_POST['cmbevent'];
			    $session = $_POST['cmbsession'];
			    $type = $_POST['cmbtype']; // "Time In" or "Time Out"
			    
			    // Get the current time
			    $attendance = date("H:i:s"); // Current time in HH:MM:SS format

			    // Insert data into the database
			    $sql = "INSERT INTO tblattendance (id_student, event_id, session, type_, attendance) 
			            VALUES ('$studentId', '$eventId', '$session', '$type', '$attendance')";

			    if ($conn->query($sql) === TRUE) {
			        $resmsg = '<script>
                    Swal.fire({
                        title: "Success",
                        text: "Attendance recorded successfully",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(function() {
                        window.location.href = window.location.href;
                    });
                  </script>';
			    } else {
			        $resmsg = '<script>
			            swal.fire({
			                title: "Error",
			                text: "There was an error recording the attendance.",
			                icon: "error",
			                button: "OK"
			            }).then(function() {
			                window.location.href = window.location.href;
			            });
			          </script>';

			    }
			}
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Event Attendance Management System</title>
	<?php include 'include/css.php'; ?>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style type="text/css">
         .circle-image {
                width: 60px;
                height: auto;
                float: left;
                margin-top: -50px
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
                font-size: 12px;
                font-weight: bold;
                text-align: center;
            }
            .text5 {
                color: black;
                text-align: center;
                font-size: 12px;
                font-weight: bold;
                display: block;
            }
            .text1 {
                color: maroon;
                font-size: 12px;
                font-weight: bold;
                text-align: center;
            }
            .text2 {
                color: green;
                font-size: 12px;
                font-weight: bold;
                text-align: center;
            }
            .text3 {
                color: black;
                font-size: 12px;
                font-weight: bold;
                text-align: center;
            }
            .text4 {
                color: black;
                font-size: 12px;
                font-weight: bold;
                text-align: center;
            }
            .additional-logo {
                width: 60px;
                height: auto;
                float: right;
                margin-top: -50px
            }
            .logo {
                width: 700px;
                text-align: center;
                display: block;
            }
            @media print {
            * {
                -webkit-print-color-adjust: exact !important; /* For Safari and Chrome */
                color-adjust: exact !important; /* For Firefox */
                print-color-adjust: exact !important; /* Standard */
            }

            .sheets {
                background-image: url("../admin/include/logo/logo4.jpg") !important; /* Force background image */
                background-size: cover !important;
                background-position: center !important;
            }  
        }

        .overlays {
            background-image: url("../admin/include/logo/logo4.jpg");
            background-size: cover; /* Ensures the image covers the container */
            background-position: center; /* Centers the image */
            background-repeat: no-repeat; /* Prevents repeating the image */
            padding: 20px; /* Adjust padding as needed */
            border-radius: 8px; /* Optional: for rounded corners */
        }
    </style>
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
			    	<button class="btn btn-primary mb-2" type="button" data-bs-toggle="modal" data-bs-target="#modaladdstudent"> <i class="bi bi-plus-circle-fill"></i> Add Student</button>
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
			                        	<th style="display: none;"></th>
			                        	<th style="display: none;"></th>
			                        	<th style="display: none;"></th>
			                        	<th style="display: none;"></th>
			                            <th>ID</th>
			                            <th>Fullname</th>
			                            <th>Program & Year</th>
			                            <th>Section</th>
			                            <th></th>
			                        </tr>
			                    </thead>
			                    <tbody>
			                    	<?php 
			                    		while ($rowloadstudent = $qryloadstudent->fetch_assoc()) {
			                    			$fullname = $rowloadstudent['stud_fname']." ".$rowloadstudent['stud_lname'];
			                    			$programyr = $rowloadstudent['program']." ".$rowloadstudent['yrlevel'];
			                    			echo '<tr>
			                    					<td style="display: none;">'.$rowloadstudent['studentid'].'</td>
			                    					<td style="display: none;">'.$rowloadstudent['stud_fname'].'</td>
			                    					<td style="display: none;">'.$rowloadstudent['stud_mname'].'</td>
			                    					<td style="display: none;">'.$rowloadstudent['stud_lname'].'</td>
			                    					<td style="display: none;">'.$rowloadstudent['program'].'</td>
			                    					<td style="display: none;">'.$rowloadstudent['yrlevel'].'</td>
			                    					<td style="display: none;">'.$rowloadstudent['stud_section'].'</td>
			                    					<td style="display: none;">'.$rowloadstudent['stud_mobile'].'</td>
			                    					<td>'.$rowloadstudent['student_id'].'</td>
			                    					<td>'.$fullname.'</td>
			                    					<td>'.$programyr.'</td>
			                    					<td>'.$rowloadstudent['stud_section'].'</td>
			                    					<td>
			                    						<div class="btn-group mb-1">
			                                            <div class="dropdown">
			                                                <button class="btn btn-primary dropdown-toggle me-1 btn-sm" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			                                                    Action
			                                                </button>
			                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="margin: 0px;">
			                                                  	 <button type="button" class="dropdown-item generate-qr" data-id="' . $rowloadstudent['student_id'] . '" data-filename="'.$rowloadstudent['stud_profpic'].'" data-name="'.$fullname.'" data-program="'.$rowloadstudent['program'].'"> QR Code</button>
			                                                     <button type="button" class="dropdown-item getid1" > Edit</button>
			                                                   	 <a class="dropdown-item" href="#">Profile</a>
			                                                     <button type="button" class="dropdown-item getid2">Delete</button>
			                                                     <button type="button" class="dropdown-item getid3">Attendance</button>
			                                                     <button type="button" data-id="'.$rowloadstudent['student_id'].'"  class="dropdown-item getid4">Records</button>
                                                                 <a href="print_records.php?sid='.$rowloadstudent['studentid'].'" target="blank_" class="dropdown-item"> Print</a>
			                                                </div>
			                                            	</div>
			                                       		 </div>
			                    					</td>
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


	 <div class="modal fade text-left" id="modaladdstudent" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                                         <form method="post" enctype="multipart/form-data">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="myModalLabel1">Add Student</h5>
                                                    <button type="button" class="close rounded-pill"
                                                        data-bs-dismiss="modal" aria-label="Close">
                                                        <i data-feather="x"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                    	<div class="col-md-12">
                                                    		<label>Student ID</label>
                                                    		<input type="text" class="form-control" name="txtsudentid">
                                                    	</div>
                                                    	<div class="col-md-12">
                                                    		<label>Firstname</label>
                                                    		<input type="text" class="form-control" name="txtfname">
                                                    	</div>
                                                    	<div class="col-md-12">
                                                    		<label>Middlename</label>
                                                    		<input type="text" class="form-control" name="txtmdname">
                                                    	</div>
                                                    	<div class="col-md-12">
                                                    		<label>Lastname</label>
                                                    		<input type="text" class="form-control" name="txtlname">
                                                    	</div>
                                                    	<div class="col-md-6">
                                                    		<label>Program</label>
                                                    		<select class="form-control" name="cmbprogram">
                                                    			<option disabled selected>Select Program</option>
                                                    			<?php 
                                                    				$qryloadprogram = $conn->query("Select * from tblprogram") or die($conn->error);
                                                    				while ($rowloadprogram = $qryloadprogram->fetch_assoc()) {
                                                    					echo '<option value="'.$rowloadprogram['programid'].'">'.$rowloadprogram['program'].'</option>';
                                                    				}
                                                    				// $conn->close();
                                                    			 ?>
                                                    		</select>
                                                    	</div>
                                                    	<div class="col-md-6">
                                                    		<label>Year Level</label>
                                                    		<input type="text" class="form-control" name="txtyrlevel">
                                                    	</div>
                                                    	<div class="col-md-6">
                                                    		<label>Section</label>
                                                    		<input type="text" class="form-control" name="txtsection">
                                                    	</div>
                                                    	<div class="col-md-6">
                                                    		<label>Mobile No.</label>
                                                    		<input type="text" class="form-control" name="txtmobile">
                                                    	</div>
                                                    	<div class="col-md-12">
                                                    		<label>Upload Image</label>
                                                    		<input type="file" class="form-control" name="txtprofpic">
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
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

		     <!-- Modal Structure -->
		<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
		    <div class="modal-dialog modal-dialog-centered">
		        <div class="modal-content">
		            <div class="modal-header">
		                <h5 class="modal-title" id="qrModalLabel">Student QR Code</h5>
                        <div class="ml-2"><button class="btn btn-primary btn-sm" onclick="printSheet()"><i class="bi bi-printer-fill"></i></button></div>
		                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		            </div>
		            <div class="modal-body text-center">
                        <div class="overlays" id="attendanceSheet">
                            <!--  <img src="include/logo/logo2.png" style="width: 50px;float: left;" alt="Circle Image" class="circle-image"> -->
                            <i class="fas fa-times close-icon" title="Close" onclick="toggleSheet()"></i>
                            <div class="sheets">
                                <div class="header-container">
                                <img src="include/logo/logo2.png" alt="Circle Image" class="circle-image">
                                <div class="header-text">
                                    <p class="text text-white">Republic of the Philippines</p>
                                    <p class="text1 text-white">MINDANAO STATE UNIVERSITY</p>
                                    <p class="text2 text-white">LANAO DEL NORTE AGRICULTURAL COLLEGE</p>
                                    <p class="text3 text-white">Ramain, Sultan Naga Dimaporo, Lanao Del Norte, Philippines</p>
                                    <p class="text4 text-white">COLLEGE OF COMPUTER STUDIES</p>
                                </div>
                                <img src="include/logo/logo1.png" alt="Additional Logo" class="additional-logo">
                            </div>
                         <!--    <div><img src="include/logo/logo3.jfif" alt="Logo" class="logo"></div> -->
                                <p class="text5 text-white">Event Attendance ID Card</p>
                                <img id="profilepic" src="" alt="Box Image" style="width: 200px; height: auto;">
                                <p class="name"><span name="name"></span></p>
                                <div class="line"></div>
                                <p class="program"><span program="program"></span></p>
                                <!-- QR Code Image -->
                                <img id="qrCodeImage" src="" alt="QR Code" class="img-fluid">
                                <p class="id">Student ID: <span id="studentId"></span></p>
                            </div>
                        </div>
		                
		            </div>
		        </div>
		    </div>
		</div>

		<!-- Edit Student Modal -->
<div class="modal fade text-left" id="modaleditstudent" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <form method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Student</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="studentid" id="edit-studentid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Student ID</label>
                            <input type="text" class="form-control" name="txtsudentid" id="edit-txtsudentid">
                        </div>
                        <div class="col-md-12">
                            <label>Firstname</label>
                            <input type="text" class="form-control" name="txtfname" id="edit-txtfname">
                        </div>
                        <div class="col-md-12">
                            <label>Middlename</label>
                            <input type="text" class="form-control" name="txtmdname" id="edit-txtmdname">
                        </div>
                        <div class="col-md-12">
                            <label>Lastname</label>
                            <input type="text" class="form-control" name="txtlname" id="edit-txtlname">
                        </div>
                        <div class="col-md-6">
                            <label>Program</label>
                         <select class="form-control" name="cmbprogram2" id="edit-cmbprogram">
						    <option disabled>Select Program</option>
						    <?php 
						       $qryloadprogram = $conn->query("Select * from tblprogram") or die($conn->error);
                                  while ($rowloadprogram = $qryloadprogram->fetch_assoc()) {
                                   echo '<option value="'.$rowloadprogram['programid'].'">'.$rowloadprogram['program'].'</option>';
                                                    				}
						       
						    ?>
						</select>

                        </div>
                        <div class="col-md-6">
                            <label>Year Level</label>
                            <input type="text" class="form-control" name="txtyrlevel" id="edit-txtyrlevel">
                        </div>
                        <div class="col-md-6">
                            <label>Section</label>
                            <input type="text" class="form-control" name="txtsection" id="edit-txtsection">
                        </div>
                        <div class="col-md-6">
                            <label>Mobile No.</label>
                            <input type="text" class="form-control" name="txtmobile" id="edit-txtmobile">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button name="btnupdate" type="submit" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Save Changes</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade text-left" id="modaldelstudent" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                                         <form method="post">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="myModalLabel1">Confirmation</h5>
                                                    <button type="button" class="close rounded-pill"
                                                        data-bs-dismiss="modal" aria-label="Close">
                                                        <i data-feather="x"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                	<input type="hidden" name="txtdelid" id="txtdelid">
                                                   <h4>Are You sure you want to delete this records?</h4>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn" data-bs-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Close</span>
                                                    </button>
                                                    <button name="btndelete" type="submit" class="btn btn-primary ml-1">
                                                        <i class="bx bx-check d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Delete</span>
                                                    </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



     <div class="modal fade text-left" id="modalattendance" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                                         <form method="post">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="myModalLabel1">Attendance</h5>
                                                    <button type="button" class="close rounded-pill"
                                                        data-bs-dismiss="modal" aria-label="Close">
                                                        <i data-feather="x"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                	<input type="hidden" name="txtstudid" id="txtstudid">
                                                  <div class="row">
                                                  	<div class="col-md-12"> 
                                                  		<label>Title of the Event</label>
                                                  		<select name="cmbevent" class="form-control">
                                                  			<option disabled selected>Select Event</option>
                                                  			<?php 
                                                  				$qryloadevent = $conn->query("Select * from tblevent") or die($conn->error);
                                                  				while ($rowloadevent = $qryloadevent->fetch_assoc()) {
                                                  					echo '<option value="'.$rowloadevent['eventid'].'">'.$rowloadevent['title_event'].'</option>';

                                                  				}
                                                  				 $conn->close();
                                                  			 ?>
                                                  		</select>
                                                  	</div>
                                                  	<div class="col-md-12"> 
                                                  		<label>Session</label>
                                                  		<select name="cmbsession" class="form-control">
                                                  			<option disabled selected>Select Session</option>
                                                  			<option>Morning</option>
                                                  			<option>Afternoon</option>
                                                  		</select>
                                                  	</div>
                                                  	<div class="col-md-12"> 
                                                  		<label>Attendance</label>
                                                  		<select name="cmbtype" class="form-control">
                                                  			<option disabled selected>Select Option</option>
                                                  			<option>Time In</option>
                                                  			<option>Time Out</option>
                                                  		</select>
                                                  	</div>
                                                  </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn" data-bs-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Close</span>
                                                    </button>
                                                    <button name="btnattendance" type="submit" class="btn btn-primary ml-1">
                                                        <i class="bx bx-check d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Attendance</span>
                                                    </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="modal fade text-left" id="modalrecords" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                                         <form method="post">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="myModalLabel1">Attendance</h5>
                                                    <button type="button" class="close rounded-pill"
                                                        data-bs-dismiss="modal" aria-label="Close">
                                                        <i data-feather="x"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- <div class="text-end mb-2">
                                                        <a href="print_records.php" target="blank_" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill" style="margin-top: 100px"></i> Print</a>
                                                    </div> -->
                                                	<input type="hidden" name="txtstudid2" id="txtstudid2">
                                                 	<div class="row">
                                                 		<div id="attendance-table-container">
                                                 			
                                                 		</div>
                                                 	</div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn" data-bs-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Close</span>
                                                    </button>
                                                  <!--   <button name="btnattendance" type="submit" class="btn btn-primary ml-1">
                                                        <i class="bx bx-check d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Attendance</span>
                                                    </button> -->
                                                    </form>
                                                </div>
                                            </div>
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
        document.querySelectorAll('.generate-qr').forEach(button => {
        button.addEventListener('click', function () {
            const studentId = this.getAttribute('data-id');
            const profpic = this.getAttribute('data-filename');
            const studentName = this.getAttribute('data-name'); // Get the student's name
            const studentProgram = this.getAttribute('data-program'); // Get the student's program
            const imgElement = document.getElementById('profilepic');

            // Display the student's name in the <span> element
            document.querySelector('p.name span[name="name"]').textContent = studentName;

            // Display the student's program in the <span> element
            document.querySelector('p.program span[program="program"]').textContent = studentProgram;

            // Make an AJAX request to generate the QR code
            fetch('generate_qr.php?student_id=' + studentId)
                .then(response => response.blob())
                .then(blob => {
                    // Convert blob to URL and set it as the src of the QR code image
                    const url = URL.createObjectURL(blob);
                    document.getElementById('qrCodeImage').src = url;

                    // Set the src of the profile picture image
                    imgElement.src = `../uploads/${profpic}`;

                    // Show the modal
                    const qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
                    qrModal.show();
                })
                .catch(error => {
                    console.error('Error generating QR code:', error);
                });
        });
    });
});




			document.addEventListener('DOMContentLoaded', function () {
    // Add click event listener to each edit button
    document.querySelectorAll('.getid1').forEach(button => {
        button.addEventListener('click', function () {
            // Get the parent row of the clicked button
            let row = this.closest('tr');

            // Retrieve hidden data from the row
            let studentId = row.cells[0].textContent.trim();   // Hidden student ID
            let fname = row.cells[1].textContent.trim();       // Hidden first name
            let mname = row.cells[2].textContent.trim();       // Hidden middle name
            let lname = row.cells[3].textContent.trim();       // Hidden last name
            let student_id = row.cells[8].textContent.trim();
            let program = row.cells[4].textContent.trim();     // Hidden program ID (if applicable)
            let yrlevel = row.cells[5].textContent.trim();     // Hidden year level (if applicable)
            let section = row.cells[6].textContent.trim();     // Hidden section (if applicable)
            let mobile = row.cells[7].textContent.trim();      // Hidden mobile number


            // Log the values to the console for debugging
            console.log('Student ID:', studentId);
            console.log('First Name:', fname);
            console.log('Middle Name:', mname);
            console.log('Last Name:', lname);
            console.log('Student ID (used for form):', student_id);
            console.log('Program:', program);
            console.log('Year Level:', yrlevel);
            console.log('Section:', section);
            console.log('Mobile:', mobile);

            // Populate the modal fields
            document.getElementById('edit-studentid').value = studentId;
            document.getElementById('edit-txtsudentid').value = student_id;
            document.getElementById('edit-txtfname').value = fname;
            document.getElementById('edit-txtmdname').value = mname;
            document.getElementById('edit-txtlname').value = lname;
            document.getElementById('edit-cmbprogram').value = program;
            document.getElementById('edit-txtyrlevel').value = yrlevel;
            document.getElementById('edit-txtsection').value = section;
            document.getElementById('edit-txtmobile').value = mobile;

            let programDropdown = document.getElementById('edit-cmbprogram');
            for (let option of programDropdown.options) {
                if (option.text === program) {
                    option.selected = true;
                    break;
                }
            }

            // Show the modal
            var modal = new bootstrap.Modal(document.getElementById('modaleditstudent'));
            modal.show();
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Add click event listener to each edit button
    document.querySelectorAll('.getid2').forEach(button => {
        button.addEventListener('click', function() {
            // Get the parent row of the clicked button
            let row = this.closest('tr');

            // Retrieve hidden data from the row
            let studentid = row.cells[0].textContent.trim();
           
            // Populate the modal fields
            document.getElementById('txtdelid').value = studentid;

            // Show the modal
            var modal = new bootstrap.Modal(document.getElementById('modaldelstudent'));
            modal.show();
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    // Add click event listener to each edit button
    document.querySelectorAll('.getid3').forEach(button => {
        button.addEventListener('click', function() {
            // Get the parent row of the clicked button
            let row = this.closest('tr');

            // Retrieve hidden data from the row
            let studentid = row.cells[0].textContent.trim();
           
            // Populate the modal fields
            document.getElementById('txtstudid').value = studentid;

            // Show the modal
            var modal = new bootstrap.Modal(document.getElementById('modalattendance'));
            modal.show();
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Add click event listener to each edit button
    document.querySelectorAll('.getid4').forEach(button => {
        button.addEventListener('click', function() {
            // Get the parent row of the clicked button
            let row = this.closest('tr');

            // Retrieve hidden data from the row
            let studentid = row.cells[0].textContent.trim();
           
            // Populate the modal fields
            document.getElementById('txtstudid2').value = studentid;
            loadAttendanceRecords(studentid);
            // Show the modal
            var modal = new bootstrap.Modal(document.getElementById('modalrecords'));
            modal.show();
        });
    });
});


// 		document.addEventListener('DOMContentLoaded', function () {
//     document.querySelectorAll('.getid4').forEach(button => {
//         button.addEventListener('click', function () {
//             let studentId = this.getAttribute('data-id');
//             document.getElementById('txtstudid2').value = studentId;
//             // loadAttendanceRecords(studentId);
//         });
//     });
// });

function loadAttendanceRecords(studentId) {
    // Fetch and display attendance records for the selected student
    fetch(`fetch_attendance.php?student_id=${studentId}`)
        .then(response => response.text())
        .then(data => {
            document.querySelector('#attendance-table-container').innerHTML = data;
        })
        .catch(error => console.error('Error fetching attendance records:', error));
}



	document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (event) {
        // Handling single row payment
        if (event.target.classList.contains('pay-button')) {
            let row = event.target.closest('tr');
            let studentId = event.target.getAttribute('data-student-id');
            let eventId = row.cells[0].textContent.trim(); // Adjust based on correct cell
            let totalAmount = row.querySelector('.amountpenalty').textContent.trim();

            // Send AJAX request to insert payment
            fetch('insert_payment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    student_id: studentId,
                    event_id: eventId,
                    amount: totalAmount
                })
            })
            .then(response => response.text())
            .then(data => {
                console.log('Response from single payment:', data); // Log response
                alert(data); // Or use swal() if preferred
                // Update UI
                row.querySelector('.payment-status').textContent = 'Paid';
                row.querySelector('.payment-status').classList.remove('unpaid');
                row.querySelector('.payment-status').classList.add('paid');
                // Disable the button
                event.target.disabled = true;
                event.target.classList.remove('btn-primary');
                event.target.classList.add('btn-secondary');
                event.target.textContent = 'Paid';
            })
            .catch(error => console.error('Error processing payment:', error));
        } 
        
        // Handling full payment for all rows
        else if (event.target.classList.contains('fullpayment-button')) {
            let studentId = event.target.getAttribute('data-student-id');
            let totalAmount = 0; // To accumulate total payment

            // Loop through each row to get all payment data
            let promises = [];
            document.querySelectorAll('tbody tr').forEach(row => {
                if (row.querySelector('.pay-button')) {
                    let eventId = row.cells[0].textContent.trim();
                    let amount = parseFloat(row.querySelector('.amountpenalty').textContent.trim());
                    totalAmount += amount;

                    let promise = fetch('insert_payment.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({
                            student_id: studentId,
                            event_id: eventId,
                            amount: amount
                        })
                    })
                    .then(response => response.text())
                    .then(data => {
                        console.log('Response from full payment:', data); // Log response
                        // Update UI
                        let paymentStatusCell = row.querySelector('.payment-status');
                        paymentStatusCell.textContent = 'Paid';
                        paymentStatusCell.classList.remove('unpaid');
                        paymentStatusCell.classList.add('paid');
                        // Disable the button
                        let payButton = row.querySelector('.pay-button');
                        payButton.disabled = true;
                        payButton.classList.remove('btn-primary');
                        payButton.classList.add('btn-secondary');
                        payButton.textContent = 'Paid';
                    })
                    .catch(error => console.error('Error processing full payment:', error));

                    promises.push(promise);
                }
            });

            // Wait for all promises to complete
            Promise.all(promises)
            .then(() => {
                // Show a success message
                alert(`Full payment of ${totalAmount.toFixed(2)} has been processed successfully.`);
                // Optionally update the full payment button or show a message
                event.target.disabled = true;
                event.target.classList.remove('btn-primary');
                event.target.classList.add('btn-secondary');
                event.target.textContent = 'All Paid';
            })
            .catch(error => console.error('Error completing full payment:', error));
        }
    });
});

function printSheet() {
    const sheet = document.getElementById('attendanceSheet').cloneNode(true);
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.open();
    printWindow.document.write(`
        <html>
        <head>
            <title>Print</title>
            <style type="text/css">
                .line {
                    border-top: 2px solid maroon;
                    margin: 20px 0; 
                    margin-top: -10px;
                }
                .sheets {
                    border: 2px solid black;
                    padding: 10px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    text-align: center;
                    position: relative; 
                    width: 52mm; 
                    height: 83.9mm; 
                    box-sizing: border-box; 
                    background-image: url("../admin/include/logo/logo4.jpg"); 
                    background-size: cover; 
                    background-position: center; 
                }
                .sheets img {
                    max-width: 80px; 
                    height: auto; 
                    margin-top: -8px;
                    border: 1px solid black;
                }
                .name {
                    font-size: 13px;
                    font-weight: bold;
                    margin-top: -1px;
                }
                .program {
                    margin-top: -15px;
                    font-size: 11px;
                }
                .id {
                    font-size: 12px;
                    margin-top: 5px;
                    font-weight: bold;
                }
                .text5 {
                    background-color: orange;
                    font-size: 12px;
                    font-weight: bold; 
                    text-align: center;
                    margin-top: -5px;
                    color: black;
                }
                .close-icon {
                    position: absolute;
                    top: 5px;
                    right: 5px;
                    cursor: pointer;
                    font-size: 15px;
                }
                .circle-image {
                    position: absolute;
                    left: 5px;
                    top: 10px;
                    width: 30px; 
                    height: 30px; 
                    border-radius: 50%;  
                }
                .header {
                    display: flex;
                    align-items: center;
                    margin-bottom: 10px; 
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
                    font-size: 5px;
                    font-weight: bold;
                    text-align: center;
                }
                .text1 {
                    color: maroon;
                    font-size: 5px;
                    font-weight: bold;
                    text-align: center;
                }
                .text2 {
                    color: green;
                    font-size: 5px;
                    font-weight: bold;
                    text-align: center;
                }
                .text3 {
                    color: black;
                    font-size: 4px;
                    font-weight: bold;
                    text-align: center;
                }
                .text4 {
                    color: black;
                    font-size: 6px;
                    font-weight: bold;
                    text-align: center;
                }
                .header1 {
                    display: flex;
                    margin-bottom: 10px; 
                }
                .header-texts p {
                    margin: 0; 
                    color: black;
                    font-size: 6px;
                    font-weight: bold;
                    margin-left: 35px;
                    justify-content: center;
                    margin-top: -8px;
                }
                .header-logos {
                    align-items: center; 
                }
                .additional-logo {
                    position: absolute;
                    right: 5px;
                    top: 10px;
                    width: 30px; 
                    height: 30px; 
                    border-radius: 50%;
                }

                /* Force printing of background images */
                @media print {
                    * {
                        -webkit-print-color-adjust: exact !important;
                        color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }
                    .sheets {
                        background-image: url("../admin/include/logo/logo4.jpg") !important;
                        background-size: cover !important;
                        background-position: center !important;
                    }
                }
            </style>
        </head>
        <body onload="window.print(); window.close();">
            ${sheet.innerHTML}
        </body>
        </html>
    `);
    printWindow.document.close();
}

	</script>

</body>
</html>