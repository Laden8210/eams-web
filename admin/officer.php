<?php 
	session_start();
	include '../sql/sql.php';

	$resmsg = '';

	if (isset($_POST['btnsave'])) {

		// Retrieve form data
    $fname = $_POST['txtfname'];
    $mname = $_POST['txtmdname'];
    $lname = $_POST['txtlname'];
    $mobile = $_POST['txtmobile'];
    $pos = $_POST['txtpos'];
    $username = strtolower(str_replace(' ', '', $fname)) . '.' . strtolower(str_replace(' ', '', $lname)); // Combine fname and lname, remove spaces
    $password = 'password123'; // Password hash for security

    // Handle file upload
    $target_dir = "../uploads/"; // Folder to save the uploaded images
    $filename = basename($_FILES["txtprofpic"]["name"]);
    $target_file = $target_dir . basename($_FILES["txtprofpic"]["name"]);
    move_uploaded_file($_FILES["txtprofpic"]["tmp_name"], $target_file);

    // Insert data into the database
    $sql = "INSERT INTO tblofficer (off_fname, off_mname, off_lname, off_mobile, off_pos, off_pic, off_username, off_passw)
            VALUES ('$fname', '$mname', '$lname', '$mobile', '$pos', '$filename', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
          $resmsg = '<script>
                    Swal.fire({
                        title: "Success",
                        text: "Officer has been Successfully saved",
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
                        text: "There was an error adding the officer",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(function() {
                        window.location.href = window.location.href;
                    });
                  </script>';
    }

    

	}


	if (isset($_POST['btnupdate'])) {

    // Retrieve form data
    $officerid = $_POST['officerid'];
    $fname = $_POST['txtfname'];
    $mname = $_POST['txtmdname'];
    $lname = $_POST['txtlname'];
    $mobile = $_POST['txtmobile'];
    $pos = $_POST['txtpos'];
    
    // Handle file upload (if a new file is uploaded)
    $target_file = $_POST['existing_image'];
    if (!empty($_FILES['txtprofpic']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["txtprofpic"]["name"]);
        move_uploaded_file($_FILES["txtprofpic"]["tmp_name"], $target_file);
    }

    // Update data in the database
    $sql = "UPDATE tblofficer SET 
                off_fname = '$fname', 
                off_mname = '$mname', 
                off_lname = '$lname', 
                off_mobile = '$mobile', 
                off_pos = '$pos', 
                off_pic = '$target_file' 
            WHERE officerid = '$officerid'";

    if ($conn->query($sql) === TRUE) {
               $resmsg = '<script>
                    Swal.fire({
                        title: "Success",
                        text: "Officer updated successfully",
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
                        text: "There was an error updating the officer",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(function() {
                        window.location.href = window.location.href;
                    });
                  </script>';
    }

   
}


	$qryloadofficer = $conn->query("Select * from tblofficer") or die($conn->error);
	

	if (isset($_POST['btndelete'])) {
		$deleteid = $_POST['txtdelid'];

		$sqldelete = "Delete from tblofficer where officerid='$deleteid'";

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

	$conn->close();
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
			                <h3>Officer</h3>
			                <p class="text-subtitle text-muted">Officer Section</p>
			            </div>
			            <div class="col-12 col-md-6 order-md-2 order-first">
			                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
			                    <ol class="breadcrumb">
			                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
			                        <li class="breadcrumb-item active" aria-current="page">Officer</li>
			                    </ol>
			                </nav>
			            </div>
			        </div>
			    </div>

			    <div>
			    	<button class="btn btn-primary mb-2" type="button" data-bs-toggle="modal" data-bs-target="#modaladdofficer"> <i class="bi bi-plus-circle-fill"></i> Add Officer</button>
			    </div>
			    <section  class="section">
			    	<div class="card">
			    		<div class="card-header">
			                Officer List
			            </div>

			            <div class="card-body">
			            	<table class="table table-striped" id="table1">
			            		 <thead>
			                        <tr>
			                        	<th style="display: none;"></th>
			                        	<th style="display: none;"></th>
			                        	<th style="display: none;"></th>
			                        	<th style="display: none;"></th>
			                            <th>Fullname</th>
			                            <th>Position</th>
			                            <th>Mobile No.</th>
			                            <th></th>
			                        </tr>
			                    </thead>
			                    <tbody>
			                    	<?php 
			                    		while ($rowloadofficer = $qryloadofficer->fetch_assoc()) {
			                    			$fullname = $rowloadofficer['off_fname']." ".$rowloadofficer['off_lname'];
			                    			echo '<tr>
			                    					<td style="display: none;">'.$rowloadofficer['officerid'].'</td>
			                    					<td style="display: none;">'.$rowloadofficer['off_fname'].'</td>
			                    					<td style="display: none;">'.$rowloadofficer['off_mname'].'</td>
			                    					<td style="display: none;">'.$rowloadofficer['off_lname'].'</td>
			                    					<td>'.$fullname.'</td>
			                    					<td>'.$rowloadofficer['off_pos'].'</td>
			                    					<td>'.$rowloadofficer['off_mobile'].'</td>
			                    					<td><div class="btn-group mb-1">
			                                            <div class="dropdown">
			                                                <button class="btn btn-primary dropdown-toggle me-1 btn-sm" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			                                                    Action
			                                                </button>
			                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="margin: 0px;">
			                                                   
			                                                     <button type="button" class="dropdown-item getid1" > Edit</button>
			                                                    <a class="dropdown-item" href="#">Profile</a>
			                                                     <button type="button" class="dropdown-item getid2">Delete</button>
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

					 <div class="modal fade text-left" id="modaladdofficer" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                                         <form method="post" enctype="multipart/form-data">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="myModalLabel1">Add Officer</h5>
                                                    <button type="button" class="close rounded-pill"
                                                        data-bs-dismiss="modal" aria-label="Close">
                                                        <i data-feather="x"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
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
                                                    		<label>Mobile No</label>
                                                    		<input type="text" class="form-control" name="txtmobile">
                                                    	</div>
                                                    	<div class="col-md-6">
                                                    		<label>Position</label>
                                                    		<input type="text" class="form-control" name="txtpos">
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

                                    <!-- Edit Officer Modal -->
<!-- Edit Officer Modal -->
<div class="modal fade text-left" id="modalEditOfficer" tabindex="-1" role="dialog" aria-labelledby="editOfficerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <form id="editOfficerForm" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOfficerLabel">Edit Officer</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="officerid" id="officerid"> <!-- Hidden input to store officer ID -->
                    <div class="row">
                        <div class="col-md-12">
                            <label>Firstname</label>
                            <input type="text" class="form-control" name="txtfname" id="txtfname">
                        </div>
                        <div class="col-md-12">
                            <label>Middlename</label>
                            <input type="text" class="form-control" name="txtmdname" id="txtmdname">
                        </div>
                        <div class="col-md-12">
                            <label>Lastname</label>
                            <input type="text" class="form-control" name="txtlname" id="txtlname">
                        </div>
                        <div class="col-md-6">
                            <label>Mobile No</label>
                            <input type="text" class="form-control" name="txtmobile" id="txtmobile">
                        </div>
                        <div class="col-md-6">
                            <label>Position</label>
                            <input type="text" class="form-control" name="txtpos" id="txtpos">
                        </div>
                        <div class="col-md-12">
                            <label>Upload Image</label>
                            <input type="file" class="form-control" name="txtprofpic" id="txtprofpic">
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


 <div class="modal fade text-left" id="modaldelofficer" tabindex="-1" role="dialog"
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

	<?php include 'include/js.php'; ?>
	
	 <script>
	    // Simple Datatable
	    let table1 = document.querySelector('#table1');
	    let dataTable = new simpleDatatables.DataTable(table1);
	</script>
	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function() {
    // Add click event listener to each edit button
    document.querySelectorAll('.getid1').forEach(button => {
        button.addEventListener('click', function() {
            // Get the parent row of the clicked button
            let row = this.closest('tr');

            // Retrieve hidden data from the row
            let officerId = row.cells[0].textContent.trim();
            let fname = row.cells[1].textContent.trim();
            let mname = row.cells[2].textContent.trim();
            let lname = row.cells[3].textContent.trim();
            let mobile = row.cells[6].textContent.trim();
            let pos = row.cells[5].textContent.trim();

            // Populate the modal fields
            document.getElementById('officerid').value = officerId;
            document.getElementById('txtfname').value = fname;
            document.getElementById('txtmdname').value = mname;
            document.getElementById('txtlname').value = lname;
            document.getElementById('txtmobile').value = mobile;
            document.getElementById('txtpos').value = pos;

            // Show the modal
            var modal = new bootstrap.Modal(document.getElementById('modalEditOfficer'));
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
            let officerId = row.cells[0].textContent.trim();
           
            // Populate the modal fields
            document.getElementById('txtdelid').value = officerId;

            // Show the modal
            var modal = new bootstrap.Modal(document.getElementById('modaldelofficer'));
            modal.show();
        });
    });
});
	</script>
</body>
</html>