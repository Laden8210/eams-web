<?php 
	include '../sql/sql.php';
	// include '../qrcodegenerator/phpqrcode/qrlib.php';
	session_start();
	$student_id = $_SESSION['studid'];

	$qryloadstudentinfo = $conn->query("Select * from tblstudent inner join tblprogram on tblprogram.programid=tblstudent.stud_programid where studentid='$student_id'") or die($conn->error);
	$student = $qryloadstudentinfo->fetch_array();

	// // Get the student ID from the URL
	// $studentidd = $student['student_id'];

	// // Set the path for storing QR codes temporarily
	// $tempDir = "qrcodes/";
	// if (!file_exists($tempDir)) {
	//     mkdir($tempDir);
	// }

	// // Generate the file name for the QR code
	// $filename = $tempDir . 'qr_' . $studentidd . '.png';

	$resmsg = '';

	if (isset($_POST['btnupdate'])) {
    $studid = $_SESSION['studid']; // Assuming student ID is stored in session
    $stud_fname = $_POST['stud_fname'];
    $stud_mname = $_POST['stud_mname'];
    $stud_lname = $_POST['stud_lname'];
    $yrlevel = $_POST['yrlevel'];
    $stud_section = $_POST['stud_section'];
    $stud_mobile = $_POST['stud_mobile'];
    $stud_username = $_POST['stud_username'];
    $stud_password = $_POST['stud_password'];

    // Handle profile picture upload
    $stud_profpic = $student['stud_profpic']; // Default to existing pic
    if (!empty($_FILES['stud_profpic']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["stud_profpic"]["name"]);
        move_uploaded_file($_FILES["stud_profpic"]["tmp_name"], $target_file);
        $stud_profpic = $_FILES["stud_profpic"]["name"];
    }

    // Update query
    $sql = "UPDATE tblstudent SET stud_fname = ?, stud_mname = ?, stud_lname = ?, yrlevel = ?, stud_section = ?, stud_mobile = ?, stud_profpic = ?, stud_username = ?, stud_password = ? WHERE studentid = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters with correct data types
    $stmt->bind_param(
        "sssssssssi", // Correct parameter type binding
        $stud_fname,
        $stud_mname,
        $stud_lname,
        $yrlevel,
        $stud_section,
        $stud_mobile,
        $stud_profpic,
        $stud_username,
        $stud_password,
        $studid
    );

    // Execute the statement and provide feedback
    if ($stmt->execute()) {
        $resmsg = '<script>
            Swal.fire({
                title: "Success",
                text: "Profile updated successfully",
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
                text: "Failed to update profile",
                icon: "error",
                confirmButtonText: "OK"
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
			                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
			                    </ol>
			                </nav>
			            </div>
			        </div>
			    </div>

			    <div>
			    	<!-- <button class="btn btn-primary mb-2" type="button" data-bs-toggle="modal" data-bs-target="#modaladdstudent"> <i class="bi bi-plus-circle-fill"></i> Add Event</button> -->
			    </div>
			      <section class="section">
        <div class="card">
            <div class="card-header">Profile</div>
            <div class="card-body">
                <form  method="POST" enctype="multipart/form-data">
                    <div class="row">
                    	<div class="col-md-12">
                            <div class="form-group">
                                <label for="stud_fname">Student ID</label>
                                <input type="text" class="form-control" name="student_id" value="<?php echo $student['student_id']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="stud_fname">First Name</label>
                                <input type="text" class="form-control" name="stud_fname" value="<?php echo $student['stud_fname']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="stud_mname">Middle Name</label>
                                <input type="text" class="form-control" name="stud_mname" value="<?php echo $student['stud_mname']; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="stud_lname">Last Name</label>
                                <input type="text" class="form-control" name="stud_lname" value="<?php echo $student['stud_lname']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="yrlevel">Year Level</label>
                                <input type="text" class="form-control" name="yrlevel" value="<?php echo $student['yrlevel']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="stud_section">Section</label>
                                <input type="text" class="form-control" name="stud_section" value="<?php echo $student['stud_section']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="stud_mobile">Mobile Number</label>
                                <input type="text" class="form-control" name="stud_mobile" value="<?php echo $student['stud_mobile']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stud_username">Username</label>
                                <input type="text" class="form-control" name="stud_username" value="<?php echo $student['stud_username']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stud_password">Password</label>
                                <input type="password" class="form-control" name="stud_password" value="<?php echo $student['stud_password']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="stud_profpic">Profile Picture</label>
                        <input type="file" class="form-control" name="stud_profpic">
                        <?php if (!empty($student['stud_profpic'])): ?>
                            <img src="../uploads/<?php echo $student['stud_profpic']; ?>" alt="Profile Picture" class="img-thumbnail mt-2" width="150">
                        <?php endif; ?>
                    </div>

                    <button name="btnupdate" type="submit" class="btn btn-primary">Update Profile</button>
                </form>

                <div class="mt-4">
                    <h5>Student QR Code</h5>
                    <img src="generate_qr.php?student_id=<?php echo urlencode($student['student_id']); ?>" alt="QR Code">
                    <br>
                      <a href="generate_qr.php?student_id=<?php echo urlencode($student['student_id']); ?>" class="btn btn-sm btn-success">
				        Download QR Code
				    </a>
                </div>
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