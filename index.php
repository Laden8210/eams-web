<?php 
	include 'sql/sql.php';
	session_start();

	 if (isset($_POST['btnlogin'])) {
        $resmsg = '';
        $username = trim($_POST['txtusername']);
        $password = trim($_POST['txtpassword']);
        $username = stripslashes($username);
        $password = stripslashes($password);
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);

        $qrystudent = $conn->query("Select * from tblstudent") or die($conn->error);
        $rowstudent = $qrystudent->fetch_array();
        $studentusername = $rowstudent['stud_username'];
        $studpass = $rowstudent['stud_password'];
        $studid = $rowstudent['studentid'];

        $qryuser = $conn->query("SELECT * FROM tbluser") or die($conn->error);
        $rowuser = $qryuser->fetch_array();
        $adminuser = $rowuser['username'];
        $adminpass = $rowuser['passw'];

        if ($username == $studentusername && $password == $studpass) {
        	$fullname = $rowstudent['stud_fname']." ".$rowstudent['stud_lname'];
        	  header('location: ../student/dashboard.php');
        	 $_SESSION['studname'] = $fullname;
        	 $_SESSION['studid'] = $studid;
        }elseif ($username == $adminuser && $password == $adminpass) {
        	 header('location: ../admin/dashboard.php');
        	  $_SESSION['usrname'] = $username;
        }
        // $userfullname = $rowuser['fname']." ".$rowuser['lname'];
        // $userid = $rowuser['idemp'];


    }

 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Event Attendance Management System</title>
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="stylesheet" href="assets/css/pages/auth.css">
</head>
<body>
	<div id="auth">

        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="index.html"><h3>EAMS</h3></a>
                    </div>
                    <h1 class="auth-title">Log in.</h1>
                    <p class="auth-subtitle mb-5"></p>

                    <form method="post">
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" name="txtusername" class="form-control form-control-xl" placeholder="Username">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" name="txtpassword" class="form-control form-control-xl" placeholder="Password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <div class="form-check form-check-lg d-flex align-items-end">
                            <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                Keep me logged in
                            </label>
                        </div>
                        <button  type="submit" name="btnlogin" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
                    </form>
                    <!-- <div class="text-center mt-5 text-lg fs-4">
                        <p class="text-gray-600">Don't have an account? <a href="auth-register.html"
                                class="font-bold">Sign
                                up</a>.</p>
                        <p><a class="font-bold" href="auth-forgot-password.html">Forgot password?</a>.</p>
                    </div> -->
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                	<img src="assets/images/logo/back.jpg" style="margin-top: 130px" width="100%" height="60%" alt="Logo">
                </div>
            </div>
        </div>

    </div>
</body>
</html>