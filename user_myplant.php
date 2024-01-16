<?php
include('config/db_conn.php');
session_start();

// Check if the user is logged in
if (isset($_SESSION['user']) && isset($_SESSION['role'])) {
    $username = $_SESSION['user'];
    $role = $_SESSION['role'];

    // Fetch the user ID from the database based on the username
    $userQuery = "SELECT user_id FROM user WHERE Username = '$username'";
    $userResult = $conn->query($userQuery);

    if ($userResult && $userResult->num_rows > 0) {
        $userData = $userResult->fetch_assoc();
        $currentUserId = $userData['user_id'];
    } else {
        // Handle the case where the user ID is not found
        $currentUserId = 0;
    }

} else {
    // Redirect to the login page if the user is not logged in
    header('Location: login.php');
    exit();
}

?>


<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <!-- Favicon -->
    <link href="img\logo.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Roboto:wght@500;700;900&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    

    <!-- Libraries Stylesheet -->
    
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://code.jquery.com/jquery-3.7.0.js" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="lib/dropzone/dropzone.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/themes/default.min.css" />
    



    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style3.css" />
    <link rel="stylesheet" href="css/style2.css" />
    <link rel="stylesheet" href="css/app-calendar.css" />

  </head>
  <body>

    <?php
    require 'navbar.php'; // Include your navbar
    require 'user_sidebar.php';
    ?>





<!-- Main Content Area -->
<div id="main">
    <div class="container" style="margin-top: 100px;">
        <div class="row">
            <div class="col-xl-12">
                <h6 class="text-muted"></h6>
                <div class="nav-align-top mb-4">
                    <ul class="nav nav-tabs nav-fill mb-4" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-home" aria-controls="navs-justified-home" aria-selected="true">
                                <i class=""></i> My Orchid 
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile" aria-selected="false">
                                <i class=""></i> My Calendar
                            </button>
                        </li>
                        
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
                            <div class="col-xl-12">
                                <h3 class="pb-1 mb-4">My Plant</h3>
                                <div class="nav-align-top mb-4">
                                    <!-- Your navigation tabs here -->
                                </div>
                                <div class="tab-content mt-4">
                                    <div class="tab-pane fade show active" id="navs-top-Myplant" role="tabpanel">
                                        <h5 class="pb-1 mb-4"> </h5>
                                        <div class="row mb-5">
                                            <?php
                                            // Fetch plant data from the database
                                            $plantQuery = "SELECT myplants.* ,watering_schedule.frequency as watering_frequency, fertilizing_schedule.frequency as fertilizing_frequency
                                            from myplants 
                                            left join watering_schedule on myplants.myplant_id = watering_schedule.myplant_id
                                            left join fertilizing_schedule on myplants.myplant_id = fertilizing_schedule.myplant_id
                                            where user_id = $currentUserId";

                                            $plantResult = $conn->query($plantQuery);

                                            if ($plantResult && $plantResult->num_rows > 0) {
                                                while ($plantData = $plantResult->fetch_assoc()) {
                                                    // Output your HTML structure with plant data here
                                                    $imageBase64 = base64_encode($plantData['myplant_img']);
                                                    $imageType = 'image/jpeg'; // Adjust the image type based on your actual data

                                                    echo '
                                                    <div class="col-md-6 mb-4">
                                                        <a href="user_myplant_details.php?myplant_id=' . $plantData['myplant_id'] . '" class="card-link">
                                                            <div class="card mb-3 position-relative">
                                                                <div class="row g-0">
                                                                    <div class="col-md-4">
                                                                        <div class="square-container" style="height: 200px;">
                                                                            <img class="card-img card-img-left" src="data:' . $imageType . ';base64,' . $imageBase64 . '" alt="Card orchid image" style="object-fit: cover; width: 100%; height: 100%;" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <div class="card-body">
                                                                            <h5 class="card-title mb-3">' . $plantData['myplant_name'] . '</h5>
                                                                            
                                                                            <p class="card-text">Watering Frequency: Every ' . $plantData['watering_frequency'] .' days</p>
                                                                            <p class="card-text">Fertilizer Frequency: Every ' . $plantData['fertilizing_frequency'] .' days</p><br>
                                                                            <p class="card-text text-muted">Added on ' . date('j F Y', strtotime($plantData['time'])) . '</small></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>';
                                                }
                                            } else {
                                                echo "No plants found.";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">
                            <!-- Content wrapper -->
                            <div class="content-wrapper">

                                <!-- Content -->
                                <div class="container-xxl flex-grow-1 container-p-y">

                                    <div class="card app-calendar-wrapper">
                                        <div class="row g-0">

                                            <!-- Calendar Sidebar -->
                                            <div class="col app-calendar-sidebar" id="app-calendar-sidebar">
                                                <div class="border-bottom p-4 my-sm-0 mb-3">
                                                    <div class="d-grid">
                                                        <button class="btn btn-primary btn-toggle-sidebar" data-bs-toggle="offcanvas" data-bs-target="#addEventSidebar" aria-controls="addEventSidebar">
                                                            <i class="bx bx-plus me-1"></i>
                                                            <span class="align-middle">Add Event</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- Move the inline calendar here -->
                                                <div class = "p-4">
                                                    <div class="ms-n2" id='inline-calendar'></div>
                                                    <hr class="container-m-nx my-4">
                                                </div>
                                            </div>
                                            <!-- /Calendar Sidebar -->

                                            <!-- Calendar & Modal -->
                                            <div class="col app-calendar-content">
                                                <div class="card shadow-none border-0">
                                                    <div class="card-body pb-0">
                                                        <!-- FullCalendar -->
                                                        <div id="calendar"></div>
                                                    </div>
                                                </div>
                                                <div class="app-overlay"></div>
                                            </div>
                                            <!-- /Calendar & Modal -->

                                            
                                        </div>
                                    </div>
                                </div>
                                <!-- / Content -->

                                
                                            

                            </div>
                        </div>
                       
                    </div>
                

        <div class="mydiscussion myfixed-bottom">
            <button class="btn btn-primary btn-discussion-add back-to-top m-3" type="button" data-bs-toggle="modal" 
                data-bs-target="#AddMyPlant_Modal">New Orchid
            </button>
        </div>
    </div>
</div>

<!-- The Add Orchid Modal -->
<div class="modal fade" id="AddMyPlant_Modal" tabindex="-1" aria-labelledby="AddMyPlant_ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="text-center">
                    <h5 class="modal-title">Add New Orchid</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="orchidForm" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="orchidName" class="form-label">Orchid Name</label>
                            <input type="text" class="form-control" id="orchidName" name="myplant_name" placeholder="Enter Orchid Name" required>
                        </div>
                        <div class="col">
                            <label for="orchidSpecies" class="form-label">Orchid Species</label>
                            <input type="text" class="form-control" id="orchidSpecies" name="myplant_species" placeholder="Enter Orchid Species" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="plantNote" class="form-label">Plant Note</label>
                        <textarea class="form-control" id="plantNote" name="myplant_note" rows="3" placeholder="Enter Plant Note" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="plantImage" class="form-label">Plant Image</label>
                        <input type="file" class="form-control" id="plantImage" name="myplant_img" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="wateringFrequency" class="form-label">Watering Frequency (in days)</label>
                            <input type="number" class="form-control" id="wateringFrequency" name="wateringFrequency" placeholder="Enter Watering Frequency" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="fertilizingFrequency" class="form-label">Fertilizing Frequency (in days)</label>
                            <input type="number" class="form-control" id="fertilizingFrequency" name="fertilizingFrequency" placeholder="Enter Fertilizing Frequency" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar Offcanvas -->
<div class="offcanvas offcanvas-end event-sidebar" tabindex="-1" id="addEventSidebar" aria-labelledby="addEventSidebarLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title mb-2" id="addEventSidebarLabel">Add Event</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form class="event-form pt-0" id="eventForm" onsubmit="return false">
            <div class="mb-3">
                <label class="form-label" for="eventTitle">Event Title</label>
                <input type="text" class="form-control" id="eventTitle" name="eventTitle" placeholder="Event Title" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="orchidName">Orchid Name</label>
                <input type="text" class="form-control" id="orchidName" name="orchidName" placeholder="Orchid Name" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="eventDescription">Event Description</label>
                <textarea class="form-control" name="eventDescription" id="eventDescription" placeholder="Event Description"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label" for="eventDate">Event Date</label>
                <input type="text" class="form-control flatpickr" id="eventDate" name="eventDate" placeholder="Event Date" />
            </div>
            <div class="mb-3 d-flex justify-content-sm-between justify-content-start my-4">
                <div>
                    <button type="submit" class="btn btn-primary btn-add-event me-sm-3 me-1">Add</button>
                    <button type="reset" class="btn btn-label-secondary btn-cancel me-sm-0 me-1" data-bs-dismiss="offcanvas">Cancel</button>
                </div>
                <div><button class="btn btn-label-danger btn-delete-event d-none">Delete</button></div>
            </div>
        </form>
    </div>
</div>

        
</div>
</div>

          

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>

    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/isotope/isotope.pkgd.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/dropzone/dropzone.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <script src="js/script.js"></script>
    <script src="js/script2.js"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    

    


    <script>

<?php
    $eventQuery = "SELECT * FROM event WHERE user_id = $currentUserId";
    $eventResult = $conn->query($eventQuery);

    $events = array(); // Array to store events

    if ($eventResult && $eventResult->num_rows > 0) {
        while ($eventData = $eventResult->fetch_assoc()) {
            // Assign different colors based on event type
            $eventColor = '';
            switch ($eventData['event_type']) {
                case 'Watering':
                    $eventColor = '#d0foff'; 
                    break;
                case 'Fertilizing':
                    $eventColor = '#9dcd5a'; 
                    break;
                case 'New add':
                    $eventColor = '#ff0000'; 
                    break;
                default:
                    $eventColor = '#ffeb5b'; 
            }

            // Format the event data for FullCalendar
            $events[] = array(
                'title' => $eventData['event_description'],
                'start' => $eventData['event_date'],
                'backgroundColor' => $eventColor, // Set the background color
                // Add more fields as needed
            );
        }
    }
?>

    document.addEventListener('DOMContentLoaded', function () {
        flatpickr('.flatpickr', {
            enableTime: false, // Disable time selection, adjust as needed
            dateFormat: "Y-m-d", // Specify the date format
        });
    });
    
    
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize FullCalendar
        const fullCalendarEl = document.getElementById('calendar');
        const fullCalendar = new FullCalendar.Calendar(fullCalendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            events: <?php echo json_encode($events); ?>, // Pass the events array
            eventDidMount: function (info) {
                // Set the background color dynamically
                info.el.style.backgroundColor = info.event.backgroundColor;

                $(info.el).tooltip({
                    title: info.event.title,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
            }
        });

        fullCalendar.render();

        // Initialize Flatpickr
        const inlineCalendar = document.getElementById('inline-calendar');
        const flatpickrInstance = flatpickr(inlineCalendar, {
            inline: true,
            enableTime: false,
            dateFormat: "Y-m-d",
            onChange: function (selectedDates, dateStr, instance) {
                fullCalendar.gotoDate(selectedDates[0]);
            }
        });
    });




    $(document).ready(function () {
        $("#orchidForm").submit(function (e) {
            e.preventDefault(); // Prevent the form from submitting in the default way

            var formData = new FormData(this);
            formData.append("AddMyPlant", true);

            $.ajax({
                type: "POST",
                url: "code.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log('AJAX success:', response);

                    if (response.status == 422) {
                        $('#errorMessage').removeClass('d-none');
                        $('#errorMessage').text(response.message);
                    } else if (response.status == 200) {
                        $('#errorMessage').addClass('d-none');
                        $('#AddMyPlant_Modal').modal('hide');
                        $('#orchidForm')[0].reset();
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success(response.message);
                        // Reload the content or update as needed
                        $('#container').load(location.href + " #container");

                    } else if (response.status == 500) {
                        alert(response.message);
                    }
                },
                error: function (error) {
                    console.log('AJAX error:', error);
                    alert('An error occurred during the submission.');
                }
            });
        });
    });




</script>




</body>
</html>
