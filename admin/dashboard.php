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
</head>
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
			                        <li class="breadcrumb-item active" aria-current="page">Student Dashboard</li>
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
			            <div class="card-header">Calendar Event</div>
			            <div class="card-body">
			                 <div id="calendar"></div> 
			            </div>
			        </div>
   			 	</section>

            </div>
		</div>
		<!-- main div -->
	</div>
	<!-- main content -->
	<!-- FullCalendar JS -->
	
	<?php include 'include/js.php'; ?>
	<script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
	
	<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: 'fetch_events.php' // Ensure this path is correct
        });

        calendar.render();
    });

	</script>
</body>
</html>